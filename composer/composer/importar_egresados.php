<?php
include "../include/busquedas.php";
include "../include/conexion.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = $_FILES['estudiantes']['tmp_name'];
$fileType = $_FILES['estudiantes']['type'];

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

    $estudiantes = [];

    foreach ($hoja as $index => $row) {
        if ($index < 10) {
            continue;
        }
        $dni = $row[1];
        //apellido y nombre de estudiante ocupa 3 columnas
        $egresado = $row[2];
        $fecha_nac = $row[6];
        $genero = $row[5];
        $correo = $row[11];
        $telefono = $row[12];
        $programa = $row[7]; //numero
        $nivel_formativo = $row[8];
        $fecha_egreso = $row[9];      

        if (empty($dni) == true) {
            continue;
        }

        //concatenar fecha de nacimiento de dd-mm-yyyy a yyyy-mm-dd
        $fecha_nac = date("Y-m-d", strtotime($fecha_nac));
        $fecha_egreso = date("Y-m-d", strtotime($fecha_egreso));

        //cambiar formato genero de MASCULINO A 1 y FEMENINO A 2
        if ($genero == "MASCULINO") {
            $genero = 1;
        } else if ($genero == "FEMENINO") {
            $genero = 2;
        }

        if (in_array($dni, array_column($estudiantes, 'dni'))) {
            $observacion = "El dni repite en el archivo";
        } else {
            $existe = buscarEstudianteByDniPe($conexion, $programa, $dni); //cambiar
            $cont = mysqli_num_rows($existe);
            if ($cont == 0 && $dni != "" && empty($dni) == false) {
                $pass = $dni;
                $pass_secure = password_hash($pass, PASSWORD_DEFAULT);
                $genero = intval($genero);
                $semestre = 6;
                $programa = intval($programa);
                $insertar = "INSERT INTO estudiante 
                (dni, apellidos_nombres, id_genero, fecha_nac, correo, telefono, 
                id_programa_estudios, id_semestre, egresado, nivel_formativo, fecha_egreso, password)
                VALUES (
                    '$dni', '$egresado', $genero, '$fecha_nac', '$correo', '$telefono', 
                    $programa, $semestre, 'SI', '$nivel_formativo', '$fecha_egreso','$pass_secure'
                    )";

                $ejecutar_insetar = mysqli_query($conexion, $insertar);
                if ($ejecutar_insetar) {
                    $observacion = "Ninguna";
                } else {
                    $observacion = "Error desconocido";
                }
            } else {
                $observacion = "El egresado ya existe en la base de datos con el programa indicado.";
            }
        }


        $estudiantes[] = [
            'dni' => $dni,
            'egresado' => $egresado,
            'observacion' => $observacion
        ];
    }


    $newXlsx = new Spreadsheet();
    $newXlsx->getActiveSheet()
        ->fromArray(
            $estudiantes,  // The data to set
            NULL,        // Array values with this value will not be set
            'A1'         // Top left coordinate of the worksheet range where
            //    we want to set these values (default is A1)
        );

    $writer = new Xlsx($newXlsx);

    // Definir las cabeceras para forzar la descarga del archivo
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename="reporte_migraci��n.xlsx"');
    header('Cache-Control: max-age=0');

    // Enviar el archivo al navegador
    $writer->save('php://output');
    exit;
} else {
    echo "<script>
					alert('Se ha subido un documento que no es de tipo Excel. Porfavor suba un documento adecuado!');
					window.history.back();
				</script>
			";
}
