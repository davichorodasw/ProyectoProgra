<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/login.php');
    exit;
}

require_once 'conexion.php';

$conn = conectarDB();
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$password = $_POST['password'];

if (empty($email) || empty($password)) {
    header('Location: ../views/login.php?error=Email+y+contraseña+son+obligatorios');
    exit;
}

$query = "SELECT id, nombre, email, password FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    mysqli_close($conn);
    header('Location: ../views/login.php?error=Email+o+contraseña+incorrectos&email=' . urlencode($email));
    exit;
}

$usuario = mysqli_fetch_assoc($result);
mysqli_close($conn);

if (!password_verify($password, $usuario['password'])) {
    header('Location: ../views/login.php?error=Email+o+contraseña+incorrectos&email=' . urlencode($email));
    exit;
}

$_SESSION['user_id'] = $usuario['id'];
$_SESSION['user_name'] = $usuario['nombre'];
$_SESSION['user_email'] = $usuario['email'];
$_SESSION['logged_in'] = true;

header('Location: ../index.php');
exit;
