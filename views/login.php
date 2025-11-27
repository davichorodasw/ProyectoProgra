<?php
$pageTitle = "Iniciar Sesión - Ritmo Retro";
$currentPage = "login";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

// Variables para simular funcionalidad (en un caso real vendrían del backend)
$errors = isset($_GET["error"])
    ? ["Credenciales incorrectas. Por favor, inténtalo de nuevo."]
    : [];
$status = isset($_GET["status"])
    ? "Tu contraseña ha sido restablecida correctamente."
    : null;
$oldEmail = isset($_GET["email"]) ? $_GET["email"] : "";
?>

<?php include "../componentes/header.php"; ?>
<?php include "../componentes/nav.php"; ?>

<main class="main-content">
    <?php
    $formAction = '#';
    $forgotPasswordLink = './forgot-password.php';
    $registerLink = './register.php';
    include '../componentes/login-form.php';
    ?>
</main>

<?php include "../componentes/footer.php"; ?>