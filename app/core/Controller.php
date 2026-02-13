<?php
class Controller
{
    public function view($view, $data = [])
    {
        require_once '../app/views/' . $view . '.php';
    }
    public function model($model)
    {

        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        require_once '../app/models/' . $model . '.php';

        return new $model;
    }
    public function script($script)
    {
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        require_once '../app/models/script/' . $script . '.php';
        $listScript = explode('/', $script);
        if (count($listScript) > 1) {
            $script = $listScript[count($listScript) - 1];
        }
        switch ($script) {
            // class yang mempunyai __construct($data)
            case 'print_pdf':
            case 'get_data':
                return $script::createInstance($_POST);
                break;
            default:
                return new $script;
                break;
        }
    }
    public function scriptConstruct($script, $data = [])
    {
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        require_once '../app/models/script/' . $script . '.php';
        $listScript = explode('/', $script);
        if (count($listScript) > 1) {
            $script = $listScript[count($listScript) - 1];
        }
        // var_dump($script);
        return $script::createInstance($data);;
    }
    public function getFileJson($path = '../app/models/script/candaan.json')
    {
        $jsonString = file_get_contents($path);
        $jsonString = json_decode($jsonString, true);
        $nojsonString = array_rand($jsonString);
        return $jsonString[$nojsonString];
    }
}
