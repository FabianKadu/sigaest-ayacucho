<?php
header('Content-Type: application/json');

$dni = $_GET['dni'] ?? '';

if (strlen($dni) !== 8) {
    echo json_encode(['error' => 'DNI inválido']);
    exit;
}

$response = file_get_contents("https://api.apis.net.pe/v1/dni?numero=" . $dni);

if ($response === false) {
    echo json_encode(['error' => 'No se pudo obtener la información']);
} else {
    echo $response;
}
?>
