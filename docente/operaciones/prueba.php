<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$arr_uds = $_POST['datos'];
$cadena = '';
	foreach ($arr_uds as $id) {
		$ejec_cons = buscarUdById($conexion, $id);
		$mostrar=mysqli_fetch_array($ejec_cons);
		$cadena=$cadena.'<div class="checkbox"><label><input type="checkbox" name="uds_matri" value="'.$id.'" onchange="gen_arr_mat();" checked>'.$mostrar['descripcion'].'</label></div>';
	}
		echo $cadena;

?>