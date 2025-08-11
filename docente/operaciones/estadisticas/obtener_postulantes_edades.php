<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";


function calcularEdad($fecha_nacimiento)
{
    $fecha_actual = new DateTime();
    $fecha_nacimiento = new DateTime($fecha_nacimiento);
    $intervalo = $fecha_actual->diff($fecha_nacimiento);
    return $intervalo->y;

}

$edades_postulantes = array();

$pos_edad = buscarTodosPostulantes($conexion);

while ($pos_b_edad = mysqli_fetch_array($pos_edad)) {
    $fecha_nacimiento = $pos_b_edad['Fecha_Nacimiento'];
    $edad = calcularEdad($fecha_nacimiento);
    $edad_str = $edad; 

    if (array_key_exists($edad_str, $edades_postulantes)) {
        $edades_postulantes[$edad_str] += 1;
    } else {
        $edades_postulantes[$edad_str] = 1;
    }
}
ksort($edades_postulantes);

header('Content-Type: application/json; charset=utf-8');
echo json_encode($edades_postulantes);


