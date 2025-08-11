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
	$titulo = $_POST['titulo'];
	$tipo = $_POST['tipo'];
	$descripcion = $_POST['descripcion'];
	$id_modalidad = $_POST['id_modalidad'];
	
	if($tipo == 'General'){
		$consulta = "UPDATE requisito SET Titulo='$titulo', Tipo='$tipo', Descripcion='$descripcion' WHERE id=$id";
		$ejec_consulta = mysqli_query($conexion, $consulta);
		if ($ejec_consulta) {
			echo "<script>
					
					window.location= '../requisitos.php'
				</script>
			";
		}else {
			echo "<script>
					alert('Error al Actualizar Registro, por favor contacte con el administrador');
					window.history.back();
				</script>
			";
		}
	}else{
		$consulta = "UPDATE requisito SET Titulo='$titulo', Tipo='$tipo', Descripcion='$descripcion' WHERE id=$id";
		$ejec_consulta = mysqli_query($conexion, $consulta);
		if ($ejec_consulta) {
			echo "<script>
					
					window.location= '../requisitos_por_modalidad.php?id=".$id_modalidad."'
				</script>
			";
		}else {
			echo "<script>
					alert('Error al Actualizar Registro, por favor contacte con el administrador');
					window.history.back();
				</script>
			";
		}
	}
	
mysqli_close($conexion);

}

