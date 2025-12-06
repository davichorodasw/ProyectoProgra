<?php
session_start();

if (!isset($_SESSION['ultimo_pedido'])) {
    header("Location: carrito.php");
    exit();
}

$pedido = $_SESSION['ultimo_pedido'];
unset($_SESSION['ultimo_pedido']);

$pageTitle = "Pedido Confirmado - Ritmo Retro";
$currentPage = "carrito";
$cssPath = "../css/styles.css";
$imgPath = "../img/RitmoRetro.png";
$basePath = "../";

include "../componentes/header.php";
include "../componentes/nav.php";
?>

<main class="main-content confirmacion-page">
    <div class="page-header">
        <h1>¡Pedido Confirmado!</h1>
        <p>Tu compra ha sido procesada exitosamente</p>
    </div>

    <div class="confirmacion-container">
        <div class="confirmacion-mensaje">
            <div class="success-icon">✓</div>
            <h2>¡Gracias por tu compra!</h2>
            <p>Tu pedido ha sido recibido y está siendo procesado.</p>
            <p>Te hemos enviado un correo de confirmación a <strong><?= htmlspecialchars($_SESSION['user_email'] ?? '') ?></strong></p>
        </div>

        <div class="confirmacion-detalles">
            <div class="detalle-card">
                <h3>Detalles del Pedido</h3>
                <div class="detalle-info">
                    <div class="info-row">
                        <span class="info-label">Número de Pedido:</span>
                        <span class="info-value">#<?= str_pad($pedido['id'], 6, '0', STR_PAD_LEFT) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Fecha:</span>
                        <span class="info-value"><?= $pedido['fecha'] ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Total:</span>
                        <span class="info-value total-amount">$<?= number_format($pedido['total'], 2) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Método de Pago:</span>
                        <span class="info-value"><?= ucfirst($pedido['metodo_pago']) ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Dirección de Envío:</span>
                        <span class="info-value"><?= htmlspecialchars($pedido['direccion']) ?>, <?= htmlspecialchars($pedido['ciudad']) ?></span>
                    </div>
                </div>
            </div>

            <div class="detalle-card">
                <h3>Próximos Pasos</h3>
                <div class="pasos-container">
                    <div class="paso">
                        <span class="paso-icon">📧</span>
                        <div class="paso-content">
                            <h4>Confirmación por Email</h4>
                            <p>Recibirás un email con los detalles de tu pedido en los próximos minutos.</p>
                        </div>
                    </div>
                    <div class="paso">
                        <span class="paso-icon">📦</span>
                        <div class="paso-content">
                            <h4>Procesamiento y Envío</h4>
                            <p>Tu pedido será preparado y enviado en 24-48 horas hábiles.</p>
                        </div>
                    </div>
                    <div class="paso">
                        <span class="paso-icon">🚚</span>
                        <div class="paso-content">
                            <h4>Seguimiento</h4>
                            <p>Recibirás un número de seguimiento cuando tu pedido sea despachado.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="confirmacion-acciones">
            <a href="cds.php" class="button button-red">
                <span class="btn-icon">🛒</span>
                Seguir Comprando
            </a>
            <a href="mi-cuenta.php" class="button button-outline">
                <span class="btn-icon">👤</span>
                Ver Mis Pedidos
            </a>
            <a href="../index.php" class="button button-link">
                <span class="btn-icon">🏠</span>
                Volver al Inicio
            </a>
        </div>

        <div class="confirmacion-seguridad">
            <p class="seguridad-text">
                <span class="seguridad-icon">🔒</span>
                Tu pago fue procesado de forma segura. Para cualquier consulta, contáctanos a
                <a href="mailto:soporte@ritmoretro.com">soporte@ritmoretro.com</a>
            </p>
        </div>
    </div>
</main>

<style>
    .confirmacion-page {
        padding: 40px 0;
        background-color: #f9f9f9;
        min-height: calc(100vh - 200px);
    }

    .confirmacion-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 0 20px;
    }

    .confirmacion-mensaje {
        text-align: center;
        background: white;
        padding: 40px;
        border-radius: 10px;
        margin-bottom: 30px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .success-icon {
        font-size: 4rem;
        color: #27ae60;
        margin-bottom: 20px;
    }

    .confirmacion-mensaje h2 {
        color: #27ae60;
        margin-bottom: 15px;
    }

    .confirmacion-detalles {
        display: grid;
        gap: 20px;
        margin-bottom: 30px;
    }

    .detalle-card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .detalle-card h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #333;
        padding-bottom: 10px;
        border-bottom: 2px solid #eee;
    }

    .info-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f5f5f5;
    }

    .info-row:last-child {
        border-bottom: none;
        margin-bottom: 0;
    }

    .info-label {
        color: #666;
        font-weight: 500;
    }

    .info-value {
        color: #333;
        font-weight: 500;
    }

    .total-amount {
        color: #e74c3c;
        font-size: 1.2rem;
        font-weight: bold;
    }

    .pasos-container {
        display: grid;
        gap: 20px;
    }

    .paso {
        display: flex;
        gap: 15px;
        align-items: flex-start;
    }

    .paso-icon {
        font-size: 1.5rem;
        color: #3498db;
        flex-shrink: 0;
    }

    .paso-content h4 {
        margin: 0 0 5px 0;
        color: #333;
    }

    .paso-content p {
        margin: 0;
        color: #666;
        font-size: 0.95rem;
    }

    .confirmacion-acciones {
        display: flex;
        gap: 15px;
        justify-content: center;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .confirmacion-seguridad {
        text-align: center;
        padding: 20px;
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .seguridad-text {
        margin: 0;
        color: #666;
    }

    .seguridad-icon {
        color: #27ae60;
        margin-right: 5px;
    }

    .seguridad-text a {
        color: #e74c3c;
        text-decoration: none;
    }

    .seguridad-text a:hover {
        text-decoration: underline;
    }

    @media (max-width: 768px) {
        .confirmacion-mensaje {
            padding: 30px 20px;
        }

        .info-row {
            flex-direction: column;
            gap: 5px;
        }

        .confirmacion-acciones {
            flex-direction: column;
            align-items: center;
        }

        .confirmacion-acciones .button {
            width: 100%;
            max-width: 300px;
        }
    }
</style>

<?php include "../componentes/footer.php"; ?>