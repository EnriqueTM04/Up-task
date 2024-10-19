<div class="contenedor login">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Iniciar Sesión</p>

        <?php include_once __DIR__ . '/../templates/alertas.php';?>
        
        <form action="/" class="formulario" method="POST" autocomplete="off">
            <div class="campo">
                <label for="email">Correo</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu correo"
                    name="email"
                >
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu contraseña"
                    name="password"
                >
            </div>

            <input type="submit" class="boton" value="Iniciar Sesión">
        </form>

        <div class="acciones">
            <a href="/create-acount">¿Aún no tienes una cuenta? Registrate</a>
            <a href="/forgot">Olvidé mi contraseña</a>
        </div>

    </div> <!-- .contenedor-sm -->
</div>