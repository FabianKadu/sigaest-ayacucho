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
	$id = $_POST['id'];
	$codigo = $_POST['codigo'];
	$concepto = $_POST['concepto'];
	$monto = floatval($_POST['monto']);
	$monto = encryptText($monto);
	$unidad = $_POST['unidad'];

	// Preparar la declaración SQL
	$stmt = $conexion->prepare("UPDATE concepto_ingreso SET concepto=?, monto=?, codigo=?, unidad=? WHERE id=?");
	// Vincular los parámetros
	$stmt->bind_param("ssssi", $concepto, $monto, $codigo, $unidad, $id);

	// Ejecutar la declaración
	if ($stmt->execute()) {
		echo "<script>
                alert('Actualización Exitosa');
                window.location= '../concepto_ingresos.php'
              </script>";
	} else {
		echo "<script>
                alert('Error al registrar');
                window.history.back();
              </script>";
	}

	// Cerrar la declaración y la conexión
	$stmt->close();
	mysqli_close($conexion);
}
?>