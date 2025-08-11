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
	// Suponiendo que ya tienes una conexión a la base de datos establecida en $conexion

	
	$id_documento = $_POST['id']; 
	$descripcion = $_POST['descripcion'];
	$tipo = $_POST['tipo'];
	$estado = $_POST['estado'];

	$nombre_archivo = null;

	// Verificar si se intenta cargar un nuevo archivo
	if (isset($_FILES['carga_documento']) && $_FILES['carga_documento']['error'] == 0) {
		$ruta_destino = "../../admision/utils/documentos/";
		$nombre_archivo = basename($_FILES['carga_documento']['name']);
		$ruta_completa = $ruta_destino . $nombre_archivo;
		$ruta_db =substr($ruta_completa,15);
		
		// Intentar mover el archivo cargado al destino deseado
		if (!move_uploaded_file($_FILES['carga_documento']['tmp_name'], $ruta_completa)) {
			echo "<script>
					alert('Error al subir el archivo.');
					window.history.back();
				</script>";
			exit;
		}
	}

	// Preparar la consulta SQL para actualizar el registro existente
	if ($nombre_archivo) {
		// Si se ha subido un nuevo archivo, actualizar también el campo del archivo
		$consulta = "UPDATE documento_admision SET  Descripcion = '$descripcion', Tipo = '$tipo', Archivo = '$ruta_db', Estado = '$estado' WHERE Id = $id_documento";
	} else {
		// Si no se subió un nuevo archivo, no actualizar el campo del archivo
		$consulta = "UPDATE documento_admision SET  Descripcion = '$descripcion', Tipo = '$tipo', Estado = '$estado' WHERE Id = $id_documento";
	}
	
	// Ejecutar la consulta
	$ejec_consulta = mysqli_query($conexion, $consulta);

	// Ejecutar la consulta de actualización
	if ($ejec_consulta) {
		echo "<script>
				alert('Documento de admisión se a actualizado exitosamente');
				window.history.back();
			</script>";
	} else {
		echo "<script>
				alert('Error al actualizar el documento de admisión, por favor verifique sus datos');
				window.history.back();
			</script>";
	}


mysqli_close($conexion);

}

