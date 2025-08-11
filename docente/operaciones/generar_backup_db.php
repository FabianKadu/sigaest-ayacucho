<?php
header('Content-Type: application/json');
include("../../include/conexion.php");

try {
    // Create temp directory if it doesn't exist
    if (!file_exists(__DIR__ . "/../../temp")) {
        if (!mkdir(__DIR__ . "/../../temp", 0777, true)) {
            throw new Exception("No se pudo crear la carpeta temp");
        }
    }

    // Set paths and file names
    $mysqldumpPath = $mysqldumpPath; // Agregar en conexion.php esta variable
    $backupFile = __DIR__ . "/../../temp/backup_db.sql";

    // Get credentials from conexion.php
    $user = $user_db;
    $pass = $pass_db;
    $database = $db;

    // Build command with password if exists
    $passParam = empty($pass) ? '' : "-p$pass";
    $command = "$mysqldumpPath -u $user $passParam $database > \"$backupFile\"";

    // Execute backup command
    exec($command, $output, $returnVar);

    if ($returnVar !== 0) {
        throw new Exception("Error al generar backup. Código: $returnVar");
    }

    // Return success response
    echo json_encode([
        'success' => true,
        'message' => 'Backup generado correctamente',
        'file' => basename($backupFile)
    ]);
} catch (Exception $e) {
    // Return error response 
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
