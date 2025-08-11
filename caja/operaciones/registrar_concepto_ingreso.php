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
} else {
	$codigo = $_POST['codigo'];
	$concepto = $_POST['concepto'];
	$monto = floatval($_POST['monto']);
	$monto = encryptText($monto);
	$unidad = $_POST['unidad'];

	$stmt = $conexion->prepare("INSERT INTO `concepto_ingreso`(`concepto`, `monto`, `codigo`, `unidad`) VALUES (?, ?, ?, ?)");
	$stmt->bind_param("ssss", $concepto, $monto, $codigo, $unidad);

	if ($stmt->execute()) {
		echo "<script>
                alert('Registro Existoso');
                window.location= '../concepto_ingresos.php'
              </script>";
	} else {
		echo "<script>
                alert('Error al registrar, por favor verifique sus datos');
                window.history.back();
              </script>";
	}

	$stmt->close();
	mysqli_close($conexion);
}
?>