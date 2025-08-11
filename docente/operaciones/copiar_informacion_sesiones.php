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

$id_prog_actual = $_POST['myidactual'];
$id_prog_a_copiar = $_POST['sesion_copi'];

$b_silabos_prog_actual = buscarSilaboByIdProgramacion($conexion, $id_prog_actual);
$r_b_silabo_prog_actual = mysqli_fetch_array($b_silabos_prog_actual);

$b_actividades_silabo = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo_prog_actual['id']);
while ($r_b_act_silabo = mysqli_fetch_array($b_actividades_silabo)) {
    $semana_actual = $r_b_act_silabo['semana'];
    $b_sesion_actual = buscarSesionByIdProgramacionActividades($conexion, $r_b_act_silabo['id']);
    $r_b_sesion_actual = mysqli_fetch_array($b_sesion_actual);
    $id_actual = $r_b_sesion_actual['id'];

    //buscar sesion a copiar
    $b_silabos_prog_a_copiar = buscarSilaboByIdProgramacion($conexion, $id_prog_a_copiar);
    $r_b_silabos_prog_a_copiar = mysqli_fetch_array($b_silabos_prog_a_copiar);
    $id_silabo_a_copiar = $r_b_silabos_prog_a_copiar['id'];
    $b_act_silabo_a_copiar = buscarProgActividadesSilaboByIdSilaboAndSemana($conexion, $id_silabo_a_copiar, $semana_actual);
    $r_b_act_silabo_a_copiar = mysqli_fetch_array($b_act_silabo_a_copiar);
    $b_secion_a_copiar = buscarSesionByIdProgramacionActividades($conexion, $r_b_act_silabo_a_copiar['id']);
    $r_b_sesion_a_copiar = mysqli_fetch_array($b_secion_a_copiar);
    $id_a_copiar = $r_b_sesion_a_copiar['id'];





    //---------- DATOS DE SESION A COPIAR ----------------
    $b_sesion = buscarSesionById($conexion, $id_a_copiar);
    $r_b_sesion = mysqli_fetch_array($b_sesion);

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

    //ACTUALIZAR INFORMACION
    $consulta_sesion = "UPDATE sesion_aprendizaje SET tipo_actividad='$tipo_actividad', tipo_sesion='$tipo_sesion', fecha_desarrollo='$fecha_desarrollo',id_ind_logro_competencia_vinculado='$id_ind_logro_competencia_vinculado',id_ind_logro_capacidad_vinculado='$id_ind_logro_capacidad_vinculado',logro_sesion='$logro_sesion',bibliografia_obligatoria_docente='$bibliografia_obligatoria_docente',bibliografia_opcional_docente='$bibliografia_opcional_docente',bibliografia_obligatoria_estudiantes='$bibliografia_obligatoria_estudiantes',bibliografia_opcional_estudiante='$bibliografia_opcional_estudiante',anexos='$anexos' WHERE  id='$id_actual'";
    $ejec_act_sesion = mysqli_query($conexion, $consulta_sesion);


    //---------- DATOS DE MOMENTOS_SESION_APRENDIZAJE A COPIAR ----------------
    $b_momentos = buscarMomentosSesionByIdSesion($conexion, $id_a_copiar);
    while ($r_b_momentos = mysqli_fetch_array($b_momentos)) {
        $momento = $r_b_momentos['momento'];
        $estrategia = $r_b_momentos['estrategia'];
        $actividad = $r_b_momentos['actividad'];
        $recursos = $r_b_momentos['recursos'];
        $tiempo = $r_b_momentos['tiempo'];

        //---------- COPIAR  MOMENTOS DE SESION ----------------
        $reg_momentos_sesion = "UPDATE momentos_sesion_aprendizaje SET estrategia='$estrategia', actividad='$actividad', recursos='$recursos',tiempo='$tiempo' WHERE  id_sesion_aprendizaje='$id_actual' AND momento='$momento'";
        $ejec_reg_momentos_sesion = mysqli_query($conexion, $reg_momentos_sesion);
    }

    //---------- DATOS DE ACTIVIDADES EVALUACION SESION A COPIAR----------------
    $b_act_eva_sesion = buscarActividadesEvaluacionByIdSesion($conexion, $id_a_copiar);
    while ($r_b_act_eva_sesion = mysqli_fetch_array($b_act_eva_sesion)) {
        $indicador_logro_sesion = $r_b_act_eva_sesion['indicador_logro_sesion'];
        $tecnica = $r_b_act_eva_sesion['tecnica'];
        $instrumentos = $r_b_act_eva_sesion['instrumentos'];
        $peso = $r_b_act_eva_sesion['peso'];
        $momento_act = $r_b_act_eva_sesion['momento'];

        //---------- COPIAR ACTIVIDADES EVALUACION SESION ----------------
        $reg_act_eva = "UPDATE actividad_evaluacion_sesion_aprendizaje SET indicador_logro_sesion='$indicador_logro_sesion', tecnica='$tecnica', instrumentos='$instrumentos', peso='$peso' WHERE  id_sesion_aprendizaje='$id_actual' AND momento='$momento_act'";
        $ejec_reg_act_eva = mysqli_query($conexion, $reg_act_eva);
    }
}




echo "<script>
			alert('Datos Copiados Correctamente');
			window.location= '../sesiones.php?id=" . $id_prog_actual . "';
				</script>
			";

mysqli_close($conexion);
  }