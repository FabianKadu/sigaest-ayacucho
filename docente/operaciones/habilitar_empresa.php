<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    $id = $_POST["id"];

    // Consulta para insertar los datos en la base de datos
    $sql = "UPDATE `empresa` SET `estado`='Activo' WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        
        echo "<script>
        alert('Se ha habilitado a la empresa!!');
        window.location.replace('../empresas.php');
        </script>";
    } else {
        echo "<script>
        alert('Ops, Ocurrio un error al guardar!');
        window.history.back();
        </script>";
    }
    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>
