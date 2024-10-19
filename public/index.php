<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaController;
use MVC\Router;


$router = new Router();

// Login
$router->get('/', [LoginController::class, 'login']);
$router->post('/', [LoginController::class, 'login']);
$router->get('/logout', [LoginController::class, 'logout']);

// Crear Cuenta
$router->get('/create-acount', [LoginController::class, 'create']);
$router->post('/create-acount', [LoginController::class, 'create']);

// Olvidar password
$router->get('/forgot', [LoginController::class, 'forgot']);
$router->post('/forgot', [LoginController::class, 'forgot']);

// Colocar nuevo password
$router->get('/reset', [LoginController::class, 'reset']);
$router->post('/reset', [LoginController::class, 'reset']);

// confirmacion de cuenta
$router->get('/message', [LoginController::class, 'message']);
$router->get('/confirm', [LoginController::class, 'confirm']);

// Zona de proyectos
$router->get('/dashboard', [DashboardController::class, 'index']);
$router->get('/create-proyect', [DashboardController::class, 'create_proyect']);
$router->post('/create-proyect', [DashboardController::class, 'create_proyect']);
$router->get('/perfil', [DashboardController::class, 'perfil']);
$router->post('/perfil', [DashboardController::class, 'perfil']);
$router->get('/proyecto', [DashboardController::class, 'proyecto']);
$router->get('/cambiar-password', [DashboardController::class, 'cambiar_password']);
$router->post('/cambiar-password', [DashboardController::class, 'cambiar_password']);

// API para las tareas
$router->get('/api/tareas', [TareaController::class, 'index']);
$router->post('/api/tarea', [TareaController::class, 'crear']);
$router->post('/api/tarea/actualizar', [TareaController::class, 'actualizar']);
$router->post('/api/tarea/eliminar', [TareaController::class, 'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();