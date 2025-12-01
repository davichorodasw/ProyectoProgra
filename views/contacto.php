<?php
session_start();
$pageTitle = "Contacto - Ritmo Retro";
$currentPage = "contacto";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

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
    <?php include "../componentes/header.php"; ?>
    <?php include "../componentes/nav.php"; ?>

    <main class="main-content">
        <?php
        $formAction = "#";
        include "../componentes/contact-form.php";
        ?>

        <div class="contact-info">
            <div class="contact-method">
                <div class="contact-icon">📧</div>
                <h3>Email</h3>
                <p>email@ritmoretro.com</p>
            </div>

            <div class="contact-method">
                <div class="contact-icon">📞</div>
                <h3>Teléfono</h3>
                <p>+593 98 765 4321</p>
            </div>
        </div>
    </main>

    <?php include "../componentes/footer.php"; ?>