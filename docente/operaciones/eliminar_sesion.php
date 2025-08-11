<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {

$id_sesion = $_GET['data'];
$id_prog = $_GET['data2'];

// ---------- ELIMINAR ASISTENCIAS
$b_asistencias = buscarAsistenciaByIdSesion($conexion, $id_sesion);
while ($r_b_asistencias = mysqli_fetch_array($b_asistencias)) {
    $id_asitencia = $r_b_asistencias['id'];
    $consulta_asis = "DELETE FROM asistencia WHERE id='$id_asitencia'";
    $ejec_d_asis = mysqli_query($conexion, $consulta_asis);
}

// ----------- ELIMINAR ACTIVIDAD EVALUACION SESION
$b_act_eva = buscarActividadesEvaluacionByIdSesion($conexion, $id_sesion);
while ($r_b_act_eva = mysqli_fetch_array($b_act_eva)) {
    $id_act_eva = $r_b_act_eva['id'];
    $consulta_act_eva = "DELETE FROM actividad_evaluacion_sesion_aprendizaje WHERE id='$id_act_eva'";
    $ejec_d_act_eva = mysqli_query($conexion, $consulta_act_eva);
}

//----------- ELIMINAR MOMENTOS SESION
$b_momentos = buscarMomentosSesionByIdSesion($conexion, $id_sesion);
while ($r_b_momentos = mysqli_fetch_array($b_momentos)) {
    $id_momento = $r_b_momentos['id'];
    $consulta_momentos = "DELETE FROM momentos_sesion_aprendizaje WHERE id='$id_momento'";
    $ejec_d_momento = mysqli_query($conexion, $consulta_momentos);
}

//----------- ELIMINAR SESION DE APRENDIZAJE
$consulta_sesion = "DELETE FROM sesion_aprendizaje WHERE id='$id_sesion'";
$ejec_d_sesion = mysqli_query($conexion, $consulta_sesion);

if ($ejec_d_sesion) {
    echo "<script>
			alert('Sesión de Aprendizaje Elimado Correctamente');
			window.location= '../sesiones.php?id=".$id_prog."';
		</script>
	";
}else {
    echo "<script>
			alert('Error, No se pudo eliminar la Sesión de Aprendizaje');
			window.history.back();
		</script>
	";
}

}