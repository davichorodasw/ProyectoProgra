<?php
session_start();

// 1. Seguridad: Solo admins
if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

// 2. Verificar si llega el ID
if (isset($_GET['id'])) {
    $db = new mysqli('localhost', 'root', '', 'ritmoretro');
    
    // Obtenemos el tipo antes de borrar para saber a dónde redirigir (cd o vinilo)
    $id = intval($_GET['id']);
    $query = $db->query("SELECT tipo FROM productos WHERE id = $id");
    $producto = $query->fetch_object();
    
    if ($producto) {
        // Ejecutar borrado
        $delete = $db->query("DELETE FROM productos WHERE id = $id");
        
        // Redirigir según el tipo que era
        if ($producto->tipo == 'cd') {
            header("Location: cds.php");
        } else {
            header("Location: vinilos.php");
        }
    } else {
        // Si el id no existe
        header("Location: ../index.php");
    }
} else {
    header("Location: ../index.php");
}
?>