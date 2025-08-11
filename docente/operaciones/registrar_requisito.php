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


  
$titulo = $_POST['titulo'];
$tipo = $_POST['tipo'];
$descripcion = $_POST['descripcion'];
$id_modalidad = $_POST['id_modalidad'];

	if($tipo == 'General'){
		$insertar = "INSERT INTO requisito (Titulo, Tipo, Descripcion) 
		VALUES ('$titulo', '$tipo', '$descripcion')";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
		if ($ejecutar_insetar) {
				echo "<script>
					window.location= '../requisitos.php';
					</script>";
		}else{
			echo "<script>
				alert('Error al registrar, por favor verifique sus datos');
				window.history.back();
					</script>
				";
		};
	}else{
		$insertar = "INSERT INTO requisito (Titulo, Tipo, Descripcion, Id_Modalidad) 
		VALUES ('$titulo', '$tipo', '$descripcion', '$id_modalidad')";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
		if ($ejecutar_insetar) {
				echo "<script>
					window.location= '../requisitos_por_modalidad.php?id=".$id_modalidad."';
					</script>";
		}else{
			echo "<script>
				alert('Error al registrar, por favor verifique sus datos');
				window.history.back();
					</script>
				";
		};
	}
	

mysqli_close($conexion);

  }