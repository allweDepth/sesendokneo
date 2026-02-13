<?php
class About extends Controller
{
   public function index()
   {
      $dataHeader['awalHeader'] = '';
      $dataHeader['title'] = '| About';
      $dataHeader['css'] = 'css/bantuan.css';
      $dataHeader['tambahan_css'] = '';
      $dataFooter['js'] = '';
      $dataFooter['tambahan_js'] = '<script src="'.BASEURL.'js/about.js"></script>';
      $dataFooter['key_encrypt'] = '';
      $this->view('templates/header', $dataHeader);
      $this->view('about/index');
      $this->view('templates/footer', $dataFooter);
   }
   public function page()
   {
      $data['title'] = 'About';
      $this->view('templates/header', $data);
      $this->view('about/page');
      $this->view('templates/footer');
   }
}
