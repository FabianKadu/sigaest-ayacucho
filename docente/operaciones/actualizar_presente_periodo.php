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
$id_per_acad = $_POST['id_per_acad'];

$consulta = "UPDATE presente_periodo_acad SET  id_periodo_acad='$id_per_acad' WHERE id=$id";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
    //actualizamos datos de la sesion
    $_SESSION['periodo'] = $id_per_acad;
	echo "<script>
			window.location= '../presente_periodo.php'
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

