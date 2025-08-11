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
        $periodo = $_POST['periodo'];
        $creditos = $_POST['creditos'];
        $fechaInicio = $_POST['fechaInicio'];
        $ultimoDiaMatricula = $_POST['ultimoDiaMatricula'];

        $sql = "UPDATE configuracion_matricula SET periodo = ?, creditos = ?, fecha_inicio = ?, ultimo_dia_matricula = ? WHERE id = ?";
        
        if ($stmt = $conexion->prepare($sql)) {
            $stmt->bind_param("siss", $periodo, $creditos, $fechaInicio, $ultimoDiaMatricula, $id);
            if ($stmt->execute()) {
                echo "
                <script>
                    window.location.replace('../configuracion_matricula.php');
                </script>
                ";
            } else {
                echo "
                <script>
                    alert('Error al guardar la configuración de matrícula');
                    window.location.replace('../configuracion_matricula.php');
                </script>
                ";
            }
            $stmt->close();
        } else {
            echo "
                <script>
                    window.location.replace('../configuracion_matricula.php');
                </script>
                ";
        }

        $conn->close();
    }


    // Cerrar la conexión a la base de datos
    $conexion->close();
}
