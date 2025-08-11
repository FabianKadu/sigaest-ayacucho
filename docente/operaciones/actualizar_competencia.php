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
$modulo = $_POST['modulo'];
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];


$consulta = "UPDATE competencias SET id_modulo_formativo='$modulo', tipo_competencia='$tipo', codigo='$codigo', descripcion='$descripcion' WHERE id=$id";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
	echo "<script>
			window.location= '../competencias.php';
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

