<?php
if($_SERVER["REQUEST_METHOD"] == "POST") {
    $dni = htmlspecialchars(trim($_POST["dni"]));

    // Codigo para buscar en tu base de datos acá


    include '../../include/conexion.php';
    include "../../include/busquedas.php";
    include "../../include/funciones.php";

    $resultado = getNotasImportadaByDni($conexion, $dni);
    $estudiante = buscarEstudianteByDni($conexion,$dni);
    $estudiante = mysqli_fetch_array($estudiante);
    $estudiante = $estudiante['apellidos_nombres'];
    if ($resultado) {
        // Crear un array para almacenar las notas
        $notas = [];

        // Recorrer los resultados y agregarlos al array
        while ($fila = mysqli_fetch_assoc($resultado)) {
            $notas[] = $fila;
        }

        // Devolver las notas como respuesta en formato JSON
        echo json_encode(['notas' => $notas , 'estudiante' => $estudiante]);
    } else {
        // Si no se encontraron notas, devolver un mensaje de error
        echo json_encode(['error' => 'No se encontraron notas para el DNI proporcionado']);
    }
} else {
    echo "<p>No se encontro el nombre en la DB!!</p>";
}
?>