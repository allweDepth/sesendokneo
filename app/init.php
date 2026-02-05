<?php
ini_set('session.cookie_secure', isset($_SERVER['HTTPS']));
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_samesite', 'Lax');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Constants.php';