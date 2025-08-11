<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

header('Content-Type: application/json');


if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_ud = $_POST['id_ud'];
        $periodo = $_POST['periodo'];
        $dni_estudiante = $_POST['dni'];  // Recibir el DNI del estudiante

        $query = "UPDATE notas_antiguo SET semestre_academico = ? WHERE dni = ? AND id_unidad_didactica = ?";
        $stmt = mysqli_prepare($conexion, $query);
        mysqli_stmt_bind_param($stmt, "ssi", $periodo, $dni_estudiante, $id_ud);
        mysqli_stmt_execute($stmt);

        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo "Periodo actualizado";
        } else {
            echo json_encode(array("error" => "Primero debe de registrar la calificación, luego el periodo."));
        }
    }
    mysqli_close($conexion);
}
