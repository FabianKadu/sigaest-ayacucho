<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_caja.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {

// Capturar el ID desde la URL
$id_a_eliminar = $_GET['id'];


	$delete = "DELETE FROM `comprobantes_pago` WHERE id = $id_a_eliminar";
	$ejecutar_delete = mysqli_query($conexion, $delete);
	if ($ejecutar_delete) {
			echo "<script>
                alert('Eliminado Exitosamente');
                window.location= '../comprobantes.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al registrar');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}