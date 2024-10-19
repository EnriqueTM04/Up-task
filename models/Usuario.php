<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;

    public $password_actual;
    public $password_nuevo;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->password2 = $args['password2'] ?? '';
        $this->token = $args['token'] ?? '';
        $this->confirmado = $args['confirmado'] ?? 0;
    }

    public function validarNuevaCuenta(): array {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(strlen($this->nombre) > 50 ) {
            self::$alertas['error'][] = 'Nombre no valido';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        }

        if(strlen($this->nombre) > 150 ) {
            self::$alertas['error'][] = 'Correo no valido';
        }

        $this->validarPassword();

        return self::$alertas;
    }

    public function validarPassword(): array {
        // Validar passwords
        if(strlen($this->password) > 60  || strlen($this->password) < 6) {
            self::$alertas['error'][] = 'Contraseña debe contener entre 6 y 60 carácteres';
        } else if(strcmp($this->password, $this->password2) !== 0) {
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }

    public function nuevo_password(): array {
        // Validar passwords
        if(strlen($this->password_actual) > 60  || strlen($this->password_actual) < 6) {
            self::$alertas['error'][] = 'La Contraseña debe contener entre 6 y 60 carácteres';
        } else if(strlen($this->password_nuevo) > 60  || strlen($this->password_nuevo) < 6) {
            self::$alertas['error'][] = 'Las contraseñas no coinciden';
        }

        return self::$alertas;
    }

    public function validarLogin(): array {

        if(!$this->email) {
            self::$alertas['error'][] = 'El Correo es Obligatorio';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Correo ingresado no es válido';
        } else if(!$this->password) {
            self::$alertas['error'][] = 'El correo o la contraseña no coinciden';
        }

        return self::$alertas;
    }

    // Comprobar el password
    public function comprobarPassword(): bool {
        return password_verify($this->password_actual, $this->password);
    }

    // Hashear Password
    public function hashPassword(): void {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    public function crearToken(): void {
        $this->token = substr(md5(uniqid()), 0, 15);
    }

    public function validarEmail(): array {
        if(!$this->email) {
            self::$alertas['error'][] = 'El Correo es Obligatorio';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Correo ingresado no es válido';
        }

        return self::$alertas;
    }

    // Validar perfil
    public function validarPerfil(): array {
        if(!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if(strlen($this->nombre) > 50 ) {
            self::$alertas['error'][] = 'Nombre no valido';
        }

        if(!$this->email) {
            self::$alertas['error'][] = 'El correo es obligatorio';
        } else if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            self::$alertas['error'][] = 'El Correo ingresado no es válido';
        }

        if(strlen($this->nombre) > 150 ) {
            self::$alertas['error'][] = 'Correo no valido';
        }

        return self::$alertas;
    }
    
}

?>