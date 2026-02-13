<?php
class App
{
    protected $controller = 'home';
    protected $method = 'index';
    protected $params = [];
    public function __construct()
    {
        set_include_path(implode(PATH_SEPARATOR, array(
            realpath(__DIR__ . '/'),
            get_include_path()
        )));
        $url = $this->parseURL();

        //controller
        // var_dump($url);
        if ($url && file_exists('../app/controllers/' . $url[0] . '.php')) {
            //var_dump($_SESSION["user"]);
            switch ($url[0]) {
                case 'login':
                case 'data_teknis':
                case 'register':
                case 'anggaran':
                case 'organisasi':
                    $this->controller = $url[0];
                    break;
                default:
                    session_start();
                    if (isset($_SESSION["user"])) {
                        if ($_SESSION["user"]['disable_login'] <= 0) {
                            if ($url[0] == 'login' || $url[0] == 'register') {
                                $url[0] = 'home';
                            }
                            $this->controller = $url[0];
                        }
                    } else {
                        $url[0] == 'login';
                        $this->controller = $url[0];
                    }
                    break;
            }
            unset($url[0]);
        }
        require_once '../app/controllers/' . $this->controller . '.php';
        $this->controller = new $this->controller;
        //method

        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])) {
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        //params
        if (!empty($url)) {
            $this->params = array_values($url);
        }
        // var_dump([$this->controller,$this->method,$this->params]);
        call_user_func_array([$this->controller, $this->method], $this->params);
    }
    public function parseURL()
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            return $url;
        }
    }
}
