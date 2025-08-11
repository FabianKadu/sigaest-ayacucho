<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//enviar correo
require '../../PHPMailer/Exception.php';
require '../../PHPMailer/PHPMailer.php';
require '../../PHPMailer/SMTP.php';

include ('../include/verificar_sesion_caja.php');

if (!verificar_sesion($conexion)) {
	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
				window.location.replace('../login/');
	</script>";
} else {

	$fecha_registro = $_POST['fecha_registro'];
	$descripcion = $_POST['descripcion'];
	$enlace = $_POST['enlace'];
	$usuario = $_POST['usuario'];
	$instituto = $_POST['instituto'];
	$estado = $_POST['estado'];
	$comentario = $_POST['comentario'];
	$imagen_nombre1 = $_FILES['imagen1']['name'];
	$imagen_nombre2 = $_FILES['imagen2']['name'];
	$imagen_nombre3 = $_FILES['imagen3']['name'];
	$imagen_nombre4 = $_FILES['imagen4']['name'];
	$imagen_nombre5 = $_FILES['imagen5']['name'];

	$imagen_temp1 = $_FILES['imagen1']['tmp_name'];
	$imagen_temp2 = $_FILES['imagen2']['tmp_name'];
	$imagen_temp3 = $_FILES['imagen3']['tmp_name'];
	$imagen_temp4 = $_FILES['imagen4']['tmp_name'];
	$imagen_temp5 = $_FILES['imagen5']['tmp_name'];
	$imagen_ruta1 = "../../img/soporte_imagenes/" . $imagen_nombre1; // Ruta donde se guardará el archivo
	$imagen_ruta2 = "../../img/soporte_imagenes/" . $imagen_nombre2; // Ruta donde se guardará el archivo
	$imagen_ruta3 = "../../img/soporte_imagenes/" . $imagen_nombre3; // Ruta donde se guardará el archivo
	$imagen_ruta4 = "../../img/soporte_imagenes/" . $imagen_nombre4; // Ruta donde se guardará el archivo
	$imagen_ruta5 = "../../img/soporte_imagenes/" . $imagen_nombre5; // Ruta donde se guardará el archivo

	$fecha = date('Y-m-d', strtotime($fecha_registro));
	$hora = date('H:i', strtotime($fecha_registro));

	// Generar un código aleatorio de 5 dígitos alfanuméricos
	$codigo = substr(str_shuffle("0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);

	// Mover la imagen a la carpeta de destino
	move_uploaded_file($imagen_temp1, $imagen_ruta1);
	move_uploaded_file($imagen_temp2, $imagen_ruta2);
	move_uploaded_file($imagen_temp3, $imagen_ruta3);
	move_uploaded_file($imagen_temp4, $imagen_ruta4);
	move_uploaded_file($imagen_temp5, $imagen_ruta5);

	$insertar = "INSERT INTO soporte_ticket (fecha_registro, descripcion, enlace, usuario, instituto, estado, comentario, imagen1, imagen2, imagen3, imagen4, imagen5, codigo)
	VALUES ('$fecha_registro', '$descripcion', '$enlace', '$usuario', '$instituto', '$estado', '$comentario', '$imagen_nombre1', '$imagen_nombre2', '$imagen_nombre3', '$imagen_nombre4', '$imagen_nombre5', '$codigo')";

	$ejecutar_insertar = mysqli_query($conexion, $insertar);

	// -------------------------------------------------------enviar correo-------------------------------------------------------


	$b_datos_institucion = buscarDatosGenerales($conexion);
	$r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

	$b_datos_sistema = buscarDatosSistema($conexion);
	$r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

	$asunto = "Ticket de Soporte - " . $r_b_datos_sistema['titulo'] . "";
	//Create an instance; passing `true` enables exceptions
	$mail = new PHPMailer(true);

	try {
		//Server settings
		$mail->SMTPDebug = 0;                      //Enable verbose debug output
		$mail->isSMTP();                                            //Send using SMTP
		$mail->Host = $r_b_datos_sistema['host_email'];                     //Set the SMTP server to send through
		// $mail->Host = 'gmail.com';                     //Set the SMTP server to send through
		$mail->SMTPAuth = true;                                   //Enable SMTP authentication
		$mail->Username = $r_b_datos_sistema['email_email'];                     //SMTP username
		// $mail->Username = 'juanquispe12342@gmail.com';                  //SMTP username
		$mail->Password = $r_b_datos_sistema['password_email'];                               //SMTP password
		// $mail->Password = '$Juanquispe1324';                               //SMTP password
		$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
		$mail->Port = $r_b_datos_sistema['puerto_email'];                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

		$titulo_correo = 'SIGAEST ' . $r_b_datos_sistema['titulo'];
		//Recipients
		$mail->setFrom($r_b_datos_sistema['email_email'], $titulo_correo);
		// Obtener la cadena de correos electrónicos
		$emails = $r_b_datos_sistema['email_soporte'];

		// Separar la cadena en un array de correos electrónicos
		$emails_array = explode(',', $emails);

		// Añadir cada correo electrónico como destinatario
		foreach ($emails_array as $email) {
			// Limpiar el correo electrónico de espacios en blanco y otros caracteres no deseados
			$email_cleaned = trim($email);

			// Añadir el correo electrónico como destinatario
			$mail->addAddress($email_cleaned, 'Soporte');
			//$mail->addAddress('ellen@example.com');               //Name is optional
			//$mail->addReplyTo('info@example.com', 'Information');
			//$mail->addCC('cc@example.com');
			//$mail->addBCC('bcc@example.com');

			//Attachments
			//$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
			//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name

			//Content

			switch ($usuario) {
				case 1:
					$tipo_usuario = "DIRECTOR";
					break;
				case 2:
					$tipo_usuario = "SECRETARIO ACADEMICO";
					break;
				case 3:
					$tipo_usuario = "JEFE DE UNIDAD ACADEMICA";
					break;
				case 4:
					$tipo_usuario = "JEFE DE AREA/COORDINADOR";
					break;
				case 5:
					$tipo_usuario = "DOCENTE";
					break;
				case 6:
					$tipo_usuario = "TESORERO";
					break;
				case 7:
					$tipo_usuario = "ESTUDIANTE";
					break;
				default:
					$tipo_usuario = "Tipo de usuario desconocido";
					break;
			}

			$enlaceVerificar = $r_b_datos_sistema['dominio_sistema'] . "docente/tickets_soporte.php?codigo=" . $codigo;

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

						<div style="background-color:' . $r_b_datos_sistema['color_correo'] . '; border-radius: 0px 0px 0px 0px; height: 60px; margin-top: 0px; padding-top: 2px; padding-bottom: 10px;">
							<p style="text-align: center; font-size: 1.0rem; color: #f1f1f1; text-shadow: 2px 2px 2px #cfcfcf; ">' . $r_b_datos_institucion['nombre_institucion'] . '</p>
						</div>
						<div>
							<h2 style="text-align:center;">TICKET DE SOPORTE - Codigo: ' . $codigo . '</h2>
							<p style="font-size:1.0rem; color: #000; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
								Estimado Equipo de Soporte,
								<br>
								Espero que este mensaje los encuentre bien. Me dirijo a ustedes para informarles sobre un problema que hemos experimentado en nuestro sistema recientemente.
								<br>
								En la fecha ' . $fecha . ', siendo la hora: ' . $hora . ', nuestros usuarios se enfrentaron a una dificultad que afectó significativamente su experiencia. A continuación, detallo la descripción del problema:
								<br>
							<p/>
							<table style="border-radius: 10px; border: 1px solid black; color: #000; width: 80%; margin: 0 auto; padding: 10px;">
								<tr>
									<td colspan="2" style="background-color: #f2f2f2; text-align: center; font-size: 1.1rem;"><b>DETALLES DEL TICKET</b></td>
								</tr>
								<tr>
									<td style="font-size:1.0rem;"><b>IESTP:</b></td>
									<td style="font-size:1.0rem;">'.$r_b_datos_institucion['nombre_institucion'].'</td>
								</tr>
								<tr>
									<td style="font-size:1.0rem;"><b>Fecha:</b></td>
									<td style="font-size:1.0rem;">'.$fecha.'</td>
								</tr>
								<tr>
									<td style="font-size:1.0rem;"><b>Hora:</b></td>
									<td style="font-size:1.0rem;">'.$hora.'</td>
								</tr>
								<tr>
									<td style="font-size:1.0rem;"><b>Usuario Caja:</b></td>
									<td style="font-size:1.0rem;">' . $usuario . '</td>
								</tr>
							</table>

							<br><br/>

							<table style="border-radius: 10px; border: 1px solid black; color: #000; width: 80%; margin: 0 auto; padding: 10px;">
								<tr>
									<td style="background-color: #f2f2f2; text-align: center; font-size: 1.1rem;"><b>INCIDENTE</b></td>
								</tr>
								<tr>
									<td style="font-size:1.0rem;">"'.$descripcion.'"</td>
								</tr>
							</table>
							
							<br>
							<p style="font-size:1.0rem; color: #000; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
								Enlace para ver la ventana del error: <a href="' . $enlace . '">Enlace de ventana error </a>
								<br>
								<br>
								Esta situación ha generado inconvenientes para nuestro usuario ' . $usuario . ', quien depende de la funcionalidad afectada para llevar a cabo sus tareas diarias de manera eficiente.
								<br>
								Apreciamos profundamente su pronta atención a este asunto. Por favor, háganos saber si requieren más detalles o información adicional para abordar este problema de manera efectiva.
								<br>
								Quedamos a la espera de su pronta respuesta y solución.
								<br><br>
								Enlace para la actualizar el estado de la revision del error: <a style="font-size:1.2rem;" href="' . $enlaceVerificar . '">Soporte Tickets</a>
							</p>
						</div>
						<div style="color: #f1f1f1; width: 100%; height: 120px; background:' . $r_b_datos_sistema['color_correo'] . '; text-align: center;  border-radius: 0px 0px 10px 10px; ">
							<br>
							<p style="margin: 0px;">
								<strong>
									<a"
									style="text-decoration: none; color: #f1f1f1; ">' . $r_b_datos_institucion['direccion'] . '
										&nbsp;|&nbsp; Teléfono: ' . $r_b_datos_institucion['telefono'] . '</a>
									<br> ' . $r_b_datos_institucion['nombre_institucion'] . '
								</strong>
							</p>
						</div>
					</div>
					</body>
					</html>';

			// Adjuntar la imagen al correo
			// $mail->addAttachment($imagen_ruta1, $imagen_nombre1);
			// $mail->addAttachment($imagen_ruta2, $imagen_nombre2);
			// $mail->addAttachment($imagen_ruta3, $imagen_nombre3);
			// $mail->addAttachment($imagen_ruta4, $imagen_nombre4);
			// $mail->addAttachment($imagen_ruta5, $imagen_nombre5);
			//$mail->AltBody = '';

			$mail->send();
			$exito = true;
		}
	} catch (Exception $e) {
		echo "Error correo: {$mail->ErrorInfo}";
		$exito = false;
	}

	if ($ejecutar_insertar && $exito) {
		echo "<script>
				alert('Ticket registrada exitosamente | correo enviado correctamente');
				window.location= '../tickets_caja.php';
	</script>";
	} else {
		echo "<script>
				alert('Error al registrar el ticket, por favor verifique sus datos');
				window.history.back();
	</script>";
	}

	mysqli_close($conexion);

}