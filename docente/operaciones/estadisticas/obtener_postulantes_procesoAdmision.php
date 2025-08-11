<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";


$proceso_adm_postulantes = array();

$res_b_pro_adm = buscarTodosProcesosAdmision($conexion);

while ($res_pro_adm = mysqli_fetch_array($res_b_pro_adm)) {

    $res_b_pos_pro_adm = obtenerPostulantesPorProcesoAdmision($conexion, $res_pro_adm['Id']);

    $cantidad_postulantes = mysqli_num_rows($res_b_pos_pro_adm);
    
    $nombre_proceso = $res_pro_adm['Periodo'];
    
    if (array_key_exists($nombre_proceso, $proceso_adm_postulantes)) {

        $proceso_adm_postulantes[$nombre_proceso] += $cantidad_postulantes;;
    } else {

        $proceso_adm_postulantes[$nombre_proceso] = $cantidad_postulantes;;
    }

}


header('Content-Type: application/json; charset=utf-8');
echo json_encode($proceso_adm_postulantes);
