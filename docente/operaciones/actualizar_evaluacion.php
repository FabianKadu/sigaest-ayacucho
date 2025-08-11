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

$id_evav= $_GET['id'];
$id_prog = $_GET['id_prog'];
$nro_calificacion = $_GET['ncalif'];
$peso_eva = $_GET['peso_eva'];

$b_eva = buscarEvaluacionById($conexion, $id_evav);
$r_b_eva = mysqli_fetch_array($b_eva);

$b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
    $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
    $r_b_mat = mysqli_fetch_array($b_matricula);
    $b_estudiante = buscarEstudianteById($conexion,$r_b_mat['id_estudiante']);
    $r_b_est = mysqli_fetch_array($b_estudiante);

    $b_calificacion = buscarCalificacionByIdDetalleMatricula_nro($conexion, $r_b_det_mat['id'], $nro_calificacion);
    while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
        
        $b_evaluacion = buscarEvaluacionByIdCalificacion_detalle($conexion, $r_b_calificacion['id'], $r_b_eva['detalle']);
        while ($r_b_evaluacion = mysqli_fetch_array($b_evaluacion)) {
            $id_eevv = $r_b_evaluacion['id'];
            $consulta = "UPDATE evaluacion SET ponderado='$peso_eva' WHERE id='$id_eevv'";
            $ejec_consulta = mysqli_query($conexion, $consulta);
            
        }
    }
    
}

echo "<script>
			window.location= '../evaluacion_b.php?data=".$id_prog."&data2=".$nro_calificacion."';
		</script>
	"; 
}