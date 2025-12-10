<?php
session_start();

if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (isset($_GET['id'])) {
    $db = new mysqli('localhost', 'root', '', 'ritmoretro');

    $id = intval($_GET['id']);
    $query = $db->query("SELECT tipo FROM productos WHERE id = $id");
    $producto = $query->fetch_object();

    if ($producto) {
        $delete = $db->query("DELETE FROM productos WHERE id = $id");

        // redirigir según el tipo que era
        if ($producto->tipo == 'cd') {
            header("Location: cds.php");
        } else {
            header("Location: vinilos.php");
        }
    } else {
        // si el id no existe
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}
