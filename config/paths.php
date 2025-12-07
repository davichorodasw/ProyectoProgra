<?php
if (!defined('BASE_PATH')) {
    define('BASE_PATH', '/php/Proyecto1puro/ProyectoProgra/');
}

if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    define('BASE_URL', $protocol . $host . BASE_PATH);
}

function url($path = '')
{
    return BASE_PATH . ltrim($path, '/');
}

function asset($path)
{
    return BASE_URL . ltrim($path, '/');
}

function isInViews()
{
    return strpos($_SERVER['PHP_SELF'], '/views/') !== false;
}

function relativePath($file)
{
    if (isInViews()) {
        return '../' . $file;
    }
    return $file;
}
