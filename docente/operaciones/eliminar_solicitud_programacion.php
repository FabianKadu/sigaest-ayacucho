<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../include/verificar_sesion_docente.php";

if (!verificar_sesion($conexion)) {

	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
		</script>";
} else {

	$id_solicitud = $_POST['id_solicitud'];

	$consulta = "DELETE FROM solicitud_programacion_ud WHERE id='$id_solicitud'";
	$ejec_consulta = mysqli_query($conexion, $consulta);
	if ($ejec_consulta) {
		echo "<script>
				alert('Se elimino la solicitud');
				window.location= '../solicitudes_unidad_didactica.php';
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

