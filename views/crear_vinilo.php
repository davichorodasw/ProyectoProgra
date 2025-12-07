<?php
session_start();

if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Crear Producto - Ritmo Retro";
$cssPath = "../css/styles.css";
$additionalCSS = ["../css/notification.css"];
$jsPath = "../js/notification.js";

include "../componentes/header.php";
include "../componentes/nav.php";

$exito = false;
$redireccion = "";
$notificacion = null;

if (isset($_POST['guardar'])) {
    $db = new mysqli('localhost', 'root', '', 'ritmoretro');
    if ($db->connect_error) die("Error de conexión: " . $db->connect_error);
    $db->set_charset("utf8");

    $titulo      = $db->real_escape_string($_POST['titulo']);
    $artista     = $db->real_escape_string($_POST['artista']);
    $tipo        = 'vinilo';
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $imagen = 'default.png';

    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];

        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($detectedType, $allowedTypes)) {
            $notificacion = ['type' => 'error', 'title' => 'Error', 'message' => 'Solo se permiten JPG, PNG o WebP'];
        } elseif ($file['size'] > $maxSize) {
            $notificacion = ['type' => 'error', 'title' => 'Error', 'message' => 'La imagen no debe pesar más de 2 MB'];
        } else {
            $uploadDir = __DIR__ . '/../img/covers/';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($ext);
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $imagen = $filename;
            } else {
                $notificacion = ['type' => 'error', 'title' => 'Error', 'message' => 'No se pudo guardar la imagen (permisos?)'];
            }
        }
    }

    $sql = "INSERT INTO productos (tipo, titulo, artista, precio, imagen, descripcion, stock) 
            VALUES ('$tipo', '$titulo', '$artista', $precio, '$imagen', '$descripcion', $stock)";

    if ($db->query($sql)) {
        $exito = true;
        $notificacion = [
            'type' => 'success',
            'title' => '¡Producto creado con éxito!',
            'message' => 'Redirigiendo a Vinilos...',
            'redirect' => 'vinilos.php'
        ];
    } else {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error al guardar',
            'message' => 'No se pudo crear el producto.'
        ];
    }
    $db->close();
}
?>

<main class="main-content">
    <?php if ($notificacion): ?>
        <div id="php-notification"
            data-type="<?= htmlspecialchars($notificacion['type']) ?>"
            data-title="<?= htmlspecialchars($notificacion['title']) ?>"
            data-message="<?= htmlspecialchars($notificacion['message']) ?>"
            <?php if (isset($notificacion['redirect'])): ?> data-redirect="<?= htmlspecialchars($notificacion['redirect']) ?>" <?php endif; ?>
            style="display: none;"></div>
    <?php endif; ?>

    <div class="page-header">
        <h1>Añadir Nuevo Vinilo</h1>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <form action="" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <div class="form-group">
                <label for="titulo"><strong>Título del Álbum:</strong></label>
                <input type="text" name="titulo" required style="width: 100%; padding: 8px;"
                    value="<?= isset($_POST['titulo']) && !$exito ? htmlspecialchars($_POST['titulo']) : '' ?>"
                    placeholder="Ej. Dark Side of the Moon">
            </div>

            <div class="form-group">
                <label for="artista"><strong>Artista / Banda:</strong></label>
                <input type="text" name="artista" required style="width: 100%; padding: 8px;"
                    value="<?= isset($_POST['artista']) && !$exito ? htmlspecialchars($_POST['artista']) : '' ?>">
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="precio"><strong>Precio ($):</strong></label>
                    <input type="number" name="precio" step="0.01" required style="width: 100%; padding: 8px;"
                        value="<?= isset($_POST['precio']) && !$exito ? htmlspecialchars($_POST['precio']) : '' ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="stock"><strong>Stock:</strong></label>
                    <input type="number" name="stock" required style="width: 100%; padding: 8px;"
                        value="<?= isset($_POST['stock']) && !$exito ? htmlspecialchars($_POST['stock']) : '' ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea name="descripcion" rows="4" style="width: 100%; padding: 8px;"><?= isset($_POST['descripcion']) && !$exito ? htmlspecialchars($_POST['descripcion']) : '' ?></textarea>
            </div>

            <div class="form-group">
                <label for="imagen"><strong>Imagen de Portada (opcional):</strong></label>
                <input type="file" name="imagen" accept="image/jpeg,image/jpg,image/png,image/webp">
                <small style="color:#666;">Formatos: JPG, PNG, WebP. Máximo 2 MB</small>
            </div>

            <button type="submit" name="guardar" class="button button-red" style="padding: 15px; cursor: pointer; background-color: #e74c3c; color: white; border: none; font-weight: bold;">
                Guardar Vinilo
            </button>

            <a href="vinilos.php" style="text-align: center; display: block; margin-top: 10px;">Cancelar</a>
        </form>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>