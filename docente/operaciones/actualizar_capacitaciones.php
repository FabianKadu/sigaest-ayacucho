<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
	echo "<script>
              alert('Error: Usted no cuenta con permiso para acceder a esta página');
              window.location.replace('../login/');
          </script>";
} else {
	// Obtener los datos del formulario
	$id = (int) $_POST['id']; // ID del registro que se actualizará
	$tema = mysqli_real_escape_string($conexion, $_POST['tema']);
	$descripcion = mysqli_real_escape_string($conexion, $_POST['descripcion']);
	$fecha = mysqli_real_escape_string($conexion, $_POST['fecha']);
	$hora = mysqli_real_escape_string($conexion, $_POST['hora']);
	$unidad = mysqli_real_escape_string($conexion, $_POST['unidad']);
	$duracion = (int) $_POST['duracion'];
	$ponentes = mysqli_real_escape_string($conexion, $_POST['ponentes']);
	$cargo = isset($_POST['cargo']) && is_array($_POST['cargo'])
		? implode('-', $_POST['cargo'])
		: mysqli_real_escape_string($conexion, $_POST['cargo']);
	$enlace = isset($_POST['enlace'])
		? mysqli_real_escape_string($conexion, $_POST['enlace'])
		: "";

	// Convertir duración a minutos si la unidad es "horas"
	if ($unidad == "horas") $duracion *= 60;

	// Preparar la consulta SQL para actualizar los datos
	$actualizar = "UPDATE capacitaciones 
                   SET tema = '$tema',
                       descripcion = '$descripcion',
                       fecha = '$fecha',
                       hora = '$hora',
                       duracion = $duracion,
                       enlace = '$enlace',
                       usuarios = '$cargo',
					   ponentes = '$ponentes'
                   WHERE id = $id";

	// Ejecutar la consulta
	$ejecutar_actualizar = mysqli_query($conexion, $actualizar);

	if ($ejecutar_actualizar) {
		echo "<script>
                  alert('Datos actualizados correctamente');
                  window.location = '../capacitaciones.php';
              </script>";
	} else {
		die("Error al actualizar los datos: " . mysqli_error($conexion));
	}

	// Cerrar conexión
	mysqli_close($conexion);
}
