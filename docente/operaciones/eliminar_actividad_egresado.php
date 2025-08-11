<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include ("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
              alert('Error: Usted no cuenta con permiso para acceder a esta página');
              window.location.replace('../login/');
          </script>";
} else {
    if (isset($_POST['id_actividad'])) {
        $id_actividad = $_POST['id_actividad'];
        $id_estudiante = $_POST['id_estudiante']; 

        // Consulta de eliminación
        $eliminar = "DELETE FROM actividades_egresado WHERE id = '$id_actividad'";

        // Ejecutar la consulta
        $ejecutar_eliminar = mysqli_query($conexion, $eliminar);
        if ($ejecutar_eliminar) {
            echo "<script>
                alert('Actividad eliminada exitosamente');
                window.location= '../seguimiento_egresado.php?id=" . $id_estudiante . "';
            </script>";
        } else {
            echo "<script>
                alert('Error al eliminar la actividad, por favor intente nuevamente');
                window.history.back();
            </script>";
        }

        mysqli_close($conexion);
    } else {
        echo "<script>
            alert('ID de actividad no especificado');
            window.history.back();
        </script>";
    }
}
?>
