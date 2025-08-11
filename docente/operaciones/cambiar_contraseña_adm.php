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

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_docente = $_POST['id_docente'] ?? null;
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    // Validar que el ID del docente se haya recibido
    if (!$id_docente) {
        echo "<script>
                alert('Error: No se recibió el ID del docente.');
                window.history.back();
              </script>";
        exit;
    }

    // Validar que las contraseñas coincidan
    if ($new_password !== $confirm_password) {
        echo "<script>
                alert('Error: Las contraseñas no coinciden. Por favor, intente nuevamente.');
                window.history.back();
              </script>";
        exit;
    }

    // Validar longitud de la contraseña
    if (strlen($new_password) < 8) {
        echo "<script>
                alert('Error: La contraseña debe tener al menos 8 caracteres.');
                window.history.back();
              </script>";
        exit;
    }

    // Generar contraseña segura
    $password_hash = password_hash($new_password, PASSWORD_DEFAULT);

    // Actualizar la contraseña en la base de datos
    $update_query = "UPDATE docente SET password = ? WHERE id = ?";
    $stmt_update = $conexion->prepare($update_query);

    if (!$stmt_update) {
        echo "<script>
                alert('Error: No se pudo preparar la consulta. Por favor, contacte al administrador.');
              </script>";
        error_log("Error en la preparación de la consulta: " . $conexion->error);
        exit;
    }

    $stmt_update->bind_param("si", $password_hash, $id_docente);

    if ($stmt_update->execute()) {
        echo "<script>
                alert('Contraseña actualizada correctamente.');
                window.location = '../administrativo_docente.php';
              </script>";
    } else {
        echo "<script>
                alert('Error: No se pudo actualizar la contraseña. Por favor, contacte al administrador.');
                window.history.back();
              </script>";
        error_log("Error en la ejecución de la consulta: " . $stmt_update->error);
    }

    // Cerrar statement y conexión
    $stmt_update->close();
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Solicitud no válida.');
            window.history.back();
          </script>";
}
?>
