<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_secretaria.php");

include_once("../PHP_XLSXWriter/xlsxwriter.class.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
  alert('Usted no tiene los privilegios para generar reportes.');
  window.location.replace('../include/login.php');
    </script>";
}else{
    $id_proceso_admision = $_GET['id'];
    $res_proceso = buscarProcesoAdmisionPorId($conexion, $id_proceso_admision);
    $proceso = mysqli_fetch_array($res_proceso);
    /*header ("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header ("Content-Disposition: attachment; filename=plantilla.xls");*/

    $titulo_archivo = "ProcesoAdmision" .$proceso['Tipo']. $proceso['Periodo'] ;

    //generamos excel
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);

    $filename = $titulo_archivo.".xlsx";
    header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $writer = new XLSXWriter();

    $styles1 = array('font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom');
    
    $ord = 1;
    $header = array(
            'N°' => 'N°',
            'PROGRAMA DE ESTUDIOS' => 'PROGRAMA DE ESTUDIOS', 
            'PROGRAMA SEGUNDA OPCIÓN' => 'PROGRAMA SEGUNDA OPCIÓN', 
            'DNI' => 'DNI',
            'APELLIDO PATERNO' => 'APELLIDO PATERNO',
            'APELLIDO MATERNO' => 'APELLIDO MATERNO',
            'NOMBRES' => 'NOMBRES', 
            'PUNTAJE' => 'PUNTAJE', 
            'ÓRDEN DE MÉRITO' => 'ÓRDEN DE MÉRITO', 
            'CONDICIÓN' => 'CONDICIÓN', 
        );

        //imprime encabezado
        $writer->writeSheetRow('Plantilla', $header, $styles1);

        $busc_ingr = obtenerPostulantesAptosProcesoAdmision($conexion, $id_proceso_admision); 
        while ($postulantes=mysqli_fetch_array($busc_ingr)){
            //imprime contenido
            $res_segunda_opcion = $postulantes['Id_Segunda_Opcion'];
            $segunda_opcion = "NINGUNO";

            if($res_segunda_opcion > 0){
                //recuperar programa segunda opcion
                $res_programa2 = buscarCarrerasById($conexion, $res_segunda_opcion);
                $cont_programa2 = mysqli_num_rows($res_programa2);
                if($cont_programa2 > 0){
                    $programa2 = mysqli_fetch_array($res_programa2);
                    $segunda_opcion = $programa2['nombre'];
                }
            }

            $writer->writeSheetRow('Plantilla', $rowdata = array(
                $ord, $postulantes['nombre'], $segunda_opcion, $postulantes['Dni'], $postulantes['Apellido_Paterno'], 
                $postulantes['Apellido_Materno'], $postulantes['Nombres'], "", 
               "",""), $styles9);

            $ord += 1;
        }

        $writer->writeToStdOut();

exit(0);
}
?>