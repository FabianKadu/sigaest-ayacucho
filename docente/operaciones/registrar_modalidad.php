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

	$descripcion = $_POST['descripcion'];
	$monto = $_POST['monto'];
	$monto_extemporaneo = $_POST['monto_extemporaneo'];
	
	$insertar = "INSERT INTO modalidad (Descripcion, Monto, Monto_Extemporaneo) 
				 VALUES ('$descripcion', '$monto', '$monto_extemporaneo')";
	
	$ejecutar_insertar = mysqli_query($conexion, $insertar);
	
	if ($ejecutar_insertar) {
		echo "<script>
				alert('Modalidad registrada exitosamente');
				window.location= '../requisitos_por_modalidad.php?id=".mysqli_insert_id($conexion)."';
			  </script>";
	} else {
		echo "<script>
				alert('Error al registrar la modalidad, por favor verifique sus datos');
				window.history.back();
			  </script>";
	}
	
mysqli_close($conexion);

  }