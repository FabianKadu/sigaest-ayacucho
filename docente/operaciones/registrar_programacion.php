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



  $unidad_didactica = $_POST['unidad_didactica'];
  $docenteTeoria = $_POST['docente-teoria'];
  $docentePractica = $_POST['docente-practica'];
  $hoy = date("Y-m-d");

  //buscar periodo actual
  $busc_per_actual = buscarPresentePeriodoAcad($conexion);
  $res_b_per_actual = mysqli_fetch_array($busc_per_actual);
  $periodo_actual = $res_b_per_actual['id_periodo_acad'];

  $res_periodo_aca = buscarPeriodoAcadById($conexion, $periodo_actual);
  $periodo_aca = mysqli_fetch_array($res_periodo_aca);
  $semanas = $periodo_aca['semanas'];

  //verificar que el docente no este programado en la unidad didactica
  $busc_programacion_existe = buscarProgramacionByUd_Peridodo($conexion, $unidad_didactica, $periodo_actual);
  $conteo_b_programacion_existe = mysqli_num_rows($busc_programacion_existe);

  if ($conteo_b_programacion_existe < 1) {

    $registro_programacion = realizar_programacion($conexion, $unidad_didactica, $periodo_actual, $docenteTeoria, $docentePractica, $semanas);

    if ($registro_programacion) {
      echo "<script>
			alert('Programacion registrado correctamente');
			window.location= '../programacion.php'
		</script>
		";
    } else {
      echo "<script>
        alert('Error, falló en el registro');
        window.history.back();
    </script>
    ";
    }

  } else {
    echo "<script>
			alert('Error, Esta Unidad Didáctica ya está programado para este periodo Académico');
			window.history.back();
		</script>
		";


  }


  mysqli_close($conexion);

}