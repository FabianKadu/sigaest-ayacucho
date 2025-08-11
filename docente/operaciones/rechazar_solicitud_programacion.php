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

	$id_solicitud = $_POST['id_solicitud'];

	$consulta = "UPDATE solicitud_programacion_ud SET estado='Rechazado' WHERE id='$id_solicitud'";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
				window.location= '../solicitudes_unidad_didactica_adm.php';
			</script>
		";
	} else {
		echo "<script>
				alert('Error al Rechazar la solicitud');
				window.history.back();
			</script>
		";
	}
	mysqli_close($conexion);
}

