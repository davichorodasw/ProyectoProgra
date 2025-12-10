<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

require_once 'conexion.php';
require_once 'manejoUsuarios.php';

$conn = conectarDB();
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
mysqli_close($conn);

$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: ../views/login.php?error=Email+y+contraseña+son+obligatorios');
    exit;
}

$usuario = obtenerUsuarioPorEmail($email);

if (!$usuario) {
    header('Location: ../views/login.php?error=Email+o+contraseña+incorrectos&email=' . urlencode($email));
    exit;
}

if (!password_verify($password, $usuario['password'])) {
    header('Location: ../views/login.php?error=Email+o+contraseña+incorrectos&email=' . urlencode($email));
    exit;
}

$userObj = new stdClass();
$userObj->id = $usuario['id'];
$userObj->nombre = $usuario['nombre'];
$userObj->email = $usuario['email'];
$userObj->rol = $usuario['rol'];

$_SESSION['user_id'] = $usuario['id'];
$_SESSION['user_name'] = $usuario['nombre'];
$_SESSION['user_email'] = $usuario['email'];
$_SESSION['user_role'] = $usuario['rol'];
$_SESSION['logged_in'] = true;
$_SESSION['identity'] = $userObj;

header('Location: ../index.php');
exit;
