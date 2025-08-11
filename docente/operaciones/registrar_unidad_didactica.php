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
} else {




	$ud = $_POST['ud'];
	$carrera = $_POST['carrera_m'];
	$modulo = $_POST['modulo'];
	$semestre = $_POST['semestre'];
	$creditos = floatval($_POST['creditos']);
	$horas = $_POST['horas'];
	$tipo = $_POST['tipo'];
	$cod_correlativo = null;
	$cods_predcs = null;

	//consulta para poder generar el orden de la ud en el semestre
	$consul = "SELECT * FROM unidad_didactica WHERE id_semestre='$semestre' AND id_modulo='$modulo' AND id_programa_estudio='$carrera'";
	$ejec_consl = mysqli_query($conexion, $consul);
	$conteo = mysqli_num_rows($ejec_consl);
	$orden = $conteo + 1;


	$insertar = "INSERT INTO unidad_didactica (descripcion, id_programa_estudio, id_modulo, id_semestre, creditos, horas, tipo, orden, codigo_correlativo, codigos_ud_predecesora) 
	VALUES ('$ud','$carrera','$modulo','$semestre','$creditos','$horas','$tipo','$orden', '$cod_correlativo', '$cods_predcs')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
		echo "<script>
            
                window.location= '../unidad_didactica.php'
    			</script>";
	} else {
		echo "<script>
			alert('Error al registrar, por favor verifique sus datos');
			window.history.back();
				</script>
			";
	}
	;






	mysqli_close($conexion);

}