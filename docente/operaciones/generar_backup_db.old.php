<?php
header('Content-Type: application/json');
include("../../include/conexion.php");

try {
    // Create temp directory at root level if it doesn't exist
    if (!file_exists(__DIR__ . "/../../temp")) {
        if (!mkdir(__DIR__ . "/../../temp", 0777, true)) {
            throw new Exception("No se pudo crear la carpeta temp");
        }
    }

    $tables = array();
    $result = mysqli_query($conexion, "SHOW TABLES");
    if ($result === false) {
        throw new Exception("Error al obtener las tablas: " . mysqli_error($conexion));
    }

    while ($row = mysqli_fetch_row($result)) {
        $tables[] = $row[0];
    }

    $return = '';
    foreach ($tables as $table) {
        $result = mysqli_query($conexion, "SELECT * FROM " . $table);
        if ($result === false) {
            throw new Exception("Error al obtener los datos de la tabla $table: " . mysqli_error($conexion));
        }

        $num_fields = mysqli_num_fields($result);

        $return .= 'DROP TABLE IF EXISTS ' . $table . ';';
        $row2 = mysqli_fetch_row(mysqli_query($conexion, 'SHOW CREATE TABLE ' . $table));
        if ($row2 === false) {
            throw new Exception("Error al obtener la estructura de la tabla $table: " . mysqli_error($conexion));
        }

        $return .= "\n\n" . $row2[1] . ";\n\n";

        while ($row = mysqli_fetch_row($result)) {
            $return .= 'INSERT INTO ' . $table . ' VALUES(';
            for ($j = 0; $j < $num_fields; $j++) {
                $row[$j] = addslashes($row[$j]);
                $row[$j] = str_replace("\n", "\\n", $row[$j]);
                if (isset($row[$j])) {
                    $return .= '"' . $row[$j] . '"';
                } else {
                    $return .= '""';
                }
                if ($j < ($num_fields - 1)) {
                    $return .= ',';
                }
            }
            $return .= ");\n";
        }
        $return .= "\n\n";
    }

    $filename = __DIR__ . "/../../temp/backup_db.sql";
    if (file_put_contents($filename, $return) === false) {
        throw new Exception("No se pudo escribir el archivo de backup");
    }

    echo json_encode([
        'success' => true,
        'message' => 'Backup de base de datos completado exitosamente'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
