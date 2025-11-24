<div class="contact-container">
    <h2>Contáctanos</h2>
    <p style="text-align: center; margin-bottom: 2rem;">
        ¿Tienes preguntas? Rellena el formulario y nos pondremos en contacto contigo.
    </p>

    <!-- Mostrar mensaje de éxito -->
    <?php if (isset($success) && !empty($success)): ?>
        <div class="alert-success">
            <?php echo $success; ?>
        </div>
    <?php endif; ?>

    <form action="<?php echo isset($formAction)
        ? $formAction
        : "#"; ?>" method="POST">
        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input 
                type="text" 
                id="nombre" 
                name="nombre" 
                placeholder="Tu nombre completo" 
                value="<?php echo isset($oldNombre)
                    ? htmlspecialchars($oldNombre)
                    : ""; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="email">Correo Electrónico</label>
            <input 
                type="email" 
                id="email" 
                name="email" 
                placeholder="tu@email.com" 
                value="<?php echo isset($oldEmail)
                    ? htmlspecialchars($oldEmail)
                    : ""; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="asunto">Asunto</label>
            <input 
                type="text" 
                id="asunto" 
                name="asunto" 
                placeholder="Ej: Duda sobre un pedido" 
                value="<?php echo isset($oldAsunto)
                    ? htmlspecialchars($oldAsunto)
                    : ""; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label for="mensaje">Mensaje</label>
            <textarea 
                id="mensaje" 
                name="mensaje" 
                placeholder="Escribe tu mensaje aquí..." 
                required
            ><?php echo isset($oldMensaje)
                ? htmlspecialchars($oldMensaje)
                : ""; ?></textarea>
        </div>

        <button type="submit" class="btn btn-primary submit-btn">Enviar Mensaje</button>
    </form>
</div>