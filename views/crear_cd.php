<?php
session_start();

if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Crear Producto - Ritmo Retro";
$cssPath = "../css/styles.css";
$additionalCSS = ["css/notification.css"];

include "../componentes/header.php";
include "../componentes/nav.php";

$exito = false;
$notificacion = null;

if (isset($_POST['guardar'])) {
    $db = new mysqli('localhost', 'root', '', 'ritmoretro');
    if ($db->connect_error) die("Error de conexión: " . $db->connect_error);
    $db->set_charset("utf8");

    $titulo      = $db->real_escape_string($_POST['titulo']);
    $artista     = $db->real_escape_string($_POST['artista']);
    $tipo        = 'cd';
    $genero      = $db->real_escape_string($_POST['genero']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $imagen      = 'default.png';

    if ($precio < 0) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Precio inválido',
            'message' => 'El precio no puede ser negativo.'
        ];
    } elseif ($stock < 0) {
        $notificacion = [
            'type' => 'error',
            'title' => 'Stock inválido',
            'message' => 'El stock no puede ser negativo.'
        ];
    }

    if (!$notificacion) {
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

        if (!$notificacion) {

            $sql = "INSERT INTO productos (tipo, titulo, artista, genero, precio, imagen, descripcion, stock) 
                    VALUES ('$tipo', '$titulo', '$artista', '$genero', $precio, '$imagen', '$descripcion', $stock)";

            if ($db->query($sql)) {
                $exito = true;
                $notificacion = [
                    'type' => 'success',
                    'title' => '¡CD creado con éxito!',
                    'message' => 'Redirigiendo a CDs...',
                    'redirect' => 'cds.php'
                ];
            } else {
                $notificacion = [
                    'type' => 'error',
                    'title' => 'Error',
                    'message' => 'No se pudo crear el CD. ' . $db->error
                ];
            }
        }
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
            style="display: none;">
        </div>

        <script>
            if (typeof checkForPHPNotification === 'function') {
                checkForPHPNotification();
            }
        </script>
    <?php endif; ?>

    <div class="page-header">
        <h1>Añadir Nuevo CD</h1>
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

            <div class="form-group">
                <label for="genero"><strong>Género:</strong></label>
                <select name="genero" required style="width: 100%; padding: 8px;">
                    <option value="">Selecciona un género</option>
                    <option value="rock" <?= isset($_POST['genero']) && $_POST['genero'] == 'rock' ? 'selected' : '' ?>>Rock</option>
                    <option value="pop" <?= isset($_POST['genero']) && $_POST['genero'] == 'pop' ? 'selected' : '' ?>>Pop</option>
                    <option value="jazz" <?= isset($_POST['genero']) && $_POST['genero'] == 'jazz' ? 'selected' : '' ?>>Jazz</option>
                    <option value="clasica" <?= isset($_POST['genero']) && $_POST['genero'] == 'clasica' ? 'selected' : '' ?>>Clásica</option>
                    <option value="electronica" <?= isset($_POST['genero']) && $_POST['genero'] == 'electronica' ? 'selected' : '' ?>>Electrónica</option>
                    <option value="blues" <?= isset($_POST['genero']) && $_POST['genero'] == 'blues' ? 'selected' : '' ?>>Blues</option>
                    <option value="soul" <?= isset($_POST['genero']) && $_POST['genero'] == 'soul' ? 'selected' : '' ?>>Soul</option>
                    <option value="funk" <?= isset($_POST['genero']) && $_POST['genero'] == 'funk' ? 'selected' : '' ?>>Funk</option>
                </select>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="precio"><strong>Precio ($):</strong></label>
                    <input type="number" name="precio" step="0.01" min="0" required style="width: 100%; padding: 8px;"
                        value="<?= isset($_POST['precio']) && !$exito ? htmlspecialchars($_POST['precio']) : '' ?>">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="stock"><strong>Stock:</strong></label>
                    <input type="number" name="stock" min="0" required style="width: 100%; padding: 8px;"
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
                Guardar CD
            </button>

            <a href="cds.php" style="text-align: center; display: block; margin-top: 10px;">Cancelar</a>
        </form>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>