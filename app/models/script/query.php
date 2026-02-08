<?php
class Query
{
    private $_errors = array();
    private $_result = array();
    private $_data = array();
    private $_jenis = '';
    private $_tbl = '';
    private $_tabel_pakai = '';
    private function __construct($data)
    {
        $this->_data = $data;
        if (isset($this->_data['jns'])) {
            $this->_jenis = trim($this->_data['jns']);
        }
        if (isset($this->_data['tbl'])) {
            $this->_tbl = trim($this->_data['tbl']);
            require 'init_no_session.php';
            $Fungsi = new MasterFungsi();
            $this->_tabel_pakai = $Fungsi->tabel_pakai($this->_tbl)['tabel_pakai'];
        }
        if (array_key_exists('cry', $this->_data)) {
            foreach ($this->_data as $key => $rowValue) {
                switch ($key) {
                    case 'tbl':
                        $this->_tbl = $rowValue;
                        break;
                    case 'jns':
                        $this->_jenis = $rowValue;
                        break;
                    default:
                        $formValue = $this->encrypt($rowValue);
                        $this->_data[$key] = $formValue;
                        break;
                }
            }
        }
    }
    public static function createInstance($data)
    {
        return new self($data);
    }
    
    public function encrypt($formValue)
    {
        if (isset($_SESSION["user"]["key_encrypt"])) {
            $keyEncrypt = $_SESSION["user"]["key_encrypt"];
        } else if (isset($_SESSION["key_encrypt"])) {
            $keyEncrypt = $_SESSION["key_encrypt"];
        } else {
            $real_path = realpath(dirname(__FILE__));
            if (strpos($real_path, 'script')) {
                header("Location: login");
            } else {
                header("Location: login");
            }
        }
        if ($formValue != null && $keyEncrypt) {
            require_once 'class/CryptoUtils.php';
            $crypto = new CryptoUtils();
            return $crypto->decrypt($formValue, $keyEncrypt);
        }
    }
    public function json_list_dropdown()
    {
        require_once 'class/DB.php';
        $DB = DB::getInstance();
        $jenis = $this->_jenis;
        $tbl = $this->_tbl;
        $tabel_pakai =  $this->_tabel_pakai;
        $kondisi = [];
        $data = [];
        $dataJson = array();
        $message_tambah = '';
        $code = 1;
        $sukses = false;
        if (array_key_exists('kondisi', $this->_data)) {
            $kondisi = $this->_data['kondisi'];
            switch ($jenis) {
                case 'json_list_dropdown':
                    switch ($tbl) {
                        case 'organisasi':
                            // var_dump($this->_data);
                            // $kd_wilayah =  $this->_data['kondisi']['kd_wilayah'];
                            // $kondisi['kd_wilayah'] = $kd_wilayah;
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;
            }
            $result = $this->getRows($kondisi);
            // var_dump($result);
            switch ($jenis) {
                case 'json_list_dropdown':
                    switch ($tbl) {
                        case 'wilayah':
                            $name_json = 'uraian';
                            $value_json = 'kode';
                            $text_json = 'uraian';
                            $description_json = 'kode';
                            break;
                        case 'organisasi':
                            $name_json = 'uraian';
                            $value_json = 'kode';
                            $text_json = 'uraian';
                            $description_json = 'kode';
                            break;
                        default:
                            # code...
                            break;
                    }
                    break;

                default:
                    # code...
                    break;
            }
            // var_dump($result[0]);
            // var_dump(gettype($result));
            $dataJson['results'] = [];
            if (is_array($result)) {
                foreach ($result as $key => $row) {
                    $dataJson['results'][] = ['name' => $row->$name_json, 'text' => $row->$text_json, 'value' => $row->$value_json, 'description' => $row->$description_json, "descriptionVertical" => true];
                }
                $code = 200;
                $sukses = true;
            } else {
                $code = 204;
            }
        } else {
            $code = 416;
        }
        $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
        $json = array('success' => $sukses,  'results' => $dataJson['results'],  'data' => $data, 'error' => $item);
        return json_encode($json, JSON_HEX_APOS);
    }
    public function getRows($kondisi)
    {
        require_once 'class/DB.php';
        $DB = DB::getInstance();
        $tabel_pakai =  $this->_tabel_pakai;
        $dataJson = array();
        if ($kondisi !== false) {
            $dataJson = $DB->getArrayLike($tabel_pakai, $kondisi);
        }
        return $dataJson;
    }
    public function insertRow($set)
    {
        require_once 'class/DB.php';
        $DB = DB::getInstance();
        $tabel_pakai =  $this->_tabel_pakai;
        $DB->insert($tabel_pakai, $set);
        if ($DB->lastInsertId()) {
            $code = 3;
        } else {
            $code = 47;
        }
        return ['add_row' => $DB->lastInsertId(), 'code' => $code];
    }
    public function updateRow($kondisi, $set)
    {
        require_once 'class/DB.php';
        $DB = DB::getInstance();
        $tabel_pakai =  $this->_tabel_pakai;
        $DB->update_array($tabel_pakai, $set, $kondisi);
        if ($DB->count()) {
            $code = 3;
        } else {
            $code = 47;
        }
        return ['update' => $DB->count(), 'code' => $code];
    }
    public function key_encrypt()
    {
        $msx = rand(32, 64);
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ()<>?{}#$&=-*^@';
        $keyEnc = substr(str_shuffle($permitted_chars), 0, $msx);
        return $keyEnc;
    }
}
