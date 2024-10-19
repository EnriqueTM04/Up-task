<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController {

    public static function index(Router $router) {

        session_start();
        isAuth();

        $id = $_SESSION['id'];

        $proyectos = Proyecto::belongsTo('propietarioId', $id);

        $router->render('dashboard/index', [
            'titulo' => 'Proyectos',
            'proyectos' => $proyectos
        ]);
    }

    public static function create_proyect(Router $router) {

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $proyecto = new Proyecto($_POST);

            // Validacion
            $alertas = $proyecto->validarProyecto();

            if(empty($alertas)) {
                // Generar url unica
                $hash = md5(uniqid());
                $proyecto->url = $hash;

                // Almacenar creador del proyetco
                $proyecto->propietarioId = $_SESSION['id'];

                // Guardar proyecto
                $resultado = $proyecto->guardar();

                if($resultado) {
                    header('Location: /proyecto?id=' . $proyecto->url);
                }

            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('dashboard/create-proyect', [
            'titulo' => 'Crear Proyecto',
            'alertas' => $alertas
        ]);
    }

    public static function perfil(Router $router) {

        session_start();
        isAuth();

        $usuario = Usuario::find($_SESSION['id']);
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPerfil();

            if(empty($alertas)) {

                // Verificar que el correo no este registrado
                $existeUsuario = Usuario::where('email', $usuario->email);
                
                
                if($existeUsuario && $existeUsuario->id !==  $usuario->id) {
                    // Mensaje error
                    Usuario::setAlerta('error', 'Correo no válido, ya pertenece a otra persona');
                } else {
                    // Guardar registro
                    $usuario->guardar();
                    Usuario::setAlerta('exito', 'Datos Guardados Correctamente');

                    $_SESSION['nombre'] = $usuario->nombre;
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('dashboard/perfil', [
            'titulo' => 'Perfil',
            'nombre' => $usuario->nombre,
            'email' => $usuario->email,
            'alertas' => $alertas
        ]);
    }

    public static function proyecto(Router $router) {

        session_start();
        isAuth();

        $token = $_GET['id'];
        if(!$token) header('Location: /dashboard');

        // Revisar que la persona que visista el proyecto es quien lo creo
        $proyecto =  Proyecto::where('url', $token);
        
        if($proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $router->render('dashboard/proyecto', [
            'titulo' => $proyecto->proyecto
        ]);
    }

    // cambiar password
    public static function cambiar_password(Router $router) {

        session_start();
        isAuth();

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario = Usuario::find($_SESSION['id']);

            // SINCRONIZAR CON LO QUE EL USUARIO ENVIO
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)) {
                $resultado = $usuario->comprobarPassword();

                if($resultado) {
                    // Asignar nuevo password
                    $usuario->password = $usuario->password_nuevo;
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        Usuario::setAlerta('exito', 'Contraseña cambiada correctamente');
                    } else {
                        Usuario::setAlerta('error', 'Contraseña no guardada, intentelo mas tarde');
                    }
                } else {
                    Usuario::setAlerta('error', 'Contraseña incorrecta');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('dashboard/cambiar-password', [
            'titulo' => 'Cambiar Contraseña',
            'alertas' => $alertas
        ]);
    }
}

?>