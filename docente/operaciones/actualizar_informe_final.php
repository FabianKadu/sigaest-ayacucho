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

$fue_supervisado = $_POST['fue_supervisado'];
$reg_evaluacion = $_POST['reg_evaluacion'];
$reg_auxiliar = $_POST['reg_auxiliar'];
$prog_curricular = $_POST['prog_curricular'];
$otros = $_POST['otros'];
$logros_obtenidos = $_POST['logros_obtenidos'];
$dificultades = $_POST['dificultades'];
$sugerencias = $_POST['sugerencias'];


$actualizar = "UPDATE programacion_unidad_didactica SET supervisado='$fue_supervisado',reg_evaluacion='$reg_evaluacion',reg_auxiliar='$reg_auxiliar',prog_curricular='$prog_curricular',otros='$otros',logros_obtenidos='$logros_obtenidos',dificultades='$dificultades',sugerencias='$sugerencias' WHERE  id='$id_programacion'";
$ejecutar = mysqli_query($conexion, $actualizar);
echo "<script>
			
			window.location= '../informe_final.php?id=".$id_programacion."';
		</script>
	";
  }