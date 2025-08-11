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

	$pregunta = $_POST['pregunta'];
	$respuesta = $_POST['respuesta'];
	$roles = implode('-', $_POST['cargo']);

	$insertar = "INSERT INTO preguntas_frecuentes (pregunta, respuesta, roles) 
		VALUES ('$pregunta','$respuesta','$roles')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
		echo "<script>
					window.location= '../preguntas_frecuentes.php'
					</script>";
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