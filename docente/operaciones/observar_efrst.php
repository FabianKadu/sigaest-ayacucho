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

	$observacion = $_POST['observacion'];
	$id = $_POST['id'];

	$update = "UPDATE efsrt SET observacion = '$observacion', estado = 3 WHERE id = '$id'";
	$ejecutar_update = mysqli_query($conexion, $update);

	if ($ejecutar_update) {
		// Obtener información del docente para la auditoría
		$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
		$b_docente = buscarDocenteById($conexion, $id_docente_sesion);
		$r_b_docente = mysqli_fetch_array($b_docente);

		// Insertar registro de auditoría
		$queryAudit = "INSERT INTO efsrt_auditoria (
				id_efsrt,
				id_usuario,
				nombre_usuario,
				accion
			) VALUES (
				'$id',
				'$id_docente_sesion',
				'" . mysqli_real_escape_string($conexion, $r_b_docente['apellidos_nombres']) . "',
				'Observación registrada en EFSRT'
			)";

		$resultAudit = mysqli_query($conexion, $queryAudit);
		if (!$resultAudit) {
			error_log("Error en auditoría EFSRT: " . mysqli_error($conexion));
		}

		echo "<script>
					window.location= '../efsrt_aperturado.php'
					</script>";
	} else {
		echo "<script>
				alert('Error al registrar, por favor verifique sus datos');
				window.history.back();
					</script>
				";
	};

	mysqli_close($conexion);
}
