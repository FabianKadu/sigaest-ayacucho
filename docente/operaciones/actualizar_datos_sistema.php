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
} else {




	$id = 1;
	$pagina = $_POST['pagina'];
	$dominio_sistema = $_POST['dominio_sistema'];
	$favicon = $_POST['favicon'];
	$logo = $_POST['logo'];
	$titulo = $_POST['titulo'];
	$pie_pagina = $_POST['pie_pagina'];
	$host_email = $_POST['host_email'];
	$email_email = $_POST['email_email'];
	$email_soporte = $_POST['email_soporte'];
	$password_email = $_POST['password_email'];
	$puerto_email = $_POST['puerto_email'];
	$color_correo = $_POST['color_correo'];
	$ruc = $_POST['ruc'];
	$region = $_POST['region'];

	$consulta = "UPDATE sistema SET pagina='$pagina', dominio_sistema='$dominio_sistema', favicon='$favicon', logo='$logo', titulo='$titulo', pie_pagina='$pie_pagina', host_email='$host_email', email_email='$email_email', email_soporte='$email_soporte', password_email='$password_email', 	puerto_email='$puerto_email', color_correo='$color_correo', ruc='$ruc', region='$region' WHERE id=$id";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
					alert('Datos actualizados de manera Correcta');
					window.location= '../sistema.php';
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