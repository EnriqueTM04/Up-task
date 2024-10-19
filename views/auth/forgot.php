<div class="contenedor forgot">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Recuperar Contraseña</p>

        <?php include_once __DIR__ . '/../templates/alertas.php';?>
        
        <form action="/forgot" class="formulario" method="POST" autocomplete="off">
            <div class="campo">
                <label for="email">Correo</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu correo"
                    name="email"
                >
            </div>

            <input type="submit" class="boton" value="Enviar Correo">
        </form>

        <div class="acciones">
            <a href="/create-acount">¿Aún no tienes una cuenta? Registrate</a>
            <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
        </div>

    </div> <!-- .contenedor-sm -->
</div>