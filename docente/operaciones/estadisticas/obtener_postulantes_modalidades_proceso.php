
<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$modalidades_postulantes = array();
$id_proceso = $_POST['id_proceso'];
$res_b_mod = buscarTodasModalidades($conexion);


while ($modalidad = mysqli_fetch_array($res_b_mod)) {
    
    $res_b_pos_mod = obtenerPostulantesPorModalidadProceso($conexion, $modalidad['Id'], $id_proceso);
    
    $cantidad_postulantes = mysqli_num_rows($res_b_pos_mod);
    
    $modalidades_postulantes[$modalidad['Descripcion']] = $cantidad_postulantes;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($modalidades_postulantes);
