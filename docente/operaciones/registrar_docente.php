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


$dni = $_POST['dni'];
$nom_ap = $_POST['nom_ap'];
$cond_laboral = $_POST['cond_laboral'];
$fecha_nac = $_POST['fecha_nac'];
$niv_formacion = $_POST['niv_formacion'];
$profesion = $_POST['profesion'];
$direccion = $_POST['direccion'];
$genero = $_POST['genero'];
$telefono = $_POST['telefono'];
$email = $_POST['email'];
$cargo = $_POST['cargo'];
$pe = $_POST['pe'];

//verificar si el docente ya esta registrado
	$busc_ult_docente = "SELECT * FROM docente WHERE dni='$dni'";
	$ejec_busc_ult_doc = mysqli_query($conexion, $busc_ult_docente);
	$conteo = mysqli_num_rows($ejec_busc_ult_doc);
if ($conteo > 0) {
		echo "<script>
			alert('El docente ya está registrado en el Sistema');
			window.history.back();
				</script>
			";
	}else{
	$pass = $dni;
	$pass_secure = password_hash($pass, PASSWORD_DEFAULT);
	if(intval($cargo) == 2 || intval($cargo) == 6){
		$insertar = "INSERT INTO docente (dni, apellidos_nombres, fecha_nac, direccion, correo, telefono, id_genero, nivel_educacion, profesion, cond_laboral, id_cargo, cargo_inmutable ,id_programa_estudio, password, activo, reset_password) 
		VALUES ('$dni','$nom_ap','$fecha_nac', '$direccion', '$email', '$telefono', '$genero', '$niv_formacion', '$profesion', '$cond_laboral', '$cargo', '$cargo', '$pe', '$pass_secure', 1, 0)";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
	}else{
		$insertar = "INSERT INTO docente (dni, apellidos_nombres, fecha_nac, direccion, correo, telefono, id_genero, nivel_educacion, profesion, cond_laboral, id_cargo, id_programa_estudio, password, activo, reset_password) 
		VALUES ('$dni','$nom_ap','$fecha_nac', '$direccion', '$email', '$telefono', '$genero', '$niv_formacion', '$profesion', '$cond_laboral', '$cargo', '$pe', '$pass_secure',1, 0)";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
	}
	
	if ($ejecutar_insetar) {
		
			echo "<script>
                alert('Registro Existoso');
                window.location= '../administrativo_docente.php'
    			</script>";
			
	}else{
		echo "<script>
			alert('Error al registrar usuario, por favor verifique sus datos');
			window.history.back();
				</script>
			";
	};

};




mysqli_close($conexion);

  }