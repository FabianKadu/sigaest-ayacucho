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

$id_programacion = $_POST['id_prog'];
$id_silabo = $_POST['id_silabo'];
$coordinador = $_POST['coordinador'];
$horario = $_POST['horario'];
$metodologia = $_POST['metodologia'];
$recursos_didacticos = $_POST['recursos_didacticos'];
$sistema_evaluacion = $_POST['sistema_evaluacion'];
$indicadores_estrategias = $_POST['indicadores_estrategias'];
$tecnicas_estrategias = $_POST['tecnicas_estrategias'];
$recursos_bib_imp = $_POST['recursos_bib_imp'];
$recursos_bib_digi = $_POST['recursos_bib_digi'];

$b_act_silabo = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
while ($r_b_act_silabo = mysqli_fetch_array($b_act_silabo)) {
    //actualizamos los registros de la programacion de la actividad
    $id_prog_act = $r_b_act_silabo['id'];
    $fecha = $_POST['fecha_'.$r_b_act_silabo['id']];
    $elemento = $_POST['elemento_'.$r_b_act_silabo['id']];
    $actividad = $_POST['actividad_'.$r_b_act_silabo['id']];
    $contenido = $_POST['contenidos_'.$r_b_act_silabo['id']];
    $tareas = $_POST['tareas_'.$r_b_act_silabo['id']];
    if ($fecha=="") {
        $consulta = "UPDATE programacion_actividades_silabo SET fecha=NULL,elemento_capacidad='$elemento',actividades_aprendizaje='$actividad',contenidos_basicos='$contenido',tareas_previas='$tareas' WHERE  id='$id_prog_act'";
    }else{
        $consulta = "UPDATE programacion_actividades_silabo SET fecha='$fecha',elemento_capacidad='$elemento',actividades_aprendizaje='$actividad',contenidos_basicos='$contenido',tareas_previas='$tareas' WHERE  id='$id_prog_act'";
    }
    $ejec_consulta = mysqli_query($conexion, $consulta);
}
$actualizar = "UPDATE silabo SET id_coordinador='$coordinador',horario='$horario',metodologia='$metodologia',recursos_didacticos='$recursos_didacticos',sistema_evaluacion='$sistema_evaluacion',estrategia_evaluacion_indicadores='$indicadores_estrategias',estrategia_evaluacion_tecnica='$tecnicas_estrategias',recursos_bibliograficos_impresos='$recursos_bib_imp',recursos_bibliograficos_digitales='$recursos_bib_digi' WHERE  id='$id_silabo'";
$ejecutar = mysqli_query($conexion, $actualizar);
echo "<script>
			
			window.location= '../silabos.php?id=".$id_programacion."';
		</script>
	";
  }