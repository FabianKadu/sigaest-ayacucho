<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
} else {
    function realizarMatricula($conexion, $id_est, $id_periodo_acad, $carrera, $semestre, $detalle_matricula)
    {
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
            $busc_prog = buscarProgramacionByIdPeriodoAndUnidad($conexion,$id_periodo_acad , $valor);
            $res_b_prog = mysqli_fetch_array($busc_prog);
            $id_ud = $res_b_prog['id_unidad_didactica'];
            $id_programacion = $res_b_prog['id'];
            $b_cant_mat_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_programacion);
            $new_orden = mysqli_num_rows($b_cant_mat_detalle_mat) + 1;

            mysqli_query($conexion, "INSERT INTO detalle_matricula_unidad_didactica (id_matricula, orden, id_programacion_ud, recuperacion, mostrar_calificacion) 
                                     VALUES ('$id_matricula', '$new_orden', '$id_programacion', '', 0)");
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
                history.back();
              </script>";
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id']; // Suponiendo que `id` viene del formulario
        $tipo = $_POST['tipo'];
        //buscar datos de solicitud de matricula
        $res_soli = buscarSolicitudeMatriculaId($conexion, $id);
        $res_solicitud = mysqli_fetch_array($res_soli);
        $id_est = $res_solicitud['id_estudiante'];
        $id_periodo_acad = $_SESSION['periodo'];

        $res_estudiante = buscarEstudianteById($conexion, $id_est);
        $res_est = mysqli_fetch_array($res_estudiante);
        $carrera = $res_est['id_programa_estudios'];
        $semestre = $res_est['id_semestre'] + 1;

        $detalle_matricula = explode(",", $res_solicitud['programas']);


        if ($tipo == "observacion") {
            $observacion = $_POST['observacion'];
            // Si existe, hacer UPDATE
            $sql = "UPDATE solicitud_matricula SET observacion = '$observacion', estado = 2 WHERE id = $id";
            $res = mysqli_query($conexion, $sql);
            if ($res) {
                echo "<script>
                alert('Observación registrada correctamente');
                window.location.replace('../solicitudes_matricula.php');
            </script>";
            }
        }
        if ($tipo == "aceptar") {
            $sql = "UPDATE solicitud_matricula SET  estado = 3 WHERE id = $id";
            $res = mysqli_query($conexion, $sql);
            if ($res) {
                realizarMatricula($conexion, $id_est, $id_periodo_acad, $carrera, $semestre, $detalle_matricula);
                echo "<script>
                alert('Observación registrada correctamente');
                window.location.replace('../solicitudes_matricula.php');
            </script>";
            }
        }
    }
}
