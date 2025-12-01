<?php
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
