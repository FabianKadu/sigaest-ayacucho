<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../include/consultas.php");

    $id = $_GET['id'];

    $sql = "SELECT * FROM oferta_documentos WHERE id = $id";
    $res = mysqli_query($conexion, $sql);

    $o_doc = mysqli_fetch_array($res);
        
    $ruta_archivo = '../'.$o_doc['url_documento'];
    // Verificar si el archivo existe antes de intentar eliminarlo
    if (file_exists($ruta_archivo)) {
            // Intentar eliminar el archivo
        if (unlink($ruta_archivo)) {
            $sql = "DELETE FROM oferta_documentos WHERE id = $id";
            $res = mysqli_query($conexion, $sql);
            echo "<script>
            alert('Se ha eliminado el documento de manera exitosa!!');
            window.history.back();
            </script>";
        } else {
            echo "<script>
            alert('No se puede eliminar, contacte con soporte.');
            window.history.back();
            </script>";
            }
    } else {
            echo "<script>
            alert('No se puede eliminar, contacte con soporte.');
            window.history.back();
            </script>";
        }
    

    // Cerrar la conexión a la base de datos
    $conexion->close();
