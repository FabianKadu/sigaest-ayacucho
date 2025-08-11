<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//enviar correo
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

require '../../composer/vendor/autoload.php';

// Leer los datos del archivo temporal
$datos = json_decode(file_get_contents('datos_correo.json'), true);

$listaCorreos = $datos['correos'];
$asunto = $datos['asunto'];
$cuerpo = $datos['cuerpo'];




function enviarCorreoMasivo($listaCorreos, $asunto, $cuerpo, $conexion) {
    $mail = new PHPMailer(true);

        //BUSCAR DATOS DEL SISTEMA
    $b_datos_institucion = buscarDatosGenerales($conexion);
    $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

    $b_datos_sistema = buscarDatosSistema($conexion);
    $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

    try {
        // Configuración del servidor SMTP
        $mail->SMTPDebug = 0;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
        $mail->Host       = $r_b_datos_sistema['host_email'];                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = $r_b_datos_sistema['email_email'];                     //SMTP username
        $mail->Password   = $r_b_datos_sistema['password_email'];                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
        $mail->Port       = $r_b_datos_sistema['puerto_email'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

        //Recipients
        $mail->setFrom($r_b_datos_sistema['email_email'], 'SIGAEST');

        // Configuración del correo
        $mail->isHTML(true);
        $mail->Subject = $asunto;
        $mail->Body = $cuerpo;

        // Añadir destinatarios
        foreach ($listaCorreos as $correo) {
            $mail->addAddress($correo);
        }

        // Enviar el correo
        $mail->send();
        echo 'Correo enviado correctamente';
    } catch (Exception $e) {
        echo "Error al enviar el correo: {$mail->ErrorInfo}";
    }
}

enviarCorreoMasivo($listaCorreos, $asunto, $cuerpo, $conexion);
?>