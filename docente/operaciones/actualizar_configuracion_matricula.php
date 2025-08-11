<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id']; // Suponiendo que `id` viene del formulario
        $periodo = $_POST['periodo'];
        $creditos = $_POST['creditos'];
        $fechaInicio = $_POST['fechaInicio'];
        $ultimoDiaMatricula = $_POST['ultimoDiaMatricula'];
    
        // Verificar si el registro ya existe
        $sql_check = "SELECT id FROM configuracion_matricula WHERE id = ?";
        $stmt_check = $conexion->prepare($sql_check);
        $stmt_check->bind_param("i", $id);
        $stmt_check->execute();
        $stmt_check->store_result();
    
        if ($stmt_check->num_rows > 0) {
            // Si existe, hacer UPDATE
            $sql = "UPDATE configuracion_matricula 
                    SET periodo = ?, creditos = ?, fecha_inicio = ?, ultimo_dia_matricula = ? 
                    WHERE id = ?";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sissi", $periodo, $creditos, $fechaInicio, $ultimoDiaMatricula, $id);
        } else {
            // Si no existe, hacer INSERT
            $sql = "INSERT INTO configuracion_matricula (periodo, creditos, fecha_inicio, ultimo_dia_matricula) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("siss", $periodo, $creditos, $fechaInicio, $ultimoDiaMatricula);
        }
    
        if ($stmt->execute()) {
            echo "<script>window.location.replace('../configuracion_matricula.php');</script>";
        } else {
            echo "<script>alert('Error al guardar la configuración de matrícula');</script>";
        }
    
        $stmt->close();
        $stmt_check->close();
        $conexion->close();
    }
}
