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
  }else {


  

$id = $_POST['id'];
$ud = $_POST['ud'];
$carrera = $_POST['carrera_m'];
$modulo = $_POST['modulo'];
$semestre = $_POST['semestre'];
$creditos = $_POST['creditos'];
$horas = $_POST['horas'];
$tipo = $_POST['tipo'];
$cod_correlativo = $_POST['codigo_correlativo'];
$codigos_correlativos_predecesoras = $_POST['codigos_ud_predecesora'];

$consulta = "UPDATE unidad_didactica SET descripcion='$ud', id_programa_estudio='$carrera', id_modulo='$modulo', id_semestre='$semestre', creditos='$creditos', horas='$horas', tipo='$tipo', codigo_correlativo='$codigo_correlativo', codigos_ud_predecesoras='$codigos_correlativos_predecesoras' WHERE id=$id";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
	echo "<script>
			alert('Registro Actualizado de manera Correcta');
			window.location= '../unidad_didactica.php';
		</script>
	";
}else {
	echo "<script>
			alert('Error al Actualizar Registro, por favor contacte con el administrador');
			window.history.back();
		</script>
	";
}




mysqli_close($conexion);


  }

