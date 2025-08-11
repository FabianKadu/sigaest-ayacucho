<?php
include '../include/verificar_sesion_docente_operaciones.php';
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";

$id_prog = $_POST['id_prog'];
$nro_calificacion = $_POST['nro_calificacion'];


$b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
    $b_calificacion = buscarCalificacionByIdDetalleMatricula_nro($conexion, $r_b_det_mat['id'], $nro_calificacion);
while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
    $b_evaluacion = buscarEvaluacionByIdCalificacion($conexion, $r_b_calificacion['id']); 
    $count = 1;                 
    while ($r_b_evaluacion = mysqli_fetch_array($b_evaluacion)) {
        $id = $r_b_evaluacion['id'];
        $dato = $_POST['ponderad_'.$count];
        $consulta = "UPDATE evaluacion SET ponderado='$dato' WHERE id='$id'";
        $ejec_consulta = mysqli_query($conexion, $consulta);
        $count +=1;
    }
}
}


echo "<script>
			window.location= '../evaluacion.php?data=".$id_prog."&data2=".$nro_calificacion."';
		</script>
	";




?>