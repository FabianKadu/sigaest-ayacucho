<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
				window.location.replace('../login/');
			</script>";
} else {

	$id = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
	$fecha_nac = $_POST['fecha_nac'];
	$direccion = $_POST['direccion'];
	$telefono = $_POST['telefono'];
	$email = $_POST['correo'];
	$nivel_edu = $_POST['nivel_edu'];
	$profesion = $_POST['profesion'];

	$sql = "UPDATE docente SET fecha_nac='$fecha_nac', direccion='$direccion', nivel_educacion = '$nivel_edu' , profesion = '$profesion' , correo='$email', telefono='$telefono' WHERE id=$id";
	$ejec_consulta = mysqli_query($conexion, $sql);
	if ($ejec_consulta) {
		echo "<script>
		window.location= '../mi_perfil.php';
	</script>
	";
	} else {
		echo "<script>
		alert('Error al actualizar, revise los campos ingresados.');
		window.history.back();
	</script>
	";
	}

	mysqli_close($conexion);
}