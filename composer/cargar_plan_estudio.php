<?php
include "../include/busquedas.php";
include "../include/conexion.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = $_FILES['plan_estudio']['tmp_name'];
$fileType = $_FILES['plan_estudio']['type'];

if ($fileType === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    /**  Identify the type of $inputFileName  **/
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
    /**  Create a new Reader of the type that has been identified  **/
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    /**  Load $inputFileName to a Spreadsheet Object  **/
    $spreadsheet = $reader->load($inputFileName);

    // Obtener las hojas del documento
    $hoja1 = $spreadsheet->getSheet(0)->toArray();
    $hoja2 = $spreadsheet->getSheet(1)->toArray();


    //------------------------------------------ CARGA DE PROGRAMA DE ESTUDIO ------------------------------------------

    // Procesar los datos
    $codigo = "NO DEF";
    $tipo = $hoja2[3][4];
    $plan_estudio = $hoja2[2][4];
    $nombre = $hoja2[2][1];
    $resolucion = "NO DEF";
    $perfil = "NO DEF";


    // Buscar si el programa de estudios ya existe en la base de datos
    $carreras = buscarCarreras($conexion);
    $nombreExiste = false;

    while ($row = mysqli_fetch_assoc($carreras)) {
        if ($row['nombre'] === $nombre) {
            $nombreExiste = true;
            $id_programa_estudios = $row['id'];
            break;
        }
    }

    if (!$nombreExiste) {
        // Insertar los datos en la tabla programa_estudios
        $insertar = "INSERT INTO programa_estudios (codigo, tipo, plan_estudio, nombre, resolucion, perfil_egresado) VALUES ('$codigo','$tipo','$plan_estudio','$nombre','$resolucion','$perfil')";
        $ejecutar_insertar = mysqli_query($conexion, $insertar);

        if ($ejecutar_insertar) {
            // echo "<script>
            //         alert('Registro Exitoso');
            //         window.history.back();
            //     </script>";
        } else {
            // echo "<script>
            //         alert('Error al registrar el programa de estudios');
            //         window.history.back();
            //     </script>";
        }
    } else {
        // echo "<script>
        //         alert('El programa de estudios ya existe y no se ha registrado nuevamente');
        //     </script>";
    }

    //------------------------------------------ CARGA DE MODULOS ------------------------------------------

    // Buscar el id del programa de estudios
    while ($row = mysqli_fetch_assoc($carreras)) {
        if ($row['nombre'] === $nombre) {
            $id_programa_estudios = $row['id'];
            break;
        }
    }

    $hoja2Col = array_map(null, ...$hoja2);

    // Procesar los datos

    $conteo_modulos = 1;
    $columna2 = $hoja2Col[1];


    for ($i = 5; $i < count($columna2); $i++) {
        $num_col_mod = $i + 2;
        if ($columna2[$i] == "Módulo Formativo" && $columna2[$num_col_mod] !== null) {
            $nombre_modulo = $columna2[$num_col_mod];

            // Buscar si el modulo formativo ya existe en la base de datos
            $modulos = buscarModuloFormativo($conexion);
            $moduloExiste = false;

            while ($row = mysqli_fetch_assoc($modulos)) {

                if ($row['descripcion'] == $nombre_modulo) {
                    $moduloExiste = true;
                    break;
                }
            }

            if ($moduloExiste) {
                // echo "<script>
                //     alert('El modulo $nombre_modulo ya existe y no se ha registrado nuevamente');
                // </script>";
            } else {
                $insertar_modulo = "INSERT INTO modulo_profesional (descripcion, nro_modulo, id_programa_estudio) VALUES ('$nombre_modulo', '$conteo_modulos', '$id_programa_estudios')";
                mysqli_query($conexion, $insertar_modulo);
                $conteo_modulos++;
                echo "<script>
                        alert('Se ha registrado el modulo $nombre_modulo');
                    </script>";
            }

        }
    }

    //------------------------------------------ CARGA DE U.D. ------------------------------------------

    for ($i = 0; $i < count($hoja2); $i++) {
        $fila = $hoja2[$i];

        if ($fila[1] != "" && ($fila[2] == "Esp" || $fila[2] == "Emp" || $fila[2] == "EF")) {

            // Buscar el id del modulo

            $nomb_mod_for = $fila[1];
            $modulos = buscarModuloFormativo($conexion);
            $id_modulo_for = 1;
            while ($row = mysqli_fetch_assoc($modulos)) {


                if ($row['descripcion'] == $nomb_mod_for) {
                    $id_modulo_for = $row['id'];
                    break;
                }
            }

            $conteo_ud = 0;

            while ($fila[2] != null) {

                $nomb_ud = $fila[4];
                // $id_prog_est = $id_programa_estudios;
                // $id_modulo = $id_modulo_for;
                $id_semestre = 1;

                for ($j = 6; $j < count($fila); $j++) {
                    if ($fila[$j] != null) {
                        $id_semestre = $j - 5;
                        break;
                    }
                }
                $creditos = $fila[20];
                $horas = $fila[16];
                $tipo = "";

                switch ($fila[2]) {
                    case "Esp":
                        $tipo = "Especialidad";
                        break;
                    case "Emp":
                        $tipo = "Empleabilidad";
                        break;
                    case "EF":
                        $tipo = "Experiencia Formativa";
                        break;
                }
                $orden = 0;
                //consulta para poder generar el orden de la ud en el semestre
                $consul = "SELECT * FROM unidad_didactica WHERE id_semestre='$id_semestre' AND id_modulo='$id_modulo_for' AND id_programa_estudio='$id_programa_estudios'";
                $ejec_consl = mysqli_query($conexion, $consul);
                $conteo = mysqli_num_rows($ejec_consl);
                $orden = $conteo + 1;

                $cod_correlativo = $fila[3];
                $cods_predcs = $fila[5];

                // Buscar si la unidad didactica ya existe en la base de datos

                $unidade_didact = buscarUdByName($conexion, $nomb_ud);
                $udExiste = false;

                while ($row = mysqli_fetch_assoc($unidade_didact)) {

                    if ($row['descripcion'] == $nomb_ud) {
                        $udExiste = true;
                        break;
                    }
                }

                if ($udExiste) {
                    // echo "<script>
                    //     alert('La unidad didactica $nomb_ud ya existe y no se ha registrado nuevamente');
                    // </script>";
                } else {
                    // Insertar los datos en la tabla unidad_didactica
                    $insertar_ud = "INSERT INTO unidad_didactica (descripcion, id_programa_estudio, id_modulo, id_semestre, creditos, horas, tipo, orden, codigo_correlativo, codigos_ud_predecesora) 
                    VALUES ('$nomb_ud', '$id_programa_estudios', '$id_modulo_for', '$id_semestre', '$creditos', '$horas', '$tipo', '$orden', '$cod_correlativo', '$cods_predcs')";
                    mysqli_query($conexion, $insertar_ud);
                    $conteo_ud++;
                }


                $i++;
                $fila = $hoja2[$i];

            }
            // echo "<script>
            //         alert('Se han registrado $conteo_ud unidades didacticas del modulo $nomb_mod_for');
            //     </script>";
        }
    }

    //---------------------------- FIN DE CARGA DE PLAN DE ESTUDIOS ------------------------------------------

    mysqli_close($conexion);
    echo "<script>
            alert('Se completo la carga del plan de estudio');
            window.history.back();
        </script>";

} else {
    echo "<script>
					alert('Se ha subido un documento que no es de tipo Excel. Porfavor suba un documento adecuado!');
					window.history.back();
				</script>
			";
}
?>