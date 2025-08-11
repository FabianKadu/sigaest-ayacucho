<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {
	
	$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

	// Manejo de la carga del archivo
	if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == 0) {
		$ruta_destino = "../documentos_docente/";
		$nombre_archivo = basename($_FILES['fotografia']['name']);
		$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
		$ruta_completa = $ruta_destino .' Fotografia_'.$id_docente_sesion.'.'.$extension;
		$ruta_db =substr($ruta_completa,3);
		
		// Mover el archivo cargado al destino deseado
		if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $ruta_completa)) {
			// Preparar la consulta SQL para insertar el nuevo documento de admisión
			$insertar = "UPDATE `docente`
            SET `foto` = '$ruta_db'
            WHERE `id` = $id_docente_sesion";

			$ejecutar_insertar = mysqli_query($conexion, $insertar);

			if ($ejecutar_insertar) {
				echo "<script>
						window.history.back();
					</script>";
			} else {
				echo "<script>
						alert('No se pudo cambiar la foto, intente con otro archivo.');
						window.history.back();
					</script>";
			}
		}}
mysqli_close($conexion);

}