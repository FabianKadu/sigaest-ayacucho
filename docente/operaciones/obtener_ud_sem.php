<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$id_modulo = $_POST['id_modulo'];
$id_semestre = $_POST['id_semestre'];

	$ejec_cons = buscarUdByModSem($conexion, $id_modulo, $id_semestre);

		$cadena = '<option></option>';
		while ($mostrar=mysqli_fetch_array($ejec_cons)) {
			$cadena=$cadena.'<option value='.$mostrar['id'].'>'.$mostrar['descripcion'].'</option>';
		}
		echo $cadena;

?>