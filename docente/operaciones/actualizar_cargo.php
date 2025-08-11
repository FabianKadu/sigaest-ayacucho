<?php
session_start();
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";

	$id_cargo = $_GET['cargo'];
	$id_docente = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

	$sql = "UPDATE docente SET id_cargo='$id_cargo' WHERE id=$id_docente";
	$ejec_consulta = mysqli_query($conexion, $sql);
	if ($ejec_consulta) {
		if($id_cargo == 2){
			echo "<script>
			window.location= '../secretaria.php';
			</script>";
		}
		if($id_cargo == 6){
			echo "<script>
			window.location= '../../caja/';
			</script>";
		}
		if($id_cargo == 7){
			echo "<script>
			window.location= '../../bolsa_laboral/';
			</script>";
		}
		if($id_cargo == 5)
			echo "<script>
			window.location= '../../docente/docente.php';
		</script>
	";
	} else {
			echo "<script>
			alert('Error al Actualizar Registro, por favor contacte con el administrador..');
			window.history.back();
		</script>
	";
	}

	mysqli_close($conexion);