<?php
include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");

$id_programa_estudios = $_POST['id_programa_estudios'];
$unidades = buscarUdByPrograma($conexion, $id_programa_estudios);

$options = "<option value=''>Seleccione</option>";
while ($unidad = mysqli_fetch_array($unidades)) {
    $options .= "<option value='{$unidad['descripcion']}'>{$unidad['descripcion']}</option>";
}

echo $options;
?>