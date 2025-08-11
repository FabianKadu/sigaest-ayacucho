<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

// Verificar sesión
if (!verificar_sesion($conexion)) {
    echo "<script>
            alert('Error: Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('../login/');
          </script>";
    exit;
}

// Función para eliminar un docente
function eliminar_estudiante($conexion, $id_estudiante) {
    // Verificar si el docente existe antes de eliminar
    $query_verificar = "SELECT * FROM estudiante WHERE id = ?";
    $stmt_verificar = $conexion->prepare($query_verificar);
    $stmt_verificar->bind_param("i", $id_estudiante);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows === 0) {
        echo "<script>
                alert('Error: No se encontró al estudiante con el ID proporcionado.');
                window.history.back();
              </script>";
        return false;
    }

    // Eliminar el docente
    $query_eliminar = "DELETE FROM estudiante WHERE id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    $stmt_eliminar->bind_param("i", $id_estudiante);

    if ($stmt_eliminar->execute()) {
        echo "<script>
                alert('Estudiante eliminado correctamente.');
                window.history.back();
              </script>";
        return true;
    } else {
        echo "<script>
                alert('Error: No se puede eliminar al estudiante una vez que tenga datos asociados en otras tablas');
                window.history.back();
              </script>";
        return false;
    }
}

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_estudiante = $_POST['user_id'] ?? null;

    if (!$id_estudiante) {
        echo "<script>
                alert('Error: ID del estudiante no proporcionado.');
                window.history.back();
              </script>";
        exit;
    }

    // Llamar a la función para eliminar al docente
    eliminar_estudiante($conexion, $id_estudiante);

    // Cerrar conexión
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Solicitud no válida.');
            window.history.back();
          </script>";
}
?>