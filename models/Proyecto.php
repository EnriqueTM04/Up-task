<?php

namespace Model;

class Proyecto extends ActiveRecord {
    
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id', 'proyecto', 'url', 'propietarioId'];

    public $proyecto;
    public $url;
    public $propietarioId;

    public function __construct($args = []) {
        
        $this->id = $args['id'] ?? null;
        $this->proyecto = $args['proyecto'] ?? '';
        $this->url = $args['url'] ?? '';
        $this->propietarioId = $args['propietarioId'] ?? '';
    }

    public function validarProyecto() {
        if(!$this->proyecto) {
            self::$alertas['error'][] = 'El nombre del proyecto es obligatorio';
        } else if(strlen($this->proyecto) > 60) {
            self::$alertas['error'][] = 'El nombre es demasiado largo';
        }
        return self::$alertas;
    }
}

?>