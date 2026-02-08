<?php
class get_data
{
  private $_jenis = '';
  private $_tbl = '';
  private $_tabel_pakai = '';
  private $_data = array();
  private function __construct($data)
  {
    $this->_data = $data;
    if (isset($this->_data['jenis'])) {
      $this->_jenis = trim($this->_data['jenis']);
    }
    if (isset($this->_data['tbl'])) {
      $this->_tbl = trim($this->_data['tbl']);
      require 'init.php';
      $Fungsi = new MasterFungsi();
      $this->_tabel_pakai = $Fungsi->tabel_pakai($this->_tbl)['tabel_pakai'];
    }
    if (array_key_exists('cry', $this->_data)) {
      foreach ($this->_data as $key => $rowValue) {
        switch ($key) {
          case 'tbl':
            $this->_tbl = $rowValue;
            break;
          case 'jenis':
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
  public function get_data()
  {
    // var_dump($_POST);
    require 'init.php';
    $user = new User();
    $user->cekUserSession();
    $type_user = $_SESSION["user"]["type_user"];
    $username = $_SESSION["user"]["username"];
    $id_user = $_SESSION["user"]["id"];
    $nip = $_SESSION["user"]["nip"];
    $keyEncrypt = $_SESSION["user"]["key_encrypt"];
    $kd_opd = $_SESSION["user"]["kd_organisasi"];
    //var_dump($keyEncrypt);
    //var_dump(sys_get_temp_dir());lokasi tempoerer
    $DB = DB::getInstance();
    $message_tambah = '';
    $sukses = false;
    $code = 39;
    $pesan = 'posting kosong';
    $item = array('code' => "1", 'message' => $pesan);
    $json = array('success' => $sukses, 'error' => $item);
    $data = array();
    $dataJson = array();
    //ambil row user
    $rowUsername = $DB->getWhereOnceCustom('user_sesendok_biila', [['username', '=', $username], ['nip', '=', $nip, 'AND']]);
    $dataJson['results'] = [];
    $rowPengaturan = false;
    if ($rowUsername !== false) {
      foreach ($rowUsername as $key => $value) {
        ${$key} = $value;
      }
      $tahun = (int) $rowUsername->tahun;
      $kd_wilayah = $rowUsername->kd_wilayah;
      $kd_opd = $rowUsername->kd_organisasi;
      $id_user = $rowUsername->id;
      $rowPengaturan = $DB->getWhereOnce('pengaturan_neo', ['tahun', '=', $tahun]);
      if ($rowPengaturan !== false) {
        foreach ($rowPengaturan as $key => $value) {
          ${$key} = $value;
        }
      } else {
        // $id_user = 0;
        // $code = 407;
      }
    } else {
      $id_user = 0;
      $code = 407;
    }
    // var_dump($rowTahunAktif);
    $group_by = "";
    $jenis = '';
    if (!empty($_POST) && $id_user > 0 && $code != 407) {
      if (isset($_POST['jenis']) && isset($_POST['tbl'])) {
        $code = 40;
        $validate = new Validate($_POST);
        $jenis = $validate->setRules('jenis', 'jenis', [
          'sanitize' => 'string',
          'required' => true,
          'min_char' => 1,
          'max_char' => 100
        ]);
        $tbl = $validate->setRules('tbl', 'tbl', [
          'sanitize' => 'string',
          'required' => true,
          'min_char' => 1,
          'max_char' => 100
        ]);
        $cari = '';
        if (!empty($_POST['cari'])) {
          $cari = $validate->setRules('cari', 'Cari', [
            'sanitize' => 'string',
            'max_char' => 100
          ]);
        }
        $halaman = 1;
        if (!empty($_POST['halaman'])) {
          $halaman = $validate->setRules('halaman', 'Halaman aktif ', [
            'numeric' => true
          ]);
        }
        // jumlah baris
        $limit = 'all';
        if (!empty($_POST['rows'])) {
          if ($_POST['rows'] != 'all') {
            $limit = $validate->setRules('rows', 'Jumlah Halaman', [
              'numeric' => true
            ]);
            // var_dump($limit);
          };
        }
        $Fungsi = new MasterFungsi();
        //================
        //PROSES VALIDASI
        //================
        switch ($jenis) {
          case 'upload':
            switch ($tbl) {
              case 'daftar_paket':
                $val_in_array = ['file_kontrak', 'file_addendum', 'file_pho', 'file_fho', 'file_laporan', 'file_dokumentasi0', 'file_dokumentasi50', 'file_dokumentasi100'];
                break;
              case 'value':
                # code...
                break;
              default:
                # code...
                break;
            }
            switch ($tbl) {
              case 'daftar_paket':
                $id_row = $validate->setRules('id_row', 'dokumen', [
                  'required' => true,
                  'numeric' => true,
                  'min_char' => 1
                ]);
                $dok = $validate->setRules('dok', 'jenis dokumen', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'in_array' => $val_in_array
                ]);
                break;
              default:
                # code...
                break;
            }

            break;
          case 'get_data':
            switch ($tbl) {
              case 'satuan':
                $value = $validate->setRules('value', 'value pengenal', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 150
                ]);
                break;
              case 'realisasi':
                $id_row = $validate->setRules('id_row', 'id_row', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                break;
              case 'akun_belanja':
              case 'neraca':
                $akun = $validate->setRules('akun', 'akun', [
                  'required' => true,
                  'nuneric' => true,
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                $kelompok = null;
                $jenis_akun = null;
                $objek = null;
                $rincian_objek = null;
                $sub_rincian_objek = null;
                $kelompok = $validate->setRules('kelompok', 'kelompok', [
                  'nuneric' => true,
                  'max_char' => 100
                ]);
                $kode = (int)$akun;
                if ($kelompok > 0) {
                  $jenis_akun = $validate->setRules('jenis_akun', 'jenis', [
                    'nuneric' => true,
                    'max_char' => 100
                  ]);
                  if ($jenis_akun > 0) {
                    $kode = implode('.', [$akun, (int)$kelompok, $Fungsi->zero_pad((int)$jenis_akun, 2)]);
                    $objek = $validate->setRules('objek', 'objek', [
                      'nuneric' => true,
                      'max_char' => 100
                    ]);
                    if ($objek > 0) {
                      $kode = implode('.', [$akun, (int)$kelompok, $Fungsi->zero_pad((int)$jenis_akun, 2), $Fungsi->zero_pad((int)$objek, 2)]);
                      $rincian_objek = $validate->setRules('rincian_objek', 'rincian objek', [
                        'nuneric' => true,
                        'max_char' => 100
                      ]);
                      if ($rincian_objek > 0) {
                        $kode = implode('.', [$akun, (int)$kelompok, $Fungsi->zero_pad((int)$jenis_akun, 2), $Fungsi->zero_pad((int)$objek, 2), $Fungsi->zero_pad((int)$rincian_objek, 2)]);
                        $sub_rincian_objek = $validate->setRules('sub_rincian_objek', 'sub rincian objek', [
                          'nuneric' => true,
                          'max_char' => 100
                        ]);
                        if ((int)$sub_rincian_objek > 0) {
                          $kode = implode('.', [$akun, (int)$kelompok, $Fungsi->zero_pad((int)$jenis_akun, 2), $Fungsi->zero_pad((int)$objek, 2), $Fungsi->zero_pad((int)$rincian_objek, 2), $Fungsi->zero_pad((int)$sub_rincian_objek, 4)]);
                        }
                      }
                    }
                  }
                }
                // var_dump($kode);
                break;
              case 'bidang_urusan':
              case 'prog':
              case 'keg':
              case 'sub_keg':
                $urusan = $validate->setRules('urusan', 'urusan', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);

                if ($urusan !== 'x' || $urusan !== 'X') {
                  $urusan = $validate->setRules('urusan', 'urusan', [
                    'numeric' => true
                  ]);
                } else {
                  $urusan = $validate->setRules('urusan', 'urusan', [
                    'strtolower' => true,
                    'in_array' => ['x', 'X'],
                  ]);
                }
                $kode = implode('.', [$urusan]);
                $bidang = '';
                $prog = 0;
                $keg = '';
                $sub_keg = 0;
                $bidang = $validate->setRules('bidang', 'bidang', [
                  'sanitize' => 'string',
                  'max_char' => 100
                ]);
                if (strlen($bidang)) {
                  if (strtolower($bidang) !== 'xx') {
                    $bidang = $validate->setRules('bidang', 'bidang', [
                      'numeric' => true
                    ]);
                  } else {
                    $bidang = $validate->setRules('bidang', 'bidang', [
                      'strtolower' => true,
                      'in_array' => ['xx', 'XX'],
                    ]);
                  }
                  $kode = implode('.', [$urusan, $bidang]);
                  //program
                  $prog = $validate->setRules('prog', 'program', [
                    'sanitize' => 'string',
                    'max_char' => 100
                  ]);
                  if (strlen($prog)) {
                    $prog = $validate->setRules('prog', 'program', [
                      'numeric' => true
                    ]);
                    if ($prog > 0) {
                      $kode = implode('.', [$urusan, $bidang, $Fungsi->zero_pad($prog, 2)]);
                      $keg = $validate->setRules('keg', 'kegiatan', [
                        'sanitize' => 'string',
                        'max_char' => 100
                      ]);
                      $explode_keg = explode('.', $keg);
                      // var_dump($explode_keg);
                      if (strlen($keg) > 0 && (int)$explode_keg[0] > 0 && (int)$explode_keg[1] > 0) {
                        $sub_keg = $validate->setRules('sub_keg', 'sub kegiatan', [
                          'sanitize' => 'string',
                          'max_char' => 100
                        ]);
                        $kode = implode('.', [$urusan, $bidang, $Fungsi->zero_pad($prog, 2), $keg]);
                        if (strlen($sub_keg)) {
                          $sub_keg = $validate->setRules('sub_keg', 'sub kegiatan', [
                            'numeric' => true
                          ]);
                          if ($sub_keg > 0) {
                            $kode = implode('.', [$urusan, $bidang, $Fungsi->zero_pad($prog, 2), $keg, $Fungsi->zero_pad($sub_keg, 4)]);
                          }
                        }
                      }
                    }
                  }
                }
                // var_dump($kode);
                break;
              default:
                $text = $validate->setRules('text', 'text', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 255
                ]);
                switch ($tbl) {
                  case 'peraturan':
                  case 'user':
                  case 'asn':
                    $klm = $validate->setRules('klm', 'kolom', [
                      'numeric' => true,
                      'required' => true,
                      'min_char' => 1,
                      'max_char' => 11
                    ]);
                    break;
                  default:
                    $kondisi = [['kode', '= ?', $text]];
                    break;
                }

                break;
            }

            break;
          case 'edit':
            $id_row = $validate->setRules('id_row', 'id_row', [
              'numeric' => true,
              'required' => true,
              'min_char' => 1,
              'max_char' => 11
            ]);
            switch ($tbl) {
              case 'dppa':
              case 'renja_p':
              case 'dpa':
              case 'renja':
                $id_sub_keg = $validate->setRules('id_sub_keg', 'nomor sub kegiatan', [
                  'numeric' => true,
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                break;
            }
            break;
          case 'get_tbl':
            switch ($tbl) {
              case 'sk_asn':
                break;
              case 'renstra':
              case 'realisasi':
              case 'daftar_paket':
              case 'sub_keg_dpa':
              case 'sub_keg_renja':
                if ($rowPengaturan === false) {
                  $error = $validate->setRules('error', "Pengaturan Tahun Anggran $tahun belum di buat, hubungi administrator", [
                    'error' => true
                  ]);
                }
                break;
              case 'dpa':
              case 'dppa':
              case 'renja_p':
              case 'renja':
                if ($rowPengaturan === false) {
                  $error = $validate->setRules('error', "Pengaturan Tahun Anggran $tahun belum di buat, hubungi administrator", [
                    'error' => true
                  ]);
                }
                $id_sub_keg = $validate->setRules('id_sub_keg', 'nomor sub kegiatan', [
                  'numeric' => true,
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                break;
              default:
                # code...
                break;
            }
            break;
          case 'getJsonRows':
            switch ($tbl) {
              case 'sub_keg_renja':
              case 'sub_keg_dpa':
                $id_sub_keg = $validate->setRules('id_sub_keg', 'nomor sub kegiatan', [
                  'numeric' => true,
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                $nama_kolom = $validate->setRules('klm', 'nama kolom', [
                  'sanitize' => 'string',
                  'required' => true,
                  'min_char' => 3
                ]);
                break;

              default:
                # code...
                break;
            }
            break;
          case 'get_rows':
            switch ($tbl) {
              case 'dpa_and_dppa': //jenis tabel tergantung dari data dok_anggaran tiap baris
                $send = $validate->setRules('send', 'data', [
                  'min_char' => 8,
                  'required' => true,
                  'json_repair' => true
                ]);
                $send = json_decode($send);
                break;
              default:
                break;
            }
            break;
          case 'get_row':
            switch ($tbl) {
              case 'sbu':
              case 'ssh':
              case 'hspk':
              case 'asb':
                $id_row = $validate->setRules('id_row', 'id_row', [
                  'required' => true,
                  'sanitize' => 'string',
                  'min_char' => 1,
                  'max_char' => 100
                ]);
                break;
              default:
                break;
            }
            break;
          case 'get_Search_Json':
            switch ($tbl) {
              case 'sbu':
              case 'ssh':
              case 'hspk':
              case 'asb':
                $kd_akun = $validate->setRules('kd_akun', "kode akun", [
                  'sanitize' => 'string',
                  'required' => true,
                  'inDB' => ['akun_neo', 'kode', [['disable', '<=', 0], ['peraturan', '=', ${"aturan_$tbl"}, 'AND']]],
                  'min_char' => 1
                ]);
                break;
              case 'renja':
              case 'dpa':
              case 'dppa':
              case 'renja_p':
                break;
              case 'dpa_dppa':
                $tabel_pakai_temporerSubkeg = 'sub_keg_dpa_neo';
                $kd_sub_keg = $validate->setRules('kd_sub_keg', "kd_sub_keg", [
                  'sanitize' => 'string',
                  'required' => true,
                  'inDB' => [$tabel_pakai_temporerSubkeg, 'kd_sub_keg', [['kd_sub_keg', '=', $_POST['kd_sub_keg']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]],
                  'min_char' => 1
                ]);
                break;
              default:
                break;
            }
            break;
          case 'get_row_json':
            // isset($_POST['jenis'])
            if (isset($_POST['kd_akun'])) {
              $kd_sub_keg = $validate->setRules('kd_akun', "kode akun", [
                'sanitize' => 'string',
                'required' => true,
                'inDB' => ['akun_neo', 'kode', [['kode', '=', $_POST['kd_akun']]]],
                'min_char' => 1
              ]);
            }
            break;
          case 'get_field_json':
            // $nama_kolom = $validate->setRules('klm', 'nama kolom', [
            //     'sanitize' => 'string',
            //     'required' => true,
            //     'min_char' => 3
            // ]);
            $tabel_pakai_temporer = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
            $id_sub_keg = $validate->setRules('id_sub_keg', 'sub kegiatan', [
              'sanitize' => 'string',
              'numeric' => true,
              'required' => true,
              'inDB' => [$tabel_pakai_temporer, 'id', [['id', '=', (int)$_POST['id_sub_keg']], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']]]
            ]);
            $nama_kolom = $validate->setRules('klm', 'kolom', [
              'sanitize' => 'string',
              'required' => true,
              'min_char' => 3
            ]);
            switch ($nama_kolom) {
              case 'kelompok_json':
                $jenis_kelompok = $validate->setRules('jns_kel', 'jenis kelompok belanja', [
                  'sanitize' => 'string',
                  'required' => true,
                  'in_array' => ['kelompok', 'paket'],
                  'min_char' => 3
                ]);
                break;
              default:
                // $jenis_kelompok nama key json
                $jenis_kelompok = $validate->setRules('jns_kel', 'jenis kelompok belanja', [
                  'sanitize' => 'string',
                  'required' => true,
                  'min_char' => 1
                ]);
                break;
            }
            break;
          case 'yyyyyy':
            // isset($_POST['jenis'])
            break;
          case 'xxxx':
            $posisi = $validate->setRules('posisi', 'posisi', [
              'sanitize' => 'string',
              'required' => true,
              'in_array' => ['top', 'bottom']
            ]);
            break;
          default:
            # code...
            break;
        }
        $jumlah_kolom = 0;
        //==============================
        //== FINISH PROSES VALIDASI ====
        //==============================
        if ($validate->passed()) {
          $code = 55;
          //tabel pakai
          $tabel_pakai = $Fungsi->tabel_pakai($tbl)['tabel_pakai'];
          $jumlah_kolom = $Fungsi->tabel_pakai($tbl)['jumlah_kolom'];
          $kolom = '*';
          $sukses = true;
          $dataJson = [];
          $kodePosting = '';
          $value_dinamic = [];

          switch ($jenis) {
            case 'get_row':
              $kodePosting = 'get_row';
              switch ($tbl) {
                case 'sbu':
                case 'ssh':
                case 'hspk':
                case 'asb':
                  $kondisi_result = [['kd_wilayah', '=', $kd_wilayah], ['peraturan', '=', ${"aturan_$tbl"}, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $id_row, 'AND']];
                  break;
                case 'user':
                  $kondisi_result = [['username', '=', $username], ['kd_wilayah', '=', $kd_wilayah, 'AND']];
                  $kodePosting = 'get_row';
                  break;
                default:
                  break;
              }
              break;
            case 'upload':
              switch ($tbl) {
                case 'logo':
                  $kondisi_result = [['kode', '=', $kd_wilayah]];
                  $kodePosting = 'get_row';
                  $DB->select("kode,logo");
                  break;
                case 'daftar_paket':
                  $kondisi_result = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id', '=', $id_row, 'AND']];
                  $kodePosting = 'get_row';
                  $DB->select("kd_sub_keg,uraian,$dok");
                  break;
                case 'user':
                  $kondisi_result = [['username', '=', $username], ['id', '>', 0, 'AND']];
                  $kodePosting = 'get_row';
                  break;
                case 'value':
                  # code...
                  break;
                default:
                  # code...
                  break;
              }

              break;
            case 'atur':
              switch ($tbl) {
                case 'organisasi':
                  $like = "kd_wilayah = ? AND disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nama_kepala LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, 0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND disable <= ?";
                  $data_where1 = [$kd_wilayah, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, 0];
                  $jumlah_kolom = 7;
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'get_pengaturan':
              $rowTahunAktif = $DB->getWhereOnceCustom($tabel_pakai, [['tahun', '=', $tahun], ['kd_wilayah', '=', $kd_wilayah, 'AND']]);
              if ($rowTahunAktif !== false) {
                $rowTahun = $rowTahunAktif;
                $DB->select('*');
                $data['row_tahun'] = $rowTahun;
                //tambahkan value dropdown organisasi
                $cari_drop = $data['row_tahun']->id_opd_tampilkan;
                if ($cari_drop) {
                  $kondisi_result = [['disable', '<=', 0], ['id', '=', $cari_drop, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND']];
                  $row = $DB->getWhereOnceCustom('organisasi_neo', $kondisi_result);
                  if ($row !== false) {
                    $data['values']['id_opd_tampilkan'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->id, 'description' => $row->kode, "descriptionVertical" => true, 'selected' => true]];
                  }
                }
              } else {
                $rowTahun = "pengaturan tahun $tahun tidak ditemukan";
              }
              $data['tahun'] = $tahun;
              $data['row_tahun'] = $rowTahun;
              //pilih kolom yang diambil
              $DB->select('id, judul,judul_singkat, nomor, tgl_pengundangan, keterangan');
              $peraturan = $DB->getWhereArray('peraturan_neo', [['disable', '=', 0], ['status', '=', 'umum', 'AND']]);
              //var_dump(count($peraturan));
              $jumlahArray = is_array($peraturan) ? count($peraturan) : 0;
              $dataJson['results'] = [];
              if ($jumlahArray > 0) {
                foreach ($peraturan as $row) {
                  $dataJson['results'][] = ['name' => $row->judul, 'text' => $row->judul_singkat, 'value' => $row->id, 'description' => $row->nomor, "descriptionVertical" => true];
                }
              }
              $DB->select('*');
              //var_dump($peraturan);
              $data['peraturan'] = $dataJson['results'];
              break;
            case 'get_tbl':
              $kodePosting = 'get_tbl';
              switch ($tbl) {
                case 'create_surat':
                  $like = "kd_wilayah = ? AND tahun = ? AND kd_opd = ? AND disable <= ? AND(nomor LIKE CONCAT('%',?,'%') OR tgl_surat_dibuat LIKE CONCAT('%',?,'%') OR tentang LIKE CONCAT('%',?,'%') OR nama_pemberi_tgs LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $kd_opd, 0, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY tgl_surat_dibuat ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                  $whereGet_row_json = "kd_wilayah = ? kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun, 0];
                  break;
                case 'register_surat':
                  $like = "kd_wilayah = ? AND tahun = ? AND kd_opd = ? AND disable <= ? AND(nomor LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR tanggal LIKE CONCAT('%',?,'%') OR asal_surat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $kd_opd, 0, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY tanggal ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                  break;
                case 'sk_asn':
                  $like = "kd_wilayah = ? AND tahun = ? AND kd_opd = ? AND disable <= ? AND(nomor LIKE CONCAT('%',?,'%') OR tgl_surat_dibuat LIKE CONCAT('%',?,'%') OR tentang LIKE CONCAT('%',?,'%') OR nama_ditugaskan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $kd_opd, 0, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY tgl_surat_dibuat ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                  $whereGet_row_json = "kd_wilayah = ? kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun, 0];
                  break;
                case 'realisasi':
                  $like = "kd_wilayah = ? AND tahun = ? AND kd_opd = ? AND disable <= ? AND(ket_paket LIKE CONCAT('%',?,'%') OR ket_uraian_paket LIKE CONCAT('%',?,'%') OR tanggal LIKE CONCAT('%',?,'%') OR jumlah LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $kd_opd, 0, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY id_paket ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                  $whereGet_row_json = "kd_wilayah = ? kd_opd = ? AND tahun = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun, 0];
                  break;
                case 'berita':
                  $like = "kd_wilayah = ? AND id > ? AND(jenis LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%') OR uraian_html LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kelompok, urutan ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND id > ?";
                  $data_where1 =  [$kd_wilayah, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND id > ? AND";
                  $data_hereGet_row_json = [$kd_wilayah, 0];
                  break;
                case 'users':
                  $like = "id > ? AND(nama LIKE CONCAT('%',?,'%') OR username LIKE CONCAT('%',?,'%') OR email LIKE CONCAT('%',?,'%') OR kd_organisasi LIKE CONCAT('%',?,'%') OR nama_org LIKE CONCAT('%',?,'%') OR kd_wilayah LIKE CONCAT('%',?,'%') OR tgl_daftar LIKE CONCAT('%',?,'%') OR tahun LIKE CONCAT('%',?,'%') OR ket LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY nama ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "id > ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "id > ?";
                  $data_hereGet_row_json = [0];
                  break;
                case 'asn':
                  $like = "kd_wilayah = ? AND kd_opd = ? AND(nama LIKE CONCAT('%',?,'%') OR 	nip LIKE CONCAT('%',?,'%') OR t4_lahir LIKE CONCAT('%',?,'%') OR tgl_lahir LIKE CONCAT('%',?,'%') OR jabatan LIKE CONCAT('%',?,'%') OR no_ktp LIKE CONCAT('%',?,'%') OR npwp LIKE CONCAT('%',?,'%') OR no_ktp LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR nama_anak LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $kd_opd, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kelompok, urutan, id ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd];
                  $whereGet_row_json = "kd_wilayah = ? kd_opd = ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd];
                  break;
                case 'daftar_paket':
                  $like = "kd_wilayah = ? AND tahun = ? AND kd_opd = ? AND(uraian LIKE CONCAT('%',?,'%') OR 	metode_pengadaan LIKE CONCAT('%',?,'%') OR metode_pemilihan LIKE CONCAT('%',?,'%') OR pengadaan_penyedia LIKE CONCAT('%',?,'%') OR jns_kontrak LIKE CONCAT('%',?,'%') OR nama_rekanan LIKE CONCAT('%',?,'%') OR nama_ppk LIKE CONCAT('%',?,'%') OR tgl_kontrak LIKE CONCAT('%',?,'%') OR no_kontrak LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $kd_opd, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY uraian ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun];
                  $whereGet_row_json = "kd_wilayah = ? kd_opd = ? AND tahun = ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun];
                  break;
                case 'hspk':
                case 'sbu':
                case 'ssh':
                case 'asb':
                  $like = "kd_wilayah = ? AND tahun = ? AND(kd_aset LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_aset ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND tahun = ?";
                  $data_where1 =  [$kd_wilayah, $tahun];
                  $whereGet_row_json = "kd_wilayah = ? AND tahun = ?";
                  $data_hereGet_row_json = [$kd_wilayah, $tahun];
                  //untuk input dan edit renja dpa dkk
                  if (isset($kd_sub_keg)) {
                    $like = "kd_wilayah = ? AND tahun = ? AND kd_akun  LIKE CONCAT('%',?,'%') AND(kd_aset LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%'))";
                    $data_like = [$kd_wilayah, $tahun, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                    $whereGet_row_json = "kd_wilayah = ? AND tahun = ? AND kd_akun  LIKE CONCAT('%',?,'%')";
                    $data_hereGet_row_json = [$kd_wilayah, $tahun, $kd_sub_keg];
                    $where1 = "kd_wilayah = ? AND tahun = ? AND kd_akun  LIKE CONCAT('%',?,'%')";
                    $data_where1 =  [$kd_wilayah, $tahun, $kd_sub_keg];
                  }
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 7;
                  break;
                case 'mapping':
                  $like = "disable <= ? AND(kd_aset LIKE CONCAT('%',?,'%') OR uraian_aset LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%') OR uraian_akun LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_aset ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 11;
                  break;
                case 'sub_keg':
                  // $kondisi = [['kode', '=', $kd_wilayah], ['nomenklatur_urusan', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'tujuan', 'AND']];
                  $like = "sub_keg > ? AND(kode LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "sub_keg > ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "sub_keg > ?";
                  $data_hereGet_row_json =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  break;
                case 'keg':
                  $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND sub_keg < ?";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ? AND sub_keg <= ?";
                  $data_where1 =  [0, 0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 11;
                  break;
                case 'prog':
                  $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND keg < ?";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ? AND keg <= ?";
                  $data_where1 =  [0, 0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 11;
                  break;
                case 'bidang_urusan':
                  $like = "disable <= ? AND(bidang LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%')) AND prog < ?";
                  $data_like = [0, $cari, $cari, $cari, $cari, $cari, $cari, 0];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ? AND prog <= ?";
                  $data_where1 =  [0, 0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 11;
                  break;
                case 'aset':
                case 'akun_belanja':
                case 'akun_belanja_val':
                  $like = "sub_rincian_objek > ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "sub_rincian_objek > ?";
                  $data_hereGet_row_json =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  break;
                case 'sumber_dana':
                  $like = "id > ? AND(uraian LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "id > ?";
                  $data_hereGet_row_json =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 9;
                  break;
                case 'peraturan':
                  $like = "kd_wilayah = ? AND(judul LIKE CONCAT('%',?,'%') OR bentuk_singkat LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nomor LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY tgl_pengundangan ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ?";
                  $data_where1 = [$kd_wilayah];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 6;
                  break;
                case 'organisasi':
                  $like = "kd_wilayah = ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nama_kepala LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ?";
                  $data_where1 = [$kd_wilayah];
                  $whereGet_row_json = "kd_wilayah = ?";
                  $data_hereGet_row_json = [$kd_wilayah];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 7;
                  break;
                case 'wilayah':
                  $like = "disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR status LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ?";
                  $data_hereGet_row_json = [0];
                  break;
                case 'satuan':
                  $like = "disable <= ? AND(value LIKE CONCAT('%',?,'%') OR item LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR sebutan_lain LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY value ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ?";
                  $data_hereGet_row_json = [0];
                  break;
                case 'rekanan':
                  $like = "kd_wilayah = ? AND nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR data_lain LIKE CONCAT('%',?,'%') OR file LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%')";
                  $data_like = [$kd_wilayah, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY nama_perusahaan, id ASC";
                  $where1 = "kd_wilayah = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND disable <= ?";
                  $data_hereGet_row_json =  [$kd_wilayah, 0];
                  break;
                case 'renja':
                case 'dpa':
                case 'renja_p':
                case 'dppa':
                  switch ($tabel_pakai) {
                    case 'dppa_neo':
                    case 'renja_p_neo':
                      $kolomHarga_satuan = 'harga_satuan';
                      break;
                    default:
                      $kolomHarga_satuan = 'harga_satuan';
                      break;
                  }
                  $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                  if ($rowOrganisasi !== false) {
                    $unit_kerja = $rowOrganisasi->uraian;
                    $jumlahRincian_Input = 'jumlah_rincian';
                    switch ($tbl) {
                      case 'renja':
                        $tabel_sub_keg = 'sub_keg_renja_neo';
                        $tabel_tbl = 'sub_keg_renja';
                        break;
                      case 'renja_p':
                        $tabel_sub_keg = 'sub_keg_renja_neo';
                        $tabel_tbl = 'sub_keg_renja';
                        $jumlahRincian_Input = 'jumlah_rincian_p';
                        break;
                      case 'dpa':
                        $tabel_sub_keg = 'sub_keg_dpa_neo';
                        $tabel_tbl = 'sub_keg_dpa';
                        break;
                      case 'dppa':
                        $tabel_sub_keg = 'sub_keg_dpa_neo';
                        $tabel_tbl = 'sub_keg_dpa';
                        $jumlahRincian_Input = 'jumlah_rincian_p';
                        break;
                      default:
                        #code...
                        break;
                    };
                    $value_dinamic = ['id_sub_keg' => $id_sub_keg];
                    $rowSubKeg = $DB->getWhereOnceCustom($tabel_sub_keg, [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['id', '=', $id_sub_keg, 'AND']]);
                    if ($rowSubKeg !== false) {
                      $kd_sub_keg = $rowSubKeg->kd_sub_keg;
                      $group_by = "GROUP BY sumber_dana, jenis_kelompok, kelompok, uraian,komponen,$kolomHarga_satuan";
                      $data['unit_kerja'] = "$unit_kerja ($kd_opd)";
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND kd_sub_keg = ? AND kel_rek = ? AND (kd_sub_keg LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%') OR komponen LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun, $kd_sub_keg, 'uraian', $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                      $order = "ORDER BY kd_sub_keg, jenis_kelompok,kelompok,uraian,komponen ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ? AND kd_sub_keg = ? AND kel_rek = ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0, $kd_sub_keg, 'uraian'];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kd_sub_keg', '=', $kd_sub_keg, 'AND'], ['kel_rek', '=', 'uraian', 'AND']];
                      //tambahkan data dari tabel sub_keg_renja_neo/sub_keg_dpa_neo nama sub kegiatan/kegiatan/program/bidang/perangkat daerah
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND disable <= ? AND kd_sub_keg = ? ";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun, 0, $kd_sub_keg];
                      $dinamic = ['tbl' => $tabel_tbl, 'kode' => $kd_sub_keg, 'column' => 'id, kd_sub_keg, uraian, jumlah_pagu, jumlah_pagu_p, jumlah_rincian,jumlah_rincian_p'];
                      $bidang_sub_keg = $Fungsi->get_bidang_sd_sub_keg($dinamic);
                      // var_dump($bidang_sub_keg);
                      // keterangan di atas
                      $data['tr_sub_keg'] = '<tr>
                                                    <td class="collapsing">Perangkat Daerah</td>
                                                    <td>' . $data['unit_kerja'] . '</td>
                                                    <td class="right aligned collapsing">Rp. ' . number_format((float)$bidang_sub_keg['opd']->{$jumlahRincian_Input}, 2, ',', '.') . '</td>
                                                </tr>
                                                <tr>
                                                    <td>Bidang</td>
                                                    <td>' . $bidang_sub_keg['kd_bidang']->uraian . ' (' . $bidang_sub_keg['kd_bidang']->kd_sub_keg . ')</td>
                                                    <td class="right aligned collapsing">Rp. ' . number_format((float)$bidang_sub_keg['kd_bidang']->{$jumlahRincian_Input}, 2, ',', '.') . '</td>
                                                </tr>
                                                <tr>
                                                    <td>Program</td>
                                                    <td>' . $bidang_sub_keg['kd_prog']->uraian . ' (' . $bidang_sub_keg['kd_prog']->kd_sub_keg . ')</td>
                                                    <td class="right aligned collapsing">Rp. ' . number_format((float)$bidang_sub_keg['kd_prog']->{$jumlahRincian_Input}, 2, ',', '.') . '</td>
                                                </tr>
                                                <tr>
                                                    <td>Kegiatan</td>
                                                    <td>' . $bidang_sub_keg['kd_keg']->uraian . ' (' . $bidang_sub_keg['kd_keg']->kd_sub_keg . ')</td>
                                                    <td class="right aligned collapsing">Rp. ' . number_format((float)$bidang_sub_keg['kd_keg']->{$jumlahRincian_Input}, 2, ',', '.') . '</td>
                                                </tr>
                                                <tr>
                                                    <td>Sub Kegiatan</td>
                                                    <td>' . $bidang_sub_keg['kd_sub_keg']->uraian . ' (' . $bidang_sub_keg['kd_sub_keg']->kd_sub_keg . ')</td>
                                                    <td class="right aligned collapsing">Rp. ' . number_format((float)$bidang_sub_keg['kd_sub_keg']->{$jumlahRincian_Input}, 2, ',', '.') . '</td>
                                                </tr>';
                      $data['tr_sub_keg'] = preg_replace('/(\s\s+|\t|\n)/', ' ', $data['tr_sub_keg']);
                    } else {
                      $message_tambah = ' (kode sub kegiatan tidak ditemukan)';
                      $kodePosting = '';
                      $code = 70;
                    }
                  } else {
                    $data['tr_sub_keg'] = '';
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'tujuan_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND kelompok = ?  AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, 'tujuan', $cari, $cari, $cari];
                      $order = "ORDER BY id, id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_where1 = [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'tujuan'];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'tujuan', 'AND']];
                      //pilih kolom yang diambil
                      $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'tujuan'];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'sasaran_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND kelompok = ?  AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, 'sasaran', $cari, $cari, $cari];
                      $order = "ORDER BY id, id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'sasaran'];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'sasaran', 'AND']];
                      //pilih kolom yang diambil
                      $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'sasaran'];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'tujuan_sasaran_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari];
                      $order = "ORDER BY id, id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND']];
                      //pilih kolom yang diambil
                      // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (kd_sub_keg LIKE CONCAT('%',?,'%') OR uraian_prog_keg LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR data_capaian_awal LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari, $cari, $cari, $cari];
                      $order = "ORDER BY kd_sub_keg, tujuan, sasaran ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND']];
                      //pilih kolom yang diambil
                      // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'sub_keg_renja':
                case 'sub_keg_dpa':
                  $rowOrganisasi = $DB->getWhereOnceCustom('organisasi_neo', [['kd_wilayah', '=', $kd_wilayah], ['kode', '=', $kd_opd, 'AND']]);
                  if ($rowOrganisasi !== false) {
                    $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (kd_sub_keg LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR tolak_ukur_capaian_keg LIKE CONCAT('%',?,'%') OR tolak_ukur_keluaran LIKE CONCAT('%',?,'%') OR keluaran_sub_keg LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                    $data_like = [$kd_wilayah, $kd_opd, $tahun, $cari, $cari, $cari, $cari, $cari, $cari];
                    $order = "ORDER BY kd_sub_keg ASC";
                    $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                    $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0];
                    $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND']];
                    //pilih kolom yang diambil
                    // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                    $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                    $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun, 0];
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'get_data':
              $kodePosting = 'get_data';
              switch ($tbl) {
                case 'rekanan':
                  $kondisi = [['kd_wilayah', '= ?', $kd_wilayah], ['nama_perusahaan', '= ?', $text, 'AND']];
                  break;
                case 'neraca':
                case 'akun_belanja':
                  $kondisi = [['kode', '= ?', $kode]];
                  break;
                case 'satuan':
                  $kondisi = [['value', '= ?', $value]];
                  break;
                case 'bidang_urusan':
                  $kondisi = [['urusan', '= ?', $urusan], ['bidang', '= ?', $bidang, 'AND'], ['kode', '= ?', $kode, 'AND']];
                  break;
                case 'prog':
                  $kondisi = [['urusan', '= ?', $urusan], ['bidang', '= ?', $bidang, 'AND'], ['prog', '= ?', $prog, 'AND'], ['kode', '= ?', $kode, 'AND']];
                  break;
                case 'keg':
                  $kondisi = [['urusan', '= ?', $urusan], ['bidang', '= ?', $bidang, 'AND'], ['prog', '= ?', $prog, 'AND'], ['keg', '= ?', $keg, 'AND'], ['kode', '= ?', $kode, 'AND']];
                  break;
                case 'sub_keg':
                  //get data dari modal second
                  $kondisi = [['urusan', '= ?', $urusan], ['bidang', '= ?', $bidang, 'AND'], ['prog', '= ?', $prog, 'AND'], ['keg', '= ?', $keg, 'AND'], ['sub_keg', '= ?', $sub_keg, 'AND'], ['kode', '= ?', $kode, 'AND']];
                  break;
                case 'wilayah':
                  //get data dari modal second
                  $kondisi = [['kode', '= ?', $text]];
                  break;
                case 'realisasi':
                  //get data dari modal second
                  $kondisi = [['id', '= ?', $id_row], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND'], ['tahun', '= ?', $tahun, 'AND']];
                  break;
                default:
                  switch ($tbl) {
                    case 'asn':
                      $kondisi = [[$klm, '= ?', $text], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND']];
                      break;
                    case 'user':
                    case 'peraturan':
                      $kondisi = [[$klm, '= ?', $text]];
                      break;
                    default:
                      $kondisi = [['kode', '= ?', $text]];
                      break;
                  }
                  break;
              }
              break;
            case 'edit':
              $kodePosting = 'get_data';
              $kondisi = [['id', '= ?', $id_row]];
              switch ($tbl) {
                case 'wilayah':
                  $kondisi = [['id', '= ?', $id_row]];
                  break;
                case 'realisasi':
                  $tabel_get_row = $Fungsi->tabel_pakai('realisasi')['tabel_pakai'];
                  $kondisi = [['id', '=', $id_row], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                  $rowRealisasi = $DB->getWhereOnceCustom($tabel_get_row, $kondisi);
                  if ($rowRealisasi !== false) {
                    $id_paket = $rowRealisasi->id_paket;
                    $id_uraian_paket = $rowRealisasi->id_uraian_paket;
                    $id_dok_anggaran = $rowRealisasi->id_dok_anggaran;
                    $dok = $rowRealisasi->dok;
                    $tanggal = $rowRealisasi->tanggal;
                    //ambil row paket
                    $tabel_get_row = $Fungsi->tabel_pakai('daftar_paket')['tabel_pakai'];
                    $kondisi = [['id', '=', $id_paket], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                    $row_paket = $DB->getWhereOnceCustom($tabel_get_row, $kondisi);
                    $uraian_paket = $row_paket->uraian;
                    $row_paket->ket_paket = $row_paket->keterangan;
                    $row_paket->tanggal_paket = $row_paket->tanggal;
                    $desimaljumlah = ($Fungsi->countDecimals($row_paket->jumlah) < 2) ? 2 : $Fungsi->countDecimals($row_paket->jumlah);
                    $row_paket->jumlah = number_format((float)$row_paket->jumlah, $desimaljumlah, ',', '.');
                    $row_paket->tanggal = $tanggal;
                    $row_paket->keterangan = $rowRealisasi->keterangan;
                    $data['users'] = $row_paket;
                    //ambil rows realisasi
                    $tabel_get_row = $Fungsi->tabel_pakai('realisasi')['tabel_pakai'];
                    $kondisi = [['tanggal', '=', $tanggal], ['id_paket', '=', $id_paket, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                    $rows_realisasi = $DB->getWhereArray($tabel_get_row, $kondisi);
                    $rowTBody = '';
                    if ($rows_realisasi !== false) {
                      foreach ($rows_realisasi as $key => $value) {
                        $id_uraian_paket = $value->id_uraian_paket;
                        //ambil row uraian paket
                        $tabel_get_row = $Fungsi->tabel_pakai('uraian_paket')['tabel_pakai'];
                        $kondisi = [['id', '=', $id_uraian_paket], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                        $row_uraian_paket = $DB->getWhereOnceCustom($tabel_get_row, $kondisi);
                        $dok = $row_uraian_paket->dok;
                        //ambil row dok anggaran
                        switch ($dok) {
                          case 'dpa':
                          case 'renja':
                            $klmvolume = 'volume';
                            $klmjumlah = 'jumlah';
                            break;
                          case 'dppa':
                          case 'renja_p':
                            $klmvolume = 'volume_p';
                            $klmjumlah = 'jumlah_p';
                            break;
                        };
                        $tabel_get_row = $Fungsi->tabel_pakai($dok)['tabel_pakai'];
                        $kondisi = [['id', '=', $id_dok_anggaran], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['kel_rek', '=', 'uraian', 'AND']];
                        $row_dok_anggaran = $DB->getWhereOnceCustom($tabel_get_row, $kondisi);
                        //buat rows tbody
                        $desimalvol_kontrak = ($Fungsi->countDecimals($row_uraian_paket->vol_kontrak) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->vol_kontrak);
                        $desimaljumlah_pagu = ($Fungsi->countDecimals($row_uraian_paket->jumlah_pagu) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->jumlah_pagu);
                        $desimaljumlah_kontrak = ($Fungsi->countDecimals($row_uraian_paket->jumlah_kontrak) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->jumlah_kontrak);
                        $desimalrealisasi_vol = ($Fungsi->countDecimals($row_uraian_paket->realisasi_vol) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->realisasi_vol);
                        $desimalrealisasi_jumlah = ($Fungsi->countDecimals($row_uraian_paket->realisasi_jumlah) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->realisasi_jumlah);
                        $desimalvol = ($Fungsi->countDecimals($value->vol) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->vol);
                        $desimaljumlah = ($Fungsi->countDecimals($value->jumlah) < 2) ? 2 : $Fungsi->countDecimals($row_uraian_paket->jumlah);
                        $realisasi_jumlah = $row_uraian_paket->realisasi_jumlah - $value->jumlah;
                        $realisasi_vol = $row_uraian_paket->realisasi_vol - $value->vol;
                        $rowTBody .= '<tr id_row="' . $value->id . '" id_row_uraian_paket="' . $row_uraian_paket->id . '" pagu="433200.000000000000" dok_anggaran="' . $dok . '">//@audit now
                                                <td klm="kd_sub_keg">' . $row_uraian_paket->kd_sub_keg . '</td>
                                                <td klm="uraian">' . $row_dok_anggaran->uraian . '</td>
                                                <td klm="vol_kontrak">' . number_format((float)$row_uraian_paket->vol_kontrak, $desimalvol_kontrak, ',', '.') . '</td>
                                                <td klm="sat_kontrak">' . $row_uraian_paket->sat_kontrak . '</td>
                                                <td klm="pagu">' . number_format((float)$row_uraian_paket->jumlah_pagu, $desimaljumlah_pagu, ',', '.') . '</td>
                                                <td klm="jumlah_kontrak">' . number_format((float)$row_uraian_paket->jumlah_kontrak, $desimaljumlah_kontrak, ',', '.') . '</td>
                                                <td klm="realisasi_vol">' . number_format((float)$realisasi_vol, $desimalrealisasi_vol, ',', '.') . '</td>
                                                <td klm="realisasi_jumlah">' . number_format((float)$realisasi_jumlah, $desimalrealisasi_jumlah, ',', '.') . '</td>
                                                <td klm="vol" class="positive">
                                                    <div contenteditable oninput="onkeypressGlobal({ jns: \'realisasi\', tbl: \'vol_realisasi\' },this);" rms>' . number_format((float)$value->vol, $desimalvol, ',', '.') . '</div>
                                                </td>
                                                <td klm="jumlah" class="positive">
                                                    <div contenteditable oninput="onkeypressGlobal({ jns: \'realisasi\', tbl: \'vol_realisasi\' },this);" rms>' . number_format((float)$value->jumlah, $desimaljumlah, ',', '.') . '</div>
                                                </td>
                                                <td><button class="ui blue basic icon mini button" name="modal_second" jns="get_data" tbl="realisasi" id_row="' . $value->id . '"><i class="edit alternate outline icon"></i></button></td>
                                            </tr>';
                      }
                    } else {
                      $rowTBody .= '<tr><td colspan="12"><div class="ui icon info message"><i class="yellow exclamation circle icon"></i><div class="content"><div class="header">Data Tidak ditemukan </div><p>input data baru atau hubungi administrator</p></div></div></td></tr>';
                    }
                    $data['tbody'] = $rowTBody;
                  } else {
                    $code = 404;
                  }
                  $kodePosting = '';
                  break;
                case 'renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      // $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (kd_sub_keg LIKE CONCAT('%',?,'%') OR uraian_prog_keg LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR data_capaian_awal LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                      // $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari, $cari, $cari, $cari];
                      // $order = "ORDER BY kd_sub_keg, tujuan, sasaran ASC";
                      // $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['id', '= ?', $id_row], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND'], ['tahun', '= ?', $tahun_renstra, 'AND'], ['disable', '<= ?', 0, 'AND']];
                      //pilih kolom yang diambil
                      // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      // $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'tujuan_sasaran_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, $cari, $cari, $cari];
                      $order = "ORDER BY id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['id', '= ?', $id_row], ['kd_wilayah', '= ?', $kd_wilayah, 'AND'], ['kd_opd', '= ?', $kd_opd, 'AND'], ['tahun', '= ?', $tahun_renstra, 'AND'], ['disable', '<= ?', 0, 'AND']];
                      //pilih kolom yang diambil
                      // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'get_row_json': //ambil semua rows untuk dropdown
              $kodePosting = 'get_row_json';
              switch ($tbl) {
                case 'asn':
                  $like = "kd_wilayah = ? AND kd_opd = ? AND disable <= ? AND(nama LIKE CONCAT('%',?,'%') OR jabatan LIKE CONCAT('%',?,'%') OR npwp LIKE CONCAT('%',?,'%') OR nip LIKE CONCAT('%',?,'%') OR nama_anak LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $kd_opd, 0, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY id_jabatan ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND kd_opd = ? AND disable <= ?";
                  $data_where1 = [$kd_wilayah, $kd_opd, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, $kd_opd, 0];
                  break;
                case 'organisasi':
                  $like = "kd_wilayah = ? AND disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR nama_kepala LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, 0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND disable <= ?";
                  $data_where1 = [$kd_wilayah, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, 0];
                  break;
                case 'rekanan':
                  $like = "kd_wilayah = ? AND (nama_perusahaan LIKE CONCAT('%',?,'%') OR alamat LIKE CONCAT('%',?,'%') OR direktur LIKE CONCAT('%',?,'%') OR npwp LIKE CONCAT('%',?,'%') OR nama_notaris_pendirian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY nama_perusahaan ASC";
                  $where1 = "kd_wilayah = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, 0];
                  $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['disable', '<=', 0, 'AND']];
                  //pilih kolom yang diambil
                  // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                  $whereGet_row_json = "kd_wilayah = ? AND disable <= ? ";
                  $data_hereGet_row_json =  [$kd_wilayah, 0];
                  break;
                case 'sub_keg_renja':
                case 'sub_keg_dpa':
                  $like = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND kel_rek = ? AND (kd_sub_keg LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR tolak_ukur_capaian_keg LIKE CONCAT('%',?,'%') OR tolak_ukur_keluaran LIKE CONCAT('%',?,'%') OR keluaran_sub_keg LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $kd_opd, $tahun, 'sub_keg', $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_sub_keg ASC";
                  $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kel_rek = ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0, 'sub_keg'];
                  $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kel_rek', '=', 'sub_keg', 'AND']];
                  //pilih kolom yang diambil
                  // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                  $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kel_rek = ?";
                  $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun, 0, 'sub_keg'];
                  break;
                case 'sumber_dana':
                  $like = "disable <= ? AND(uraian LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ?";
                  $data_hereGet_row_json =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 9;
                  break;
                case 'sub_keg':

                  $like = "sub_keg > ? AND disable <= ? AND(kode LIKE CONCAT('%',?,'%') OR nomenklatur_urusan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kinerja LIKE CONCAT('%',?,'%') OR indikator LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, 0, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ? AND sub_keg > ?";
                  $data_where1 =  [0, 0];
                  $whereGet_row_json = "disable <= ? AND sub_keg > ?";
                  $data_hereGet_row_json =  [0, 0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  break;
                case 'sasaran_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND kelompok = ?  AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, 'sasaran', $cari, $cari, $cari];
                      $order = "ORDER BY id, id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_where1 =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'sasaran'];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'sasaran', 'AND']];
                      //pilih kolom yang diambil
                      $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'sasaran'];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'tujuan_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    if ($tahun_renstra > 2000) {
                      $like = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND kelompok = ?  AND (text LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR kelompok LIKE CONCAT('%',?,'%'))";
                      $data_like = [$kd_wilayah, $kd_opd, $tahun_renstra, 'tujuan', $cari, $cari, $cari];
                      $order = "ORDER BY id, id_tujuan ASC";
                      $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_where1 = [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'tujuan'];
                      // $where_row = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ?";
                      // $data_where_row =  [$kd_wilayah, $kd_opd, $tahun_renstra, 0];
                      $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'tujuan', 'AND']];
                      //pilih kolom yang diambil
                      $DB->select('id, kelompok, id_tujuan, text, keterangan');
                      $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kelompok = ?";
                      $data_hereGet_row_json = [$kd_wilayah, $kd_opd, $tahun_renstra, 0, 'tujuan'];
                    } else {
                      $message_tambah = ' (atur tahun renstra OPD)';
                      $code = 70;
                      $kodePosting = '';
                    }
                  } else {
                    $message_tambah = ' (atur organisasi OPD)';
                    $kodePosting = '';
                    $code = 70;
                  }
                  break;
                case 'satuan':
                  $like = "disable <= ? AND(value LIKE CONCAT('%',?,'%') OR item LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%') OR sebutan_lain LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY value ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ?";
                  $data_hereGet_row_json = [0];
                  break;
                case 'aset':
                case 'akun_belanja':
                case 'akun_belanja_val':
                  $like = "disable <= ? AND sub_rincian_objek > ? AND(kode LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, 0, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ? AND sub_rincian_objek > ?";
                  $data_hereGet_row_json =  [0, 0];
                  break;
                case 'hspk':
                case 'sbu':
                case 'ssh':
                case 'asb':
                  $like = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND (kd_aset LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, 0, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_aset ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND tahun = ? AND disable <= ?";
                  $data_where1 =  [$kd_wilayah, $tahun, 0];
                  $whereGet_row_json = "kd_wilayah = ? AND tahun = ? AND disable <= ?";
                  $data_hereGet_row_json = [$kd_wilayah, $tahun, 0];
                  //untuk input dan edit renja dpa dkk
                  if (isset($kd_sub_keg)) {
                    $like = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND (kd_akun LIKE CONCAT('%',?,'%') AND kd_aset LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%'))";
                    $data_like = [$kd_wilayah, $tahun, 0, $cari, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                    $whereGet_row_json = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND kd_akun  LIKE CONCAT('%',?,'%')";
                    $data_hereGet_row_json = [$kd_wilayah, $tahun, 0, $kd_sub_keg];
                    $where1 = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND kd_akun  LIKE CONCAT('%',?,'%')";
                    $data_where1 =  [$kd_wilayah, $tahun, 0, $kd_sub_keg];
                  }
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 7;
                  break;
                case 'wilayah':
                  $like = "disable <= ? AND(uraian LIKE CONCAT('%',?,'%') OR status LIKE CONCAT('%',?,'%') OR kode LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [0, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kode ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "disable <= ?";
                  $data_where1 =  [0];
                  $whereGet_row_json = "disable <= ?";
                  $data_hereGet_row_json =  [0];
                  // $where = "nomor = ?";
                  // $data_where =  [$text];
                  $jumlah_kolom = 9;
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'get_field_json':
              switch ($tbl) {
                case 'sub_keg_renja':
                case 'sub_keg_dpa':
                  $dataKondisiField = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                  if (isset($id_sub_keg)) {
                    $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                  }

                  $kodePosting = $jenis;
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'getJsonRows': //ambil semua rows untuk dropdown
              $kodePosting = 'getAllValJson';
              switch ($tbl) {
                case 'tujuan_renstra':
                  $rowPengaturan = $DB->getWhereOnceCustom('pengaturan_neo', [['kd_wilayah', '=', $kd_wilayah], ['tahun', '=', $tahun, 'AND']]);
                  if ($rowPengaturan !== false) {
                    $tahun_renstra = $rowPengaturan->tahun_renstra;
                    $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'tujuan', 'AND']];
                    //pilih kolom yang diambil
                    $DB->select('id, kelompok, id_tujuan, text, keterangan');
                  }
                  break;
                case 'sub_keg_renja':
                case 'sub_keg_dpa':
                  // var_dump(isset($nama_kolom));
                  if (isset($nama_kolom)) {
                    $DB->select($nama_kolom);
                    $kondisi = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND']];
                  }
                  break;
                case 'value':
                  break;
                default:
                  break;
              }
              break;
            case 'get_rows':
              $kodePosting = 'get_data';
              switch ($tbl) {
                case 'dpa_and_dppa':
                  $elm = '';
                  foreach ($send as $key => $value) {
                    // var_dump($key);
                    // var_dump($value);

                    $tabel_pakai = $Fungsi->tabel_pakai($value->dok_anggaran)['tabel_pakai'];
                    $kondisi = [['id', '=', $value->id], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kel_rek', '=', 'uraian', 'AND']];
                    $row_sub = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);

                    if ($row_sub !== false) {
                      $klm_jumlah = ($tabel_pakai == 'dpa_neo') ? 'jumlah' : 'jumlah_p';

                      $desimal = ($Fungsi->countDecimals($row_sub->$klm_jumlah) <= 2) ? 2 : $Fungsi->countDecimals($row_sub->$klm_jumlah);
                      $paguku = number_format((float)$row_sub->$klm_jumlah, $desimal, ',', '.');
                      $desimal = ($Fungsi->countDecimals($value->val_kontrak) <= 2) ? 2 : $Fungsi->countDecimals($value->val_kontrak);
                      $kontrak = number_format((float)$value->val_kontrak, $desimal, ',', '.');

                      $desimal2 = ($Fungsi->countDecimals($value->vol_kontrak) <= 2) ? 2 : $Fungsi->countDecimals($value->vol_kontrak);
                      $vol = number_format((float)$value->vol_kontrak, $desimal2, ',', '.');

                      $elm .= '<tr id_row="' . $row_sub->id . '" pagu="' . $row_sub->$klm_jumlah . '" dok_anggaran="' . $value->dok_anggaran . '"><td klm="kd_sub_keg">' . $row_sub->kd_sub_keg . '</td><td klm="uraian">' . $row_sub->uraian . '</td><td klm="vol_kontrak"><div contenteditable rms>' . $vol . '</div></td><td klm="sat_kontrak"><div contenteditable>' . $value->sat_kontrak . '</div></td><td klm="pagu">' . $paguku . '</td><td klm="kontrak"><div contenteditable rms oninput="onkeypressGlobal({ jns: \'uraian_sub_keg\', tbl:\'renja_p\'},this);">' . $kontrak . '</div></td><td><button class="ui red basic icon mini button" name="del_row" jns="direct" tbl="remove_uraian" id_row="' . $value->id . '"><i class="trash alternate outline icon"></i></button></td></tr>';
                    }
                  }
                  $data['users'] = $elm;
                  // var_dump($elm);
                  $kodePosting = '';
                  break;
                default:
                  break;
              }
              break;
            case 'get_Search_Json':
              $kodePosting = 'get_row_json';
              switch ($tbl) {
                case 'daftar_paket':
                  $like = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND (uraian LIKE CONCAT('%',?,'%') OR id_uraian LIKE CONCAT('%',?,'%') OR metode_pengadaan LIKE CONCAT('%',?,'%') OR metode_pemilihan LIKE CONCAT('%',?,'%') OR nama_rekanan LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $kd_opd, $tahun, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY id ASC";
                  $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun];
                  $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                  $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? ";
                  $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun];
                  break;
                case 'hspk':
                case 'sbu':
                case 'ssh':
                case 'asb':
                  $like = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND peraturan = ? AND (kd_aset LIKE CONCAT('%',?,'%') OR uraian_barang LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR satuan LIKE CONCAT('%',?,'%') OR harga_satuan LIKE CONCAT('%',?,'%') OR merek LIKE CONCAT('%',?,'%') OR kd_akun LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $tahun, 0, ${"aturan_$tbl"}, $cari, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_aset ASC";
                  $posisi = " LIMIT ?, ?";
                  $where1 = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND peraturan = ?";
                  $data_where1 =  [$kd_wilayah, $tahun, 0, ${"aturan_$tbl"}];
                  $whereGet_row_json = "kd_wilayah = ? AND tahun = ? AND disable <= ? AND peraturan = ?";
                  $data_hereGet_row_json = [$kd_wilayah, $tahun, 0, ${"aturan_$tbl"}];
                  break;
                case 'renja':
                case 'dpa':
                case 'dppa':
                case 'renja_p':
                  break;
                case 'dpa_dppa':
                  $tabel_pakai_temporerSubkeg = 'sub_keg_dpa_neo';
                  //tambahkan kriteria jika id nama uraian belum ada di tabel daftar_uraian_paket
                  $like = "kd_wilayah = ? AND kd_opd = ? AND tahun = ? AND kel_rek = ? AND kd_sub_keg = ? AND (jumlah LIKE CONCAT('%',?,'%') OR uraian LIKE CONCAT('%',?,'%') OR komponen LIKE CONCAT('%',?,'%') OR spesifikasi LIKE CONCAT('%',?,'%') OR sumber_dana LIKE CONCAT('%',?,'%') OR keterangan LIKE CONCAT('%',?,'%'))";
                  $data_like = [$kd_wilayah, $kd_opd, $tahun, 'uraian', $kd_sub_keg, $cari, $cari, $cari, $cari, $cari, $cari];
                  $order = "ORDER BY kd_akun ASC";
                  $where1 = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kel_rek = ? AND kd_sub_keg = ?";
                  $data_where1 =  [$kd_wilayah, $kd_opd, $tahun, 0, 'uraian', $kd_sub_keg];
                  $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kel_rek', '=', 'uraian', 'AND'], ['kd_sub_keg', '=', $kd_sub_keg, 'AND']];
                  //pilih kolom yang diambil
                  // $DB->select('id, kelompok, id_tujuan, text, keterangan');
                  $whereGet_row_json = "kd_wilayah = ? AND kd_opd = ?  AND tahun = ? AND disable <= ? AND kel_rek = ?";
                  $data_hereGet_row_json =  [$kd_wilayah, $kd_opd, $tahun, 0, 'uraian'];
                  break;
                default:
                  break;
              }
              break;
            default:
              $kodePosting = '';
              $code = 204;
              break;
          };

          // var_dump($kodePosting);
          //================================================
          //==========JENIS POST DATA/INSERT DATA===========
          //================================================
          switch ($kodePosting) {
            case 'get_field_json':
              $dataJson['results'] = [];
              //ambil data
              $data_klm = $DB->readJSONField($tabel_pakai, $nama_kolom, $jenis_kelompok, $dataKondisiField); //sdh ok
              // Menghapus tanda kutip tunggal yang tidak valid
              // var_dump($data_klm);
              if ($data_klm) {
                $data_klm = json_decode($data_klm, true);
                switch ($tbl) {
                  case 'sub_keg_dpa':
                  case 'sub_keg_renja':
                    // balikkan nilai agar yang terakhir di input muncul duluan
                    $data_klm = array_reverse($data_klm);
                    if (count($data_klm)) {
                      foreach ($data_klm as $row) {
                        switch ($nama_kolom) {
                          case 'sumber_dana':
                            // cari di data sumber dana
                            $kondisi_result_sub = [['kode', '=', $row]];
                            $row_sub = $DB->getWhereOnceCustom('sumber_dana_neo', $kondisi_result_sub);
                            if ($row_sub !== false) {
                              $uraian = $row_sub->uraian;
                              $dataJson['results'][] = ['name' => $uraian, 'value' => $row];
                            } else {
                              $jenis = '';
                              $code = 404;
                            }
                            break;
                          default:
                            $dataJson['results'][] = ['name' => $row, 'value' => $row];
                            break;
                        }
                      }
                    }
                    break;
                  default:
                    # code...
                    break;
                }
              } else {
                $jenis = '';
                $code = 404;
              }
              break;
            case 'getAllValJson':
              $results = $DB->getWhereArray($tabel_pakai, $kondisi);
              $jumlahArray = is_array($results) ? count($results) : 0;
              $dataJson['results'] = [];
              if ($jumlahArray > 0) {
                switch ($jenis) {
                  case 'getJsonRows':
                    switch ($tbl) {
                      case 'sub_keg_dpa':
                      case 'sub_keg_renja':
                        // balikkan nilai agar yang terakhir di input muncul duluan
                        $results = $results[0]->sumber_dana;
                        $results = preg_replace('/\s+/', '', trim($results));
                        $results = explode(',', $results);
                        $results = array_reverse($results);
                        $DB->select('*');
                        break;
                    };
                    break;
                  case 'value1':
                    #code...
                    break;
                  default:
                    #code...
                    break;
                };
                foreach ($results as $row) {
                  switch ($jenis) {
                    case 'getJsonRows':
                      switch ($tbl) {
                        case 'tujuan_renstra':
                          $dataJson['results'][] = ['name' => $row->text, 'value' => $row->id];
                          break;
                        case 'sub_keg_dpa':
                        case 'sub_keg_renja':
                          // balikkan nilai agar yang terakhir di input muncul duluan
                          switch ($nama_kolom) {
                            case 'sumber_dana':
                              // cari di data sumber dana
                              $kondisi_result_sub = [['kode', '=', $row]];
                              $row_sub = $DB->getWhereOnceCustom('sumber_dana_neo', $kondisi_result_sub);
                              // var_dump($row);
                              // var_dump($row_sub);
                              if ($row_sub !== false) {
                                $uraian = $row_sub->uraian;
                                $dataJson['results'][] = ['name' => $uraian, 'value' => $row];
                              } else {
                                $jenis = '';
                                $code = 404;
                              }
                              break;
                            default:
                              $dataJson['results'][] = ['name' => $row, 'value' => $row];
                              break;
                          }
                          break;
                        case 'value1':
                          break;
                        default:
                          $dataJson['results'][] = ['name' => $row->text, 'value' => $row->id, 'description' => $row->nomor];
                          break;
                      };
                      break;
                    case 'value1':
                      #code...
                      break;
                    default:
                      #code...
                      break;
                  };
                }
              }
              break;
            case 'get_row': //  ambil data 1 baris 
              $row_result = $DB->getWhereOnceCustom($tabel_pakai, $kondisi_result);
              // var_dump($tabel_pakai);
              // var_dump($kondisi_result);
              if ($row_result !== false) {
                $data['users'] = $row_result;
                switch ($tbl) {
                  case 'daftar_paket':
                    break;
                  case 'value':
                    # code...
                    break;
                  default:
                    # code...
                    break;
                }
              }
              break;
            case 'get_data':
              $resul = $DB->getArrayLike($tabel_pakai, $kondisi);
              // $resul = $DB->getQuery("SELECT $kolom FROM $tabel_pakai WHERE $where_row", $data_where_row);
              // var_dump($resul);
              // $jumlahArray = is_array($resul) ? count($resul) : 0;
              if ($resul !== false) {
                $code = 302; //202
                $data['users'] = $resul[0];
                switch ($jenis) {
                  case 'edit':
                    switch ($tbl) {
                      case 'users':
                        $cari_drop = $data['users']->kd_organisasi;
                        if ($cari_drop) {
                          $kondisi_result = [['kode', '=', $cari_drop], ['kd_wilayah', '=', $kd_wilayah, 'AND']];
                          $tabel_pakai_temp = $Fungsi->tabel_pakai('organisasi')['tabel_pakai'];
                          $row = $DB->getWhereOnceCustom($tabel_pakai_temp, $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_organisasi'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true, 'selected' => true]];
                          }
                        }
                        $cari_drop = $data['users']->kd_wilayah;
                        if ($cari_drop) {
                          $kondisi_result = [['kode', '=', $cari_drop]];
                          $tabel_pakai_temp = $Fungsi->tabel_pakai('wilayah')['tabel_pakai'];
                          $row = $DB->getWhereOnceCustom($tabel_pakai_temp, $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_wilayah'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true, 'selected' => true]];
                          }
                        }
                        break;
                      case 'sk_asn':
                        $cari_drop = $data['users']->pemberi_tgs;
                        if ($cari_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['nip', '=', $cari_drop, 'AND']];
                          $tabelCari = $Fungsi->tabel_pakai('asn')['tabel_pakai'];
                          $row = $DB->getWhereOnceCustom($tabelCari, $kondisi_result);
                          // var_dump($row);
                          if ($row !== false) {
                            $namaLengkap = ucwords($row->nama);
                            if (strlen($row->gelar ?? '') > 0) {
                              $namaLengkap .= ', ' . $row->gelar;
                            }
                            if (strlen($row->gelar_depan ?? '') > 0) {
                              $namaLengkap = $row->gelar_depan . ' ' . $namaLengkap;
                            }
                            $deskripsi = $row->nip . ' (' . $row->jabatan . ')';
                            $data['values']['pemberi_tgs'] = [['text' => $namaLengkap, 'category' => $row->jabatan, 'name' => $namaLengkap, 'value' => $row->nip, 'description' => $deskripsi, "descriptionVertical" => true, 'nama' => $row->nama, 'nip' => $row->nip, 'golongan' => $row->golongan, 'ruang' => $row->ruang, 'selected' => true]];
                          }
                        }
                        break;
                      case "bidang_urusan":
                      case "prog":
                      case "keg":
                      case "sub_keg":
                        $satuan_drop = $data['users']->satuan;
                        if ($satuan_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['value', '=', $satuan_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('satuan_neo', $kondisi_result);
                          // var_dump($row);
                          if ($row !== false) {
                            $data['values']['satuan'] = [['name' => $row->item, 'value' => $row->value, 'selected' => true]];
                          }
                        }
                        break;
                      case 'daftar_paket':
                        $data['values'] = [];
                        $satuan_drop = $data['users']->satuan;
                        if ($satuan_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['value', '=', $satuan_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('satuan_neo', $kondisi_result);
                          // var_dump($row);
                          if ($row !== false) {
                            $data['values']['satuan'] = [['name' => $row->item, 'value' => $row->value, 'selected' => true]];
                          }
                        }
                        $cari_drop = $data['users']->id_rekanan;
                        if ($cari_drop) {
                          $kondisi_result = $kondisi = [['kd_wilayah', '=', $kd_wilayah], ['disable', '<=', 0, 'AND']];
                          $row = $DB->getWhereOnceCustom('rekanan_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['id_rekanan'] = [['name' => $row->nama_perusahaan, 'text' => $row->nama_perusahaan, 'value' => $row->id, 'description' => $row->npwp . ' (' . $row->direktur . ')', "descriptionVertical" => true, 'selected' => true]];
                          }
                        }
                        // tambahkan $data['users'][] count_uraian_belanja
                        $send = json_decode($data['users']->id_uraian);
                        // atur kembali id uraian
                        $pagu = 0;
                        $jumlah = 0;
                        $kd_sub_keg = [];
                        $si = 0;
                        foreach ($send as $key => $value) {
                          $si++;
                          $tabel_pakai = $Fungsi->tabel_pakai($value->dok_anggaran)['tabel_pakai'];
                          $kondisi = [['id', '=', $value->id], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['disable', '<=', 0, 'AND'], ['kel_rek', '=', 'uraian', 'AND']];
                          $row_sub = $DB->getWhereOnceCustom($tabel_pakai, $kondisi);
                          if ($row_sub !== false) {
                            $klm_jumlah = ($tabel_pakai == 'dpa_neo') ? 'jumlah' : 'jumlah_p';
                            $pagu += $row_sub->$klm_jumlah;
                            $jumlah += $value->val_kontrak;
                            $kd_sub_keg[] = $row_sub->kd_sub_keg;
                          } else {
                            //hapus key yang tidak mempunyai persyaratan
                            unset($send[$key]);
                          }
                        }
                        $kd_sub_keg = implode(',', $kd_sub_keg);
                        $data['users']->count_uraian_belanja = "$si uraian { $kd_sub_keg }";
                        break;
                      case 'mapping':
                        $data['values'] = [];
                        $kode_drop = $data['users']->kd_aset;
                        if ($kode_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $kode_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('aset_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_aset'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'selected' => true, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true]];
                          }
                        }
                        $cari_drop = $data['users']->kd_akun;
                        if ($cari_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $cari_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('akun_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_akun'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true, 'selected' => true]];
                          }
                        }
                        break;
                      case 'asb':
                      case 'sbu':
                      case 'hspk':
                      case 'ssh':
                        $data['values'] = [];
                        $kode_drop = $data['users']->kd_aset;
                        if ($kode_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $kode_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('aset_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_aset'] = [['name' => $row->uraian, 'value' => $row->kode, 'selected' => true]];
                          }
                        }
                        // kd akun
                        $kode_drop = $data['users']->kd_akun;
                        if ($kode_drop) {
                          $data['values']['kd_akun'] = [];
                          $formValueExplode = explode(',', $kode_drop);
                          foreach ($formValueExplode as $key_row => $row) {
                            $kondisi_result = [['kode', '=', trim($row)]];
                            $row_sub = $DB->getWhereOnceCustom('akun_neo', $kondisi_result);
                            // var_dump( $kondisi_result);
                            if ($row_sub !== false) {
                              if ($row_sub) {
                                $uraian = $row_sub->uraian;
                              } else {
                                $uraian = 'data tidak ditemukan';
                              }
                              $data['values']['kd_akun'][] = ['name' => $uraian, 'text' => trim($row_sub->kode), 'value' => $row_sub->kode, 'selected' => true];
                            }
                          }
                        }
                        $satuan_drop = $data['users']->satuan;
                        if ($satuan_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['value', '=', $satuan_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('satuan_neo', $kondisi_result);
                          // var_dump($row);
                          if ($row !== false) {
                            $data['values']['satuan'] = [['name' => $row->item, 'value' => $row->value, 'selected' => true]];
                          }
                        }
                        break;
                      case 'renstra':
                        // ambil data untuk values dropdown
                        //sasaran
                        $data['values'] = [];
                        $sasaran_drop = $data['users']->sasaran;
                        $kode_drop = $data['users']->kd_sub_keg;
                        $satuan_drop = $data['users']->satuan;
                        //cari sasaran dengan id
                        if ($sasaran_drop) {
                          $kondisi_result = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'sasaran', 'AND'], ['id', '=', $sasaran_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('tujuan_sasaran_renstra_neo', $kondisi_result);
                          $data['values']['sasaran'] = [['name' => $row->text, 'text' => $row->text, 'value' => $row->id, 'description' => $row->id_tujuan, "descriptionVertical" => true, 'selected' => true]];
                        }
                        //cari sub_keg dengan kode
                        if ($kode_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $kode_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('sub_kegiatan_neo', $kondisi_result);
                          $data['values']['kd_sub_keg'] = [['name' => $row->nomenklatur_urusan, 'text' => $row->nomenklatur_urusan, 'value' => $row->kode, 'description' => $row->kode, 'descriptionVertical' => true, 'selected' => true]];
                        }
                        //cari satuan dengan satuan
                        if ($satuan_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['value', '=', $satuan_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('satuan_neo', $kondisi_result);
                          $data['values']['satuan'] = [['name' => $row->item, 'value' => $row->value, 'selected' => true]];
                        }
                        break;
                      case 'renja':
                      case 'dpa':
                      case 'renja_p':
                      case 'dppa':
                        // ambil data untuk values dropdown
                        $data['values'] = [];
                        //kd_akun
                        $cari_drop = $data['users']->kd_akun;
                        if ($cari_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $cari_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('akun_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_akun'] = [['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true, 'selected' => true]];
                          }
                        }
                        // kelompok
                        switch ($tbl) {
                          case 'dpa':
                          case 'dppa':
                            $tabel_pakai_temporerSubkeg = 'sub_keg_dpa_neo';
                            break;
                          case 'renja':
                          case 'renja_p':
                            $tabel_pakai_temporerSubkeg = 'sub_keg_renja_neo';
                            break;
                        };
                        $cari_drop = $data['users']->jenis_kelompok;
                        // kelompok
                        if ($cari_drop) {
                          $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                          $data_klm = $DB->readJSONField($tabel_pakai_temporerSubkeg, 'kelompok_json', $cari_drop, $dataKondisiField);
                          $data_klm = json_decode($data_klm, true);
                          if ($data_klm) {
                            // var_dump($data_klm);
                            $cari_drop = $data['users']->kelompok;
                            // var_dump($cari_drop);
                            $key = array_search($cari_drop, $data_klm, true);

                            $data['values']['kelompok'] = [['name' => $data_klm[$key], 'value' => $data_klm[$key], 'selected' => true]];
                          }
                        }
                        $cari_drop = $data['users']->komponen;
                        $jenis_standar_harga = $data['users']->jenis_standar_harga;
                        $id_standar_harga = $data['users']->id_standar_harga;
                        // komponen
                        if ($cari_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['id', '=', $id_standar_harga, 'AND'], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['tahun', '=', $tahun, 'AND']];
                          $row = $DB->getWhereOnceCustom("{$jenis_standar_harga}_neo", $kondisi_result);
                          // var_dump($kondisi_result);
                          // var_dump($row);
                          if ($row !== false) {
                            $deskripsi = $row->kd_aset . ' (' . number_format((float)$row->harga_satuan, 2, ',', '.') . ')';
                            $data['values']['komponen'] = [['name' => $row->uraian_barang, 'text' => $row->uraian_barang, 'value' => $row->id, 'description' => $deskripsi, "descriptionVertical" => true, 'satuan' => $row->satuan, 'harga_satuan' => $row->harga_satuan, 'spesifikasi' => $row->spesifikasi, 'tkdn' => $row->tkdn, 'selected' => true]];
                          }
                        }
                        // sumber_dana



                        if ($tbl == 'dppa' || $tbl == 'renja_p') {
                          $cari_drop = $data['users']->sumber_dana_p;
                          $data['values']['sumber_dana_p'] = [];
                        } else {
                          $data['values']['sumber_dana'] = [];
                          $cari_drop = $data['users']->sumber_dana;
                        }
                        if ($cari_drop) {
                          $formValueExplode = explode(',', $cari_drop);
                          foreach ($formValueExplode as $key_row => $row) {
                            $kondisi_result_sub = [['kode', '=', $row]];
                            $row_sub = $DB->getWhereOnceCustom('sumber_dana_neo', $kondisi_result_sub);
                            if ($row_sub !== false) {
                              if ($row_sub) {
                                $uraian_sumberDana = $row_sub->uraian;
                              } else {
                                $uraian_sumberDana = 'data tidak ditemukan';
                              }
                              if ($tbl == 'dppa' || $tbl == 'renja_p') {
                                $data['values']['sumber_dana_p'][] = ['name' => $uraian_sumberDana, 'value' => $row, 'selected' => true];
                              } else {
                                $data['values']['sumber_dana'][] = ['name' => $uraian_sumberDana, 'value' => $row, 'selected' => true];
                              }
                            }
                          }
                        }
                        // keterangan/uraian
                        $cari_drop = $data['users']->uraian;
                        if ($cari_drop) {
                          $dataKondisiField = [['id', '=', $id_sub_keg], ['kd_wilayah', '=', $kd_wilayah, 'AND'], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND']];
                          $data_klm = $DB->readJSONField($tabel_pakai_temporerSubkeg, 'keterangan_json', 'keterangan_json', $dataKondisiField);
                          $data_klm = json_decode($data_klm, true);
                          if ($data_klm) {
                            $key = array_search($cari_drop, $data_klm, true);
                            $data['values']['uraian'] = [['name' => $data_klm[$key], 'value' => $data_klm[$key], 'selected' => true]];
                          }
                        }
                        //satuan
                        $array_sat = [];
                        for ($i = 1; $i <= 4; $i++) {
                          $def = "vol_{$i}";
                          $def2 = "sat_{$i}";
                          $cari_vol = $data['users']->{$def};
                          $cari_sat = $data['users']->{$def2};
                          $data['values'][$def] = $cari_vol;
                          if ($cari_vol > 0) {
                            $kondisi_result = [['disable', '<=', 0], ['value', '=', $cari_sat, 'AND']];
                            $row = $DB->getWhereOnceCustom('satuan_neo', $kondisi_result);
                            if ($row !== false) {
                              // harus dibedakan satuan pokok dan perubahan
                              $valSat = "sat_{$i}";
                              if ($tbl == 'dppa' || $tbl == 'renja_p') {
                                $valSat = "sat_{$i}_p";
                              }
                              $data['values'][$valSat] = [['name' => $row->item, 'value' => $row->value, 'selected' => true]];
                            }
                          }
                        }
                        break;
                      case 'sub_keg_dpa':
                      case 'sub_keg_renja':
                        $data['values'] = [];
                        $kd_sub_keg_drop = $data['users']->kd_sub_keg;
                        //cari sub_keg dengan kode
                        if ($kd_sub_keg_drop) {
                          $kondisi_result = [['disable', '<=', 0], ['kode', '=', $kd_sub_keg_drop, 'AND']];
                          $row = $DB->getWhereOnceCustom('sub_kegiatan_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['kd_sub_keg'] = [['name' => $row->nomenklatur_urusan, 'text' => $row->nomenklatur_urusan, 'value' => $row->kode, 'description' => $row->kode, 'descriptionVertical' => true, 'selected' => true]];
                          }
                        }
                        // sumber_dana
                        $cari_drop = $data['users']->sumber_dana;
                        if ($cari_drop) {
                          $data['values']['sumber_dana'] = [];
                          $formValueExplode = explode(',', $cari_drop);
                          foreach ($formValueExplode as $key_row => $row) {
                            $kondisi_result_sub = [['kode', '=', $row]];
                            $row_sub = $DB->getWhereOnceCustom('sumber_dana_neo', $kondisi_result_sub);
                            if ($row_sub !== false) {
                              if ($row_sub) {
                                $uraian_sumberDana = $row_sub->uraian;
                              } else {
                                $uraian_sumberDana = 'data tidak ditemukan';
                              }
                              $data['values']['sumber_dana'][] = ['name' => $uraian_sumberDana, 'value' => $row, 'selected' => true];
                            }
                          }
                        }
                        break;
                      case 'tujuan_sasaran_renstra':
                        $data['values'] = [];
                        $value_a = $data['users']->kelompok;
                        $value_b = $data['users']->id_tujuan;
                        //cari sasaran dengan id
                        if ($value_a == 'sasaran' && $value_b > 0) {
                          $kondisi_result = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun_renstra, 'AND'], ['disable', '<=', 0, 'AND'], ['kelompok', '=', 'tujuan', 'AND'], ['id', '=', $value_b, 'AND']];
                          $row = $DB->getWhereOnceCustom('tujuan_sasaran_renstra_neo', $kondisi_result);
                          if ($row !== false) {
                            $data['values']['id_tujuan'] = [['name' => $row->text, 'value' => $row->id, 'selected' => true]];
                          }
                        }
                        break;
                      case 'xx':
                        break;
                      case 'yy':
                        break;
                      default:
                        break;
                    };
                    break;
                  case 'get_rows':
                    switch ($tbl) {
                      case 'tujuan_renstra':
                        // buatkan json dropdown tujuan renstra

                        break;
                      case 'value1':
                        #code...
                        break;
                      default:
                        #code...
                        break;
                    };
                    break;
                  case 'get_data':
                    switch ($tbl) {
                      case 'tujuan_renstra':
                        break;
                      case 'value1':
                        #code...
                        break;
                      default:
                        #code...
                        break;
                    };
                    break;
                  default:
                    #code...
                    break;
                };
              } else {
                $code = 404;
              }
              break;
            case 'get_row_json': // ambil data > 1 baris 
              if ($limit !== "all") {
                if ($cari != '') {
                  //var_dump($data_like);
                  //var_dump("SELECT * FROM $tabel_pakai WHERE ($like)");
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like)", $data_like);
                  // var_dump("SELECT * FROM $tabel_pakai WHERE ($like)");
                } else {
                  //var_dump("SELECT * FROM $tabel_pakai WHERE $where1");
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $whereGet_row_json", $data_hereGet_row_json);
                }
                $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                $jmlhalaman = ceil($jmldata / $limit);
              } else {
                $jmlhalaman = 1;
              }
              if ($halaman > $jmlhalaman) {
                $halaman = $jmlhalaman;
              }
              if (empty($halaman)) {
                $posisi = 0;
                $halaman = 1;
              } else {
                $posisi = ((int)$halaman - 1) * (int)$limit;
              }
              if ($limit != "all") {
                if ($cari != '') {
                  //var_dump("SELECT * FROM $tabel_pakai WHERE ($like) $order ");
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order ", $data_like, [$posisi, $limit]);
                } else {
                  //var_dump("SELECT * FROM $tabel_pakai WHERE $where1 $order ");
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $whereGet_row_json $order ", $data_hereGet_row_json, [$posisi, $limit]);
                }
              } else {
                if ($cari != '') {
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $order", $data_like);
                } else {
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $whereGet_row_json $order ", $data_hereGet_row_json);
                }
              }

              $dataJson['results'] = [];
              $jumlahArray = is_array($get_data) ? count($get_data) : 0;
              if ($jumlahArray > 0) {
                // var_dump($get_data);
                foreach ($get_data as $row) {
                  switch ($jenis) {
                    case 'get_row_json':
                      switch ($tbl) {
                        case 'asn':
                          $pangkat = $Fungsi->golongan_ruang(['golongan' => $row->golongan, 'ruang' => $row->ruang]);
                          $pangkat = ucfirst($pangkat['pangkat']) . ', ' . $pangkat['singkat'];
                          $namaLengkap = ucwords($row->nama);
                          if (strlen($row->gelar ?? '') > 0) {
                            $namaLengkap .= ', ' . $row->gelar;
                          }
                          if (strlen($row->gelar_depan ?? '') > 0) {
                            $namaLengkap = $row->gelar_depan . ' ' . $namaLengkap;
                          }
                          $deskripsi = 'Nip: ' . $row->nip . '; Jabatan: ' . $row->jabatan . '; Pangkat: ' . $pangkat;
                          $dataJson['results'][] = ['text' => $namaLengkap, 'category' => $row->jabatan, 'name' => $namaLengkap, 'value' => $row->nip, 'description' => $deskripsi, "descriptionVertical" => true, 'golongan' => $row->golongan, 'ruang' => $row->ruang];
                          break;
                        case 'organisasi':
                          $dataJson['results'][] = ['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->id, 'description' => $row->kode . ' (' . $row->nama_kepala . ')', "descriptionVertical" => true];
                          break;
                        case 'rekanan':
                          $dataJson['results'][] = ['name' => $row->nama_perusahaan, 'text' => $row->nama_perusahaan, 'value' => $row->id, 'description' => $row->npwp . ' (' . $row->direktur . ')', "descriptionVertical" => true];
                          break;
                        case 'sub_keg_renja':
                        case 'sub_keg_dpa':
                          $dataJson['results'][] = ['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kd_sub_keg, 'description' => $row->kd_sub_keg, "descriptionVertical" => true];
                          break;
                        case 'aset':
                        case 'akun_belanja':
                          $dataJson['results'][] = ['name' => $row->uraian, 'text' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true];
                          break;
                        case 'akun_belanja_val':
                          $dataJson['results'][] = ['name' => $row->uraian, 'text' => $row->kode, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true];
                          break;
                        case 'tujuan_renstra':
                          $dataJson['results'][] = ['text' => $row->text, 'name' => $row->text, 'value' => $row->id];
                          break;
                        case 'sasaran_renstra':
                          $dataJson['results'][] = ['name' => $row->text, 'text' => $row->text, 'value' => $row->id];
                          break;
                        case 'sub_keg':
                          $dataJson['results'][] = ['name' => $row->nomenklatur_urusan, 'text' => $row->nomenklatur_urusan, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true];
                          break;
                        case 'sumber_dana':
                          $dataJson['results'][] = ['name' => $row->uraian, 'value' => $row->kode, 'description' => $row->kode, "descriptionVertical" => true];
                          break;
                        case 'wilayah':
                          $deskripsi = $row->status . " ( {$row->kode} )";
                          $dataJson['results'][] = ['name' => $row->uraian, 'value' => $row->kode, 'description' => $deskripsi, "descriptionVertical" => true];
                          break;
                        case 'satuan':
                          $dataJson['results'][] = ['name' => $row->item, 'value' => $row->value];
                          break;
                        case 'hspk':
                        case 'asb':
                        case 'ssh':
                        case 'sbu':
                          $deskripsi = $row->kd_aset . ' (' . number_format((float)$row->harga_satuan, 2, ',', '.') . ')';
                          $dataJson['results'][] = ['category' => $row->kd_aset, 'name' => $row->uraian_barang, 'value' => $row->id, 'description' => $deskripsi, "descriptionVertical" => true, 'satuan' => $row->satuan, 'harga_satuan' => $row->harga_satuan, 'spesifikasi' => $row->spesifikasi, 'tkdn' => $row->tkdn];
                          break;
                        case 'value1':
                          break;
                        default:
                          $dataJson['results'][] = ['name' => $row->text, 'value' => $row->id, 'description' => $row->nomor, "descriptionVertical" => true];
                          break;
                      };
                      break;
                    case 'get_Search_Json':
                      switch ($tbl) {
                        case 'daftar_paket':
                          $id_uraian = $row->id_uraian;
                          // tambahkan $data['users'][] count_uraian_belanja
                          $send = json_decode($id_uraian);
                          // atur kembali id uraian
                          $pagu = 0;
                          $jumlahSum = 0;
                          $kd_sub_keg = [];
                          $si = 0;
                          foreach ($send as $key => $value) {
                            $si++;
                            $tabel_pakai = $Fungsi->tabel_pakai($value->dok_anggaran)['tabel_pakai'];
                            switch ($value->dok_anggaran) {
                              case 'dpa':
                              case 'renja':
                                $sumberDan = 'sumber_dana';
                                $volume = 'volume';
                                $jumlah = 'jumlah';
                                break;

                              case 'dppa':
                              case 'renja_p':
                                $sumberDan = 'sumber_dana_p';
                                $volume = 'volume_p';
                                $jumlah = 'jumlah_p';
                                break;
                            };
                            //ambil row dari 2 tabel yaitu tabel daftar_uraian_paket dan dokumen anggaran dok
                            $kondisi = [["$tabel_pakai.id", '=', $value->id], ["daftar_uraian_paket.id_paket", '=', $row->id, 'AND'], ["daftar_uraian_paket.id_dok_anggaran", '=', $value->id, 'AND'], ["$tabel_pakai.kd_wilayah", '=', $kd_wilayah, 'AND'], ["$tabel_pakai.kd_opd", '=', $kd_opd, 'AND'], ["$tabel_pakai.tahun", '=', $tahun, 'AND'], ["$tabel_pakai.disable", '<=', 0, 'AND'], ["$tabel_pakai.kel_rek", '=', 'uraian', 'AND']];

                            $DB->select("$tabel_pakai.id,$tabel_pakai.kd_wilayah,$tabel_pakai.kd_opd,$tabel_pakai.tahun,$tabel_pakai.kd_sub_keg,$tabel_pakai.kd_akun,$tabel_pakai.uraian,$tabel_pakai.komponen,$tabel_pakai.jenis_standar_harga,$tabel_pakai.komponen,$tabel_pakai.harga_satuan,$tabel_pakai.$volume,$tabel_pakai.$jumlah,$tabel_pakai.$sumberDan, $tabel_pakai.id,daftar_uraian_paket.id AS id_uraian_paket,daftar_uraian_paket.id_paket,daftar_uraian_paket.id_dok_anggaran,daftar_uraian_paket.dok,daftar_uraian_paket.jumlah_pagu,daftar_uraian_paket.jumlah_kontrak,daftar_uraian_paket.vol_kontrak,daftar_uraian_paket.sat_kontrak,daftar_uraian_paket.realisasi_vol,daftar_uraian_paket.realisasi_jumlah");
                            // ambil rincian
                            $row_sub = $DB->getWhereOnceCustom("$tabel_pakai, daftar_uraian_paket", $kondisi);
                            // var_dump($row_sub);
                            if ($row_sub !== false) {
                              $row_sub->dok = $value->dok_anggaran;
                              $klm_jumlah = ($tabel_pakai == 'dpa_neo') ? 'jumlah' : 'jumlah_p';
                              $pagu += $row_sub->$klm_jumlah;
                              $jumlahSum += $value->val_kontrak;
                              $kd_sub_keg[] = $row_sub;
                            } else {
                              //hapus key yang tidak mempunyai persyaratan
                              unset($send[$key]);
                            }
                          }

                          $deskripsi = $row->nama_rekanan . ' (Pagu' . number_format((float)$row->pagu, 2, ',', '.') . ')';
                          $dataJson['results'][] = ['category' => $row->metode_pengadaan, 'title' => $row->uraian, 'value' => $row->id, 'description' => $deskripsi, "descriptionVertical" => true, 'id_uraian' => $send, 'satuan' => $row->satuan, 'harga_satuan' => $row->harga_satuan, 'volume' => $row->volume, 'jumlah' => $row->jumlah, 'keterangan' => $row->keterangan, 'pagu' => $row->pagu, 'uraian_id_uraian' => $kd_sub_keg];
                          break;
                        case 'hspk':
                        case 'asb':
                        case 'ssh':
                        case 'sbu':
                          $kd_akun_db = explode(',', $row->kd_akun); //agar konek dengan kode akun yang dipilih
                          if (in_array($kd_akun, $kd_akun_db)) {
                            $deskripsi = $row->kd_aset . ' (' . number_format((float)$row->harga_satuan, 2, ',', '.') . ')';
                            $dataJson['results'][] = ['title' => $row->uraian_barang, 'value' => $row->id, 'description' => $deskripsi, "descriptionVertical" => true, 'satuan' => $row->satuan, 'harga_satuan' => $row->harga_satuan, 'spesifikasi' => $row->spesifikasi, 'tkdn' => $row->tkdn, 'keterangan' => $row->keterangan, 'disable' => $row->disable, 'kd_aset' => $row->kd_aset, 'kd_akun' => $row->kd_akun];
                          }
                          break;
                        case 'renja':
                        case 'dpa':
                        case 'dppa':
                        case 'renja_p':
                          break;
                        case 'dpa_dppa':
                          //memilih dokumen anggaran yang sudah disetujui di pengaturan
                          $dok_anggaran = 'dpa';
                          switch ($tabel_pakai) {
                            case 'dpa_neo':
                              $clmVol = "volume";
                              $clmSat = "sat_1";
                              $clmJumlah = "jumlah";
                              break;
                            case 'dppa_neo':
                              $clmVol = "volume_p";
                              $clmSat = "sat_1_p";
                              $clmJumlah = "jumlah_p";
                              $dok_anggaran = 'dppa';
                              break;
                            default:
                              # code...
                              break;
                          }
                          //tambahkan kriteria jika id nama uraian belum ada di tabel daftar_uraian_paket
                          $kondisi1 = [['kd_wilayah', '=', $kd_wilayah], ['kd_opd', '=', $kd_opd, 'AND'], ['tahun', '=', $tahun, 'AND'], ['id_dok_anggaran', '=', $row->id, 'AND'], ['dok', '=', $dok_anggaran, 'AND']];
                          $row_sub2 = $DB->getWhereOnceCustom('daftar_uraian_paket', $kondisi1);
                          if ($row_sub2 === false) {
                            $deskripsi = $row->kd_akun . ', ' . $row->komponen . ' (' . number_format((float)$row->$clmJumlah, 2, ',', '.') . ')';
                            $dataJson['results'][] = ['category' => $row->kd_akun, 'title' => $row->uraian, 'value' => $row->id, 'description' => $deskripsi, "descriptionVertical" => true, 'jumlah' => $row->$clmJumlah, 'kd_sub_keg' => $row->kd_sub_keg, 'dok_anggaran' => $dok_anggaran, 'sat' => $row->$clmSat, 'vol' => $row->$clmVol];
                          }
                          break;
                        default:
                          break;
                      }
                      break;
                    case 'value1':
                      #code...
                      break;
                    default:
                      #code...
                      break;
                  };
                }
              }
              break;
            case 'get_tbl':
              //get data
              // var_dump($limit);
              if ($limit !== "all") {
                if ($cari != '') {
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $group_by", $data_like);
                } else {
                  if (strlen($where1) > 3) {
                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $group_by", $data_where1);
                  } else {
                    $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai $group_by");
                  }
                }
                $jmldata = sizeof($get_data); //$get_data->fetchColumn();
                $jmlhalaman = ceil($jmldata / $limit);
              } else {
                $jmlhalaman = 1;
              }
              if ($halaman > $jmlhalaman) {
                $halaman = $jmlhalaman;
              }
              if (empty($halaman)) {
                $posisi = 0;
                $halaman = 1;
              } else {
                $posisi = ((int)$halaman - 1) * (int)$limit;
              }
              if ($limit != "all") {
                if ($cari != '') {
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like)  $group_by $order ", $data_like, [$posisi, $limit]);
                } else {
                  if (strlen($where1) > 3) {
                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $group_by $order ", $data_where1, [$posisi, $limit]);
                  } else {
                    $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai $group_by $order LIMIT $posisi, $limit");
                  }
                }
              } else {
                if ($cari != '') {
                  $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE ($like) $group_by $order", $data_like);
                } else {
                  if (strlen($where1) > 3) {
                    $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $group_by $order ", $data_where1);
                  } else {
                    $get_data = $DB->runQuery2("SELECT * FROM $tabel_pakai $group_by $order ");
                  }
                }
              }
              $jumlah_rows = is_array($get_data) ? count($get_data) : 0;
              if ($jumlah_rows <= 0) {
                $code = 404;
              } else {
                $code = 202; //202
              }
              // var_dump($get_data);
              $value_dinamic['tahun_tabel'] = $tahun;
              $set = [];
              switch ($tbl) {
                case 'daftar_paket':
                case 'sub_keg_renja':
                case 'sub_keg_dpa':
                case 'renja':
                case 'renja_p':
                case 'dpa':
                case 'dppa':
                case 'renstra':
                case 'tujuan_renstra':
                case 'sasaran_renstra':
                case 'tujuan_sasaran_renstra':
                case 'realisasi':
                  $set = [
                    'kunci_renstra' => $kunci_renstra,
                    'kunci_renja' => $kunci_renja,
                    'kunci_dpa' => $kunci_dpa,
                    'kunci_renja_p' => $kunci_renja_p,
                    'kunci_dppa' => $kunci_dppa,
                    'kunci_paket' => $kunci_paket,
                    'kunci_realisasi' => $kunci_realisasi,
                    'setujui_renstra' => $setujui_renstra,
                    'setujui_renja' => $setujui_renja,
                    'setujui_dpa' => $setujui_dpa,
                    'setujui_renja_p' => $setujui_renja_p,
                    'setujui_dppa' => $setujui_dppa
                  ];
                  break;

                default:
                  # code...
                  break;
              }
              $value_dinamic = array_merge($value_dinamic, $set);
              $dataTabel = $Fungsi->getTabel($tbl, $tabel_pakai, $get_data, $jmlhalaman, $halaman, $jumlah_kolom, $type_user, $value_dinamic);
              $data = array_merge($dataTabel, $data, $set);
              break;
            case 'get_Dropdown':
              $get_data = $DB->getQuery("SELECT * FROM $tabel_pakai WHERE $where1 $order ", $data_where1);
              $dataTabel = $Fungsi->getDropdownItem($get_data, $tabel_pakai, $nama_dropdown, $jenisdropdown, $jumlah_kolom_dropdown, $type_user);
              $data['dropdown'] = $dataTabel;
              break;
            default:
              break;
          }
        } else {
          $code = 29;
          $pesan = $validate->getError();
          $data['error_validate'][] = $pesan;
          $keterangan = '<ol class="ui horizontal ordered suffixed list">';
          foreach ($pesan as $key => $value) {
            $keterangan .= '<li class="item">' . $value . '</li>';
          }
          $keterangan .= '</ol>';
          $message_tambah = $keterangan;
        }
      } else {
        $pesan = 'tidak didefinisikan';
        $code = 39;
      }
    } else {
      $code = 407;
    }
    // cara menampilkan json
    switch ($jenis) {
      case 'get_Search_Json':
      case 'get_field_json':
      case 'get_row_json':
      case 'get_users_list':
      case 'getJsonRows':
        switch ($tbl) {
          case 'wilayah':
          case 'asn':
          case 'daftar_paket':
          case 'organisasi':
          case 'rekanan':
          case 'renja':
          case 'dpa':
          case 'dppa':
          case 'dpa_dppa':
          case 'renja_p':
          case 'sub_keg_dpa':
          case 'sub_keg_renja':
          case 'sumber_dana':
          case 'sasaran_renstra':
          case 'tujuan_renstra':
          case 'sub_keg':
          case 'satuan':
          case 'aset':
          case 'akun_belanja_val':
          case 'akun_belanja':
          case 'hspk':
          case 'asb':
          case 'ssh':
          case 'sbu':
            $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
            $json = array('success' => $sukses,  'results' => $dataJson['results'],  'data' => $data, 'error' => $item);
            break;
          default:
            $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
            $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
            break;
        }
        break;
      default:
        $item = array('code' => $code, 'message' => hasilServer[$code] . $message_tambah);
        $json = array('success' => $sukses, 'data' => $data, 'error' => $item);
        break;
    }
    //var_dump($json);
    return json_encode($json, JSON_HEX_APOS);
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
}
