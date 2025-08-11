<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include ("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				alert('Error Usted no cuenta con permiso para acceder a esta página');
				window.location.replace('../login/');
			</script>";
} else {


	$id = $_POST['id'];
	$dni_a = $_POST['dni_a'];
	$dni = $_POST['dni'];
	$nom_ap = $_POST['nom_ap'];
	$cond_laboral = $_POST['cond_laboral'];
	$profesion = $_POST['profesion'];
	$fecha_nac = $_POST['fecha_nac'];
	$niv_formacion = $_POST['niv_formacion'];
	$direccion = $_POST['direccion'];
	$genero = $_POST['genero'];
	$telefono = $_POST['telefono'];
	$email = $_POST['email'];
	$cargo = $_POST['cargo'];
	$pe = $_POST['pe'];
	$activo = $_POST['activo'];
	$carga_academica = $_POST['carga_academica'];

	//verificar que el dni solo este registrado 1 vez
	$busc_doc = "SELECT * FROM docente WHERE dni='$dni'";
	$ejec_busc_doc = mysqli_query($conexion, $busc_doc);
	$conteo = mysqli_num_rows($ejec_busc_doc);
	if (($dni_a != $dni) && ($conteo == 0)) {
		//$pass = $dni;
		//$pass_secure = password_hash($pass, PASSWORD_DEFAULT);
		$sql = "UPDATE docente SET dni='$dni', apellidos_nombres='$nom_ap', fecha_nac='$fecha_nac', direccion='$direccion', correo='$email', 
		telefono='$telefono', id_genero='$genero', nivel_educacion='$niv_formacion', profesion = '$profesion',
		cond_laboral='$cond_laboral', id_cargo='$cargo',cargo_inmutable='$cargo', id_programa_estudio='$pe', activo='$activo', 
		carga_academica='$carga_academica' WHERE id=$id";
		$ejec_consulta = mysqli_query($conexion, $sql);
		if ($ejec_consulta) {
			echo "<script>
			alert('Registro Actualizado de manera Correcta');
			window.location= '../administrativo_docente.php';
		</script>
	";
		} else {
			echo "<script>
			alert('Error al Actualizar Registro, por favor contacte con el administrador..');
			window.history.back();
		</script>
	";
		}

	} elseif (($dni_a == $dni)) {
		$sql = "UPDATE docente SET apellidos_nombres='$nom_ap', fecha_nac='$fecha_nac', direccion='$direccion', correo='$email', 
		telefono='$telefono', id_genero='$genero', nivel_educacion='$niv_formacion', cond_laboral='$cond_laboral', profesion = '$profesion',
		id_cargo='$cargo', cargo_inmutable='$cargo' ,id_programa_estudio='$pe', activo='$activo', carga_academica='$carga_academica' 
		WHERE id=$id";
		$ejec_consulta = mysqli_query($conexion, $sql);
		if ($ejec_consulta) {
			echo "<script>
			alert('Registro Actualizado de manera Correcta');
			window.location= '../administrativo_docente.php';
		</script>
	";
		} else {
			echo "<script>
			alert('Error al Actualizar Registro, por favor contacte con el administrador');
			window.history.back();
		</script>
	";
		}

	} else {
		echo "<script>
    alert('Error, el docente ya está registrado en el sistema');
    window.history.back();
</script>
";
	}

	mysqli_close($conexion);


}