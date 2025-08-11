<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('../index.php');
              </script>";
  }else {


$id_detalle = $_GET['id'];
$id_prog = $_GET['id_prog'];
$nro_calificacion = $_GET['ncalif'];
$detalle_eva = $_GET['detalle_eva'];
$detalle_crit = $_GET['detalle_crit'];
$orden_crit = $_GET['orden_crit'];
$peso_crit = $_GET['peso_crit'];



$b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
    $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
    $r_b_mat = mysqli_fetch_array($b_matricula);
    $b_estudiante = buscarEstudianteById($conexion,$r_b_mat['id_estudiante']);
    $r_b_est = mysqli_fetch_array($b_estudiante);

    $b_calificacion = buscarCalificacionByIdDetalleMatricula_nro($conexion, $r_b_det_mat['id'], $nro_calificacion);
    while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
        $b_evaluacion = buscarEvaluacionByIdCalificacion_detalle($conexion, $r_b_calificacion['id'], $detalle_eva);
        while ($r_b_evaluacion = mysqli_fetch_array($b_evaluacion)) {
            $b_criterio_evaluacion = buscarCriterioEvaluacionByEvaluacionOrden($conexion, $r_b_evaluacion['id'], $orden_crit);
            while ($r_b_criterio_evaluacion = mysqli_fetch_array($b_criterio_evaluacion)) {
                $id_crit = $r_b_criterio_evaluacion['id'];
                $consulta = "UPDATE criterio_evaluacion SET detalle='$detalle_crit', ponderado='$peso_crit' WHERE id='$id_crit'";
                $ejec_consulta = mysqli_query($conexion, $consulta);
            }
        }
    }
    
}

echo "<script>
			window.location= '../evaluacion_b.php?data=".$id_prog."&data2=".$nro_calificacion."';
		</script>
	"; 

}