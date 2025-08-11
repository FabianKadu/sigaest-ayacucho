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

$id_sesion = $_POST['id_sesion'];

$tipo_actividad = $_POST['tipo_actividad'];
$tipo_sesion = $_POST['tipo_sesion'];
$fecha_desarrollo = $_POST['fecha_desarrollo'];
$ind_logro_competencia = $_POST['ind_logro_competencia'];
$ind_logro_capacidad = $_POST['ind_logro_capacidad'];
$logro_sesion = $_POST['logro_sesion'];

$bib_doc_oblig = $_POST['bib_doc_oblig'];
$bib_doc_opci = $_POST['bib_doc_opci'];
$bib_est_oblig = $_POST['bib_est_oblig'];
$bib_est_opci = $_POST['bib_est_opci'];
$anexos = $_POST['anexos'];
$ta_sesion = $_POST['ta_sesion'];

$b_momentos_ses = buscarMomentosSesionByIdSesion($conexion, $id_sesion);
while ($r_b_momentos_ses = mysqli_fetch_array($b_momentos_ses)) {
    $id_momen = $r_b_momentos_ses['id'];
    //actualizamos los registros de los momentos de sesion
    $estrategia = $_POST['estrategia_'.$r_b_momentos_ses['id']];
    $actividad = $_POST['actividades_'.$r_b_momentos_ses['id']];
    $recursos = $_POST['recursos_'.$r_b_momentos_ses['id']];
    $tiempo = $_POST['tiempo_'.$r_b_momentos_ses['id']];

        $consulta = "UPDATE momentos_sesion_aprendizaje SET estrategia='$estrategia',actividad='$actividad',recursos='$recursos', tiempo='$tiempo' WHERE  id='$id_momen'";
    $ejec_consulta = mysqli_query($conexion, $consulta);
}

$b_actividades_eval = buscarActividadesEvaluacionByIdSesion($conexion, $id_sesion);
while ($r_b_actividades_eval = mysqli_fetch_array($b_actividades_eval)) {
    $id_act_eva = $r_b_actividades_eval['id'];
    //actualizamos los registros de las actividades de evaluacion de sesion
    $indicador = $_POST['indicador_eva_'.$r_b_actividades_eval['id']];
    $tecnicas = $_POST['tecnicas_eva_'.$r_b_actividades_eval['id']];
    $instrumentos = $_POST['instrumentos_eva_'.$r_b_actividades_eval['id']];
    $peso = $_POST['peso_eva_'.$r_b_actividades_eval['id']];

        $consulta = "UPDATE actividad_evaluacion_sesion_aprendizaje SET indicador_logro_sesion='$indicador',tecnica='$tecnicas',instrumentos='$instrumentos', peso='$peso' WHERE  id='$id_act_eva'";
    $ejec_consulta = mysqli_query($conexion, $consulta);
}


$actualizar = "UPDATE sesion_aprendizaje SET tipo_actividad='$tipo_actividad',tipo_sesion='$tipo_sesion',fecha_desarrollo='$fecha_desarrollo',id_ind_logro_competencia_vinculado='$ind_logro_competencia',id_ind_logro_capacidad_vinculado='$ind_logro_capacidad',logro_sesion='$logro_sesion',bibliografia_obligatoria_docente='$bib_doc_oblig',bibliografia_opcional_docente='$bib_doc_opci',bibliografia_obligatoria_estudiantes='$bib_est_oblig',bibliografia_opcional_estudiante='$bib_est_opci',anexos='$anexos', ta_sesion = '$ta_sesion' WHERE  id='$id_sesion'";
$ejecutar = mysqli_query($conexion, $actualizar);
echo "<script>
			
			window.location= '../sesion_de_aprendizaje.php?id=".$id_sesion."';
		</script>
	";
  }