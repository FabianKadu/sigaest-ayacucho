<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$proceso = $_POST['id_proceso'];
$modalidad = $_POST['id_modalidad'];
$res_proceso = buscarProcesoAdmisionPorId($conexion, $proceso);
$proceso_ad = mysqli_fetch_array($res_proceso); 
$ejec_cons = buscarProgramaPorProcesoModalidad($conexion, $proceso_ad['Periodo'], $modalidad);
$cadena = '<option value="" disabled selected>Seleccionar</option>';
while ($programa=mysqli_fetch_array($ejec_cons)) {
	$cadena=$cadena.'<option value="'.$programa['id'].'">'.$programa['nombre'].'</option>';
}
echo $cadena;

?>