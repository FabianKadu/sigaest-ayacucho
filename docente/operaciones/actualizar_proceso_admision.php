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


	$id = $_POST['id'];
	$tipo = $_POST['tipo'];
	$periodo_ant = $_POST['periodo'];
	$periodo = $_POST['periodo_anio'];
	$periodo =$periodo.'-'.$_POST['periodo_unidad'];
	$inicio = $_POST['inicio'];
	$fin = $_POST['fin'];
	$inicio_ins = $_POST['inicio_ins'];
	$fin_ins = $_POST['fin_ins'];
	$inicio_ext = $_POST['inicio_ext'];
	$fin_ext = $_POST['fin_ext'];
	$fecha_examen = $_POST['fecha_examen'];
	$lugar_examen = $_POST['lugar_examen'];

    $contador_periodo = 0;

	$res_procesos = buscarProcesosActivosPorFechasActualizar($conexion, $inicio, $id);
	$contador = mysqli_num_rows($res_procesos);
    if($periodo_ant != $periodo){
        $res_procesos_periodo = buscarProcesoAdmisionPorPeriodoTipo($conexion,$tipo, $periodo);
    	$contador_periodo = mysqli_fetch_array($res_procesos_periodo);
    	$contador_periodo = $contador_periodo['num_rows'];
    }
	
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
	}else{
		$insertar = "UPDATE proceso_admision SET Tipo='$tipo',Periodo='$periodo',Fecha_Inicio= '$inicio',Fecha_Fin= '$fin',
		Inicio_Inscripcion= '$inicio_ins',Fin_Inscripcion= '$fin_ins',Inicio_Extemporaneo= '$inicio_ext',Fin_Extemporaneo= '$fin_ext', Fecha_Examen= '$fecha_examen', Lugar_Examen= '$lugar_examen' WHERE Id = $id";
		$ejecutar_insetar = mysqli_query($conexion, $insertar);
		if ($ejecutar_insetar) {
				echo "<script>
					alert('Actualización Existosa');
					window.location= '../procesos_admision.php'
					</script>";
		}else{
			echo "<script>
				alert('Error al actualizar');
				window.history.back();
					</script>
				";
		};

		mysqli_close($conexion);
	}

}