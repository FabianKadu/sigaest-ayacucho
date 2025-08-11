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

//---------- DATOS DE SESION ----------------
$b_sesion = buscarSesionById($conexion, $id_sesion);
$r_b_sesion = mysqli_fetch_array($b_sesion);

$id_programacion_actividad_silabo = $r_b_sesion['id_programacion_actividad_silabo'];
$tipo_actividad = $r_b_sesion['tipo_actividad'];
$tipo_sesion = $r_b_sesion['tipo_sesion'];
$fecha_desarrollo = $r_b_sesion['fecha_desarrollo'];
$id_ind_logro_competencia_vinculado = $r_b_sesion['id_ind_logro_competencia_vinculado'];
$id_ind_logro_capacidad_vinculado = $r_b_sesion['id_ind_logro_capacidad_vinculado'];
$logro_sesion = $r_b_sesion['logro_sesion'];
$bibliografia_obligatoria_docente = $r_b_sesion['bibliografia_obligatoria_docente'];
$bibliografia_opcional_docente = $r_b_sesion['bibliografia_opcional_docente'];
$bibliografia_obligatoria_estudiantes = $r_b_sesion['bibliografia_obligatoria_estudiantes'];
$bibliografia_opcional_estudiante = $r_b_sesion['bibliografia_opcional_estudiante'];
$anexos = $r_b_sesion['anexos'];

//-------- REGISTRAMOS EL NUEVO SESION DE APRENDIZAJE ------------------
$reg_sesion = "INSERT INTO sesion_aprendizaje (id_programacion_actividad_silabo, tipo_actividad, tipo_sesion, fecha_desarrollo, id_ind_logro_competencia_vinculado, id_ind_logro_capacidad_vinculado, logro_sesion, bibliografia_obligatoria_docente, bibliografia_opcional_docente, bibliografia_obligatoria_estudiantes, bibliografia_opcional_estudiante, anexos) VALUES ('$id_programacion_actividad_silabo','$tipo_actividad','$tipo_sesion','$fecha_desarrollo','$id_ind_logro_competencia_vinculado','$id_ind_logro_capacidad_vinculado','$logro_sesion','$bibliografia_obligatoria_docente','$bibliografia_opcional_docente','$bibliografia_obligatoria_estudiantes','$bibliografia_opcional_estudiante','$anexos')";
$ejec_reg_sesion = mysqli_query($conexion, $reg_sesion);

$id_new_sesion = mysqli_insert_id($conexion);

//---------- DATOS DE MOMENTOS DE SESION ----------------
$b_momentos = buscarMomentosSesionByIdSesion($conexion, $id_sesion);
while ($r_b_momentos= mysqli_fetch_array($b_momentos)) {
    $momento = $r_b_momentos['momento'];
    $estrategia = $r_b_momentos['estrategia'];
    $actividad = $r_b_momentos['actividad'];
    $recursos = $r_b_momentos['recursos'];
    $tiempo = $r_b_momentos['tiempo'];

    //---------- REGISTRAR EL NUEVO MOMENTOS DE SESION ----------------
    $reg_momentos_sesion = "INSERT INTO momentos_sesion_aprendizaje (id_sesion_aprendizaje, momento, estrategia, actividad, recursos, tiempo) VALUES ('$id_new_sesion', '$momento','$estrategia','$actividad','$recursos','$tiempo')";
    $ejec_reg_momentos_sesion = mysqli_query($conexion, $reg_momentos_sesion);
}


//---------- DATOS DE ACTIVIDADES EVALUACION SESION ----------------
$b_act_eva_sesion = buscarActividadesEvaluacionByIdSesion($conexion, $id_sesion);
while ($r_b_act_eva_sesion = mysqli_fetch_array($b_act_eva_sesion)) {
    $indicador_logro_sesion = $r_b_act_eva_sesion['indicador_logro_sesion'];
    $tecnica = $r_b_act_eva_sesion['tecnica'];
    $instrumentos = $r_b_act_eva_sesion['instrumentos'];
    $peso = $r_b_act_eva_sesion['peso'];
    $momento_act = $r_b_act_eva_sesion['momento'];

    //---------- REGISTRAR EL NUEVO ACTIVIDADES EVALUACION SESION ----------------
    $reg_act_eva = "INSERT INTO actividad_evaluacion_sesion_aprendizaje (id_sesion_aprendizaje, indicador_logro_sesion, tecnica, instrumentos, peso, momento) VALUES ('$id_new_sesion','$indicador_logro_sesion','$tecnica','$instrumentos','$peso','$momento_act')";
    $ejec_reg_act_eva = mysqli_query($conexion, $reg_act_eva);
}

//---------- DATOS DE ASISTENCIA ----------------
$b_asistencia = buscarAsistenciaByIdSesion($conexion, $id_sesion);
while ($r_b_asistencia = mysqli_fetch_array($b_asistencia)) {
    $id_estudiante = $r_b_asistencia['id_estudiante'];
    //------------- REGISTRAMOS LAS ASISTENCCIAS -------------------
    $r_asistencia = "INSERT INTO asistencia (id_sesion_aprendizaje, id_estudiante, asistencia) VALUES ('$id_new_sesion','$id_estudiante','')";
    $ejecutar_r_asistencia = mysqli_query($conexion, $r_asistencia);
}



echo "<script>
			
			window.location= '../sesiones.php?id=".$id_prog."';
		</script>
	";



  }