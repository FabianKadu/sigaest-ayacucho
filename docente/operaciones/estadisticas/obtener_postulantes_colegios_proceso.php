
<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$colegios_postulantes = array();

$id_proceso = $_POST['id_proceso'];
$pos_col = buscarTodosColegiosPorProceso($conexion, $id_proceso);

while ($pos_b_col = mysqli_fetch_array($pos_col)) {
    $nombre_colegio = $pos_b_col['Tipo'];
    
    if (array_key_exists($nombre_colegio, $colegios_postulantes)) {
        
        $colegios_postulantes[$nombre_colegio] += 1;
    } else {
        
        $colegios_postulantes[$nombre_colegio] = 1;
    }
}
header('Content-Type: application/json; charset=utf-8');
echo json_encode($colegios_postulantes);
