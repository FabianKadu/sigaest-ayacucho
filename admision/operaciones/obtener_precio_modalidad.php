<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$modalidad = $_POST['id_modalidad'];
$proceso = $_POST['id_proceso'];
$res_modalidad = buscarModalidadPorId($conexion, $modalidad);
$modalidad = mysqli_fetch_array($res_modalidad); 
$precio = $modalidad['Monto'];
$precio_extemporaneo = $modalidad['Monto_Extemporaneo'];

$res_proceso = buscarProcesoAdmisionPorId($conexion, $proceso);
$proceso = mysqli_fetch_array($res_proceso);

$es_extemporaneo = determinarEstadoAdmision($proceso['Inicio_Extemporaneo'], $proceso['Fin_Extemporaneo']);

if($es_extemporaneo === "En proceso"){
    echo $precio_extemporaneo;
}else{
    echo $precio;
}

?>