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
	$id = $_POST['id'];
	$titulo = $_POST['titulo'];
	$descripcion = $_POST['descripcion'];
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$cargo = implode('-', $_POST['cargo']);
	$url = isset($_POST['enlace']) ? $_POST['enlace'] : "";
	$tipo = "ENCUESTA";

	$consulta = "UPDATE anuncio SET titulo='$titulo', descripcion='$descripcion', fecha_activa_inicio='$inicio', fecha_activa_fin='$fin', enlace='$url', usuarios='$cargo' ,tipo = '$tipo' WHERE id=$id";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
				window.location= '../encuesta.php';
			</script>
		";
	}else {
		echo "<script>
				alert('Error al actualizar');
				window.history.back();
			</script>
		";
	}
mysqli_close($conexion);
}

