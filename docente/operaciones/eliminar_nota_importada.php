<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {

// Capturar el ID desde la URL
    $id_a_eliminar = $_POST['id'];


	$delete = "DELETE FROM `notas_antiguo` WHERE id = $id_a_eliminar";
	$ejecutar_delete = mysqli_query($conexion, $delete);
	if ($ejecutar_delete) {
			echo "Eliminación exitosa";
	}else{
		echo "No se pudo eliminar";
	};

mysqli_close($conexion);

}