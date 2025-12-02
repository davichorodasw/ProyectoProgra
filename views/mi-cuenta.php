<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: ./login.php?error=Por+favor+inicia+sesión+para+acceder+a+esta+página');
    exit;
}

$pageTitle = "Mi Cuenta - Ritmo Retro";
$currentPage = "mi-cuenta";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
?>

<?php include "../componentes/header.php"; ?>
<?php include "../componentes/nav.php"; ?>

<main class="main-content">
    <div class="auth-container">
        <h1>Mi Cuenta</h1>

        <div class="account-info" style="margin: 2rem 0; padding: 1.5rem; background: #f9f9f9; border-radius: 8px;">
            <h2>Información Personal</h2>
            <p><strong>Nombre:</strong> <?php echo htmlspecialchars($_SESSION['user_name']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>
            <p><strong>ID de Usuario:</strong> <?php echo htmlspecialchars($_SESSION['user_id']); ?></p>
        </div>

        <div class="account-actions" style="display: flex; gap: 1rem; margin-top: 2rem;">
            <a href="../index.php" class="btn btn-primary">Volver al Inicio</a>
        </div>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>