<?php

namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

class TareaController {

    public static function index() {

        session_start();
        $proyectoId = $_GET['id'];

        if(!$proyectoId) {
            header('Location: /dashboard');
        }

        $proyecto = Proyecto::where('url', $proyectoId);

        if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
            header('Location: /dashboard');
        }

        $tareas = Tarea::belongsTo('proyectoId', $proyecto->id);

        echo json_encode(['tareas' => $tareas]);
    }

    public static function crear() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];

            } else {
                // Instaciar y crear la tarea
                $tarea = new Tarea($_POST);
                $tarea->proyectoId = $proyecto->id;
                $resultado = $tarea->guardar();

                if($resultado) {
                    $respuesta = [
                        'tipo' => 'exito',
                        'mensaje' => 'Tarea agregada exitosamente',
                        'id' => $resultado['id'],
                        'proyectoId' => $proyecto->id
                    ];
                } else {
                    $respuesta = [
                        'tipo' => 'error',
                        'mensaje' => 'Hubo un Error al agregar la tarea'
                    ];
                }

            }

            echo json_encode($respuesta);
        }
    }

    public static function actualizar() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            // Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];

                echo json_encode($respuesta);
                return;

            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;

            $resultado = $tarea->guardar();

            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'id' => $tarea->id,
                    'mensaje' => 'Tarea actualizada correctamenete',
                    'proyectoId' => $proyecto->id
                ];

            }  else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
            }
            echo json_encode(['respuesta' =>$respuesta]);
        }
    }

    public static function eliminar() {

        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            session_start();

            // Validar que el proyecto exista
            $proyecto = Proyecto::where('url', $_POST['proyectoId']);

            if(!$proyecto || $proyecto->propietarioId !== $_SESSION['id']) {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al actualizar la tarea'
                ];

                echo json_encode($respuesta);
                return;

            }
            $tarea = new Tarea($_POST);
            $resultado = $tarea->eliminar();

            if($resultado) {
                $respuesta = [
                    'tipo' => 'exito',
                    'mensaje' => 'Tarea eliminada correctamenete',
                ];

            } else {
                $respuesta = [
                    'tipo' => 'error',
                    'mensaje' => 'Hubo un Error al agregar la tarea'
                ];
            }
            echo json_encode(['respuesta' =>$respuesta]);            
        }
    }
}

?>