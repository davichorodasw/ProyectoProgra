<?php
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $script_name = $_SERVER['SCRIPT_NAME'];

    $base_dir = str_replace('\\', '/', dirname(dirname(__FILE__)));
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $base_path = str_replace($doc_root, '', $base_dir);

    define('BASE_URL', $protocol . '://' . $host . $base_path . '/');
}

function conectarDB()
{
    $servername = "localhost";
    $username = "root";
    $password = "";
    $db = "ritmoretro";

    $conn = mysqli_connect($servername, $username, $password, $db);

    if (!$conn) {
        die("Conexión fallida: " . mysqli_connect_error());
    }

    return $conn;
}

function desconectarDB($conn)
{
    mysqli_close($conn);
}
