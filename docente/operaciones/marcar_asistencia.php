<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
    $id_asistencia = $_POST['id'];

	$insertar = "UPDATE `asistencia_docente` SET `estado` = 'REALIZADO', `fecha_registro` = DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 HOUR) WHERE `id` = $id_asistencia";
	$ejecutar_insertar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insertar) {
		echo "<script>
				window.history.back();
			</script>";
	} else {
		echo "<script>
				alert('No se registrar la asistencia.');
				window.history.back();
			</script>";
	}
mysqli_close($conexion);
?>