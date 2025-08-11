<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_estudiante.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
} else {

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id']; // Suponiendo que `id` viene del formulario        
        $sql = "UPDATE solicitud_matricula SET  estado = 0 WHERE id = $id";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
                window.location.replace('../solicitar_matricula.php');
            </script>";
        }
    }
}
