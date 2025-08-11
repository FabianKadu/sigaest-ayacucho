
<?php
include '../../../include/conexion.php';
include "../../../include/busquedas.php";
include "../../../include/funciones.php";

$programas_postulantes = array();

// Obtener todos los programas de estudio
$res_b_prog = obtenerTodoProgramaEstudios($conexion);

// Iterar sobre los resultados y obtener la cantidad de postulantes por programa de estudio
while ($programa = mysqli_fetch_array($res_b_prog)) {
    // Obtener la cantidad de postulantes por programa de estudio
    $res_b_pos_prog = obtenerPostulantesPorProgramaEstudios($conexion, $programa['id']);
    
    // Contar la cantidad de filas (postulantes) devueltas por la consulta
    $cantidad_postulantes = mysqli_num_rows($res_b_pos_prog);
    
    // Almacenar el nombre del programa de estudio y la cantidad de postulantes en el objeto
    $programas_postulantes[$programa['nombre']] = $cantidad_postulantes;
}

// Mostrar el objeto con los datos
header('Content-Type: application/json; charset=utf-8');
echo json_encode($programas_postulantes);
