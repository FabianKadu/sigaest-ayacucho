<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$metodosPago_postulantes = array();

$res_b_met_pag = buscarTodosMetodosPago($conexion);

while ($medios_pago = mysqli_fetch_array($res_b_met_pag)) {

    $res_b_pos_met_pag = obtenerPostulantesPorMedioDifusion($conexion, $medios_pago['Id']);

    $cantidad_postulantes = mysqli_num_rows($res_b_pos_met_pag);

    $metodosPago_postulantes[$medios_pago['Metodo']] = $cantidad_postulantes;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($metodosPago_postulantes);