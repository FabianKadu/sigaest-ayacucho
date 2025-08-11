<?php
include("../../include/conexion.php");

header('Content-Type: application/json');

try {
    // Get JSON input
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!$data || !isset($data['registro'])) {
        throw new Exception('Datos inválidos');
    }

    $registro = $data['registro'];
    $es_permiso = isset($registro['es_permiso']) ? $registro['es_permiso'] : false;

    // Verify DNI exists in docente table and get docente_id
    $sql_verify = "SELECT id FROM docente WHERE dni = ?";
    $stmt = mysqli_prepare($conexion, $sql_verify);
    mysqli_stmt_bind_param($stmt, "s", $registro['dni']);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 0) {
        throw new Exception('DNI no encontrado en la base de datos de docentes');
    }

    // Get docente_id
    $row = mysqli_fetch_assoc($result);
    $docente_id = $row['id'];

    // Get current date and time in correct format
    $fecha_actual = date('Y-m-d');
    $hora_actual = date('H:i:s');

    if ($es_permiso) {
        // Si es permiso, no necesitamos procesar imagen
        $sql_insert = "INSERT INTO asistencia_administrativo (fecha_asistencia, hora_asistencia, docente_id, permiso) 
                       VALUES (?, ?, ?, 1)";
        $stmt = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt, "ssi", $fecha_actual, $hora_actual, $docente_id);
    } else {
        // Process and save image
        $upload_dir = "../../documentos/asistencia";
        if (!file_exists($upload_dir)) {
            if (!mkdir($upload_dir, 0777, true)) {
                throw new Exception('No se pudo crear el directorio para las fotos');
            }
        }

        $image_parts = explode(";base64,", $registro['foto_url']);
        $image_base64 = base64_decode($image_parts[1]);
        $file_name = "asistencia_" . $registro['dni'] . "_" . date('Y-m-d_His') . ".jpg";
        $file_path = $upload_dir . "/" . $file_name;

        if (!file_put_contents($file_path, $image_base64)) {
            throw new Exception('Error al guardar la imagen');
        }

        // Save attendance record with docente_id and foto_url
        $sql_insert = "INSERT INTO asistencia_administrativo (fecha_asistencia, hora_asistencia, docente_id, foto_url, permiso) 
                       VALUES (?, ?, ?, ?, 0)";
        $stmt = mysqli_prepare($conexion, $sql_insert);
        mysqli_stmt_bind_param($stmt, "ssis", $fecha_actual, $hora_actual, $docente_id, $file_name);
    }

    if (!mysqli_stmt_execute($stmt)) {
        // If insert fails and we have an image, delete it
        if (!$es_permiso && file_exists($file_path)) {
            unlink($file_path);
        }
        throw new Exception('Error al registrar la asistencia en la base de datos: ' . mysqli_error($conexion));
    }

    echo json_encode([
        'success' => true,
        'message' => $es_permiso ? 'Permiso registrado correctamente' : 'Asistencia registrada correctamente',
        'foto_url' => $es_permiso ? null : $file_name
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

mysqli_close($conexion);
