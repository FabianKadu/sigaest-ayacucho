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
function eliminar_docente($conexion, $id_docente) {
    // Verificar si el docente existe antes de eliminar
    $query_verificar = "SELECT * FROM docente WHERE id = ?";
    $stmt_verificar = $conexion->prepare($query_verificar);
    $stmt_verificar->bind_param("i", $id_docente);
    $stmt_verificar->execute();
    $result_verificar = $stmt_verificar->get_result();

    if ($result_verificar->num_rows === 0) {
        echo "<script>
                alert('Error: No se encontró el docente con el ID proporcionado.');
                window.history.back();
              </script>";
        return false;
    }

    // Eliminar el docente
    $query_eliminar = "DELETE FROM docente WHERE id = ?";
    $stmt_eliminar = $conexion->prepare($query_eliminar);
    $stmt_eliminar->bind_param("i", $id_docente);

    if ($stmt_eliminar->execute()) {
        echo "<script>
                alert('Docente eliminado correctamente.');
                window.location = '../administrativo_docente.php';
              </script>";
        return true;
    } else {
        echo "<script>
                alert('Error: No se puede eliminar al docente una vez que tenga datos asociados en otras tablas');
                window.history.back();
              </script>";
        return false;
    }
}

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_docente = $_POST['user_id'] ?? null;

    if (!$id_docente) {
        echo "<script>
                alert('Error: ID del docente no proporcionado.');
                window.history.back();
              </script>";
        exit;
    }

    // Llamar a la función para eliminar al docente
    eliminar_docente($conexion, $id_docente);

    // Cerrar conexión
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Solicitud no válida.');
            window.history.back();
          </script>";
}
?>