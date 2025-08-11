<?php
header('Content-Type: application/json');

try {
    // Get absolute path to documentos folder
    $rootPath = realpath(__DIR__ . "/../../documentos");
    $tempPath = realpath(__DIR__ . "/../../temp");

    if (!$rootPath) {
        throw new Exception("La carpeta 'documentos' no existe en: " . __DIR__ . "/../../documentos");
    }

    // Create temp directory at root level if it doesn't exist
    if (!file_exists(__DIR__ . "/../../temp")) {
        if (!mkdir(__DIR__ . "/../../temp", 0777, true)) {
            throw new Exception("No se pudo crear la carpeta temp");
        }
    }

    $zip = new ZipArchive();
    $filename = __DIR__ . "/../../temp/backup_docs.zip";

    if ($zip->open($filename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== TRUE) {
        throw new Exception("No se pudo crear el archivo zip");
    }

    $files = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($rootPath),
        RecursiveIteratorIterator::LEAVES_ONLY
    );

    $fileCount = 0;
    foreach ($files as $name => $file) {
        if (!$file->isDir()) {
            $filePath = $file->getRealPath();
            $relativePath = substr($filePath, strlen($rootPath) + 1);
            $zip->addFile($filePath, $relativePath);
            $fileCount++;
        }
    }

    $zip->close();

    echo json_encode([
        'success' => true,
        'message' => "Backup completado. $fileCount archivos procesados.",
        'fileCount' => $fileCount
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
