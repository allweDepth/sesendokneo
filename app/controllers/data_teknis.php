<?php
class Data_Teknis extends Controller
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
        $dataHeader['title'] = '| Data Teknis';
        $dataHeader['css'] = 'css/login.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = 'js/login.js';
        $dataFooter['tambahan_js'] = '';//GET http://localhost/sesendokneo/public/js/login net::ERR_TOO_MANY_REDIRECTS
        $dataFooter['dok'] = 'Data_Teknis';
        $dataFooter['key_encrypt'] = $key_encrypt;
        $this->view('templates/header_login', $dataHeader);
        $this->view('data_teknis/index');
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
