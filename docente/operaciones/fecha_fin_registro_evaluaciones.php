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

function actualizar_periodo($conexion, $id_periodo, $date_evaluacion ){

    $query = "UPDATE periodo_academico SET fecha_fin_registro_evaluaciones = ? WHERE id = ?";
    $stmt = $conexion->prepare($query);
    $stmt->bind_param("si", $date_evaluacion, $id_periodo);

    if ($stmt->execute()) {
        echo "<script>
                alert('Fecha de fin de registro de evaluaciones actualizada correctamente.');
                window.history.back();
              </script>";
    } else {
        echo "<script>
                alert('Error: No se pudo actualizar la fecha de fin de registro de evaluaciones.');
                window.history.back();
              </script>";
    }

}

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id_periodo = $_POST['id_periodo'];
    $date_evaluacion = $_POST['date_evaluacion'];

    if (!$id_periodo) {
        echo "<script>
                alert('Error: ID del estudiante no proporcionado.');
                window.history.back();
              </script>";
        exit;
    }

    // Llamar a la función para eliminar al docente
    actualizar_periodo($conexion, $id_periodo, $date_evaluacion);

    // Cerrar conexión
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Solicitud no válida.');
            window.history.back();
          </script>";
}
?>