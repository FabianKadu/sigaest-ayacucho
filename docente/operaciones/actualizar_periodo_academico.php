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
$per_acad = $_POST['per_acad'];
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$director = $_POST['director'];
$fecha_actas = $_POST['fecha_actas'];

$consulta = "UPDATE periodo_academico SET nombre='$per_acad', fecha_inicio='$fecha_inicio', fecha_fin='$fecha_fin', director='$director' ,fecha_actas='$fecha_actas' WHERE id=$id";
$ejec_consulta = mysqli_query($conexion, $consulta);
if ($ejec_consulta) {
	echo "<script>
			alert('Registro Actualizado de manera Correcta');
			window.location= '../periodo_academico.php'
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

