<?php
class Pelayanan extends Controller
{
    public function index()
    {
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        session_start();
        $key_encrypt = $this->scriptConstruct("query",['jns'=>'key_encrypt','tbl'=>'key_encrypt'])->key_encrypt();
        $_SESSION["key_encrypt"] = $key_encrypt;
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Pelayanan';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';
        $dataFooter['dok'] = 'Pelayanan';
        $dataFooter['key_encrypt'] = $key_encrypt;
        $this->view('templates/header_login', $dataHeader);
        $this->view('pelayanan/index');
        $this->view('templates/footer_modal');
        $this->view('templates/footer', $dataFooter);
    }
    public function masuk()
    {
        $data = $this->script("masuk")->masuk();
        echo $data;
    }
    public function register()
    {
        $data = $this->script("register")->register();
        // var_dump('masuksini');
        echo $data;
    }
    public function wilayah()
    {
        $send = ['jns' => 'json_list_dropdown', 'tbl' => 'wilayah', 'kondisi' => [['disable', '<= ?', 0]]];
        $data = $this->scriptConstruct("query", $send)->json_list_dropdown();
        echo $data;
    }
    public function organisasi()
    {
        $send = ['jns' => 'json_list_dropdown', 'tbl' => 'organisasi', 'kondisi' => [['kd_wilayah', '= ?', $_POST['kd_wilayah']], ['disable', '<= ?', 0, 'AND']]];
        $data = $this->scriptConstruct("query", $send)->json_list_dropdown();
        echo $data;
    }
}
