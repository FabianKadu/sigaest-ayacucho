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
        if ($index < 5) {
            continue;
        }
        $periodo_matriculado = $row[0];
        $dni = $row[8];
        $apellido_paterno = $row[9];
        $apellido_materno = $row[10];
        $nombres = $row[11];
        $fecha_nac = $row[12];
        $genero = $row[13];
        $correo = $row[14];
        $telefono = $row[15];
        $semestre = $row[28]; //numero
        $programa = $row[27]; //numero
        $ubigeo_ie = $row[18];
        $departamento = $row[19];
        $provincia = $row[20];
        $distrito = $row[21];
        $tipo_ie = $row[22];
        $codigo_modular_ie = $row[23];
        $nombre_ie = $row[24];
        $tipo_gestion_ie = $row[25];
        $anio_egreso_ie = $row[26];

        if (empty($dni) == true) {
            continue;
        }

        //converti semestre de I,II,III,IV a 1,2,3,4..
        if ($semestre == "I") {
            $semestre = 1;
        } else if ($semestre == "II") {
            $semestre = 2;
        } else if ($semestre == "III") {
            $semestre = 3;
        } else if ($semestre == "IV") {
            $semestre = 4;
        }else if ($semestre == "V") {
            $semestre = 5;
        }else if ($semestre == "VI") {
            $semestre = 6;
        } else if ($semestre == "VII") {
            $semestre = 7;
        } else if ($semestre == "VIII") {
            $semestre = 8;
        }

        //eliminar espacios en blanco de periodo matriculado y luego convertir su ultimo digito de 1 a I y de 2 a II
        $periodo_matriculado = trim($periodo_matriculado);
        //obtener el ultimo digito
        $ultimo_digito = substr($periodo_matriculado, -1);
        if ($ultimo_digito == 1) {
            $periodo_matriculado = substr($periodo_matriculado, 0, -1) . "I";
        } else if ($ultimo_digito == 2) {
            $periodo_matriculado = substr($periodo_matriculado, 0, -1) . "II";
        }

        //convertir a int año de egreso
        $anio_egreso_ie = intval($anio_egreso_ie);

        //concatenar apellidos y nombres
        $estudiante = $apellido_paterno . " " . $apellido_materno . " " . $nombres;

        //eliminar comillas simples de los nombres
        $nombre_ie = str_replace("'", "", $nombre_ie);

        //concatenar fecha de nacimiento de dd/mm/yyyy a yyyy-mm-dd
        $fecha_nac = date("Y-m-d", strtotime($fecha_nac));

        //cambiar formato genero de MASCULINO A 1 y FEMENINO A 2
        if ($genero == "MASCULINO") {
            $genero = 1;
        } else if ($genero == "FEMENINO") {
            $genero = 2;
        }

        if (in_array($dni, array_column($estudiantes, 'dni'))) {
            $observacion = "El dni repite en el archivo";
        } else {
            $existe = buscarEstudianteDni($conexion, $dni);
            $cont = mysqli_num_rows($existe);
            if ($cont == 0 && $dni != "" && empty($dni) == false) {
                $pass = $dni;
                $pass_secure = password_hash($pass, PASSWORD_DEFAULT);
                $genero = intval($genero);
                $semestre = intval($semestre);
                $programa = intval($programa);
                $insertar = "INSERT INTO estudiante 
                (dni, apellidos_nombres, id_genero, fecha_nac, correo, telefono, 
                id_programa_estudios, id_semestre, egresado,
                password, ubigeo_ie, departamento_ie, provincia_ie, distrito_ie, 
                tipo_ie, codigo_mod_ie, nombre_ie, tipo_gestion_ie, anio_egreso_ie) 
                VALUES (
                    '$dni', '$estudiante', $genero, '$fecha_nac', '$correo', '$telefono', 
                    $programa, $semestre, 'NO', '$pass_secure', '$ubigeo_ie', '$departamento', 
                    '$provincia', '$distrito', '$tipo_ie', '$codigo_modular_ie', '$nombre_ie', 
                    '$tipo_gestion_ie', '$anio_egreso_ie'
                    )";

                $ejecutar_insetar = mysqli_query($conexion, $insertar);
                if ($ejecutar_insetar) {
                    //quiero obtener el id de la ultima inserción
                    $id = mysqli_insert_id($conexion);
                    $sql_periodo_matriculado = "INSERT INTO periodo_matriculado (id_estudiante, id_periodo) VALUES ('$id', '$periodo_matriculado')";
                    $ejecutar_periodo_matriculado = mysqli_query($conexion, $sql_periodo_matriculado);
                    if ($ejecutar_periodo_matriculado) {
                        $observacion = "Registrado correctamente";
                    } else {
                        $observacion = "Periodo ya registrado";
                    }
                    $observacion = "Ninguna";
                } else {
                    $observacion = "Error desconocido";
                }
            } else {
                $estudiante_id = mysqli_fetch_assoc($existe);
                $estudiante_id = $estudiante_id['id'];
                //verificar si ya esta registrado en periodo matriculado
                $sql_periodo = "SELECT * FROM periodo_matriculado WHERE id_estudiante = '$estudiante_id' AND id_periodo = '$periodo_matriculado'";
                $ejecutar_periodo = mysqli_query($conexion, $sql_periodo);
                $cont_periodo = mysqli_num_rows($ejecutar_periodo);
                if ($cont_periodo == 0) {
                    $sql_periodo_matriculado = "INSERT INTO periodo_matriculado (id_estudiante, id_periodo) VALUES ('$estudiante_id', '$periodo_matriculado')";
                    $ejecutar_periodo_matriculado = mysqli_query($conexion, $sql_periodo_matriculado);
                    if ($ejecutar_periodo_matriculado) {
                        $observacion = "Periodo registrado correctamente";
                    } else {
                        $observacion = "Periodo ya registrado";
                    }
                } else {
                    $observacion = "Periodo ya registrado";
                }
            }
        }


        $estudiantes[] = [
            'dni' => $dni,
            'estudiante' => $estudiante,
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
