<?php
include "../include/busquedas.php";
include "../include/conexion.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$proceso_admision = $_POST['id_proceso'];
$id_programa = $_POST['programa'];
$inputFileName = $_FILES['resultados']['tmp_name'];
$fileType = $_FILES['resultados']['type'];

if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    /**  Identify the type of $inputFileName  **/
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
    /**  Create a new Reader of the type that has been identified  **/
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    /**  Load $inputFileName to a Spreadsheet Object  **/
    $spreadsheet = $reader->load($inputFileName);

    // Seleccionar la primera hoja del documento
    $hoja = $spreadsheet->getActiveSheet()->toArray();

    $calificaciones = [];

    foreach ($hoja as $row){
        $dni = $row[0];
        $puntaje = $row[1];//numero
        $orden_merito = $row[2];//numero
        $condicion = $row[3];
        
        if ($dni == null || $puntaje == null || $orden_merito == null){
            $observacion = "Existe(n) campo(s) vacio(s)";
        }else{
            if($dni === "DNI"){$observacion = "RESULTADO DE IMPORTACIÓN";

            }else{
                $orden_merito = intval($orden_merito);
                $puntaje = floatval($puntaje);
                
                //OBTENER ID DEL POSTULANTE
                $res_postulante = obtenerDatosPostulantePorDniProcesoAdmision($conexion, $dni, $proceso_admision);
                $postulante = mysqli_fetch_array($res_postulante);
                $id_postulante = $postulante['Id'];

                //VERIFICAR QUE EL POSTULANTE NO SE ENCUENTRE EN EL MISMO PROCESO PARA DIFERENTES PROGRAMAS 
                $existe = buscarResultadoPostulanteRepetido($conexion, $id_postulante, $proceso_admision);
                $cont = mysqli_num_rows($existe);
                if($cont == 0){
                    $insertar = "INSERT INTO `resultados_examen`(`Id_Postulante`, `Id_Programa`, `Id_Proceso_Admision`, `Puntaje`, `Orden_Merito`, `Condicion`) 
                    VALUES ('$id_postulante','$id_programa','$proceso_admision','$puntaje','$orden_merito','$condicion')";
                    $ejecutar_insetar = mysqli_query($conexion, $insertar);
                    if ($ejecutar_insetar) {
                        $observacion = "Ninguna";
                    }else{
                        $observacion = "El postulante no se encuentra inscrito en el proceso de admisión actual.";
                    }
                }else{
                    $observacion = "Postulante ya registrado con anterioridad";
                }
            }
        }
        $calificaciones[] = [
            'DNI' => $dni,
            'PUNTAJE' => $puntaje,
            'ORDEN DE MERITO' => $orden_merito,
            'CONDICIÓN' => $condicion,
            'RESULTADO DE IMPORTACIÓN' => $observacion
        ];

    }

    $newXlsx = new Spreadsheet();
    $newXlsx->getActiveSheet()
        ->fromArray(
            $calificaciones,  // The data to set
            NULL,        // Array values with this value will not be set
            'A1'         // Top left coordinate of the worksheet range where
                        //    we want to set these values (default is A1)
        );

    $writer = new Xlsx($newXlsx);

    // Definir las cabeceras para forzar la descarga del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_importacion_resultados_admision.xlsx"');
    header('Cache-Control: max-age=0');

    // Enviar el archivo al navegador
    $writer->save('php://output');
    exit;
}else{
    echo "<script>
					alert('Se ha subido un documento que no es de tipo Excel. Porfavor suba un documento adecuado!');
					//window.history.back();
				</script>
			";
}
?>