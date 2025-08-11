<?php
// Verifica si el parámetro 'codigo' está presente en la URL
include_once 'include/busquedas.php';
include("include/conexion.php");

if(isset($_GET['codigo'])) {
    // Obtiene el valor del parámetro 'codigo'
    $codigoAutenticacion = $_GET['codigo'];
    $certificado = getCertificadoByCodigo($conexion,$codigoAutenticacion);
    $boleta = getBoletaByCodigo($conexion,$codigoAutenticacion);

    if(mysqli_fetch_row($certificado)!= 0){
        $tipo_doc = 0;
        $certificado = getCertificadoByCodigo($conexion,$codigoAutenticacion);
        $certificado = mysqli_fetch_array($certificado);
        $estudiante = $certificado['apellidos_nombres'];
        $programa = $certificado['programa_estudio'];
        $fecha = $certificado['fecha_emision'];
    }
    elseif(mysqli_fetch_row($boleta) != 0){
        $tipo_doc = 1;
        $boleta = getBoletaByCodigo($conexion,$codigoAutenticacion);
        $boleta = mysqli_fetch_array($boleta);
        $estudiante = $boleta['apellidos_nombres'];
        $programa = $boleta['programa_estudio'];
        $fecha = $boleta['fecha_emision'];
        $periodo = $boleta['periodo_acad'];
    }else{
        $tipo_doc = -1;
    }
} else {
    // Si no se proporciona el parámetro 'codigo', puedes manejarlo según tus necesidades
    echo "Parámetro 'codigo' no proporcionado.";
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
	  
    <title>Documentos<?php include ("include/header_title.php"); ?></title>
    <!--icono en el titulo-->
    <link rel="shortcut icon" href="img/favicon.ico">
    <style>
        body{
            margin: 0;
            background-color: #008fff;
        }
        .contenedor{
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .detalle{
            display: flex;
            padding-right: 3rem;
            min-width: 300px;
            background-color: #fff;
        }
        .informacion h1{
            text-align: center;
        }

        @media screen and (max-width: 700px) {
            .detalle{
                flex-direction: column;
                align-items: center;
                padding-bottom: 3rem;
                padding-right: 0;
            }
            .informacion div{
                display: flex;
                flex-direction: column;
            }
        }        

    </style>
  </head>
<body>
    <div class="contenedor">
        <div class="detalle">
            <img src="img/logo.png" width=200 alt="logo">
            <div class="informacion">
            <?php 
                if($tipo_doc == 0){
                    echo "<h1>Certificado de Estudios</h1>
                    <div><b>Nombre de Estudiante: </b>". $estudiante ."</div>
                    <br>
                    <div><b>Programa de estudios: </b>". $programa ."</div>
                    <br>
                    <div><b>Fecha de Emisión: </b>". $fecha ."</div>";
                }

                elseif($tipo_doc == 1){
                    echo "<h1>Boleta de Notas</h1>
                    <div><b>Nombre de Estudiante: </b>". $estudiante ."</div>
                    <br>
                    <div><b>Programa de estudios: </b>". $programa ."</div>
                    <br>
                    <div><b>Fecha de Emisión: </b>". $periodo ."</div>";
                }
                else{
                    echo "<h1>NO SE ENCONTRARON REGISTROS</h1>";
                }
            ?>
            </div>
        </div>
        
    </div>
  </body>
</html>