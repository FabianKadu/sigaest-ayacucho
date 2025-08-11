
<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$modalidades_postulantes = array();


$res_b_mod = buscarTodasModalidades($conexion);


while ($modalidad = mysqli_fetch_array($res_b_mod)) {
    
    $res_b_pos_mod = obtenerPostulantesPorModalidad($conexion, $modalidad['Id']);
    
    $cantidad_postulantes = mysqli_num_rows($res_b_pos_mod);
    
    $modalidades_postulantes[$modalidad['Descripcion']] = $cantidad_postulantes;
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($modalidades_postulantes);
