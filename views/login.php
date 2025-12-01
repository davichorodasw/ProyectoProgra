<?php
session_start();

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    header('Location: ../index.php');
    exit;
}

$pageTitle = "Iniciar Sesión - Ritmo Retro";
$currentPage = "login";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

$errors = [];
if (isset($_GET["error"])) {
    $errors[] = urldecode($_GET["error"]);
}

$oldEmail = isset($_GET["email"]) ? $_GET["email"] : "";
?>

<?php include "../componentes/header.php"; ?>
<?php include "../componentes/nav.php"; ?>

<main class="main-content">
    <?php
    $formAction = '../php/procesar-login.php';
    $forgotPasswordLink = './forgot-password.php';
    $registerLink = './register.php';
    include '../componentes/login-form.php';
    ?>
</main>

<?php include "../componentes/footer.php"; ?>