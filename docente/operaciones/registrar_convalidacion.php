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
	$id_estudiante = $_POST['id_est'];
	$id_periodo = $_POST['periodo_lec'];
	$resolucion = $_POST['resolucion'];
	$tipo = $_POST['tipo'];
	$id_semestre = $_POST['semestre'];
	$id_UD_convalidar = $_POST['U_D_convalidar'];
	$Programa_estudios_origen = $_POST['Programa_estudios_origen_select'];
	$Unidad_didactica_origen = $_POST['Unidad_didactica_origen_select'];
	$calificacion = $_POST['calificacion'];

	// Encriptar la calificación
	$calificacion = encryptText($calificacion);

	// Manejo de la carga del archivo
	if (isset($_FILES['documento']) && $_FILES['documento']['error'] == 0) {
		// Updated destination folder path using __DIR__ to point two levels up
		$ruta_destino = __DIR__ . '/../../documentos/convalidaciones/';
		// Create the destination folder if it doesn't exist
		if (!is_dir($ruta_destino)) {
			mkdir($ruta_destino, 0777, true);
		}
		$nombre_archivo = $resolucion . '.' . pathinfo($_FILES['documento']['name'], PATHINFO_EXTENSION);
		$ruta_completa = $ruta_destino . $nombre_archivo;

		// Mover el archivo cargado al destino deseado
		if (move_uploaded_file($_FILES['documento']['tmp_name'], $ruta_completa)) {
			// Preparar la consulta SQL para insertar el nuevo documento de admisión
			$insertar = "INSERT INTO convalidacion (id_estudiante, id_periodo_academico, resolucion, archivo_resolucion, tipo, id_semestre, id_unidad_didactica, programa_estudios_origen, unidad_didactica_origen, calificacion) 
				VALUES ('$id_estudiante', '$id_periodo', '$resolucion', '$nombre_archivo', '$tipo', '$id_semestre', '$id_UD_convalidar', '$Programa_estudios_origen', '$Unidad_didactica_origen', '$calificacion')";

			$ejecutar_insertar = mysqli_query($conexion, $insertar);


			if ($ejecutar_insertar) {
				// Obtener el ID de la convalidación recién insertada
				$id_convalidacion = mysqli_insert_id($conexion);

				// Obtener información del docente para la auditoría
				$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
				$b_docente = buscarDocenteById($conexion, $id_docente_sesion);
				$r_b_docente = mysqli_fetch_array($b_docente);

				// Insertar registro de auditoría
				$queryAudit = "INSERT INTO convalidacion_auditoria (
                    id_convalidacion,
                    id_usuario,
                    nombre_usuario,
                    accion
                ) VALUES (
                    '$id_convalidacion',
                    '$id_docente_sesion',
                    '" . mysqli_real_escape_string($conexion, $r_b_docente['apellidos_nombres']) . "',
                    'Registro nuevo de Convalidación'
                )";

				$resultAudit = mysqli_query($conexion, $queryAudit);
				if (!$resultAudit) {
					error_log("Error en auditoría Convalidación: " . mysqli_error($conexion));
				}

				echo "<script>
						alert('Convalidacion registrada exitosamente');
						window.location.replace('../convalidaciones.php');
					</script>";
			} else {
				echo "<script>
						alert('Error al registar la convalidacion, por favor verifique sus datos');
						window.history.back();
					</script>";
			}
		} else {
			echo "<script>
					alert('Error al mover el archivo');
					window.history.back();
				</script>";
		}
	}
	mysqli_close($conexion);
}
