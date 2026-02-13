<?php
class Bantuan extends Controller
{
    public function index()
    {
        $dataHeader['awalHeader'] = '';
        $dataHeader['title'] = '| Bantuan';
        $dataHeader['css'] = 'css/bantuan.css';
        $dataHeader['tambahan_css'] = '';
        $dataFooter['js'] = '';
        $dataFooter['tambahan_js'] = '<script src="'.BASEURL.'js/about.js"></script>';
        // $dataFooter['key_encrypt'] = '';
        $this->view('templates/header', $dataHeader);
        $this->view('bantuan/index');
        $this->view('templates/footer', $dataFooter);
    }
}
