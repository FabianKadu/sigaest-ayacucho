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
    if (isset($_POST['id_pregunta'])) {
        $id_pregunta = $_POST['id_pregunta'];

        // Consulta de eliminación
        $eliminar = "DELETE FROM preguntas_frecuentes WHERE id = '$id_pregunta'";

        // Ejecutar la consulta
        $ejecutar_eliminar = mysqli_query($conexion, $eliminar);
        if ($ejecutar_eliminar) {
            echo "<script>
                alert('Pregunta eliminada exitosamente');
                window.location= '../preguntas_frecuentes.php';
            </script>";
        } else {
            echo "<script>
                alert('Error al eliminar la pregunta, por favor intente nuevamente');
                window.history.back();
            </script>";
        }

        mysqli_close($conexion);
    } else {
        echo "<script>
            alert('ID de pregunta no especificado');
            window.history.back();
        </script>";
    }
}
?>
