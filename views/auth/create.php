<div class="contenedor create">
    
    <?php include_once __DIR__ . '/../templates/nombre-sitio.php'; ?>

    <div class="contenedor-sm">
        <p class="descripcion-pagina">Crea tu cuenta en UpTask</p>

        <?php include_once __DIR__ . '/../templates/alertas.php'; ?>
        
        <form action="/create-acount" class="formulario" method="POST" autocomplete="off">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input 
                    type="text"
                    id="nombre"
                    placeholder="Tu Nombre"
                    name="nombre"
                    value="<?php echo $usuario->nombre; ?>"
                >
            </div>

            <div class="campo">
                <label for="email">Correo</label>
                <input 
                    type="email"
                    id="email"
                    placeholder="Tu correo"
                    name="email"
                    value="<?php echo $usuario->email; ?>"
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

            <div class="campo">
                <label for="password2">Repite la Contraseña</label>
                <input 
                    type="password"
                    id="password2"
                    placeholder="Repite tu contraseña"
                    name="password2"
                >
            </div>

            <input type="submit" class="boton" value="Crear Cuenta">
        </form>

        <div class="acciones">
            <a href="/">¿Ya tienes cuenta? Inicia Sesión</a>
            <a href="/forgot">Olvidé mi contraseña</a>
        </div>

    </div> <!-- .contenedor-sm -->
</div>