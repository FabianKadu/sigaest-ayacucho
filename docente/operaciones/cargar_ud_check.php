<?php
session_start();
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

function esNotaMayorOIgualA13($conexion, $id_estudiante, $dni, $id_unidad_didactica)
{
	// Obtener la calificación importada
	$res_calificacion = getNotasImportadaByDniAndIdUd($conexion, $dni, $id_unidad_didactica);
	$count_calificacion = mysqli_num_rows($res_calificacion);
	$result = 0;
	$resultado = 0;
	$no_empty = false;

	if ($count_calificacion == 1) {
		$calificacion = mysqli_fetch_array($res_calificacion);
		$result = $calificacion['calificacion'];
		$no_empty = true;
	}

	// Obtener la calificación de matrícula
	$res_calificacion_matricula = getNotasMatriculaByDniAndIdUd($conexion, $id_unidad_didactica, $id_estudiante);
	if ($res_calificacion_matricula) {
		$count_calificacion_matricula = mysqli_num_rows($res_calificacion_matricula);
		if ($count_calificacion_matricula == 1) {
			$calificacion_matricula = mysqli_fetch_array($res_calificacion_matricula);
			$resultado = $calificacion_matricula['promedio'];
			$no_empty = true;
		}
	}

	// Determinar la nota máxima
	$resultadomax = max($result, $resultado);

	// Evaluar si la nota máxima es mayor o igual a 13
	if ($no_empty && $resultadomax >= 12.5) {
		return true;
	} else {
		return false;
	}
}

$id_pe = $_POST['id_pe'];
$id_sem = $_POST['id_sem'];
$dni = $_POST['dni'];
$id_es = $_POST['id_es'];


$ejec_cons = buscarUdByCarSem($conexion, $id_pe, $id_sem);

$cadena = '<div class="checkbox">
		<label>
		  <input type="checkbox" onchange="select_all();" id="all_check"> <b> SELECCIONAR TODAS LAS UNIDADES DIDÁCTICAS *</b>
		</label>
		</div>';

while ($mostrar = mysqli_fetch_array($ejec_cons)) {
	$id_unidad_didactica = $mostrar["id"];
	$busc_progr = buscarProgramacionByUd_Peridodo($conexion, $id_unidad_didactica, $_SESSION['periodo']);
	$cont = mysqli_num_rows($busc_progr);
	if ($cont > 0) {
		$res_ud = mysqli_fetch_array($busc_progr);
		if (!esNotaMayorOIgualA13($conexion, $id_es, $dni, $res_ud['id_unidad_didactica'])) {
			$cadena = $cadena . '<div class="checkbox"><label><input type="checkbox" name="unidades_didacticas" onchange="gen_arr_uds();" value="' . $res_ud["id"] . '">' . $mostrar["descripcion"] . '</label></div>';
		}
	}
}
echo $cadena;
