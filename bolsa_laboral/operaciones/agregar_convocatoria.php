<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_administrador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    //info_sistema
    $b_datos_sistema = buscarDatosSistema($conexion);
    $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema);
    $url_sistema = $r_b_datos_sistema['dominio_sistema'];

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $id_empresa = $_POST["empresa"];
        $res_empresa = buscarEmpresaById($conexion, $id_empresa);
        $empresa = mysqli_fetch_array($res_empresa);
        $celular_contacto = $empresa['celular_telefono'];
        $ubicacion = $empresa['ubicacion'];
        $empresa = $empresa['razon_social'];
        
        $titulo = $_POST["titulo"];
        $vacante = intval($_POST["vacante"]);
        $salario = floatval($_POST["salario"]);
        $turno = $_POST["horario"];
        $modalidad = $_POST["modalidad"];
        $inicio = $_POST["inicio"];
        $fin = $_POST["fin"];
        $url = isset($_POST["url"]) ? $_POST["url"] : null;
        $programas = isset($_POST['carreras']) ? $_POST['carreras'] : [];

        // Datos del segundo paso
        $requisitos = $conexion -> real_escape_string($_POST["requisitos"]);
        $funciones = $conexion -> real_escape_string($_POST["funciones"]);
        $beneficios = $conexion -> real_escape_string($_POST["beneficios"]);
        $condiciones = $conexion -> real_escape_string(isset($_POST["condiciones"]) ? $_POST["condiciones"] : "No se registraron condiciones.");

        
        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `oferta_laboral_propia`(`empresa`, `celular_contacto`, `titulo`, `ubicacion`, `funciones`, `requisitos`, `condiciones`, `beneficios`, `salario`, `vacantes`,`modalidad`, `turno`, `fecha_inicio`, `fecha_fin`, `link_postulacion`, `estado`, `fecha_estado`)
            VALUES ( '$empresa' , '$celular_contacto' ,'$titulo', '$ubicacion', '$funciones', '$requisitos', '$condiciones', '$beneficios', $salario , $vacante , '$modalidad' , '$turno' ,'$inicio', '$fin', '$url', 'DISPONIBLE', CURRENT_TIMESTAMP())";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            $idOL = mysqli_insert_id($conexion);
            $listaCorreos = [];
            for ($i = 0; $i < count($programas); $i++) {
				$idProm = $programas[$i];

                //insertar programa
                $insert_programas = "INSERT INTO `oferta_programas`(`id_ol`, `id_pr`, `propietario`) VALUES ($idOL, $idProm, 'iestp')";
                mysqli_query($conexion, $insert_programas);

                //obtener correos de estudiantes de programas seleccionados
                $res_correos = buscarEstudiantesCorreosByPrograma($conexion, $idProm);
                while ($row = mysqli_fetch_assoc($res_correos)) {
                    $listaCorreos[] = $row['correo'];
                }

            }

            
            $asunto = 'Se ha registrado una nueva convocatoria laboral';
            $cuerpo = "
            <!DOCTYPE html>
            <html lang='es'>
            <head>
                <meta charset='UTF-8'>
                <meta name='viewport' content='width=device-width, initial-scale=1.0'>
                <title>Convocatoria Laboral</title>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f4f4f4;
                        margin: 0;
                        padding: 0;
                    }
                    .container {
                        background-color: #ffffff;
                        margin: 20px auto;
                        padding: 20px;
                        border-radius: 8px;
                        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                        max-width: 600px;
                    }
                    .header {
                        background-color: #4CAF50;
                        color: white;
                        padding: 10px;
                        text-align: center;
                        border-radius: 8px 8px 0 0;
                    }
                    .content {
                        padding: 20px;
                    }
                    .content p {
                        margin: 0 0 10px;
                    }
                    .content p strong {
                        color: #333;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Convocatoria Laboral</h1>
                    </div>
                    <div class='content'>
                        <p><strong>Empresa:</strong> $empresa</p>
                        <p><strong>Celular de Contacto:</strong> $celular_contacto</p>
                        <p><strong>Título del Puesto:</strong> $titulo</p>
                        <p><strong>Ubicación:</strong> $ubicacion</p>
                        <p><strong>Vacantes:</strong> $vacante</p>
                        <p><strong>Salario:</strong> $salario</p>
                        <p><strong>Turno:</strong> $turno</p>
                        <p><strong>Modalidad:</strong> $modalidad</p>
                        <p><strong>Fecha de Inicio:</strong> $inicio</p>
                        <p><strong>Fecha de Fin:</strong> $fin</p>
                    </div>
                    <center><b>Para ver más detalladamente esta convocatoria laboral, ingrese <a href='".$url_sistema."estudiante/login'>aquí</a>.</b></center>
                </div>
            </body>
            </html>
            ";
           
            // Guardar los datos en un archivo temporal (JSON)
            $datos = json_encode(['correos' => $listaCorreos, 'asunto' => $asunto, 'cuerpo' => $cuerpo]);
            file_put_contents('datos_correo.json', $datos);

            // Ejecutar el script en segundo plano
            exec('/usr/local/bin/php enviar_correo_masivo.php > /dev/null 2>&1 &');

            echo "<script>
            alert('Se ha guardado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatoria_documento.php?id=". $idOL . "');
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar en base de datos!');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
}
?>
