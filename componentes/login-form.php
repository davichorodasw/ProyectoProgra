<div class="auth-container">
    <h2>Iniciar Sesión</h2>

    <!-- Mostrar errores (simulado para PHP simple) -->
    <?php if (isset($errors) && !empty($errors)): ?>
        <div class="alert-danger">
            <strong>¡Ups! Algo salió mal.</strong>
            <ul>
                <?php foreach ($errors as $error): ?>
                    <li><?php echo $error; ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Mostrar mensajes de sesión -->
    <?php if (isset($status) && !empty($status)): ?>
        <div class="alert-success" style="margin-bottom: 1.5rem;">
            <?php echo $status; ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo isset($formAction)
                                    ? $formAction
                                    : "../php/conexion.php"; ?>">
        <!-- Email -->
        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input
                id="email"
                type="email"
                name="email"
                value="<?php echo isset($oldEmail)
                            ? htmlspecialchars($oldEmail)
                            : ""; ?>"
                required
                autofocus
                placeholder="tu@email.com">
        </div>

        <!-- Contraseña -->
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input
                id="password"
                type="password"
                name="password"
                required
                placeholder="Tu contraseña">
        </div>

        <!-- Recordarme y Olvidé mi contraseña -->
        <div class="auth-options">
            <!-- <label for="remember_me">
                <input id="remember_me" type="checkbox" name="remember">
                <span>Recordarme</span>
            </label> -->

            <a class="auth-link" href="<?php echo isset($forgotPasswordLink)
                                            ? $forgotPasswordLink
                                            : "#olvidado"; ?>">
                ¿Olvidaste tu contraseña?
            </a>
        </div>

        <!-- Botón de Login -->
        <button type="submit" class="btn btn-primary submit-btn" style="margin-top: 2rem;">
            Entrar
        </button>

        <div class="auth-footer-link">
            <a href="<?php echo isset($registerLink)
                            ? $registerLink
                            : "#registro"; ?>" class="auth-link">
                ¿No tienes cuenta? Regístrate
            </a>
        </div>
    </form>
</div>