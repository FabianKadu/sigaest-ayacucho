<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


	$distrito = $_POST['distrito'];
	$provincia = $_POST['provincia'];

	$ejec_cons = obtenerColegioPorProvinciaDistrito($conexion, $distrito, $provincia);
	$cadena = '<option value="" disabled selected>Seleccionar</option>';

		while ($colegio=mysqli_fetch_array($ejec_cons)) {
			$cadena=$cadena.'<option value="'.$colegio['Id'].'">'.$colegio['Nombre'].'</option>';
		}
		echo $cadena;
?>