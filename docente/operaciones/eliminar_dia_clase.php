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

    $id = $_GET['id'];

	$insertar = "DELETE FROM `asistencia_docente` WHERE id_horario_programacion = $id";
	$ejecutar_delete = mysqli_query($conexion, $insertar);
	if(!$ejecutar_delete){
		echo "<script>
				window.history.back();
    			</script>";
		exit;
		
	}
	$insertar = "DELETE FROM `horario_programacion` WHERE id = $id";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
				window.history.back();
    			</script>";
	}else{
		echo "<script>
			alert('Error al eliminar, contacte con personal de soporte.');
			window.history.back();
				</script>
			";
	};






mysqli_close($conexion);

}