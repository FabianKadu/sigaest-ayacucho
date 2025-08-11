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

	$id_estudiante = $_POST['id_estudiante'];
	$id_actividad = $_POST['id_actividad'];
	$tipo = $_POST['tipo'];
	$nombre_cargo = $_POST['nombre_cargo'];
	$nombre_organizacion = $_POST['nombre_organizacion'];
	$lugar = $_POST['lugar'];
	$descripcion = $_POST['descripcion'];
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];

	$consulta = "UPDATE actividades_egresado SET tipo = '$tipo', nombre_cargo  = '$nombre_cargo', nombre_organizacion  = '$nombre_organizacion', lugar  = '$lugar', descripcion  = '$descripcion', fecha_inicio  = '$fecha_inicio', fecha_fin  = '$fecha_fin' WHERE id = '$id_actividad'";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
				
				window.location= '../actividades_egresado.php?id=" . $id_estudiante . "'
			</script>
		";
	} else {
		echo "<script>
				alert('Error al Actualizar Registro, por favor contacte con el administrador');
				window.history.back();
			</script>
		";
	}
	mysqli_close($conexion);

}

