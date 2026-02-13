<?php
ini_set('display_errors', 'On');
error_reporting(error_reporting() & ~E_NOTICE);
require_once '../app/init.php';
$app = new App;