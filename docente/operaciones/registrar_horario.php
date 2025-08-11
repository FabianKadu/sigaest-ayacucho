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
} else {

	$id = $_POST['id'];
	$dia = $_POST['dia'];
	$fecha = $_POST['fecha_control'];
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];

	//buscar periodo actual
	$busc_per_actual = buscarPresentePeriodoAcad($conexion);
	$res_b_per_actual = mysqli_fetch_array($busc_per_actual);
	$periodo_actual = $res_b_per_actual['id_periodo_acad'];

	//semas del periodo
	$res_periodo_aca = buscarPeriodoAcadById($conexion, $periodo_actual);
	$periodo_aca = mysqli_fetch_array($res_periodo_aca);
	$semanas = $periodo_aca['semanas'];

	//funcion para agregar 7 dias a la fecha
	function agregar_dias_a_fecha($fecha_original)
	{
		// Crea un objeto DateTime a partir de la fecha original
		$fecha_obj = new DateTime($fecha_original);

		// Añade los días especificados a la fecha
		$fecha_obj->modify("+7 days");

		// Convierte el objeto DateTime de nuevo a un string en el formato deseado
		return $fecha_obj->format('Y-m-d'); // o el formato que necesites
	}

	$insertar = "INSERT INTO `horario_programacion` (`id_programacion_unidad`, `dia`, `hora_inicio`, `hora_fin`, `fecha_inicial`)
    VALUES($id,'$dia','$inicio','$fin', '$fecha')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
		$id_horario = mysqli_insert_id($conexion);
		$fecha_asistencia = $fecha;
		for ($i = 1; $i <= $semanas; $i++) {
			$insertar = "INSERT INTO `asistencia_docente` (`id_horario_programacion`, `fecha_asistencia`)
			VALUES($id_horario,'$fecha_asistencia')";
			$ejecutar_insetar = mysqli_query($conexion, $insertar);
			$fecha_asistencia = agregar_dias_a_fecha($fecha_asistencia);
		}
		echo "<script>
                window.location= '../horario.php?id=" . $id . "'
    			</script>";
	} else {
		echo "<script>
			alert('Error al registrar, por favor verifique los datos');
			window.history.back();
				</script>
			";
	}
	;






	mysqli_close($conexion);

}