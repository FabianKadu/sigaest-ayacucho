<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


	$region = $_POST['region'];

	$ejec_cons = obtenerProvinciaPorRegion($conexion, $region);
	$cadena = '<option value="" disabled selected>Seleccionar</option>';

		while ($provincia=mysqli_fetch_array($ejec_cons)) {
			$cadena=$cadena.'<option value="'.$provincia['Provincia'].'">'.$provincia['Provincia'].'</option>';
		}
		echo $cadena;
?>