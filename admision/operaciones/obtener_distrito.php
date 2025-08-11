<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


	$region = $_POST['region'];
	$provincia = $_POST['provincia'];

	$ejec_cons = obtenerDistritoPorRegionProvincia($conexion, $region, $provincia);
	$cadena = '<option value="" disabled selected>Seleccionar</option>';

		while ($distrito=mysqli_fetch_array($ejec_cons)) {
			$cadena=$cadena.'<option value="'.$distrito['Distrito'].'">'.$distrito['Distrito'].'</option>';
		}
		echo $cadena;
?>