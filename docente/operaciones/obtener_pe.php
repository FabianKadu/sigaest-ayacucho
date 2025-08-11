<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$id_est = $_POST['id'];

	$ejec_cons = buscarCarrerasById($conexion, $id_est);

		$cadena = '';
		$id_programa = 0;
		while ($mostrar=mysqli_fetch_array($ejec_cons)) {
		    $id_programa = intval($mostrar['id']);
			$cadena=$cadena.'<option value='.$mostrar['id'].' >'. $mostrar['nombre'].' - '.$mostrar['plan_estudio'].'</option>';
		}
		echo $cadena;

?>