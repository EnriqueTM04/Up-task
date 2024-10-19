<div class="contenedor reset">

    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Ingresa tu nueva contraseña</p>

        <?php 
        
        include_once __DIR__ . '/../templates/alertas.php';

        if($mostrar) { ?>

            <form class="formulario" method="POST" autocomplete="off">

            <div class="campo">
                <label for="password">Contraseña</label>
                <input 
                    type="password"
                    id="password"
                    placeholder="Tu contraseña"
                    name="password"
                >
            </div>

            <div class="campo">
                <label for="password2">Repetir Contraseña</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu contraseña"
                    name="password2"
                >
            </div>

            <input type="submit" class="boton" value="Guardar Contraseña">
            </form>

            <?php
        }
        
        ?>
        <div class="acciones">
            <a href="/create-acount">¿Aún no tienes una cuenta? Registrate</a>
            <a href="/forgot">Olvidé mi contraseña</a>
        </div>

    </div> <!-- .contenedor-sm -->
</div>
        
        