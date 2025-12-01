<?php
// php/procesar-registro.php
require_once 'conexion.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../views/register.php'); // RUTA CORREGIDA
    exit;
}

// Recoger y sanitizar datos
$conn = conectarDB();
$name = mysqli_real_escape_string($conn, trim($_POST['name']));
$email = mysqli_real_escape_string($conn, trim($_POST['email']));
$phone = mysqli_real_escape_string($conn, trim($_POST['phone']));
$password = $_POST['password'];
$password_confirmation = $_POST['password_confirmation'];
$terms = isset($_POST['terms']) ? 1 : 0;

// Validaciones básicas
$errors = [];

if (empty($name)) {
    $errors[] = 'El nombre es requerido';
}

if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'El email no es válido';
}

if (strlen($password) < 8) {
    $errors[] = 'La contraseña debe tener al menos 8 caracteres';
}

if ($password !== $password_confirmation) {
    $errors[] = 'Las contraseñas no coinciden';
}

if (!$terms) {
    $errors[] = 'Debes aceptar los términos y condiciones';
}

// Si hay errores, redirigir con mensajes
if (!empty($errors)) {
    $error_string = urlencode(implode('|', $errors));
    $query = http_build_query([
        'error' => $error_string,
        'name' => $name,
        'email' => $email,
        'phone' => $phone
    ]);
    header('Location: ../views/register.php?' . $query);
    exit;
}

// Verificar si el email ya existe
$checkEmailQuery = "SELECT id FROM usuarios WHERE email = '$email'";
$result = mysqli_query($conn, $checkEmailQuery);

if (mysqli_num_rows($result) > 0) {
    mysqli_close($conn);
    $query = http_build_query([
        'error' => urlencode('El email ya está registrado'),
        'name' => $name,
        'email' => $email,
        'phone' => $phone
    ]);
    header('Location: ../views/register.php?' . $query);
    exit;
}

// Hash de la contraseña
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insertar usuario en la base de datos
$insertQuery = "INSERT INTO usuarios (nombre, email, telefono, password) 
                VALUES ('$name', '$email', '$phone', '$password_hash')";

if (mysqli_query($conn, $insertQuery)) {
    mysqli_close($conn);
    header('Location: ../views/register.php?success=true');
    exit;
} else {
    $error = mysqli_error($conn);
    mysqli_close($conn);
    $query = http_build_query([
        'error' => urlencode('Error al registrar el usuario: ' . $error),
        'name' => $name,
        'email' => $email,
        'phone' => $phone
    ]);
    header('Location: ../views/register.php?' . $query);
    exit;
}
