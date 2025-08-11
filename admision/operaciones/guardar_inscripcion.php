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

$id_proceso_admision = $_POST['proceso'];
$dni = $_POST['dni'];

$cantidad = buscarDetallePostulantesByDNIProcesoAdmision($conexion, $id_proceso_admision, $dni);

if(intval($cantidad) === 0){

    //EVALUADOR
    $exito = False;
    $estado_loader = "En Proceso";

    //POSTULANTE
    $apellido_paterno = $_POST['paterno'];
    $apellido_materno = $_POST['materno'];
    $nombres = $_POST['nombres'];
    
    $rutaTemporalFotografia = $_FILES['fotografia']["tmp_name"];
    $nombreArchivo = $_FILES['fotografia']["name"];

    // Obtener la extensión del archivo
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    // Nuevo nombre de archivo
    $nuevoNombre = $dni .'.'. $extension;
    $carpetaDestino = "../utils/documentos/";
    // Ruta de destino con el nuevo nombre de archivo
    $fotografia = substr($carpetaDestino . $nuevoNombre,3);

    $genero = $_POST['genero'];
    $estado_civil = $_POST['est_civil'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $lengua_materna = $_POST['len_materna'];
    $discapacidad = $_POST['discapacidad'];
    $tipo_discapacidad = isset($_POST['tipo_discapacidad']) ? $_POST['tipo_discapacidad'] : null;
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO `postulante`(`Dni`, `Apellido_Paterno`, `Apellido_Materno`, `Nombres`, `Fotografia`, 
    `Sexo`, `Correo`, `Celular`, `Estado_Civil`, `Fecha_Nacimiento`, `Domicilio_Actual`, 
    `Lengua_Materna`, `Presenta_Discapacidad`, `Tipo_Discapacidad`) 
    VALUES ('$dni','$apellido_paterno','$apellido_materno','$nombres','$fotografia','$genero','$correo',
    '$celular','$estado_civil','$fecha_nacimiento','$direccion','$lengua_materna',
    '$discapacidad','$tipo_discapacidad')";
    $res = mysqli_query($conexion, $sql);
    if($res){

        // Mover el archivo de la ruta temporal al directorio destino
        move_uploaded_file($rutaTemporalFotografia, $carpetaDestino . $nuevoNombre);

        $id_postulante = mysqli_insert_id($conexion);
    
        //COLEGIO
        $id_colegio = $_POST['colegio'];
        $tipo_colegio = $_POST['tipo_colegio'];
        $anio_egreso = $_POST['anio_egreso'];
        
        $sql_colegio = "INSERT INTO `detalle_colegio`(`Id_Colegio`, `Tipo`, `Anio_Egreso`) 
        VALUES ('$id_colegio','$tipo_colegio','$anio_egreso')";
        $res_colegio = mysqli_query($conexion, $sql_colegio);

        if($res_colegio){
            
            $id_detalle_colegio = mysqli_insert_id($conexion);

            //PADRES
            $ap_dni = isset($_POST['ap_dni']) ? $_POST['ap_dni'] : null;
            $ap_apellidos = isset($_POST['ap_apellidos']) ? $_POST['ap_apellidos'] : null;
            $ap_nombres = isset($_POST['ap_nombres']) ? $_POST['ap_nombres'] : null;
            $ap_celular = isset($_POST['ap_celular']) ? $_POST['ap_celular'] : null;
            
            //DETALLE INSCRIPCION
            
            $id_modalidad = $_POST['modalidad'];
            $id_programa = $_POST['programa'];
            $id_programa_opcional = isset($_POST['segun_opcion']) ? $_POST['segun_opcion'] : "0";

            //REQUISITOS
            $id_medio_pago = $_POST['metodo_pago'];
            $difusion = $_POST['difusion'];

            $total_admision = cantidadPostulaciones($conexion);

            $codigo_unico = generarCodigoAdmision($dni, $total_admision);

            if($id_programa_opcional == "0"){
                $sql_postulacion = "INSERT INTO `detalle_postulacion`(`Id_Postulante`, `Id_Detalle_Colegio`, `Id_Modalidad`, `Id_Proceso_Admision`, 
                `Id_Programa_Estudio`, `Id_Metodo_Pago`, `Dni_Apoderado`, `Apellidos_Apoderado`, 
                `Nombres_Apoderado`, `Celular_Apoderado`, `Medio_Difusion`, `Codigo_Unico`) VALUES ('$id_postulante',
                '$id_detalle_colegio','$id_modalidad','$id_proceso_admision','$id_programa','$id_medio_pago','$ap_dni',
                '$ap_apellidos','$ap_nombres','$ap_celular','$difusion','$codigo_unico')";
                $res_postulacion = mysqli_query($conexion, $sql_postulacion);
            }else{
                $sql_postulacion = "INSERT INTO `detalle_postulacion`(`Id_Postulante`, `Id_Detalle_Colegio`, `Id_Modalidad`, `Id_Proceso_Admision`, 
                `Id_Programa_Estudio`,`Id_Segunda_Opcion`, `Id_Metodo_Pago`, `Dni_Apoderado`, `Apellidos_Apoderado`, 
                `Nombres_Apoderado`, `Celular_Apoderado`, `Medio_Difusion`, `Codigo_Unico`) VALUES ('$id_postulante',
                '$id_detalle_colegio','$id_modalidad','$id_proceso_admision','$id_programa', '$id_programa_opcional','$id_medio_pago','$ap_dni',
                '$ap_apellidos','$ap_nombres','$ap_celular','$difusion','$codigo_unico')";
                $res_postulacion = mysqli_query($conexion, $sql_postulacion);
            }

            if($res_postulacion){
                $id_detalle_postulacion = mysqli_insert_id($conexion);
                $directorioDestino = '../utils/documentos/';
                $res_generales = buscarRequisitosGeneralesPorProceso($conexion, $id_proceso_admision);
                while ($req_generales = mysqli_fetch_array($res_generales)) {
                    // (archivo cargado)
                    
                        $id_requisito = $req_generales['Id'];
                        $nombreArchivo =$id_requisito.$dni.".pdf";
                        
                        if($req_generales['Titulo'] === 'Fotografías'){
                            $rutaFinal = $fotografia;
                        }else{
                            $rutaTemporal = $_FILES[$id_requisito]["tmp_name"];
                            $rutaFinal = substr($directorioDestino . $nombreArchivo,3);
                        }
                        $sql_requisito = "INSERT INTO `documento_postulacion`(`Id_Detalle_Postulacion`, `Id_Requisito`, `Documento`) 
                        VALUES ('$id_detalle_postulacion','$id_requisito','$rutaFinal')";
                        $res_requisito = mysqli_query($conexion, $sql_requisito);
                        if($res_requisito){
                            $exito = True;
                            // Mover el archivo de la ruta temporal al directorio destino
                            if($req_generales['Titulo'] !== 'Fotografías'){
                                move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo);
                            }
                        }
                    
                }
                $res_especiales = buscarRequisitosEspecificosPorProcesoModalidad($conexion, $id_proceso_admision, $id_modalidad);
                while ($req_especiales = mysqli_fetch_array($res_especiales)) {
                    // (archivo cargado)
                    $id_requisito = $req_especiales['Id'];
                    $nombreArchivo = $id_requisito.$dni.".pdf";

                    $rutaTemporal = $_FILES[$id_requisito]["tmp_name"];
                    $rutaFinal = substr($directorioDestino . $nombreArchivo,3);
                    $sql_requisito_e = "INSERT INTO `documento_postulacion`(`Id_Detalle_Postulacion`,`Id_Requisito`, `Documento`) 
                    VALUES ('$id_detalle_postulacion','$id_requisito','$rutaFinal')";
                    $res_requisito_e = mysqli_query($conexion, $sql_requisito_e);
                    if($res_requisito_e){
                        $exito = True;
                        // Mover el archivo de la ruta temporal al directorio destino
                        move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo);
                    }
                }
                
            }else{
                echo "<script>
                alert('Error al registrar requisitos');
                window.location.replace('../inscripcion.php');
                    </script>
                ";
            }
        }else{
            echo "<script>
			alert('Error al registrar datos de colegio y procedencia');
            window.location.replace('../inscripcion.php');
				</script>
			";
        }

    }else{
        echo "<script>
			alert('Error al registrar datos del postulante');
            window.location.replace('../inscripcion.php');
				</script>
			";
    }
    

    if($exito){ 
        
            $b_datos_institucion = buscarDatosGenerales($conexion);
            $r_b_datos_institucion = mysqli_fetch_array($b_datos_institucion);

            $b_datos_sistema = buscarDatosSistema($conexion);
            $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);

            $asunto = "Inscripción de procesos de admisión con éxito";
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

                $titulo_correo = 'SIGAEST '.$r_b_datos_sistema['titulo'];
                //Recipients
                $mail->setFrom($r_b_datos_sistema['email_email'], $titulo_correo);
                $mail->addAddress($correo, $nombres);     //Add a recipient
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
                                <div style="background-color:'.$r_b_datos_sistema['color_correo'].'; border-radius: 10px 10px 0px 0px; text-align: center;">
                                </div>
                                <div style="background-color:'.$r_b_datos_sistema['color_correo'].'; border-radius: 0px 0px 0px 0px; height: 60px; margin-top: 0px; padding-top: 2px; padding-bottom: 10px;">
                                    <p style="text-align: center; font-size: 1.0rem; color: #f1f1f1; text-shadow: 2px 2px 2px #cfcfcf; ">'.$r_b_datos_institucion['nombre_institucion'].'</p>
                                </div>
                                <div>
                                    <h2 style="text-align:center;">SA (Sistema Académico)</h2>
                                    <h3 style="text-align:center; color: #3c4858;">ADMISIÓN</h3>
                                    <p style="font-size:1.0rem; color: #2A2C2B; margin-top: 2em; margin-bottom: 2em; margin-left: 1.5em;">
                            
                                        Hola, usted a realizado su inscripción con éxito, para poder acceder a su modulo de postulante, haz click <a href="'.$link.'">Aquí</a>.<br>
                                        Contraseña: '.$codigo_unico.'<br>
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
                $exito = true;
            } catch (Exception $e) {
                echo "Error correo: {$mail->ErrorInfo}";
                $exito = false;
            }
        }
    }else{
        $exito = false;
        $text = "El dni proporcionado ya tiene una inscripción previa, revise su correo electronico indicado en la anterior inscripción.";
    }   
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="Content-Language" content="es-ES">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Admisión</title>
        <!--icono en el titulo-->
        <link rel="shortcut icon" href="../img/favicon.ico">
        <!-- Script obtenido desde CDN jquery -->
        <script
            src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
            crossorigin="anonymous"></script>
    </head>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }

        .container-card {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .content {
            text-align: center;
            background-color: #fff;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 80%;
            /* Ajustar el ancho máximo del contenido */
        }

        .icono-exitoso img {
            width: 100px;
            margin-bottom: 20px;
        }

        .creditos {
            color: #989898;
        }
        
        .btn{
            padding: 10px 20px;
            border: none;
            background-color: green;
        }


        /* Media queries para dispositivos móviles */
        @media screen and (max-width: 600px) {
            .content {
                padding: 10px;
                
            }

            .icono-exitoso img {
                width: 80px;
                
            }

            button {
                padding: 8px 16px;
            }
        }
    </style>

<body>
    <div class="container-card">
        <div class="content">
            <center>
            <div class="icono-exitoso">
                <br>
                <center>
                    <?php if($exito){ ?>
                    <div class="icono-exitoso">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="#008000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z" />
                        </svg>
                    </div>
                    <?php }else{ ?>
                    <div class="icono-error">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="50" height="50" fill="#FF0000">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path
                                d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-1 16h2v2h-2v-2zm0-10h2v8h-2v-8z" />
                        </svg>
                    </div>
                    <?php } ?>
                </center>
            </div>
            </center>
            <?php if($exito){ ?>
                <h1 class ="green">¡Registro Exitoso!</h1>
                <span>Su código para acceder a su perfil de postulante es: <b> <?php echo $codigo_unico; ?> </b> </span>
                <p>De igual manera se envio al correo propocionado la credencial para acceder al perfil del postulante.</p>
                <br>
                <a href="../login_postulante/index.php" target="_blank"><button class="btn btn-success">Ir al Perfil de Postulante</button></a>
            <?php }else{ ?>
                <h1 class ="green">¡Error al registrar, vuelva a intentarlo de nuevo!</h1>
                <p><?php echo $text; ?></p>
            <?php } ?>
            <br><br>
            <p id="contador">La página se cerrará en <span id="segundos">30</span> segundos.</p>
        </div>
    </div>
    <script>
        setTimeout(function () {
            window.location.replace('../portal.php'); // Redirige a otra página después de 1 minuto
        }, 30000); // 30000 milisegundos = 30 segundos
    </script>
    <script>
        var segundos = 30; // Inicializar el contador de segundos

        // Función para actualizar el contador de segundos y cerrar la ventana después de 1 minuto
        var temporizador = setInterval(function () {
            document.getElementById('segundos').textContent = segundos; // Actualizar el texto del contador
            segundos--; // Decrementar el contador
            if (segundos < 0) {
                clearInterval(temporizador); // Detener el temporizador cuando llegue a cero
                window.close(); // Cerrar la ventana
            }
        }, 1000); // Actualizar cada segundo (1000 milisegundos = 1 segundo)
        // Modificar el historial del navegador para evitar el acceso a la página de registro exitoso mediante el botón de retroceso
        window.history.replaceState({}, document.title, window.location.href);
    </script>
</body>
<?php    
    mysqli_close($conexion); 
?>