<?php
class Masuk
{
    public function masuk()
    {
        // Mulai session jika belum
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Include init
        require 'init_no_session.php';

        // Hapus user lama jika ada
        if (isset($_SESSION["user"])) {
            unset($_SESSION["user"]);
        }

        $DB = DB::getInstance();
        $keyEncrypt = isset($_SESSION['key_encrypt']) ? $_SESSION['key_encrypt'] : null;
        $user = new User();
        $validate = new Validate($_POST);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Validasi input
            $username = $validate->setRules('username', 'Username', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 6
            ]);

            $password = $validate->setRules('password', 'Password', [
                'sanitize' => 'string',
                'required' => true,
                'min_char' => 4
            ]);

            if ($validate->passed()) {
                // Ambil data user
                $dataArr = $DB->getQuery(
                    'SELECT * FROM user_sesendok_biila WHERE (username = ? OR email = ?)',
                    [$username, $username]
                );

                if (count($dataArr) === 0) {
                    return 7; // login gagal
                }

                $data = $dataArr[0];
                $passIsValid = password_verify($password, $data->password);

                if ($passIsValid && $data->disable_login <= 0) {
                    // Set session user
                    $_SESSION["user"] = (array)$data;
                    $_SESSION["user"]["key_encrypt"] = KEY_ENCRYPT;

                    // Update tgl_login
                    $DB->runQuery('UPDATE `user_sesendok_biila` SET tgl_login = NOW() WHERE id = ?', [$data->id]);

                    // Return kode sukses
                    return 1; // login sukses (admin/umum)
                } elseif ($data->disable_login > 0) {
                    return 6; // akun nonaktif
                } else {
                    return 7; // login gagal
                }

            } else {
                return 4; // validasi error
            }

        } else {
            return 7; // request bukan POST
        }
    }
}
