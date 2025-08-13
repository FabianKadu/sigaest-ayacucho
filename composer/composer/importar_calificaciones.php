<?php
include "../include/busquedas.php";
include "../include/conexion.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = $_FILES['calificaciones']['tmp_name'];
$fileType = $_FILES['calificaciones']['type'];

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
        $programa = $row[1];//numero
        $curso = $row[2];
        $creditos = $row[3];//numero
        $calificacion = $row[4];//numero
        $semestre = $row[5];
        $periodo = $row[6];
        

        if ($dni == null || $programa == null || $curso == null || $creditos == null || $calificacion == null || $semestre == null || $periodo == null){
            $observacion = "Existe(n) campo(s) vacio(s)";
        }else{
            if($dni == "dni **"){$observacion = "Observaciones";

            }else{
                $programa = intval($programa);
                $creditos = floatval($creditos);
                $calificacion = floatval($calificacion);
                
                $existe = buscarNotaReptida($conexion,$dni, $programa,$curso, $calificacion, $semestre, $periodo); 
                $cont = mysqli_num_rows($existe);
                if($cont == 0){
                    $insertar = "INSERT INTO notas_antiguo (dni, id_programa, unidad_didactica, cantidad_creditos, calificacion, semestre_academico, periodo) 
                    VALUES ('$dni','$programa','$curso', '$creditos', '$calificacion', '$semestre', '$periodo')";
                    $ejecutar_insetar = mysqli_query($conexion, $insertar);
                    if ($ejecutar_insetar) {
                        $observacion = "Ninguna";
                    }else{
                        $observacion = "Error desconocido";
                    }
                }else{
                    $observacion = "Ya se encuentra registrado en la base de datos";
                }
            }
        }
        $calificaciones[] = [
            'dni' => $dni,
            'programa' => $programa,
            'curso' => $curso,
            'creditos' => $creditos,
            'calificacion' => $calificacion,
            'semestre' => $semestre,
            'periodo' => $periodo,
            'observaciones' => $observacion
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
    header('Content-Disposition: attachment;filename="reporte_migración.xlsx"');
    header('Cache-Control: max-age=0');

    // Enviar el archivo al navegador
    $writer->save('php://output');
    exit;
}else{
    echo "<script>
					alert('Se ha subido un documento que no es de tipo Excel. Porfavor suba un documento adecuado!');
					window.history.back();
				</script>
			";
}
?>