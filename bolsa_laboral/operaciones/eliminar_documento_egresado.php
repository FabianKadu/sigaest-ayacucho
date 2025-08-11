<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_administrador.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
            alert('Error: Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('../login/');
        </script>";
} else {
    if (isset($_POST['documento_id'])) {
        $documento_id = $_POST['documento_id'];

        // Consulta de eliminación
        $eliminar = "DELETE FROM documento_egresado WHERE id = '$documento_id'";

        // Ejecutar la consulta
        $ejecutar_eliminar = mysqli_query($conexion, $eliminar);
        if ($ejecutar_eliminar) {
            echo "<script>
                alert('Documento eliminada exitosamente');
                window.history.back();
            </script>";
        } else {
            echo "<script>
                alert('Error al eliminar el documento, por favor intente nuevamente');
                window.history.back();
            </script>";
        }

        mysqli_close($conexion);
    } else {
        echo "<script>
            alert('ID del documento no especificado');
            window.history.back();
        </script>";
    }
}
?>