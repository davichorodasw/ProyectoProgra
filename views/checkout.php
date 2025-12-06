<?php
session_start();

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header("Location: login.php?redirect=checkout");
    exit();
}

if (empty($_SESSION['carrito'])) {
    $_SESSION['carrito_mensaje_temp'] = [
        'tipo' => 'warning',
        'titulo' => 'Carrito vacío',
        'mensaje' => 'Agrega productos al carrito antes de proceder al pago'
    ];
    header("Location: carrito.php");
    exit();
}

$pageTitle = "Checkout - Ritmo Retro";
$currentPage = "checkout";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";
$extraCss = "../css/checkout.css";

include "../componentes/header.php";
include "../componentes/nav.php";

$subtotal = 0;
$total_items = 0;

foreach ($_SESSION['carrito'] as $item) {
    $subtotal += $item['precio'] * $item['cantidad'];
    $total_items += $item['cantidad'];
}

$envio = $subtotal >= 50 ? 0 : 5.00;
$total = $subtotal + $envio;
?>

<main class="main-content checkout-page">
    <div class="page-header">
        <h1>Finalizar Compra</h1>
        <p>Completa tus datos para procesar el pedido</p>
    </div>

    <?php if (isset($_SESSION['checkout_error'])): ?>
        <div class="alert alert-error">
            <?= htmlspecialchars($_SESSION['checkout_error']) ?>
        </div>
        <?php unset($_SESSION['checkout_error']); ?>
    <?php endif; ?>

    <div class="checkout-container">
        <div class="checkout-steps">
            <div class="step active">
                <span class="step-number">1</span>
                <span class="step-text">Datos de Envío</span>
            </div>
            <div class="step">
                <span class="step-number">2</span>
                <span class="step-text">Método de Pago</span>
            </div>
            <div class="step">
                <span class="step-number">3</span>
                <span class="step-text">Confirmación</span>
            </div>
        </div>

        <div class="checkout-content">
            <div class="checkout-form-section">
                <h2>Información de Envío</h2>
                <form action="../procesos/procesar_pago.php" method="POST" id="checkout-form">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="nombre">Nombre completo *</label>
                            <input type="text" id="nombre" name="nombre" required
                                value="<?= isset($_SESSION['user_name']) ? htmlspecialchars($_SESSION['user_name']) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="email">Email *</label>
                            <input type="email" id="email" name="email" required
                                value="<?= isset($_SESSION['user_email']) ? htmlspecialchars($_SESSION['user_email']) : '' ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="direccion">Dirección *</label>
                            <input type="text" id="direccion" name="direccion" required
                                placeholder="Calle, número, departamento">
                        </div>
                        <div class="form-group">
                            <label for="ciudad">Ciudad *</label>
                            <input type="text" id="ciudad" name="ciudad" required>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="codigo_postal">Código Postal *</label>
                            <input type="text" id="codigo_postal" name="codigo_postal" required>
                        </div>
                        <div class="form-group">
                            <label for="telefono">Teléfono *</label>
                            <input type="tel" id="telefono" name="telefono" required
                                value="<?= isset($_SESSION['user_phone']) ? htmlspecialchars($_SESSION['user_phone']) : '' ?>">
                        </div>
                    </div>

                    <h2>Método de Pago</h2>
                    <div class="payment-methods">
                        <div class="payment-method">
                            <input type="radio" id="pago-tarjeta" name="metodo_pago" value="tarjeta" checked required>
                            <label for="pago-tarjeta">
                                <span class="payment-icon">💳</span>
                                <span class="payment-text">Tarjeta de Crédito/Débito</span>
                            </label>
                        </div>
                    </div>

                    <!-- Simulación de datos de tarjeta -->
                    <div id="tarjeta-details" class="payment-details">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="numero_tarjeta">Número de Tarjeta *</label>
                                <input type="text" id="numero_tarjeta" name="numero_tarjeta"
                                    placeholder="1234 5678 9012 3456" maxlength="19">
                            </div>
                            <div class="form-group">
                                <label for="nombre_tarjeta">Nombre en la Tarjeta *</label>
                                <input type="text" id="nombre_tarjeta" name="nombre_tarjeta"
                                    placeholder="JUAN PEREZ">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="vencimiento">Vencimiento (MM/AA) *</label>
                                <input type="text" id="vencimiento" name="vencimiento"
                                    placeholder="12/25" maxlength="5">
                            </div>
                            <div class="form-group">
                                <label for="cvv">CVV *</label>
                                <input type="text" id="cvv" name="cvv"
                                    placeholder="123" maxlength="3">
                            </div>
                        </div>
                    </div>

                    <div class="checkout-actions">
                        <a href="carrito.php" class="button button-outline">← Volver al Carrito</a>
                        <button type="submit" class="button button-red btn-pagar">
                            <span class="btn-icon">💳</span>
                            Proceder al Pago
                        </button>
                    </div>
                </form>
            </div>

            <div class="checkout-summary">
                <div class="summary-content">
                    <h3>Resumen del Pedido</h3>

                    <div class="summary-items">
                        <?php foreach ($_SESSION['carrito'] as $item): ?>
                            <div class="summary-item">
                                <div class="item-info">
                                    <h4><?= htmlspecialchars($item['titulo']) ?></h4>
                                    <p class="item-artista"><?= htmlspecialchars($item['artista']) ?></p>
                                    <p class="item-quantity">Cantidad: <?= $item['cantidad'] ?></p>
                                </div>
                                <div class="item-price">
                                    $<?= number_format($item['precio'] * $item['cantidad'], 2) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="summary-totals">
                        <div class="total-row">
                            <span>Subtotal (<?= $total_items ?> items):</span>
                            <span>$<?= number_format($subtotal, 2) ?></span>
                        </div>
                        <div class="total-row">
                            <span>Envío:</span>
                            <span class="<?= $envio == 0 ? 'envio-gratis' : '' ?>">
                                <?= $envio == 0 ? 'GRATIS' : '$' . number_format($envio, 2) ?>
                            </span>
                        </div>
                        <div class="total-row total-final">
                            <span>Total a pagar:</span>
                            <span class="total-amount">$<?= number_format($total, 2) ?></span>
                        </div>
                    </div>

                    <div class="summary-security">
                        <div class="security-item">
                            <span class="security-icon">🔒</span>
                            <span>Pago 100% seguro</span>
                        </div>
                        <div class="security-item">
                            <span class="security-icon">✓</span>
                            <span>Garantía de devolución</span>
                        </div>
                        <div class="security-item">
                            <span class="security-icon">📦</span>
                            <span>Envío en 24-48h</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<link rel="stylesheet" href="../css/checkout.css">
<script src="../js/checkout.js"></script>

<?php include "../componentes/footer.php"; ?>