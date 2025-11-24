<?php
$pageTitle = "Contacto - Ritmo Retro";
$currentPage = "contacto";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

// Variables para simular funcionalidad
$success = isset($_GET["success"])
    ? "¡Mensaje enviado correctamente! Te contactaremos pronto."
    : null;
$oldNombre = isset($_GET["nombre"]) ? $_GET["nombre"] : "";
$oldEmail = isset($_GET["email"]) ? $_GET["email"] : "";
$oldAsunto = isset($_GET["asunto"]) ? $_GET["asunto"] : "";
$oldMensaje = isset($_GET["mensaje"]) ? $_GET["mensaje"] : "";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $cssPath; ?>" />
    <link rel="stylesheet" href="../css/contacto.css" />
</head>
<body>
    
<div class="header">
    <div class="header-content">
        <h1>Ritmo Retro</h1>
        <img src="<?php echo $imgPath; ?>" alt="Logo Ritmo Retro" />
        <h2>En físico, todo es mejor</h2>
    </div>
</div>

<nav>
    <a href="../index.php">Inicio</a>
    <a href="./cds.php">CDs</a>
    <a href="./vinilos.php">Vinilos</a>
    <a href="./login.php">Iniciar sesión</a>
    <a href="./contacto.php" class="active">Contacto</a>
</nav>

<main class="main-content">
    <!-- Incluir el componente de contacto -->
    <?php
    $formAction = "#"; // En un caso real, sería el script que procesa el formulario
    include "../componentes/contact-form.php";
    ?>

    <!-- Información de contacto adicional -->
    <div class="contact-info">
        <div class="contact-method">
            <div class="contact-icon">📧</div>
            <h3>Email</h3>
            <p>email@ritmoretro.com</p>
            <p>Respondemos en 24h</p>
        </div>
        
        <div class="contact-method">
            <div class="contact-icon">📞</div>
            <h3>Teléfono</h3>
            <p>+34 123 456 789</p>
            <p>Lunes a Viernes 9:00-18:00</p>
        </div>
    </div>
</main>

<footer class="footer">
    <div class="footer-content">
        <div class="footer-section">
            <h3>Ritmo Retro</h3>
            <p>Tu tienda de confianza para vinilos y CDs.</p>
        </div>
        <div class="footer-section">
            <h3>Enlaces Rápidos</h3>
            <a href="../index.php">Inicio</a>
            <a href="./cds.php">CDs</a>
            <a href="./vinilos.php">Vinilos</a>
        </div>
        <div class="footer-section">
            <h3>Contacto</h3>
            <a href="./contacto.php">Formulario de contacto</a>
            <p>email@ritmoretro.com</p>
            <p>+34 123 456 789</p>
        </div>
    </div>
    <div class="footer-bottom">
        <p>&copy; 2023 Ritmo Retro. Todos los derechos reservados.</p>
    </div>
</footer>
</body>
</html>