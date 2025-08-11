<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_administrador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
				window.location.replace('../login/');
			</script>";
} else {


	$id_estudiante = $_POST['id_estudiante'];
	$descripcion = $_POST['descripcion'];
	$ultimo_id_doc = intval($_POST['ultimo_id_doc']) + 1;

	// Manejo de la carga del archivo
	if (isset($_FILES['carga_documento']) && $_FILES['carga_documento']['error'] == 0) {
		$ruta_destino = "../utils/documentos_egresados/";
		$nombre_archivo = $id_estudiante . "_" . $ultimo_id_doc . ".pdf";
		$ruta_completa = $ruta_destino . $nombre_archivo;
		$ruta_db = "../bolsa_laboral/utils/documentos_egresados/" . $nombre_archivo;

		// Mover el archivo cargado al destino deseado
		if (move_uploaded_file($_FILES['carga_documento']['tmp_name'], $ruta_completa)) {
			// Preparar la consulta SQL para insertar el nuevo documento de admisión
			$insertar = "INSERT INTO documento_egresado (id_estudiante, descripcion, archivo) 
				VALUES ($id_estudiante, '$descripcion', '$ruta_db')";

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
		}
	}
	mysqli_close($conexion);

}