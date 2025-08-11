<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
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

    $id_postulacion = $_POST["id"];
    $detalle_post = buscarDetallePostulacionPorId($conexion, $id_postulacion);
    $detalle_post = mysqli_fetch_array($detalle_post);
    $id_proceso_admision = $detalle_post['Id_Proceso_Admision'];
    $codigo_unico = $detalle_post['Codigo_Unico'];
    $correo = $_POST["correo"];

    if ($_POST['tipo'] == "observado"){

        $observacion = $_POST["observacion"];

        if(isset($_POST['requisitos_observados'])) {
            // Recorre el array de checkboxes marcados
            foreach($_POST['requisitos_observados'] as $documento_id) {
                $sql = "UPDATE `documento_postulacion` SET `Observado`= '1' WHERE Id = '$documento_id'";
                $res = mysqli_query($conexion, $sql);
            } 
        
            // Consulta para insertar los datos en la base de datos
            $sql = "UPDATE `detalle_postulacion` SET `Estado`= 3 WHERE Id = $id_postulacion";
            $res = mysqli_query($conexion, $sql);
            if ($res) {

                $b_datos_institucion = buscarDatosGenerales($conexion);
                $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

                $b_datos_sistema = buscarDatosSistema($conexion);
                $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

                //enviamos correo
                $asunto = "Se ha encontrando algunas irregularidades en su inscripción del proceso de admisión";
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
                    $link = 'https://'.$r_b_datos_sistema['dominio_sistema'].'admision/login_postulante/';
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
                                        <h3 style="text-align:center; color: #3c4858;">ADMISIÓN</h3>
                                        <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                                
                                            Hola, se le ha observado algunos requisitos en el presente proceso de admisión. Para poder regularizar ingrese a su módulo de postulante <a href="'.$link.'">Aquí</a>. 
                                            <br>
                                            <b>Detalle de observación:</b> 
                                            <br>
                                            '.$observacion.'
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
                    $mail->send();

                } catch (Exception $e) {
                    echo "Error correo: {$mail->ErrorInfo}";
                    
                }

                echo "<script>
                window.location.replace('../postulantes_admision.php?id=".$id_proceso_admision."');
                </script>";
            }
        
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar!');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
        
    } 
    if ($_POST['tipo'] == "aceptado"){
        
        $rutaTemporal = $_FILES['documento']["tmp_name"];
        $ruta_destino = "../../admision/utils/documentos/";
		$nombre_archivo = basename($_FILES['documento']['name']);
         // Obtener la extensión del archivo
        $extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
        // Nuevo nombre de archivo
        $nuevoNombre = $codigo_unico .'.'. $extension;
		$ruta_completa = $ruta_destino . $nuevoNombre;
		$ruta_db =substr($ruta_completa,15);
        

        $sql = "UPDATE `detalle_postulacion` SET `Estado`= 2,`Ficha_Postulante`='$ruta_db' WHERE Id = '$id_postulacion'";
        $res = mysqli_query($conexion, $sql);
        
        if ($res) {
            // Mover el archivo de la ruta temporal al directorio destino
            move_uploaded_file($rutaTemporal, $ruta_completa);

            $b_datos_institucion = buscarDatosGenerales($conexion);
                $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

                $b_datos_sistema = buscarDatosSistema($conexion);
                $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

                //enviamos correo
                $asunto = "Apto para rendir examen de admision en el presente proceso de admisión";
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
                    $link = 'https://'.$r_b_datos_sistema['dominio_sistema'].'admision/login_postulante/';
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
                                        <h3 style="text-align:center; color: #3c4858;">ADMISIÓN</h3>
                                        <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                                            Hola, usted es apto para rendir el examen en el presente proceso de admisión. Para poder visualizar documentos relacionado con el proceso ingrese a su módulo de postulante <a href="'.$link.'">Aquí</a>. 
                                            <br>
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
                    $mail->addAttachment($ruta_completa, 'Ficha de Postulante');
                    $mail->send();

                } catch (Exception $e) {
                    echo "Error correo: {$mail->ErrorInfo}";
                    
                }
            echo "<script>
            alert('Registro exitoso!!');
            window.location.replace('../postulantes_admision.php?id=".$id_proceso_admision."');
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al aceptar!');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
}
?>

