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


  

$modulo = $_POST['modulo'];
$tipo = $_POST['tipo'];
$codigo = $_POST['codigo'];
$descripcion = $_POST['descripcion'];

	$insertar = "INSERT INTO competencias (id_modulo_formativo, tipo_competencia, codigo, descripcion) VALUES ('$modulo','$tipo','$codigo','$descripcion')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
                window.location= '../competencias.php'
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