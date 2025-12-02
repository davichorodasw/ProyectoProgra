<?php
session_start();

// 1. SEGURIDAD: Verificar si es admin
if (!isset($_SESSION['identity']) || $_SESSION['identity']->rol != 'admin') {
    header("Location: ../index.php"); // O la ruta a tu home
    exit();
}

// Configuración básica de la página
$pageTitle = "Crear Producto - Ritmo Retro";
$cssPath = "../css/styles.css"; 
// Ajusta las rutas de include según dónde tengas tus componentes
include "../componentes/header.php"; 
include "../componentes/nav.php"; 

// 2. LÓGICA: Procesar el formulario al enviar
$mensaje = "";

if (isset($_POST['guardar'])) {
    // A. Conexión a Base de Datos (Ajusta tus credenciales)
    $db = new mysqli('localhost', 'root', '', 'ritmoretro'); // ¡Verifica usuario/pass!
    
    if ($db->connect_error) {
        die("Error de conexión: " . $db->connect_error);
    }
    $db->set_charset("utf8");

    // B. Recoger datos
    $titulo      = $db->real_escape_string($_POST['titulo']);
    $artista     = $db->real_escape_string($_POST['artista']);
    $tipo        = $db->real_escape_string($_POST['tipo']);
    $precio      = floatval($_POST['precio']);
    $stock       = intval($_POST['stock']);
    $descripcion = $db->real_escape_string($_POST['descripcion']);
    $imagen      = 'default.jpg'; // Imagen por defecto

    // C. Subir Imagen
    if (isset($_FILES['imagen']) && !empty($_FILES['imagen']['name'])) {
        $file = $_FILES['imagen'];
        $filename = time() . "_" . $file['name']; // Nombre único
        $mimetype = $file['type'];

        if ($mimetype == "image/jpg" || $mimetype == 'image/jpeg' || $mimetype == 'image/png') {
            // Asegúrate que esta carpeta exista:
            $ruta_destino = "../img/covers/"; 
            
            if (!is_dir($ruta_destino)) {
                mkdir($ruta_destino, 0777, true);
            }
            
            if (move_uploaded_file($file['tmp_name'], $ruta_destino . $filename)) {
                $imagen = $filename;
            }
        }
    }

    // D. Insertar en Base de Datos (Tabla 'productos' según ritmoretro.sql)
    $sql = "INSERT INTO productos (tipo, titulo, artista, precio, imagen, descripcion, stock) 
            VALUES ('$tipo', '$titulo', '$artista', $precio, '$imagen', '$descripcion', $stock)";

    if ($db->query($sql)) {
        // Redirigir al listado correspondiente
        $pag = ($tipo == 'cd') ? 'cds.php' : 'vinilos.php';
        echo "<script>alert('Producto creado con éxito'); window.location.href='$pag';</script>";
    } else {
        $mensaje = "<div style='color:red'>Error al guardar: " . $db->error . "</div>";
    }
    
    $db->close();
}
?>

<main class="main-content">
    <div class="page-header">
        <h1>Añadir Nuevo Producto al Catálogo</h1>
    </div>

    <div class="form-container" style="max-width: 600px; margin: 0 auto; padding: 20px;">
        
        <?= $mensaje ?>

        <form action="" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 15px;">
            
            <div class="form-group">
                <label for="titulo"><strong>Título del Álbum:</strong></label>
                <input type="text" name="titulo" required style="width: 100%; padding: 8px;" placeholder="Ej. Dark Side of the Moon">
            </div>

            <div class="form-group">
                <label for="artista"><strong>Artista / Banda:</strong></label>
                <input type="text" name="artista" required style="width: 100%; padding: 8px;">
            </div>

            <div class="form-group">
                <label for="tipo"><strong>Formato:</strong></label>
                <select name="tipo" style="width: 100%; padding: 8px;">
                    <option value="vinilo">Vinilo</option>
                </select>
            </div>

            <div style="display: flex; gap: 20px;">
                <div class="form-group" style="flex: 1;">
                    <label for="precio"><strong>Precio ($):</strong></label>
                    <input type="number" name="precio" step="0.01" required style="width: 100%; padding: 8px;">
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="stock"><strong>Stock:</strong></label>
                    <input type="number" name="stock" required style="width: 100%; padding: 8px;">
                </div>
            </div>

            <div class="form-group">
                <label for="descripcion"><strong>Descripción:</strong></label>
                <textarea name="descripcion" rows="4" style="width: 100%; padding: 8px;"></textarea>
            </div>

            <div class="form-group">
                <label for="imagen"><strong>Imagen de Portada:</strong></label>
                <input type="file" name="imagen" accept="image/*">
            </div>

            <button type="submit" name="guardar" class="button button-red" style="padding: 15px; cursor: pointer; background-color: #e74c3c; color: white; border: none; font-weight: bold;">
                Guardar Producto
            </button>
            
            <a href="cds.php" style="text-align: center; display: block; margin-top: 10px;">Cancelar</a>
        </form>
    </div>
</main>

<?php include "../componentes/footer.php"; ?>