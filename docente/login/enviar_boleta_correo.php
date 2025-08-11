<?php
include "../../include/conexion.php";
include '../../include/busquedas.php';
include '../../include/funciones.php';

session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$documento = $_GET['documento'];
$dni_estudiante = $_GET['dni'];

//enviar correo
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

    $llave = generar_llave();
    $token = password_hash($llave, PASSWORD_DEFAULT);

        $b_datos_institucion = buscarDatosGenerales($conexion);
        $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

        $b_datos_sistema = buscarDatosSistema($conexion);
        $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

        $estudiante_res = buscarEstudianteByDni($conexion, $dni_estudiante);
        $estudiante_res = mysqli_fetch_array($estudiante_res);
        $correo = $estudiante_res['correo'];

        //enviamos correo


        $asunto = "Emisión de Boleta de Notas";
        //Create an instance; passing `true` enables exceptions
        $mail = new PHPMailer(true);

        try {
            //Server settings
            $mail->SMTPDebug = 0;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = $r_b_datos_sistema['host_email'];                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = $r_b_datos_sistema['email_email'];                     //SMTP username
            $mail->Password   = $r_b_datos_sistema['password_email'];                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = $r_b_datos_sistema['puerto_email'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

            $titulo_correo = 'SIAGEST '.$r_b_datos_sistema['titulo'];
            //Recipients
            $mail->setFrom($r_b_datos_sistema['email_email'], $titulo_correo);
            $mail->addAddress($correo);     //Add a recipient
            //$mail->addAddress('ellen@example.com');               //Name is optional
            //$mail->addReplyTo('info@example.com', 'Information');
            //$mail->addCC('cc@example.com');
            //$mail->addBCC('bcc@example.com');

            //Attachments
            //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
            //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->CharSet = 'UTF-8';
            $mail->Subject = $asunto;

            $mail->Body = '<!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <meta charset="UTF-8">
                        </head>
                        <body>
                        <div style="width: 100%; font-family: Roboto; font-size: 0.8em; display: inline;">
                            <div style="background-color:'.$r_b_datos_sistema['color_correo'].'; border-radius: 0px 0px 0px 0px; height: 60px; margin-top: 0px; padding-top: 2px; padding-bottom: 10px;">
                                <p style="text-align: center; font-size: 1.0rem; color: #f1f1f1; text-shadow: 2px 2px 2px #cfcfcf; ">'.$r_b_datos_institucion['nombre_institucion'].'</p>
                            </div>
                            <div>
                                <h2 style="text-align:center;">SA (Sistema Académico)</h2>
                                <h3 style="text-align:center; color: #3c4858;">ENVIO DE DOCUMENTO</h3>
                                <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                        
                                    Hola, se adjunta el documento solicitado. 
                                    <br>
                                    <br>
                                    Por favor, no responda sobre este correo.
                                    <br><br><br>
                                </p>
                            </div>
                            <div style="color: #f1f1f1; width: 100%; height: 120px; background:'.$r_b_datos_sistema['color_correo'].'; text-align: center;  border-radius: 0px 0px 10px 10px; ">
                                <br>
                                <p style="margin: 0px;">
                                    <strong>
                                        <a"
                                        style="text-decoration: none; color: #f1f1f1; ">'.$r_b_datos_institucion['direccion'].'
                                            &nbsp;|&nbsp; Teléfono: '.$r_b_datos_institucion['telefono'].'</a>
                                        <br> '.$r_b_datos_institucion['nombre_institucion'].'
                                    </strong>
                                </p>
                            </div>
                        </div>
                        </body>
                        </html>';
            //$mail->AltBody = '';
            $mail->addAttachment('../'.$documento, 'Boleta de notas');
            $mail->send();
            echo "<script>
        alert('Correo Enviado, Verifique su correo, sino encuentra en su bandeja de entrada. Verifique en Seccion de Spam');
        window.location= '../boleta_de_notas.php'
        </script>
        ";
        } catch (Exception $e) {
            echo "Error correo: {$mail->ErrorInfo}";
            
        }
    ?>