<?php
if (!defined('BASE_PATH')) {
    $projectRoot = __DIR__ . '/..';
    $projectRoot = realpath($projectRoot);

    $docRoot = $_SERVER['DOCUMENT_ROOT'];

    if (strpos($projectRoot, $docRoot) === 0) {
        $relativePath = substr($projectRoot, strlen($docRoot));
        $basePath = '/' . trim(str_replace('\\', '/', $relativePath), '/') . '/';
    } else {
        $basePath = '/';
    }

    define('BASE_PATH', $basePath);
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
