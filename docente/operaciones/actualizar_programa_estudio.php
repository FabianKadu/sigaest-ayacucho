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
$codigo = $_POST['codigo'];
$tipo = $_POST['tipo'];
$plan_estudio = $_POST['plan_estudio'];
$nombre = $_POST['nombre'];
$resolucion = $_POST['resolucion'];
$perfil = $_POST['perfil_egreso'];

$consulta = "UPDATE programa_estudios SET codigo='$codigo', tipo='$tipo', plan_estudio='$plan_estudio', nombre='$nombre', resolucion='$resolucion', perfil_egresado='$perfil' WHERE id=$id";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
	echo "<script>
			
			window.location= '../programa_estudio.php'
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

