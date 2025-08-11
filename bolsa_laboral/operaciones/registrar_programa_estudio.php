<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include ("../include/verificar_sesion_administrador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {


  

$codigo = $_POST['codigo'];
$tipo = $_POST['tipo'];
$plan_estudio = $_POST['plan_estudio'];
$nombre = $_POST['nombre'];
$resolucion = $_POST['resolucion'];
$perfil = $_POST['perfil_egreso'];

	$insertar = "INSERT INTO programa_estudios (codigo, tipo, plan_estudio, nombre, resolucion, perfil_egresado) VALUES ('$codigo','$tipo','$plan_estudio','$nombre','$resolucion','$perfil')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
                alert('Registro Existoso');
                window.location= '../programa_estudio.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al registrar, por favor verifique sus datos');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}