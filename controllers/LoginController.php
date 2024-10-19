<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {

    public static function login(Router $router) {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario= new Usuario($_POST);

            $alertas =  $usuario->validarLogin();

            if(empty($alertas)) {
                $usuario = Usuario::where('email', $usuario->email);

                if(!$usuario || !$usuario->confirmado) {
                    Usuario::setAlerta('error', 'El usuario no existe o no esta activado');
                } else {
                    // EL usuario existe
                    if(password_verify($_POST['password'], $usuario->password)) {
                        session_start();
                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;
                        
                        // Redireccionar
                        header('Location: /dashboard');
                        
                    } else {
                        Usuario::setAlerta('error', 'Las credenciales no coinciden');
                    }
                }
            }
        }

        $alertas =  Usuario::getAlertas();

        $router->render('auth/login', [
            'titulo' => 'Iniciar Sesion',
            'alertas' => $alertas
        ]);
    }

    public static function logout() {
        session_start();

        $_SESSION = [];
        header('Location: /');
        
    }

    public static function create(Router $router) {

        $usuario = new Usuario;
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)) {
                $existeUsuario = Usuario::where('email', $usuario->email);

                if($existeUsuario) {
                    Usuario::setAlerta('error', 'Usuario ya registrado');
                } else {

                    // Hashear password
                    $usuario->hashPassword();

                    // Eliminar password2
                    unset($usuario->password2);

                    // Generar Token
                    $usuario->crearToken();

                    // Enviar email
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear Usuario
                    $resultado = $usuario->guardar();

                    if($resultado) {
                        header('Location: /message');
                    }
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/create', [
            'titulo' => 'Crear Cuenta',
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    public static function forgot(Router $router) {

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $usuario =  new Usuario($_POST);
            $alertas = $usuario->validarEmail();

            if(empty($alertas)) {
                // Buscar usuario
                $usuario = Usuario::where('email', $usuario->email);

                if($usuario && $usuario->confirmado === '1') {
                    // Generar nuevo Token
                    $usuario->crearToken();
                    unset($usuario->password2);

                    // Actualizar usuario
                    $usuario->guardar();

                    // Enviar correo
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarInstrucciones();

                    // Imprimir alerta
                    Usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu correo');

                } else {
                    // Usuario no encontrado
                    Usuario::setAlerta('error', 'EL usuario no existe o no esta confirmado');
                }
            }
        }

        $alertas =  Usuario::getAlertas();

        $router->render('auth/forgot', [
            'titulo' => 'Olvidé la contraseña',
            'alertas' => $alertas
        ]);
    }

    public static function reset(Router $router) {

        $token = s($_GET['token']);
        $mostrar =  true;

        if(!$token) header('Location: /');

        // Identificar usuario
        $usuario = Usuario::where('token', $token);
        
        if(empty($usuario)) {
            Usuario::setAlerta('error', 'Token no válido');
            $mostrar =  false;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Agragar password
            $usuario->sincronizar($_POST);

            // Validar password
            $alertas = $usuario->validarPassword();

            if(empty($alertas)) {
                // Hashear password
                $usuario->hashPassword();
                unset($usuario->password2);

                // Eliminar token
                $usuario->token = null;

                // Guardar en DB
                $resultado = $usuario->guardar();

                // Redireccionar
                if($resultado) {
                    header('Location: /');
                }
            }

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/reset', [
            'titulo' => 'UpTask | Recuperar contraseña',
            'alertas' => $alertas,
            'mostrar' => $mostrar
        ]);
    }

    public static function message(Router $router) {

        $router->render('auth/message', [
            'titulo' => 'Cuenta creada'
        ]);
    }

    public static function confirm(Router $router) {

        $token = s($_GET['token']);

        if(!$token) {
            header('Location: /');
        }

        // Encontrar al usuario
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)) {
            // Usuario no encontrado
            Usuario::setAlerta('error', 'Token no valido');
        } else {
            // Confirmar la cuenta
            $usuario->confirmado =  1;
            $usuario->token = null;
            unset($usuario->password2);
            
            // Guardar en DB
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta activada exitosamente, puedes Iniciar Sesión');

        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/confirm', [
            'titulo' => 'Activacion de cuenta',
            'alertas' => $alertas
        ]);
    }
}