<?php
class Masuk
{
    public function masuk()
    {
        // var_dump($_POST);
        require 'init_no_session.php';
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }
        
        $DB = DB::getInstance();
        $keyEncrypt = $_SESSION['key_encrypt'];
        $user = new User();
        $validate = new Validate($_POST);
        //$crypto = new CryptoUtils();
        // var_dump($_POST);
        // var_dump($validate);
        if (isset($_POST['login'])) {
            // var_dump($_POST);
            $username = $validate->setRules('username', 'Username', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 6
            ]);
            // var_dump($username);
            //$username = $crypto->decrypt($username, $keyEncrypt);
            $password = $validate->setRules('password', 'Pasword', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 4
            ]);
            
            // var_dump($password);
            // $password = password_hash($password, PASSWORD_DEFAULT);
            // var_dump(password_hash($password, PASSWORD_DEFAULT));
            //$password = $crypto->decrypt($password, $keyEncrypt);
            if ($validate->passed()) {
                
                $data = $DB->getQuery('SELECT * FROM user_sesendok_biila WHERE (username = ? OR email = ?)', [$username, $username])[0];
                // var_dump($data);
                $passIsValid = password_verify($password, $data->password);
                // var_dump("passIsValid = $passIsValid");
                //var_dump(sizeof((array)$data));
                if (sizeof((array)$data) > 0 && $data->disable_login <= 0 && $passIsValid) { // ubah ke array sebelum menggunakan sizeof
                    $pesanError = '';
                    //var_dump($data->disable_login);
                    //$pesanError = $user->validasiLogin($_POST);
                    if (empty($pesanError)) {
                        $status = session_status();
                        if ($status == PHP_SESSION_NONE) {
                            //There is no active session
                            session_destroy();
                        } else
                        if ($status == PHP_SESSION_DISABLED) {
                            //Sessions are not available
                            session_destroy();
                        } else
                        if ($status == PHP_SESSION_ACTIVE) {
                            //Destroy current and start new one
                            session_destroy();
                            session_start();
                        }
                        $_SESSION["user"] = (array)$data;
                        $_SESSION["user"]["key_encrypt"] = KEY_ENCRYPT;
                        //var_dump($_SESSION);
                        $id = $_SESSION["user"]["id"];
                        //var_dump($id);
                        $type_user = $_SESSION["user"]["type_user"];
                        //$DB->update('user_sesendok_biila', ['tgl_login'=>NOW()], ['id','=',$id]);
                        $DB->runQuery('UPDATE `user_sesendok_biila` SET tgl_login = NOW() WHERE id = ?', [$id]);
                        if ($type_user == 'admin') {
                            $session = 1;
                            // header("Location: home");
                            // exit;
                        } else {
                            $session = 1; //$session = 2;
                            // header("Location: home");
                            // exit();
                        }
                    } else {
                        $session = 4;
                    }
                } elseif ($data->disable_login > 0) {
                    $session = 6;
                }else {
                    $session = 5;
                }
            } else {
                $session = $validate->getError();
                // var_dump($validate->getError());
            }
        }else{
            $session = 7;
        }
        return $session;
    }
}
