<?php
// ini_set('display_errors', 'On');
// error_reporting(error_reporting() & ~E_NOTICE);
// require_once '../app/init.php';
// $app = new App;
ini_set('display_errors', 'On');
error_reporting(E_ALL);

require_once __DIR__ . '/../app/init.php';

// pastikan url SELALU ada
$_GET['url'] = $_GET['url'] ?? '';

$app = new App();