<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {
	
	$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
	$b_perido = buscarPeriodoAcadById($conexion, $_SESSION['periodo']);
	$periodo = mysqli_fetch_array($b_perido);
	$id_documento = $_POST['docId']; 
	$res_documento = buscarDocumentosDocentePorId($conexion, $id_documento);
	$documento = mysqli_fetch_array($res_documento);
	$nombre_documento = $documento['archivo'];

	if (unlink('../'.$nombre_documento)) {
		$insertar = "DELETE FROM documento_docente WHERE id = $id_documento";
		$ejecutar_insertar = mysqli_query($conexion, $insertar);
		if ($ejecutar_insertar) {
			echo "<script>
					alert('Documento registrado exitosamente');
					window.history.back();
				</script>";
		} else {
			echo "<script>
					alert('Error al actualizar el documento, intentelo luego.');
					window.history.back();
				</script>";
		}
	}
	mysqli_close($conexion);

}