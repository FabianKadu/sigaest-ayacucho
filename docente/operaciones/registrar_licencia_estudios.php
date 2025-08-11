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


$id_mat = $_GET['id_mat'];
$resolucion = $_GET['res_licencia'];


$consulta = "UPDATE matricula SET licencia='$resolucion' WHERE id=$id_mat";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
	echo "<script>
			alert('Proceso Exitoso');
			window.location= '../licencias.php';
		</script>
	";
}else {
	echo "<script>
			alert('Error al Registrar Licencia');
			window.history.back();
		</script>
	";
}




mysqli_close($conexion);


}
