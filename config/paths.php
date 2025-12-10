<?php
if (!defined('BASE_PATH')) {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $scriptDir  = dirname($scriptName);

    if (basename($scriptDir) === 'views') {
        $scriptDir = dirname($scriptDir);
    }

    $basePath = rtrim($scriptDir, '/');
    $basePath = $basePath === '' ? '/' : $basePath . '/';

    define('BASE_PATH', $basePath);
}

if (!defined('BASE_URL')) {
    define('BASE_URL', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') .
        $_SERVER['HTTP_HOST'] . BASE_PATH);
}

if (!function_exists('url')) {
    function url($path = '')
    {
        return BASE_PATH . ltrim($path, '/');
    }
}

if (!function_exists('asset')) {
    function asset($path = '')
    {
        return BASE_URL . ltrim($path, '/');
    }
}

if (!function_exists('isInViews')) {
    function isInViews()
    {
        return strpos($_SERVER['SCRIPT_NAME'], '/views/') !== false;
    }
}

if (!function_exists('relativePath')) {
    function relativePath($file)
    {
        return isInViews() ? '../' . $file : $file;
    }
}
