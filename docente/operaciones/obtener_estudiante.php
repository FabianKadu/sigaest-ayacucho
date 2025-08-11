<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $NIT = htmlspecialchars(trim($_POST["dni_est"]));

    // Codigo para buscar en tu base de datos acá


    include '../../include/conexion.php';
    include "../../include/busquedas.php";
    include "../../include/funciones.php";

    $resultado = buscarEstudianteByDni($conexion,$NIT);
    $dato = mysqli_fetch_array($resultado);
    $cont = mysqli_num_rows($resultado);


$id_est = $dato['id'];
$nombre = $dato['apellidos_nombres'];
$pe = $dato['id_programa_estudios'];
$sem = $dato['id_semestre'];

if ($cont>0) {
    echo json_encode([
        'id_est' => $id_est,
        'nombre' => $nombre,
        'pe'    => $pe,
        'sem'    => $sem
     ]);
}else{
    echo json_encode([
        'nombre' => "",
        'pe'    => "",
        'sem'    => ""
     ]);
     // verificar codigo  ALERT--------------------------------------
}







} else {
    echo "<p>No se encontro el nombre en la DB!!</p>";
}
?>