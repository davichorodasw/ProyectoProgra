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

require_once 'conexion.php';
$conn = conectarDB();

mysqli_begin_transaction($conn);

try {
    mysqli_query($conn, "DELETE FROM pedidos WHERE usuario_id = $id");
    $stmt = mysqli_prepare($conn, "DELETE FROM usuarios WHERE id = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    mysqli_commit($conn);
    header('Location: ../views/gestion_usuarios.php?success=Usuario+eliminado');
} catch (Exception $e) {
    mysqli_rollback($conn);
    header('Location: ../views/gestion_usuarios.php?error=No+se+pudo+eliminar');
}

mysqli_close($conn);
