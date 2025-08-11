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
	

	$id_proceso_admision = $_POST['proceso_admision'];
	$descripcion = $_POST['descripcion'];
	$tipo = $_POST['tipo'];
	$estado = $_POST['estado']; 

	// Manejo de la carga del archivo
	if (isset($_FILES['carga_documento']) && $_FILES['carga_documento']['error'] == 0) {
		$ruta_destino = "../../admision/utils/documentos/";
		$nombre_archivo = basename($_FILES['carga_documento']['name']);
		$ruta_completa = $ruta_destino . $nombre_archivo;
		$ruta_db =substr($ruta_completa,15);
		
		// Mover el archivo cargado al destino deseado
		if (move_uploaded_file($_FILES['carga_documento']['tmp_name'], $ruta_completa)) {
			// Preparar la consulta SQL para insertar el nuevo documento de admisión
			$insertar = "INSERT INTO documento_admision (Id_Proceso_Admision, Descripcion, Tipo, Archivo, Estado) 
				VALUES ('$id_proceso_admision', '$descripcion', '$tipo', '$ruta_db', '$estado')";

			$ejecutar_insertar = mysqli_query($conexion, $insertar);

			
			if ($ejecutar_insertar) {
				echo "<script>
						alert('Documento registrado exitosamente');
						window.history.back();
					</script>";
			} else {
				echo "<script>
						alert('Error al actualizar el documento, por favor verifique sus datos');
						window.history.back();
					</script>";
			}
		}}
mysqli_close($conexion);

}