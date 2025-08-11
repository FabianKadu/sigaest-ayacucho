<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {



$id_detalle_mat = $_GET['data'];

//buscar detalle de matricula
$b_det_mat = buscarDetalleMatriculaById($conexion, $id_detalle_mat);
$r_b_det_mat = mysqli_fetch_array($b_det_mat);

//buscamos la matricula para obtener el id de estudiante
$b_mat = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
$r_b_mat = mysqli_fetch_array($b_mat);

$id_estudiante = $r_b_mat['id_estudiante'];

//buscar silabo
$b_silabo = buscarSilaboByIdProgramacion($conexion, $r_b_det_mat['id_programacion_ud']);
$r_b_silabo = mysqli_fetch_array($b_silabo);

//buscar programaciones de actividades del silabo para eliminar asistencias
$b_prog_act_silabo = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
while ($r_b_prog_act_silabo = mysqli_fetch_array($b_prog_act_silabo)) {
    //buscamos las sesiones que corresponden a la programacion de actividades del silabo
    $b_sesion_a = buscarSesionByIdProgramacionActividades($conexion, $r_b_prog_act_silabo['id']);
    while ($r_b_sesion_a = mysqli_fetch_array($b_sesion_a)) {
        //buscar asistencia del estudiante en la sesion para eliminar
        $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion_a['id'], $id_estudiante);
        $r_b_asistencia = mysqli_fetch_array($b_asistencia);
        $id_asitencia = $r_b_asistencia['id'];

        //eliminar asistencia
        $consulta_asis = "DELETE FROM asistencia WHERE id='$id_asitencia'";
        $ejec_d_asis = mysqli_query($conexion, $consulta_asis);
    }
}

// ---------------------- PROCESO PARA ELIMINAR DETALLE DE MATRICULA

//busco las calificaciones
$b_calificacion = buscarCalificacionByIdDetalleMatricula($conexion, $id_detalle_mat);
while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
    $id_calificacion = $r_b_calificacion['id'];
    // busco las evaluaciones
    $b_evaluacion = buscarEvaluacionByIdCalificacion($conexion, $id_calificacion);
    while ($r_b_evaluacion = mysqli_fetch_array($b_evaluacion)) {
        $id_evaluacion = $r_b_evaluacion['id'];
        //buscar criterios de evaluacion
        $b_crit_eva = buscarCriterioEvaluacionByEvaluacion($conexion, $id_evaluacion);
        while ($r_b_crit_eva = mysqli_fetch_array($b_crit_eva)) {
            $id_crit_eva = $r_b_crit_eva['id'];
            //eliminar criterio de evaluacion
            $consulta_crit = "DELETE FROM criterio_evaluacion WHERE id='$id_crit_eva'";
            $ejec_d_crit = mysqli_query($conexion, $consulta_crit);
        }
        //eliminar evaluacion
        $consulta_eva = "DELETE FROM evaluacion WHERE id='$id_evaluacion'";
        $ejec_d_eva = mysqli_query($conexion, $consulta_eva);
    }
    //eliminar calificacion
    $consulta_calif = "DELETE FROM calificaciones WHERE id='$id_calificacion'";
    $ejec_d_calif = mysqli_query($conexion, $consulta_calif);
}

//eliminar detalle de matricula
$consulta_det_mat= "DELETE FROM detalle_matricula_unidad_didactica WHERE id='$id_detalle_mat'";
$ejec_d_det_mat = mysqli_query($conexion, $consulta_det_mat);

if ($ejec_d_det_mat) {
    echo "<script>
			alert('Eliminado Correctamente');
			window.history.back();
		</script>
	";
}else {
    echo "<script>
			alert('Error, No se pudo eliminar el registro');
			window.history.back();
		</script>
	";
}



  }