<?php
class home extends Controller
{
    public function index()
    {
        $data['title'] = 'Home';
        $this->view('home/index',$data);
    }
    public function get_data()
    {
        $data = $this->script("get_data")->get_data();
        echo $data;
    }
    public function edit_cell()
    {
        $data = $this->script("edit_cell")->edit_cell();
        echo $data;
    }
    public function del_data()
    {
        $data = $this->script("del_data")->del_data();
        echo $data;
    }
    public function cetak_pdf()
    {
        $data = $this->script("print_pdf")->print_pdf();
        echo $data;
    }
    public function post_data()
    {
        $data = $this->script("post_data")->post_data();
        echo $data;
    }
    public function writer_xlsx()
    {
        $data = $this->script("writer_xlsx")->writer_xlsx();
        echo $data;
    }
    public function impor_xlsx()
    {
        $data = $this->script("impor_xlsx")->import_xlsx();
        echo $data;
    }
    public function logout()
    {
        $this->script("logout")->logout();
    }
    public function candaan()
    {
        // var_dump('ok');
        $data = $this->getFileJson('../app/models/script/candaan.json');
        // var_dump($data);
        // $data = 'ok';
        $item = array('code' => 202, 'message' => 'Accepted');
        $json = array('success' => true, 'data' => $data, 'error' => $item);
        echo json_encode($json);
        // $jsonData = json_decode($jsonString, true);
        // var_dump($jsonData);
    }
}
