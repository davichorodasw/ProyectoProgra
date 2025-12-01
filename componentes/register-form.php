<div class="auth-container">
    <h2>Crear Cuenta</h2>
    <p style="text-align: center; margin-bottom: 2rem; color: #666;">
        Únete a Ritmo Retro y descubre el sonido auténtico
    </p>

    <!-- Mostrar errores -->
    <?php if (!empty($errors)): ?>
        <div class="alert-danger">
            <strong>¡Ups! Algo salió mal.</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Mostrar mensajes de éxito -->
    <?php if (isset($success) && !empty($success)): ?>
        <div class="alert-success" style="margin-bottom: 1.5rem;">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo $formAction; ?>">
        <!-- Nombres -->
        <div class="form-group">
            <label for="name">Nombre Completo</label>
            <input
                id="name"
                type="text"
                name="name"
                value="<?php echo htmlspecialchars($oldName); ?>"
                placeholder="Tu nombre completo"
                required
                autofocus>
        </div>

        <!-- Email -->
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input
                id="email"
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($oldEmail); ?>"
                placeholder="tu@email.com"
                required>
        </div>

        <!-- Teléfono (Opcional) -->
        <div class="form-group">
            <label for="phone">Teléfono <small>(Opcional)</small></label>
            <input
                id="phone"
                type="tel"
                name="phone"
                value="<?php echo htmlspecialchars($oldPhone); ?>"
                placeholder="+593 98 765 4321">
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input
                id="password"
                type="password"
                name="password"
                placeholder="Mínimo 8 caracteres"
                required>
            <small style="color: #666; margin-top: 0.25rem; display: block;">
                La contraseña debe tener al menos 8 caracteres
            </small>
        </div>

        <!-- Confirmar Contraseña -->
        <div class="form-group">
            <label for="password_confirmation">Confirmar Contraseña</label>
            <input
                id="password_confirmation"
                type="password"
                name="password_confirmation"
                placeholder="Repite tu contraseña"
                required>
        </div>

        <!-- Términos y Condiciones -->
        <div class="form-group" style="margin-bottom: 1.5rem;">
            <label for="terms" style="display: flex; align-items: flex-start; gap: 0.5rem; font-weight: normal; cursor: pointer;">
                <input
                    id="terms"
                    type="checkbox"
                    name="terms"
                    style="width: auto; margin-top: 0.25rem;"
                    required>
                <span>
                    Acepto los <a href="<?php echo $termsLink; ?>" class="auth-link">términos y condiciones</a>
                    y la <a href="<?php echo $privacyLink; ?>" class="auth-link">política de privacidad</a>
                </span>
            </label>
        </div>

        <!-- Botón de Registro -->
        <button type="submit" class="btn btn-primary submit-btn">
            Crear Cuenta
        </button>

        <div class="auth-footer-link">
            <a href="<?php echo $loginLink; ?>" class="auth-link">
                ¿Ya tienes cuenta? Inicia sesión
            </a>
        </div>
    </form>
</div>