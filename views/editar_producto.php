<?php
session_start();

if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

$pageTitle = "Editar Producto - Ritmo Retro";
$cssPath = "../css/styles.css";
$additionalCSS = ["css/notification.css"];

include "../componentes/header.php";
include "../componentes/nav.php";

$db = new mysqli('localhost', 'root', '', 'ritmoretro');
if ($db->connect_error) die("Error de conexión");
$db->set_charset("utf8");

if (!isset($_GET['id'])) header("Location: gestion_productos.php");
$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id = $id";
$resultado = $db->query($sql);
$prod = $resultado->fetch_object();
if (!$prod) header("Location: gestion_productos.php");

$notificacion = null;

if (isset($_POST['actualizar'])) {
    $titulo      = $db->real_escape_string($_POST['titulo']);
    $artista     = $db->real_escape_string($_POST['artista']);
    $tipo        = $db->real_escape_string($_POST['tipo']);
    $genero      = $db->real_escape_string($_POST['genero']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $imagen_nueva = $prod->imagen;

    if (!empty($_FILES['imagen']['name']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['imagen'];
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        $maxSize = 2 * 1024 * 1024;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $detectedType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (in_array($detectedType, $allowedTypes) && $file['size'] <= $maxSize) {
            $uploadDir = __DIR__ . '/../img/covers/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(8)) . '.' . strtolower($ext);
            $destination = $uploadDir . $filename;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                if ($prod->imagen !== 'default.png') {
                    $oldFile = $uploadDir . $prod->imagen;
                    if (file_exists($oldFile)) unlink($oldFile);
                }
                $imagen_nueva = $filename;
            }
        }
    }

    $update = "UPDATE productos SET 
               titulo = '$titulo', 
               artista = '$artista', 
               tipo = '$tipo',
               genero = '$genero', 
               precio = $precio, 
               stock = $stock, 
               descripcion = '$descripcion',
               imagen = '$imagen_nueva'
               WHERE id = $id";

    if ($db->query($update)) {
        $pag = ($tipo == 'cd') ? 'cds.php' : 'vinilos.php';
        $notificacion = [
            'type' => 'success',
            'title' => '¡Producto actualizado!',
            'message' => 'Redirigiendo...',
            'redirect' => $pag
        ];
    } else {
        $notificacion = [
            'type' => 'error',
            'title' => 'Error',
            'message' => 'No se pudo actualizar. ' . $db->error
        ];
    }
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
        <h1>Editar: <?= htmlspecialchars($prod->titulo) ?></h1>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <form action="" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            <div class="form-group">
                <label>Título:</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($prod->titulo) ?>" required style="width: 100%; padding: 8px;">
            </div>

            <div class="form-group">
                <label>Artista:</label>
                <input type="text" name="artista" value="<?= htmlspecialchars($prod->artista) ?>" required style="width: 100%; padding: 8px;">
            </div>

            <div class="form-group">
                <label>Género:</label>
                <select name="genero" required style="width: 100%; padding: 8px;">
                    <option value="">Selecciona un género</option>
                    <option value="rock" <?= $prod->genero == 'rock' ? 'selected' : '' ?>>Rock</option>
                    <option value="pop" <?= $prod->genero == 'pop' ? 'selected' : '' ?>>Pop</option>
                    <option value="jazz" <?= $prod->genero == 'jazz' ? 'selected' : '' ?>>Jazz</option>
                    <option value="clasica" <?= $prod->genero == 'clasica' ? 'selected' : '' ?>>Clásica</option>
                    <option value="electronica" <?= $prod->genero == 'electronica' ? 'selected' : '' ?>>Electrónica</option>
                    <option value="blues" <?= $prod->genero == 'blues' ? 'selected' : '' ?>>Blues</option>
                    <option value="soul" <?= $prod->genero == 'soul' ? 'selected' : '' ?>>Soul</option>
                    <option value="funk" <?= $prod->genero == 'funk' ? 'selected' : '' ?>>Funk</option>
                </select>
            </div>

            <div class="form-group">
                <label>Formato:</label>
                <select name="tipo" style="width: 100%; padding: 8px;">
                    <option value="cd" <?= $prod->tipo == 'cd' ? 'selected' : '' ?>>CD</option>
                    <option value="vinilo" <?= $prod->tipo == 'vinilo' ? 'selected' : '' ?>>Vinilo</option>
                </select>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label>Precio ($):</label>
                    <input type="number" name="precio" step="0.01" value="<?= $prod->precio ?>" required style="width: 100%; padding: 8px;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label>Stock:</label>
                    <input type="number" name="stock" value="<?= $prod->stock ?>" required style="width: 100%; padding: 8px;">
                </div>
            </div>

            <div class="form-group">
                <label>Descripción:</label>
                <textarea name="descripcion" rows="4" style="width: 100%; padding: 8px;"><?= htmlspecialchars($prod->descripcion) ?></textarea>
            </div>

            <div class="form-group">
                <label>Imagen Actual:</label><br>
                <img src="../img/covers/<?= htmlspecialchars($prod->imagen) ?>" alt="portada" style="width: 150px; margin: 10px 0; border: 1px solid #ddd;"><br>
                <label>Cambiar Imagen (opcional):</label>
                <input type="file" name="imagen" accept="image/jpeg,image/jpg,image/png,image/webp">
                <small style="color:#666;">JPG, PNG, WebP. Máx 2 MB. Deja vacío para mantener la actual.</small>
            </div>

            <button type="submit" name="actualizar" class="button button-red" style="padding: 15px; cursor: pointer; background-color: #f39c12; color: white; border: none; font-weight: bold;">
                Actualizar Producto
            </button>

            <a href="gestion_productos.php" style="text-align: center; display: block; margin-top: 10px;">Cancelar</a>
        </form>
    </div>
</main>

<?php include "../componentes/footer.php";
$db->close(); ?>