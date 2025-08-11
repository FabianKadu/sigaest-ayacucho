<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$proceso = $_POST['id_proceso'];
$modalidad = $_POST['id_modalidad'];
$programa_elegido = $_POST['id_programa'];

$res_proceso = buscarProcesoAdmisionPorId($conexion, $proceso);
$proceso_ad = mysqli_fetch_array($res_proceso); 
$ejec_cons = buscarProgramaPorProcesoModalidad($conexion, $proceso_ad['Periodo'], $modalidad);
$cadena = '<option value="" disabled selected>Seleccionar</option>';
while ($programa=mysqli_fetch_array($ejec_cons)) {
	if($programa['id'] == $programa_elegido){

	}else{
		$res_programa_ajustes = buscarAjustesSegundaOpcionPorProcesoPrograma($conexion, $proceso, $programa['id']);
		$programa_ajustes = mysqli_fetch_array($res_programa_ajustes);

		if($programa_ajustes['Estado'] === '1'){
			$cadena=$cadena.'<option value="'.$programa['id'].'">'.$programa['nombre'].'</option>';
		}
	}
}
echo $cadena;

?>