<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

if (!isset($_GET['id'])) {
    header('Location: ../views/gestion_usuarios.php?error=Falta+ID');
    exit;
}

$id = intval($_GET['id']);

if ($id === $_SESSION['user_id'] ?? 0) {
    header('Location: ../views/gestion_usuarios.php?error=No+puedes+eliminarse+a+ti+mismo');
    exit;
}

require_once 'manejoUsuarios.php';

$ok = eliminarUsuarioConPedidos($id);

if ($ok) {
    header('Location: ../views/gestion_usuarios.php?success=Usuario+eliminado');
    exit;
} else {
    header('Location: ../views/gestion_usuarios.php?error=No+se+pudo+eliminar');
    exit;
}
