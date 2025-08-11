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

	if ($unidad == "horas") $duracion *= 60;

	$insertar = "INSERT INTO capacitaciones (tema, descripcion, fecha, hora, duracion, enlace, usuarios, ponentes) 
                 VALUES ('$tema', '$descripcion', '$fecha', '$hora', $duracion, '$enlace', '$cargo', '$ponentes')";

	$ejecutar_insetar = mysqli_query($conexion, $insertar);

	if ($ejecutar_insetar) {
		echo "<script>
                  window.location = '../capacitaciones.php';
              </script>";
	} else {
		die("Error al registrar los datos: " . mysqli_error($conexion));
	}

	mysqli_close($conexion);
}
