o<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_secretaria.php");

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//enviar correo
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    function generarPass() {
        // Generar 5 letras minúsculas aleatorias
        $letras = 'abcdefghijklmnopqrstuvwxyz';
        $contraseña = '';
        for ($i = 0; $i < 5; $i++) {
            $contraseña .= $letras[rand(0, strlen($letras) - 1)];
        }
        
        // Generar 3 números aleatorios
        $numeros = '0123456789';
        for ($i = 0; $i < 3; $i++) {
            $contraseña .= $numeros[rand(0, strlen($numeros) - 1)];
        }
        
        // Mezclar la contraseña para hacerla más aleatoria
        $contraseña = str_shuffle($contraseña);
        
        return $contraseña;
    }

    

    include("../../include/conexion.php");
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre_empresa = $_POST["nombre_empresa"];
        $ruc = $_POST["ruc"];
        $ubicacion = $_POST["ubicacion"];
        $contacto = $_POST["contacto"];
        $cargo = $_POST["cargo"];
        $correo = $_POST["correo"];
        $celular = $_POST["celular"];

        $nombreArchivo = $_FILES['logo']['name'];
        $tipoArchivo = $_FILES['logo']['type'];
        $tamañoArchivo = $_FILES['logo']['size'];
        $tempArchivo = $_FILES['logo']['tmp_name'];
        $errorArchivo = $_FILES['logo']['error'];

        $rutaDestino = "";

        if ($tamañoArchivo === 0) {
            // No se ha subido ningún archivo
            $rutaDestino = '../../empresa/files/img_defaul_empresa.png
            ';
        }
        // Verificar si no hubo errores al subir la imagen
        if($errorArchivo === 0) {
            // Mover la imagen de la ubicación temporal a la ubicación deseada
            $rutaDestino = '../../empresa/files/' . $nombreArchivo;
            move_uploaded_file($tempArchivo, $rutaDestino);
        
        }
            
        $rutaDestino = substr($rutaDestino,14);
        
        $passPlano = generarPass();
        $pass = password_hash($passPlano, PASSWORD_DEFAULT);

        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `empresa`(`razon_social`, `ruc`, `correo_institucional`, `ubicacion`, `contacto`, `cargo` , `celular_telefono`, `estado`, `usuario`, `ruta_logo`)
            VALUES ('$nombre_empresa' ,'$ruc', '$correo', '$ubicacion', '$contacto', '$cargo', '$celular', 'Activo', '$correo', '$rutaDestino')";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            $b_datos_institucion = buscarDatosGenerales($conexion);
            $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

            $b_datos_sistema = buscarDatosSistema($conexion);
            $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

            $asunto = "Aceptado para ser parte de nuestra bolsa laboral";
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

                $titulo_correo = 'SISPA '.$r_b_datos_sistema['titulo'];
                //Recipients
                $mail->setFrom($r_b_datos_sistema['email_email'], $titulo_correo);
                $mail->addAddress($correo, $contacto);     //Add a recipient
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
                $link = 'https://'.$r_b_datos_sistema['dominio_sistema'].'/empresa/login/';
                $mail->Body = '<!DOCTYPE html>
                            <html lang="es">
                            <head>
                                <meta charset="UTF-8">
                            </head>
                            <body>
                            <div style="width: 100%; font-family: Roboto; font-size: 0.8em; display: inline;">
                                <div style="background-color:'.$r_b_datos_sistema['color_correo'].'; border-radius: 10px 10px 0px 0px; text-align: center;">
                                </div>
                                <div style="background-color:'.$r_b_datos_sistema['color_correo'].'; border-radius: 0px 0px 0px 0px; height: 60px; margin-top: 0px; padding-top: 2px; padding-bottom: 10px;">
                                    <p style="text-align: center; font-size: 1.0rem; color: #f1f1f1; text-shadow: 2px 2px 2px #cfcfcf; ">'.$r_b_datos_institucion['nombre_institucion'].'</p>
                                </div>
                                <div>
                                    <h2 style="text-align:center;">SA (Sistema Académico)</h2>
                                    <h3 style="text-align:center; color: #3c4858;">BOLSA LABORAL</h3>
                                    <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                            
                                        Hola, para poder acceder a su modulo de bolsa laboral, Haz click <a href="'.$link.'">Aquí</a>.<br>
                                        
                                        Usuario: '.$correo.'<br>
                                        Contraseña: '.$passPlano.'<br>

                                        <br>
                                        <br>
                                        Por favor, no responda sobre este correo. Y se recomienda cambiar la contraseña.
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

                $mail->send();
                
            } catch (Exception $e) {
                echo "Error correo: {$mail->ErrorInfo}";
            }
            echo "<script>
            alert('Su registro ha sido exitoso y se ha enviado un correo a la empresa!');
            window.location.replace('../empresas.php');
            </script>";
        } else {
            echo "<script>
            alert('Ops, ha ocurrido un error! Recuerde no registrar un RUC o correo eledctrónico ya existente.
            ');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
}
?>
