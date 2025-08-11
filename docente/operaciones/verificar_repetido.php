<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";

    $tipo = $_POST['tipo'];
    $periodo = $_POST['periodo'];

	$resultado = buscarProcesoAdmisionPorPeriodoTipo($conexion, $tipo, $periodo);
    $existe = mysqli_fetch_array($resultado);
    if ($existe['num_rows'] == 0) {
        echo "False";
    }else{
        echo "True";
    }
?>