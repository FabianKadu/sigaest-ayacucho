<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../include/consultas.php");
include("../include/verificar_sesion_empresa.php");
include("../operaciones/sesiones.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('login/');
    		</script>";
} else {

    $id = $_POST['id'];

    $sql = "SELECT * FROM oferta_documentos WHERE id_ol = $id";
    $res = mysqli_query($conexion, $sql);

    while ($o_doc = mysqli_fetch_array($res)){
        
        $ruta_archivo = '../'.$o_doc['url_documento'];
        // Verificar si el archivo existe antes de intentar eliminarlo
        if (file_exists($ruta_archivo)) {
            // Intentar eliminar el archivo
            if (unlink($ruta_archivo)) {
                $error = false;
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
    }

    $sql = "SELECT * FROM oferta_postulantes WHERE id_ol = $id";
    $res = mysqli_query($conexion, $sql);

    while ($o_post = mysqli_fetch_array($res)){
        
        $ruta_archivo = '../'.$o_post['url_documento'];
        // Verificar si el archivo existe antes de intentar eliminarlo
        if (file_exists($ruta_archivo)) {
            // Intentar eliminar el archivo
            if (unlink($ruta_archivo)) {
                $error = false;
            } else {
                $error = true;
            }
        } else {
            $error = true;
        }
    }
        
    echo "<script>
    alert('Se ha eliminado los documentos de manera exitosa!!');
    window.history.back();
    </script>";
    
    // Cerrar la conexión a la base de datos
    $conexion->close();
}
