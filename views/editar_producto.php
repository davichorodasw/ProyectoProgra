<?php
session_start();

// 1. Seguridad
if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php");
    exit();
}

$db = new mysqli('localhost', 'root', '', 'ritmoretro');
$db->set_charset("utf8");

// 2. Obtener datos del producto a editar
if (!isset($_GET['id'])) {
    header("Location: cds.php");
}
$id = intval($_GET['id']);
$sql = "SELECT * FROM productos WHERE id = $id";
$resultado = $db->query($sql);
$prod = $resultado->fetch_object();

// Si no existe el producto, fuera
if (!$prod) { header("Location: cds.php"); }

// 3. Procesar formulario al guardar
$mensaje = "";
if (isset($_POST['actualizar'])) {
    $titulo      = $db->real_escape_string($_POST['titulo']);
    $artista     = $db->real_escape_string($_POST['artista']);
    $tipo        = $db->real_escape_string($_POST['tipo']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    
    // Gestión de Imagen (Solo actualizamos si suben una nueva)
    $sql_imagen = ""; 
    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'])) {
        $file = $_FILES['imagen'];
        $filename = time() . "_" . $file['name'];
        $mimetype = $file['type'];

        if ($mimetype == "image/jpg" || $mimetype == 'image/jpeg' || $mimetype == 'image/png') {
            if (!is_dir('../img/covers/')) { mkdir('../img/covers/', 0777, true); }
            move_uploaded_file($file['tmp_name'], '../img/covers/' . $filename);
            $sql_imagen = ", imagen = '$filename'"; // Parte del SQL
        }
    }

    // UPDATE Query
    $update = "UPDATE productos SET 
               titulo = '$titulo', 
               artista = '$artista', 
               tipo = '$tipo', 
               precio = $precio, 
               stock = $stock, 
               descripcion = '$descripcion' 
               $sql_imagen 
               WHERE id = $id";

    if ($db->query($update)) {
        // Redirigir a la lista correcta
        $pag = ($tipo == 'cd') ? 'cds.php' : 'vinilos.php';
        echo "<script>alert('Producto actualizado correctamente'); window.location.href='$pag';</script>";
    } else {
        $mensaje = "<div style='color:red'>Error al actualizar: " . $db->error . "</div>";
    }
}

// Includes visuales
$pageTitle = "Editar Producto";
$cssPath = "../css/styles.css"; 
include "../componentes/header.php"; 
include "../componentes/nav.php"; 
?>

<main class="main-content">
    <div class="page-header">
        <h1>Editar: <?= $prod->titulo ?></h1>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <?= $mensaje ?>

        <form action="" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            
            <div class="form-group">
                <label>Título:</label>
                <input type="text" name="titulo" value="<?= $prod->titulo ?>" required style="width: 100%; padding: 8px;">
            </div>

            <div class="form-group">
                <label>Artista:</label>
                <input type="text" name="artista" value="<?= $prod->artista ?>" required style="width: 100%; padding: 8px;">
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
                <textarea name="descripcion" rows="4" style="width: 100%; padding: 8px;"><?= $prod->descripcion ?></textarea>
            </div>

            <div class="form-group">
                <label>Imagen Actual:</label><br>
                <?php if($prod->imagen): ?>
                    <img src="../img/covers/<?= $prod->imagen ?>" style="width: 100px; margin-bottom: 10px;">
                <?php endif; ?>
                <br>
                <label>Cambiar Imagen (opcional):</label>
                <input type="file" name="imagen" accept="image/*">
            </div>

            <button type="submit" name="actualizar" class="button button-red" style="padding: 15px; cursor: pointer; background-color: #f39c12; color: white; border: none; font-weight: bold;">
                Actualizar Producto
            </button>
            
            <a href="cds.php" style="text-align: center; display: block; margin-top: 10px;">Cancelar</a>
        </form>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>