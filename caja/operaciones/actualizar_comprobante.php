<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_caja.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {


  
$id = $_POST['id'];
$compr = $_POST['comprobante'];
$cod = $_POST['codigo'];
$lon = $_POST['longitud'];

	$insertar = "UPDATE comprobantes_pago SET comprobante='$compr', codigo= '$cod', longitud= $lon WHERE id = $id";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
			echo "<script>
                alert('Actualización Existosa');
                window.location= '../comprobantes.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al registrar');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}