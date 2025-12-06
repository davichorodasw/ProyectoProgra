<?php
session_start();

$pageTitle = "Carrito de Compras - Ritmo Retro";
$currentPage = "carrito";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$extraCss = "../css/carrito.css";

include "../componentes/header.php";
include "../componentes/nav.php";

if (isset($_SESSION['carrito_mensaje'])) {
    $mensaje = $_SESSION['carrito_mensaje'];
    unset($_SESSION['carrito_mensaje']);
}
?>

<main class="main-content">
    <div class="page-header">
        <h1>Tu Carrito de Compras</h1>
        <p>Revisa, modifica o finaliza tu compra</p>
    </div>

    <?php if (isset($_SESSION['carrito_mensaje_temp'])): ?>
        <div id="php-notification-toast"
            data-type="<?= htmlspecialchars($_SESSION['carrito_mensaje_temp']['tipo']) ?>"
            data-title="<?= htmlspecialchars($_SESSION['carrito_mensaje_temp']['titulo']) ?>"
            data-message="<?= htmlspecialchars($_SESSION['carrito_mensaje_temp']['mensaje']) ?>"
            style="display: none;">
        </div>
        <?php unset($_SESSION['carrito_mensaje_temp']); ?>
    <?php endif; ?>

    <?php if (empty($_SESSION['carrito'])): ?>
        <div class="carrito-vacio">
            <div class="empty-cart-icon">🛒</div>
            <h2>Tu carrito está vacío</h2>
            <p>¡Añade algunos productos para comenzar a comprar!</p>
            <div class="carrito-acciones">
                <a href="cds.php" class="button button-red">Ver CDs</a>
                <a href="vinilos.php" class="button button-blue">Ver Vinilos</a>
                <a href="../index.php" class="button button-outline">Volver al Inicio</a>
            </div>
        </div>
    <?php else: ?>
        <div class="carrito-container">
            <div class="carrito-items">
                <!-- Lista de productos en el carrito -->
                <?php
                $total = 0;
                $total_items = 0;

                foreach ($_SESSION['carrito'] as $index => $item):
                    $subtotal = $item['precio'] * $item['cantidad'];
                    $total += $subtotal;
                    $total_items += $item['cantidad'];
                ?>
                    <div class="carrito-item" data-item-id="<?= $item['id'] ?>">
                        <div class="item-imagen">
                            <img src="../img/covers/<?= $item['imagen'] ?>"
                                alt="<?= htmlspecialchars($item['titulo']) ?>"
                                onerror="this.src='../img/covers/default.png'">
                        </div>

                        <div class="item-info">
                            <h3><?= htmlspecialchars($item['titulo']) ?></h3>
                            <p class="item-artista"><?= htmlspecialchars($item['artista']) ?></p>
                            <div class="item-details">
                                <span class="item-formato"><?= $item['tipo'] == 'cd' ? 'CD' : 'Vinilo' ?></span>
                                <span class="item-codigo">ID: <?= $item['id'] ?></span>
                            </div>
                        </div>

                        <div class="item-cantidad">
                            <form action="../procesos/actualizar_carrito.php" method="POST" class="cantidad-form">
                                <input type="hidden" name="item_index" value="<?= $index ?>">
                                <button type="submit" name="accion" value="menos" class="cantidad-btn" <?= $item['cantidad'] <= 1 ? 'disabled' : '' ?>>-</button>
                                <input type="number" name="cantidad" value="<?= $item['cantidad'] ?>"
                                    min="1" max="99" class="cantidad-input" readonly>
                                <button type="submit" name="accion" value="mas" class="cantidad-btn">+</button>
                            </form>
                            <div class="stock-info">
                                <?php if ($item['stock'] > 0 && $item['cantidad'] > $item['stock']): ?>
                                    <span class="stock-warning">Solo <?= $item['stock'] ?> disponibles</span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="item-precio">
                            <span class="subtotal">$<?= number_format($subtotal, 2) ?></span>
                            <span class="precio-unitario">$<?= number_format($item['precio'], 2) ?> c/u</span>
                        </div>

                        <div class="item-eliminar">
                            <form action="../procesos/eliminar_carrito.php" method="POST" class="eliminar-form">
                                <input type="hidden" name="item_index" value="<?= $index ?>">
                                <button type="submit" class="eliminar-btn" title="Eliminar del carrito">
                                    <span class="eliminar-icon">✕</span>
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="carrito-resumen">
                <div class="resumen-contenido">
                    <h3>Resumen del Pedido</h3>

                    <div class="resumen-detalle">
                        <div class="resumen-fila">
                            <span>Productos (<?= $total_items ?> items):</span>
                            <span>$<?= number_format($total, 2) ?></span>
                        </div>
                        <div class="resumen-fila">
                            <span>Envío:</span>
                            <span class="<?= $total >= 50 ? 'envio-gratis' : '' ?>">
                                <?= $total >= 50 ? 'GRATIS' : '$5.00' ?>
                            </span>
                        </div>

                        <?php if ($total >= 50): ?>
                            <div class="resumen-fila envio-gratis-nota">
                                <span>¡Envío gratis por compras mayores a $50!</span>
                            </div>
                        <?php endif; ?>

                        <div class="resumen-fila total">
                            <span>Total a pagar:</span>
                            <span class="total-pagar">$<?= number_format($total + ($total >= 50 ? 0 : 5.00), 2) ?></span>
                        </div>
                    </div>

                    <div class="resumen-acciones">
                        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']): ?>
                            <a href="checkout.php" class="button button-red btn-comprar">
                                <span class="btn-icon">→</span>
                                Proceder al Pago
                            </a>
                        <?php else: ?>
                            <a href="login.php?redirect=carrito" class="button button-red btn-comprar">
                                <span class="btn-icon">🔒</span>
                                Iniciar Sesión para Comprar
                            </a>
                        <?php endif; ?>

                        <a href="cds.php" class="button button-outline">
                            <span class="btn-icon">+</span>
                            Seguir Comprando
                        </a>

                        <form action="../procesos/vaciar_carrito.php" method="POST" class="vaciar-form">
                            <button type="submit" class="button button-link">
                                <span class="btn-icon">🗑️</span>
                                Vaciar Carrito
                            </button>
                        </form>
                    </div>

                    <div class="resumen-seguridad">
                        <div class="seguridad-item">
                            <span class="seguridad-icon">🔒</span>
                            <span>Compra 100% segura</span>
                        </div>
                        <div class="seguridad-item">
                            <span class="seguridad-icon">✓</span>
                            <span>Garantía de calidad</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</main>

<link rel="stylesheet" href="../css/notification.css">
<script src="../js/notification.js"></script>
<script src="../js/carrito.js"></script>

<?php include "../componentes/footer.php"; ?>