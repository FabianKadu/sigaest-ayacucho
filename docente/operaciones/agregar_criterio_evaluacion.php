<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador.php");
if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
} else {
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
    $r_b_docente = mysqli_fetch_array($b_docente);

    $id_prog = $_GET['data'];
    $nro_calificacion = $_GET['data2'];
    $evaluacion = $_GET['data3'];

    $b_prog = buscarProgramacionById($conexion, $id_prog);
    $res_bb_prog = mysqli_fetch_array($b_prog);

    if ($res_bb_prog['id_docente'] == $id_docente_sesion) {

        $b_det_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
        while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
            //buscamos la calificacion que correspone
            $b_calificacion = buscarCalificacionByIdDetalleMatricula_nro($conexion, $r_b_det_mat['id'], $nro_calificacion);
            $r_b_calificacion = mysqli_fetch_array($b_calificacion);
            $id_calificacion = $r_b_calificacion['id'];
            $b_evaluacion = buscarEvaluacionByIdCalificacion_detalle($conexion, $id_calificacion, $evaluacion);
            $r_b_evaluacion = mysqli_fetch_array($b_evaluacion);
            $id_evaluacion = $r_b_evaluacion['id'];
            $b_crit_evaluacion = buscarCriterioEvaluacionByEvaluacion($conexion, $id_evaluacion);

            $cant_crit = mysqli_num_rows($b_crit_evaluacion) + 1;
            //agregaremos nueva criterio de evaluacion
            $consulta = "INSERT INTO criterio_evaluacion (id_evaluacion, orden, detalle, ponderado, calificacion) VALUES ('$id_evaluacion','$cant_crit','','0','')";
            mysqli_query($conexion, $consulta);
        }
        echo "<script>
				  alert('Se agregó un criterio de evaluación');
				  window.location.replace('../evaluacion_b.php?data=" . $id_prog . "&data2=" . $nro_calificacion . "');
			  </script>";
    } else {
        echo "<script>
				  alert('Error Usted no cuenta con permiso para realizar esta acción');
				  window.history.back();
			  </script>";
    }
}
