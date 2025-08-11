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


  


$hoy = date("Y-m-d");

$anio = $_POST['anio'];
$per = $_POST['per'];
$periodo = $anio . "-" . $per;
$fecha_inicio = $_POST['fecha_inicio'];
$fecha_fin = $_POST['fecha_fin'];
$director = $_POST['director'];
$fecha_actas = $_POST['fecha_actas'];
$semanas = $_POST['semanas'];
$docente = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);


$insertar = "INSERT INTO periodo_academico (nombre, fecha_inicio, fecha_fin, director, fecha_actas, semanas) 
VALUES ('$periodo','$fecha_inicio','$fecha_fin', '$director', '$fecha_actas', $semanas)";
$ejecutar_insetar = mysqli_query($conexion, $insertar);
if ($ejecutar_insetar) {
	//buscaremos el id del ultimo periodo creado para asignar al ultimo periodo y las fechas para impresión de nominas
	$busc_ult_periodo = "SELECT * FROM periodo_academico WHERE nombre='$periodo'";
	$ejec_busc_ult_per = mysqli_query($conexion, $busc_ult_periodo);
	$res_busc_ult_per = mysqli_fetch_array($ejec_busc_ult_per);
	$id_ult_periodo = $res_busc_ult_per['id'];

	$update_per_act = "UPDATE presente_periodo_acad SET id_periodo_acad='$id_ult_periodo' WHERE id='1'";
	$ejec_upd_per_act = mysqli_query($conexion, $update_per_act);
	if ($ejec_upd_per_act) {
		//actualizamos datos de la sesion
		$_SESSION['periodo'] = $id_ult_periodo;


		// <<<<<<<<<<<<<<<<<<<<<<< GENERAMOS LA PROGRAMACION DE TODOS LOS UDS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>

		// contaremos los registros fallidos para verificar que todo cargue perfectamente
		$contar_reg_fallidos = 0;

		$b_pe = buscarCarreras($conexion);
		while ($r_b_pe = mysqli_fetch_array($b_pe)) {
			if (isset($_POST['pe_' . $r_b_pe['id']]) && ($_POST['pe_' . $r_b_pe['id']] == "on")) {
				$id_pe = $r_b_pe['id'];
				//echo $r_b_pe['nombre'] .$res_busc_ult_per['nombre']. "<br>";
				
					$_b_semestre = buscarSemestre($conexion);
					while ($r_b_semestres = mysqli_fetch_array($_b_semestre)) {
						$b_uds = buscarUdByCarSem($conexion, $id_pe, $r_b_semestres['id']);
						if (mysqli_num_rows($b_uds) > 0) {

							
							//validamos los semestre de periodo
							while ($r_b_uds = mysqli_fetch_array($b_uds)) {
								/*$unidad_didactica = $r_b_uds['id'];
								realizar_programacion($conexion,$unidad_didactica, $id_ult_periodo, $docente);
								echo ">>>>>>" . $r_b_uds['descripcion'] . "<br>";*/
								if ($per == "I") {
									if ($r_b_semestres['descripcion'] == "I" || $r_b_semestres['descripcion'] == "III" || $r_b_semestres['descripcion'] == "V" || $r_b_semestres['descripcion'] == "VII" || $r_b_semestres['descripcion'] == "IX") {
										//echo ">>>" . $r_b_semestres['descripcion'] . "<br>";
											$unidad_didactica = $r_b_uds['id'];
											//>>>>>>>>>>>>>>>>>>>>> REGISTRAR PROGRAMACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
											$res_registrar = realizar_programacion($conexion, $unidad_didactica, $id_ult_periodo, $docente, $docente, $semanas);
											//echo ">>>>>>" . $r_b_uds['descripcion'] . "<br>";
											if ($res_registrar == 0) {
												$contar_reg_fallidos +=1;
											}
											//>>>>>>>>>>>>>>>>>>>>> FIN REGISTRAR PROGRAMACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
										
									}
								} else {
									if ($r_b_semestres['descripcion'] == "II" || $r_b_semestres['descripcion'] == "IV" || $r_b_semestres['descripcion'] == "VI" || $r_b_semestres['descripcion'] == "VIII" || $r_b_semestres['descripcion'] == "X") {
										//echo ">>>" . $r_b_semestres['descripcion'] . "<br>";
										
											$unidad_didactica = $r_b_uds['id'];

											//>>>>>>>>>>>>>>>>>>>>> REGISTRAR PROGRAMACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
											$res_registrar = realizar_programacion($conexion, $unidad_didactica, $id_ult_periodo, $docente,$docente,$semanas);
											//echo ">>>>>>" . $r_b_uds['descripcion'] . "<br>";
											if ($res_registrar == 0) {
												$contar_reg_fallidos +=1;
											}
											//>>>>>>>>>>>>>>>>>>>>> FIN REGISTRAR PROGRAMACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
										
									}
								}
							}
						}
					}
				
			}
		}
		//echo "registros fallidos ".$contar_reg_fallidos;

		if ($contar_reg_fallidos > 0) {
			echo "<script>
			alert('Error al registrar Programación de ".$contar_reg_fallidos." unidades didácticas');
			window.history.back();
				</script>
			";
		}else {
			echo "<script>
                alert('Registro y Programación Existoso');
                window.location= '../periodo_academico.php'
    			</script>";
		}
		// <<<<<<<<<<<<<<<<<<<<<<< FIN GENERAMOS LA PROGRAMACION DE TODOS LOS UDS >>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>>


		
	} else {
		echo "<script>
			alert('Error al Actualizar periodo actual, por favor contacte al administrador');
			window.history.back();
				</script>
			";
	}
} else {
	echo "<script>
			alert('Error al registrar, por favor verifique sus datos');
			window.history.back();
				</script>
			";
};



/*echo "<script>
                
                window.location= '../programacion.php'
    			</script>";*/

mysqli_close($conexion);
}
