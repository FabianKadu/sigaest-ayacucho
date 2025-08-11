<?php
include "../include/conexion.php";
include '../include/busquedas.php';
include '../include/funciones.php';

session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//enviar correo
require '../PHPMailer/Exception.php';
require '../PHPMailer/PHPMailer.php';
require '../PHPMailer/SMTP.php';

$correo = $_POST['email'] ?? null;
$dni = $_POST['dni'] ?? null;
$id_sesion = $_SESSION['id_sesion'] ?? $_SESSION['id_sesion_est'] ?? null;
$token = $_SESSION['token'] ?? null;

$enviar = 0;
$id_usuario = null;
$tipo_usuario = null;

// Verificar si se pasó el correo y el DNI por el formulario
if ($correo && $dni) {
    // Buscar en docentes
    $busc_docente = "SELECT * FROM docente WHERE dni='$dni' AND correo='$correo'";
    $ejec_busc_docente = mysqli_query($conexion, $busc_docente);
    $cont_docente = mysqli_num_rows($ejec_busc_docente);

    if ($cont_docente > 0) {
        $res_docente = mysqli_fetch_array($ejec_busc_docente);
        $id_usuario = $res_docente['id'];
        $tipo_usuario = 'docente';
        $enviar = 1;
    } else {
        // Buscar en estudiantes
        $busc_estudiante = "SELECT * FROM estudiante WHERE dni='$dni' AND correo='$correo'";
        $ejec_busc_estudiante = mysqli_query($conexion, $busc_estudiante);
        $cont_estudiante = mysqli_num_rows($ejec_busc_estudiante);

        if ($cont_estudiante > 0) {
            $res_estudiante = mysqli_fetch_array($ejec_busc_estudiante);
            $id_usuario = $res_estudiante['id'];
            $tipo_usuario = 'estudiante';
            $enviar = 1;
        }
    }
} else {
    // Verificar la sesión
    if ($id_sesion && $token) {
        // Verificar en docentes
        $b_sesion_docente = buscarSesionLoginById($conexion, $id_sesion);
        $r_b_sesion_docente = mysqli_fetch_array($b_sesion_docente);
        if (password_verify($r_b_sesion_docente['token'], $token)) {
            $id_usuario = buscar_docente_sesion($conexion, $id_sesion, $token);
            $tipo_usuario = 'docente';
            $enviar = 1;
        } else {
            // Verificar en estudiantes
            $b_sesion_estudiante = buscarSesionEstudianteLoginById($conexion, $id_sesion);
            $r_b_sesion_estudiante = mysqli_fetch_array($b_sesion_estudiante);
            if (password_verify($r_b_sesion_estudiante['token'], $token)) {
                $id_usuario = buscar_estudiante_sesion($conexion, $id_sesion, $token);
                $tipo_usuario = 'estudiante';
                $enviar = 1;
            }
        }
    }
}

$llave = generar_llave();
$token = password_hash($llave, PASSWORD_DEFAULT);

if ($enviar) {
    $b_datos_institucion = buscarDatosGenerales($conexion);
    $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

    $b_datos_sistema = buscarDatosSistema($conexion);
    $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

    if ($tipo_usuario == 'docente') {
        $b_usuario = buscarDocenteById($conexion, $id_usuario);
    } else {
        $b_usuario = buscarEstudianteById($conexion, $id_usuario);
    }

    $r_usuario = mysqli_fetch_array($b_usuario);

    // Enviar correo
    $asunto = "Cambio de Contraseña SA (Sistema Académico)";
    $mail = new PHPMailer(true);

    try {
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = $r_b_datos_sistema['host_email'];
        $mail->SMTPAuth = true;
        $mail->Username = $r_b_datos_sistema['email_email'];
        $mail->Password = $r_b_datos_sistema['password_email'];
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = $r_b_datos_sistema['puerto_email'];

        $titulo_correo = 'SIGAEST ' . $r_b_datos_sistema['titulo'];
        $mail->setFrom($r_b_datos_sistema['email_email'], $titulo_correo);
        $mail->addAddress($r_usuario['correo'], $r_usuario['apellidos_nombres']);

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
        $mail->Subject = $asunto;

        $protocol = 'http://';
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $protocol = 'https://';
        }
        $domain = $_SERVER['HTTP_HOST'];
        $urlLogo = $protocol . $domain . "/img/logo.png";

        $link = 'https://' . $r_b_datos_sistema['dominio_sistema'] . '/' . $tipo_usuario . '/login/recuperar_password.php?id=' . $id_usuario . '&token=' . $token;
        $mail->Body = '<!DOCTYPE html>
                        <html lang="es">
                        <head>
                            <meta charset="UTF-8">
                        </head>
                        <body>
                        <div style="width: 100%; font-family: Roboto; font-size: 0.8em; display: inline;">
                            <div style="background-color:' . $r_b_datos_sistema['color_correo'] . '; border-radius: 10px 10px 0px 0px; text-align: center;">
                                <img src="' . $urlLogo . '" alt="' . $r_b_datos_sistema['pagina'] . '" style="padding: 0.5em; text-align: center;" height="50px">
                            </div>
                            <div style="background-color:' . $r_b_datos_sistema['color_correo'] . '; border-radius: 0px 0px 0px 0px; height: 60px; margin-top: 0px; padding-top: 2px; padding-bottom: 10px;">
                                <p style="text-align: center; font-size: 1.0rem; color: #f1f1f1; text-shadow: 2px 2px 2px #cfcfcf; ">' . $r_b_datos_institucion['nombre_institucion'] . '</p>
                            </div>
                            <div>
                                <h2 style="text-align:center;">SA (Sistema Académico)</h2>
                                <h3 style="text-align:center; color: #3c4858;">CAMBIO DE CONTRASEÑA</h3>
                                <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                        
                                    Hola ' . $r_usuario['apellidos_nombres'] . ', para poder recuperar tu contraseña, Haz click <a href="' . $link . '">Aquí</a>.<br>
                                    
                                    
                                    <br>
                                    <br>
                                    Por favor, no responda sobre este correo.
                                    <br><br><br>
                        
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

        $mail->send();
        $sql = "UPDATE $tipo_usuario SET reset_password=1, token_password='$llave' WHERE id='$id_usuario'";
        $ejec_consulta = mysqli_query($conexion, $sql);
        echo "<script>
                alert('Correo Enviado, Verifique su correo, sino encuentra en su bandeja de entrada. Verifique en Seccion de Spam');
                window.location= '../index.php' 
              </script>";
    } catch (Exception $e) {
        echo "Error correo: {$mail->ErrorInfo}";
    }
} else {
    echo "<script>
            alert('Ops, Ocurrio un Error, si persiste, comuníquese con la Institución');
            window.location= '../index.php' 
          </script>";
}
?>
