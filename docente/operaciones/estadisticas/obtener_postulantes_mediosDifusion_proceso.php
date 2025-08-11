<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$medios_postulantes = array();
$id_proceso = $_POST['id_proceso'];
$pos_MedDif = obtenerPostulantesPorProcesoAdmision($conexion, $id_proceso);

while ($pos_b_MedDif = mysqli_fetch_array($pos_MedDif)) {
    $nombre_Medio_Difusion = $pos_b_MedDif['Medio_Difusion'];

    if (array_key_exists($nombre_Medio_Difusion, $medios_postulantes)) {

        $medios_postulantes[$nombre_Medio_Difusion] += 1;
    } else {

        $medios_postulantes[$nombre_Medio_Difusion] = 1;
    }
}

header('Content-Type: application/json; charset=utf-8');
echo json_encode($medios_postulantes);
