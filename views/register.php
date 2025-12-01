<?php
$pageTitle = 'Registro - Ritmo Retro';
$currentPage = 'register';
$cssPath = '../css/styles.css';
$imgPath = '../img/RitmoRetro.png';
$basePath = '../';

// Manejar errores
$errors = [];
if (isset($_GET['error'])) {
    $error_param = $_GET['error'];
    if (strpos($error_param, '|') !== false) {
        $errors = explode('|', urldecode($error_param));
    } else {
        $errors[] = urldecode($error_param);
    }
}

$success = isset($_GET['success']) ? '¡Cuenta creada correctamente!' : null;
$oldName = isset($_GET['name']) ? $_GET['name'] : '';
$oldEmail = isset($_GET['email']) ? $_GET['email'] : '';
$oldPhone = isset($_GET['phone']) ? $_GET['phone'] : '';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $pageTitle; ?></title>
    <link rel="stylesheet" href="<?php echo $cssPath; ?>" />
    <link rel="stylesheet" href="../css/auth.css" />
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
        <a href="./contacto.php">Contacto</a>
    </nav>

    <main class="main-content">
        <?php
        // RUTA CORREGIDA: apunta a php/procesar-registro.php
        $formAction = '../php/procesar-registro.php';
        $loginLink = './login.php';
        $termsLink = './terminos.php';
        $privacyLink = './privacidad.php';
        include '../componentes/register-form.php';
        ?>
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
                <h3>Legal</h3>
                <a href="./terminos.php">Términos y Condiciones</a>
                <a href="./privacidad.php">Política de Privacidad</a>
                <a href="./cookies.php">Cookies</a>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2023 Ritmo Retro. Todos los derechos reservados.</p>
        </div>
    </footer>
</body>

</html>