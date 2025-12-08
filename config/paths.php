<?php

if (defined('BASE_PATH')) {
    return;
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
    return BASE_PATH . ltrim($path, '/');
}

function asset($path = '')
{
    return BASE_URL . ltrim($path, '/');
}

function isInViews()
{
    return strpos($_SERVER['SCRIPT_NAME'], '/views/') !== false;
}

function relativePath($file)
{
    return isInViews() ? '../' . $file : $file;
}

// Debug opcional (puedes comentar esta línea en producción)
/*
echo "<pre>BASE_PATH: " . BASE_PATH . "\nBASE_URL: " . BASE_URL . "\nScript: " . $_SERVER['SCRIPT_NAME'] . "</pre>";
*/