<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
              alert('Error: Usted no cuenta con permiso para acceder a esta página');
              window.location.replace('../login/');
          </script>";
    exit;
}

function realizarMatricula($conexion, $id_est, $id_periodo_acad, $carrera, $semestre, $detalle_matricula) {
    $hoy = date("Y-m-d");

    // Verificar si el estudiante ya está matriculado en este período
    $busc_matricula = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $id_periodo_acad);
    if (mysqli_num_rows($busc_matricula) > 0) {
        echo "<script>
                alert('El estudiante ya está matriculado en este período académico');
                window.history.back();
              </script>";
        exit;
    }

    // Registrar la matrícula
    $reg_matricula = "INSERT INTO matricula (id_periodo_acad, id_programa_estudio, id_semestre, id_estudiante, licencia, fecha_reg) 
                      VALUES ('$id_periodo_acad', '$carrera', '$semestre', '$id_est', '', '$hoy')";
    mysqli_query($conexion, $reg_matricula);
    $id_matricula = mysqli_insert_id($conexion);

    // Actualizar semestre del estudiante
    mysqli_query($conexion, "UPDATE estudiante SET id_semestre='$semestre' WHERE id='$id_est'");

    // Procesar detalle de la matrícula
    foreach ($detalle_matricula as $valor) {
        $busc_prog = buscarProgramacionById($conexion, $valor);
        $res_b_prog = mysqli_fetch_array($busc_prog);
        $id_ud = $res_b_prog['id_unidad_didactica'];

        $b_cant_mat_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $valor);
        $new_orden = mysqli_num_rows($b_cant_mat_detalle_mat) + 1;

        mysqli_query($conexion, "INSERT INTO detalle_matricula_unidad_didactica (id_matricula, orden, id_programacion_ud, recuperacion, mostrar_calificacion) 
                                 VALUES ('$id_matricula', '$new_orden', '$valor', '', 0)");
        $id_detalle_matricula = mysqli_insert_id($conexion);

        $cont_ind = 0;
        $busc_capacidad_p = buscarCapacidadesByIdUd($conexion, $id_ud);
        while ($res_b_capacidad_p = mysqli_fetch_array($busc_capacidad_p)) {
            $b_indicador_p = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $res_b_capacidad_p['id']);
            while ($res_b_capacidad_p = mysqli_fetch_array($b_indicador_p)) {
                $cont_ind++;
            }
        }

        $orden = 1;
        $busc_capacidad = buscarCapacidadesByIdUd($conexion, $id_ud);
        while ($res_b_capacidad = mysqli_fetch_array($busc_capacidad)) {
            $id_capacidad = $res_b_capacidad['id'];
            $b_indicador = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_capacidad);
            while ($res_b_capacidad = mysqli_fetch_array($b_indicador)) {
                $ponderado_calificaciones = round(100 / $cont_ind);
                mysqli_query($conexion, "INSERT INTO calificaciones (id_detalle_matricula, nro_calificacion, ponderado, mostrar_calificacion) 
                                         VALUES ('$id_detalle_matricula', '$orden', '$ponderado_calificaciones', 0)");
                $id_calificacion = mysqli_insert_id($conexion);

                $ponderado_evaluacion = round(100 / 3);
                for ($i = 1; $i <= 3; $i++) {
                    $det_eva = ($i == 1) ? "Conceptual" : (($i == 2) ? "Procedimental" : "Actitudinal");
                    mysqli_query($conexion, "INSERT INTO evaluacion (id_calificacion, detalle, ponderado) 
                                             VALUES ('$id_calificacion', '$det_eva', '$ponderado_evaluacion')");
                }
                $orden++;
            }
        }
    }
    echo "<script>
            alert('Matrícula Exitosa');
            window.location= '../matricula.php';
          </script>";
}

// Obtener datos del formulario y ejecutar la función
$id_periodo_acad = $_SESSION['periodo'];
$id_est = $_POST['id_est'];
$carrera = $_POST['carrera_m'];
$semestre = $_POST['semestre'];
$detalle_matricula = explode(",", $_POST['mat_relacion']);

realizarMatricula($conexion, $id_est, $id_periodo_acad, $carrera, $semestre, $detalle_matricula);
