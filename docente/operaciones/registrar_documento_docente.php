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
	$titulo = $_POST['titulo']; 

	

	// Manejo de la carga del archivo
	if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
		$ruta_destino = "../documentos_docente/";
		$nombre_archivo = basename($_FILES['documento']['name']);
		$extension = pathinfo($nombre_archivo, PATHINFO_EXTENSION);
		$ruta_completa = $ruta_destino . $titulo.'.'.$extension;
		$ruta_db =substr($ruta_completa,3);

		// Mover el archivo cargado al destino deseado
		if (move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_completa)) {
			// Preparar la consulta SQL para insertar el nuevo documento de admisión
			$insertar = "UPDATE docente SET hoja_vida='$ruta_db' WHERE id=$id_docente_sesion";

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
		}}
mysqli_close($conexion);

}