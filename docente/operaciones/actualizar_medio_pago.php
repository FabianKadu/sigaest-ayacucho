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
	$medio = $_POST['medio'];
	$banco = $_POST['banco'];
	$cuenta = $_POST['cuenta'];
	$cci = $_POST['cci'];
	$titular = $_POST['titular'];

	$insertar = "UPDATE metodo_pago SET Metodo='$medio',Banco='$banco',Cuenta= '$cuenta',CCI= '$cci',
	Titular= '$titular' WHERE Id = $id";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
                alert('Actualización Existosa');
                window.location= '../medios_pago.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al actualizar');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}