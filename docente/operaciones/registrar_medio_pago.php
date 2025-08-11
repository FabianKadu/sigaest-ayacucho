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

	$medio = $_POST['medio'];
	$banco = $_POST['banco'];
	$cuenta = $_POST['cuenta'];
	$cci = $_POST['cci'];
	$titular = $_POST['titular'];


	$insertar = "INSERT INTO `metodo_pago`(`Metodo`, `Banco`, `Cuenta`, `CCI`, `Titular`) 
	VALUES ('$medio', '$banco', '$cuenta', '$cci', '$titular')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
                alert('Registro Existoso');
                window.location= '../medios_pago.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al registrar, verifique la información proporcionada');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}