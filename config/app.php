<?php
define('APP_NAME', 'SIAKAD SMK NU Muara Sugihan');
define('BASE_PATH', dirname(__DIR__));
define('VIEW_PATH', BASE_PATH.'/views');
date_default_timezone_set('Asia/Jakarta');

function base_url($path='') {
    $script = $_SERVER['SCRIPT_NAME'];
    $base = rtrim(dirname($script), '/');
    return $base.'/'.$path;
}

function redirect($url) { header("Location: $url"); exit; }
function old($key, $default='') { return $_POST[$key] ?? $default; }

function flash($key, $val=null) {
    if (session_status()===PHP_SESSION_NONE) session_start();
    if ($val!==null) { $_SESSION['flash_'.$key]=$val; return; }
    $v=$_SESSION['flash_'.$key] ?? null;
    unset($_SESSION['flash_'.$key]);
    return $v;
}
