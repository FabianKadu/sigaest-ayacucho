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
	$tipo = $_POST['tipo'];
	$nombre_cargo = $_POST['nombre_cargo'];
	$nombre_organizacion = $_POST['nombre_organizacion'];
	$lugar = $_POST['lugar'];
	$descripcion = $_POST['descripcion'];
	$fecha_inicio = $_POST['fecha_inicio'];
	$fecha_fin = $_POST['fecha_fin'];

	$insertar = "INSERT INTO  actividades_egresado(id_estudiante, tipo, nombre_cargo, nombre_organizacion, lugar, descripcion, fecha_inicio, fecha_fin)
	VALUES ('$id_estudiante', '$tipo', '$nombre_cargo', '$nombre_organizacion', '$lugar', '$descripcion', '$fecha_inicio', '$fecha_fin')";

	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
		echo "<script>
			alert('Actividad registrada exitosamente');
			window.location= '../seguimiento_egresado.php?id=" . $id_estudiante . "'</script>";
	} else {
		echo "<script>
			alert('Error al registrar, por favor verifique sus datos');
			//window.history.back();
				</script>
			";
	}
	;

	mysqli_close($conexion);

}