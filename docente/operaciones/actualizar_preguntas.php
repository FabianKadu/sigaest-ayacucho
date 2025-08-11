<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include ("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
				window.location.replace('../login/');
		</script>";
} else {
	$id = $_POST['id'];
	$pregunta = $_POST['pregunta'];
	$respuesta = $_POST['respuesta'];
	$roles = implode('-', $_POST['cargo']);

	$consulta = "UPDATE preguntas_frecuentes SET pregunta='$pregunta', respuesta='$respuesta', roles='$roles' WHERE id='$id'";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
				window.location= '../preguntas_frecuentes.php';
			</script>
		";
	} else {
		echo "<script>
				alert('Error al actualizar');
				window.history.back();
			</script>
		";
	}
	mysqli_close($conexion);
}

