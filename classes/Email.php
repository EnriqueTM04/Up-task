<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

class Email {
    
    protected $email;
    protected $nombre;
    protected $token;

    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'be66f4d0e1c187';
        $mail->Password = '0a76973e56ed81';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Confirma tu Cuenta';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p> Hola <strong>' . $this->nombre . '</strong> has creado tu cuenta en UpTask, da clic en el siguiente enlace para activarla</p>';
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/confirm?token=" . $this->token . "'>Confirmar Cuenta</a></p>";
        $contenido .= 'Si tu no creaste esta cuenta puedes ignorar este mensaje';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();
    }

    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = 'sandbox.smtp.mailtrap.io';
        $mail->SMTPAuth = true;
        $mail->Port = 2525;
        $mail->Username = 'be66f4d0e1c187';
        $mail->Password = '0a76973e56ed81';

        $mail->setFrom('cuentas@uptask.com');
        $mail->addAddress('cuentas@uptask.com', 'uptask.com');
        $mail->Subject = 'Reestablece tu contraseña';

        $mail->isHTML(TRUE);
        $mail->CharSet = 'UTF-8';

        $contenido = '<html>';
        $contenido .= '<p> Hola <strong>' . $this->nombre . '</strong> parece que has olvidado tu contraseña, da clic al siguiente enlace para reestablecerla</p>';
        $contenido .= "<p>Presiona aquí: <a href='http://localhost:3000/reset?token=" . $this->token . "'>Reestablecer contraseña</a></p>";
        $contenido .= 'Si tu no creaste esta cuenta puedes ignorar este mensaje';
        $contenido .= '</html>';

        $mail->Body = $contenido;

        $mail->send();
    }
}

?>