<?php
class User
{
    private $_db = null;
    private $_formItem = [];
    public
    function validasiInsert($formMethod)
    {
        $validate = new Validate($formMethod);
        $this->_formItem['username'] = $validate->setRules('username', 'Username', [
            'sanitize' => 'string',
            'required' => true,
            'min_char' => 4,
            'regexp' => '/^[A-Za-z0-9]+$/',
            'unique' => ['user_sesendok_biila', 'username'],
        ]);
        $this->_formItem['password'] = $validate->setRules('password', 'Password', [
            'sanitize' => 'string',
            'required' => true,
            'min_char' => 6,
            'regexp' => '/[A-Za-z]+[0-9]|[0-9]+[A-Za-z]/'
        ]);
        $this->_formItem['ulangi_password'] =
            $validate->setRules('ulangi_password', 'Ulangi password', [
                'sanitize' => 'string',
                'required' => true,
                'matches' => 'password'
            ]);
        $this->_formItem['email'] = $validate->setRules('email', 'Email', [
            'sanitize' => 'string',
            'required' => true,
            'email' => true
        ]);
        if (!$validate->passed()) {
            return $validate->getError();
        }
    }
    public
    function getItem($item)
    {
        return isset($this->_formItem[$item]) ? $this->_formItem[$item] : '';
    }
    public
    function insert()
    {
        $this->_db = DB::getInstance();
        $newUser = [
            'username' => $this->getItem('username'),
            'password' => password_hash($this->getItem('password'), PASSWORD_DEFAULT),
            'email' => $this->getItem('email')
        ];
        return $this->_db->insert('user_sesendok_biila', $newUser);
    }
    public
    function validasiLogin($formMethod)
    {
        $validate = new Validate($formMethod);
        $this->_formItem['username'] = $validate->setRules('username', 'Username', [
            'sanitize' => 'string',
            'required' => true
        ]);
        $this->_formItem['password'] = $validate->setRules('password', 'Password', [
            'sanitize' => 'string',
            'required' => true
        ]);
        if (!$validate->passed()) {
            return $validate->getError();
        } else {
            $this->_db = DB::getInstance();
            $this->_db->select('password');
            $result = $this->_db->getWhereOnce('user_sesendok_biila', [
                'username', '=',
                $this->_formItem['username']
            ]);
            if (empty($result) || !password_verify(
                $this->_formItem['password'],
                $result->password
            )) {
                $pesanError[] = 'Maaf, username / password salah';
                return $pesanError;
            }
        }
    }
    public
    function login()
    {
        $_SESSION["username"] = $this->getItem('username');


        //$g=$this->generate( $_SESSION[ "username" ] );
        //var_dump($g);
        //var_dump($_SESSION);
        /*$real_path = realpath( dirname( __FILE__ ) );
        if ( strpos( $real_path, 'script' ) ) {
            header("Location: ../index.php");
        } else {
            header( "Location: ../index.php" );
        }*/
    }
    public
    function cekUserSession()
    {
        $real_path = realpath(dirname(__FILE__));
        //var_dump($_SESSION["user"]);
        if (!isset($_SESSION["user"]['nama'])) {
            // var_dump(!isset($_SESSION["user"]));
            if (strpos($real_path, 'script')) {
                header("Location: login");
            }
        }
    }
    public
    function logout()
    {
        unset($_SESSION["user"]);
        unset($_SESSION["key_encrypt"]);
        $real_path = realpath(dirname(__FILE__));
        //session_destroy();
        if (strpos($real_path, 'script')) {
            header("Location: ../login");
        }
    }
    public
    function generate($username)
    {
        $this->_db = DB::getInstance();
        $result = $this->_db->getWhereOnce('user_sesendok_biila', ['username', '=', $username]);
        foreach ($result as $key => $val) {
            $this->_formItem[$key] = $val;
        }
    }
    public
    function validasiUbahPassword($formMethod)
    {
        $validate = new Validate($formMethod);
        $this->_formItem['password_lama'] = $validate->setRules('password_lama', 'Password lama', [
                'sanitize' => 'string',
                'required' => true,
            ]);
        $this->_formItem['password_baru'] = $validate->setRules('password_baru', 'Password baru', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 6,
                'regexp' => '/[A-Za-z]+[0-9]|[0-9]+[A-Za-z]/'
            ]);
        $this->_formItem['ulangi_password_baru'] = $validate->setRules('ulangi_password_baru', 'Ulangi password baru', [
                'sanitize' => 'string',
                'required' => true,
                'matches' => 'password_baru'
            ]);
        if (!$validate->passed()) {
            return $validate->getError();
        } else {
            $this->_db = DB::getInstance();
            $this->_db->select('password');
            $result = $this->_db->getWhereOnce('user_sesendok_biila', [
                'username', '=',
                $this->_formItem['username']
            ]);
            if (empty($result) || !password_verify(
                $this->_formItem['password_lama'],
                $result->password
            )) {
                $pesanError[] = 'Maaf, password lama anda tidak sesuai';
                return $pesanError;
            }
        }
    }
    public
    function ubahPassword()
    {
        $newUserPassword = [
            'password' => password_hash(
                $this->getItem('password_baru'),
                PASSWORD_DEFAULT
            )
        ];
        $this->_db->update('user_sesendok_biila', $newUserPassword, [
            'username', '=',
            $this->_formItem['username']
        ]);
    }
}
