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

	$titulo = $_POST['titulo'];
	$descripcion = $_POST['descripcion'];
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$cargo = implode('-', $_POST['cargo']);
	$url = isset($_POST['enlace']) ? $_POST['enlace'] : "";

		$insertar = "INSERT INTO anuncio (titulo, descripcion, fecha_activa_inicio, fecha_activa_fin, enlace, usuarios, tipo) 
		VALUES ('$titulo','$descripcion','$inicio','$fin', '$url' ,'$cargo', 'ENCUESTA')";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
		if ($ejecutar_insetar) {
				echo "<script>
					window.location= '../encuesta.php'
					</script>";
		}else{
			echo "<script>
				alert('Error al registrar, por favor verifique sus datos');
				//window.history.back();
					</script>
				";
		};
	mysqli_close($conexion);
}