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

	
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$periodo = strval($_POST['periodo_anio']);
	$periodo =$periodo.'-'.$_POST['periodo_unidad'];
    $tipo = $_POST['tipo'];
	$res_procesos = buscarProcesosActivosPorFechas($conexion, $inicio, $fin);
	$contador = mysqli_num_rows($res_procesos);
	
	$res_procesos_periodo = buscarProcesoAdmisionPorPeriodoTipo($conexion,$tipo, $periodo);
	$contador_periodo = mysqli_fetch_array($res_procesos_periodo);
	$contador_periodo = $contador_periodo['num_rows'];

	if($contador > 0){
		echo "<script>
			alert('La fecha de inicio o fin de un proceso no debe coincidir entre la fecha inicio y fin de un proceso de admisión!');
			window.history.back();
		</script>";
		exit();
	}
	elseif($contador_periodo > 0){
	    echo "<script>
			alert('El periodo que registró ya se encuentra en la base de datos indique otro periodo!');
			window.history.back();
		</script>";
		exit();
	}
	else{
		$inicio_ins = $_POST['inicio_ins'];
		$fin_ins = $_POST['fin_ins'];
		$inicio_ext = $_POST['inicio_ext'];
		$fin_ext = $_POST['fin_ext'];
		$fecha_examen = $_POST['fecha_examen'];
		$lugar_examen = $_POST['lugar_examen'];

		//MODALIDADES
		$res_modalidades = buscarTodasModalidades($conexion);
	
		//PROGRAMAS DE ESTUDIO
		$res_programas = buscarCarreras($conexion);

		//CANTIDAD INICIAL DE VACANTES
		$cantidad_vacantes = 0;

		//BUSCAR PERIODO YA REGISTRADO
		$res_periodo = buscarTodosProcesosAdmisionPeriodo($conexion, $periodo);
		$registrado_periodo = mysqli_num_rows($res_periodo);

		if($registrado_periodo == 0){
			$insertar = "INSERT INTO `proceso_admision`(`Periodo`, `Tipo`, `Fecha_Inicio`, `Fecha_Fin`, `Inicio_Inscripcion`, `Fin_Inscripcion`,`Inicio_Extemporaneo`, `Fin_Extemporaneo`,`Fecha_Examen`, `Lugar_Examen`) 
			VALUES ('$periodo', '$tipo','$inicio','$fin', '$inicio_ins','$fin_ins', '$inicio_ext','$fin_ext','$fecha_examen','$lugar_examen')";
			$ejecutar_insetar = mysqli_query($conexion, $insertar);
			if ($ejecutar_insetar) {
				$id_proceso_admision = mysqli_insert_id($conexion);
					
				while ($programas = mysqli_fetch_array($res_programas)) {
					$id_programa = $programas['id'];
					// Restablecer el puntero interno de $res_modalidades al principio
					mysqli_data_seek($res_modalidades, 0);
					while ($modalidades = mysqli_fetch_array($res_modalidades)) {
						$id_modalidad = $modalidades['Id'];
						$consulta = "INSERT INTO `cuadro_vacantes`(`Id_Proceso_Admision`, `Id_Programa`, `Id_Modalidad`, `Vacantes`) 
							VALUES ($id_proceso_admision, $id_programa, $id_modalidad, $cantidad_vacantes)";
						$ejecutar_consulta = mysqli_query($conexion, $consulta);
					}
				}

				$res_requisitos = buscarTodosRequisitos($conexion);
				while ($requisitos = mysqli_fetch_array($res_requisitos)) {
					$id_requisitos = $requisitos['Id'];
					$consulta = "INSERT INTO `ajustes_admision`(`Id_Proceso_admision`, `Id_Requisito`) 
						VALUES ($id_proceso_admision, $id_requisitos)";
					$ejecutar_consulta = mysqli_query($conexion, $consulta);
				}

				$res_programas = buscarCarreras($conexion);
				while ($programas = mysqli_fetch_array($res_programas)) {
					$id_programa = $programas['id'];
					$consulta = "INSERT INTO `admision_segunda_opcion`(`Id_Programa`, `Id_Proceso_Admision`)
						VALUES ($id_programa, $id_proceso_admision)";
					$ejecutar_consulta = mysqli_query($conexion, $consulta);
				}
				
				echo "<script>
						alert('Registro Existoso');
						window.location= '../procesos_admision.php'
						</script>";
			}else{
				echo "<script>
					alert('Error al registrar, verifique la información proporcionada');
					window.history.back();
						</script>
					";
			};
		}else{
			$insertar = "INSERT INTO `proceso_admision`(`Periodo`, `Tipo`, `Fecha_Inicio`, `Fecha_Fin`, `Inicio_Inscripcion`, `Fin_Inscripcion`,`Inicio_Extemporaneo`, `Fin_Extemporaneo`,`Fecha_Examen`, `Lugar_Examen`) 
			VALUES ('$periodo', '$tipo','$inicio','$fin', '$inicio_ins','$fin_ins', '$inicio_ext','$fin_ext','$fecha_examen', '$lugar_examen')";
			$ejecutar_insetar = mysqli_query($conexion, $insertar);
			if ($ejecutar_insetar) {
				$id_proceso_admision = mysqli_insert_id($conexion);
				$res_requisitos = buscarTodosRequisitos($conexion);
				while ($requisitos = mysqli_fetch_array($res_requisitos)) {
					$id_requisitos = $requisitos['Id'];
					$consulta = "INSERT INTO `ajustes_admision`(`Id_Proceso_admision`, `Id_Requisito`) 
						VALUES ($id_proceso_admision, $id_requisitos)";
					$ejecutar_consulta = mysqli_query($conexion, $consulta);
				}

				$res_programas = buscarCarreras($conexion);
				while ($programas = mysqli_fetch_array($res_programas)) {
					$id_programa = $programas['id'];
					$consulta = "INSERT INTO `admision_segunda_opcion`(`Id_Programa`, `Id_Proceso_Admision`)
						VALUES ($id_programa, $id_proceso_admision)";
					$ejecutar_consulta = mysqli_query($conexion, $consulta);
				}

				echo "<script>
						alert('Registro Existoso');
						window.location= '../procesos_admision.php'
						</script>";
			}else{
				echo "<script>
					alert('Error al registrar, verifique la información proporcionada');
					window.history.back();
						</script>
					";
			};
		}
	}

mysqli_close($conexion);

}