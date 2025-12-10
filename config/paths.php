<?php

if (defined('BASE_PATH')) {
    return; // ya no redefine si ya se incluyó antes con lo que está en el archivo de conexión
}

$scriptName = $_SERVER['SCRIPT_NAME'];
$scriptDir  = dirname($scriptName);

if (basename($scriptDir) === 'views') {
    $scriptDir = dirname($scriptDir);
}

$basePath = rtrim($scriptDir, '/');
$basePath = $basePath === '' ? '/' : $basePath . '/';

define('BASE_PATH', $basePath);
define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') .
    $_SERVER['HTTP_HOST'] . $basePath);

function url($path = '')
{
    return BASE_PATH . ltrim($path, '/'); // URL relativa desde la raíz del proyecto
}

function asset($path = '')
{
    return BASE_URL . ltrim($path, '/'); // URL de los archivos estáticos (css, js, fotos)
}

function isInViews()
{
    return strpos($_SERVER['SCRIPT_NAME'], '/views/') !== false;
}

function relativePath($file)
{
    return isInViews() ? '../' . $file : $file;
}
