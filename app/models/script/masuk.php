<?php
class Masuk
{
    public function masuk()
    {
       header('Content-Type: text/plain');

    echo "=== POST DATA ===\n";
    var_dump($_POST);

    echo "\n=== SESSION ===\n";
    var_dump($_SESSION);

    exit;

    }
}
