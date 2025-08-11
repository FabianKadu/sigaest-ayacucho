<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_coordinador.php';
if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

$id_pe = $_POST['car_consolidado'];
$id_sem = $_POST['sem_consolidado'];

$b_pe = buscarCarrerasById($conexion, $id_pe);
$r_b_pe = mysqli_fetch_array($b_pe);

$b_sem = buscarSemestreById($conexion, $id_sem);
$r_b_sem = mysqli_fetch_array($b_sem);

$per_select = $_SESSION['periodo'];
$array_estudiantes = [];
// armar la nomina de estudiantes para poder mostrar todos los estudiantes del semestre
$b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);
$cont_ud_sem = mysqli_num_rows($b_ud_pe_sem);
$cont_ind_capp = 0;
while ($r_b_ud = mysqli_fetch_array($b_ud_pe_sem)) {
  $id_ud = $r_b_ud['id'];

  //buscar capacidades
  $b_capp = buscarCapacidadesByIdUd($conexion, $id_ud);
  while ($r_b_capp = mysqli_fetch_array($b_capp)) {
    $id_capp = $r_b_capp['id'];
    //buscar indicadores de logro de capacidad
    $b_ind_l_capp = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_capp);
    $cont_ind_capp += mysqli_num_rows($b_ind_l_capp);
  }



  //buscar si la unidad didactica esta programado en el presente periodo
  $b_ud_prog = buscarProgramacionByUd_Peridodo($conexion, $id_ud, $per_select);
  $r_b_ud_prog = mysqli_fetch_array($b_ud_prog);
  $cont_res = mysqli_num_rows($b_ud_prog);
  if ($cont_res > 0) {
    $id_prog_ud = $r_b_ud_prog['id'];
    //buscar detalle de matricula matriculas a la programacion de la unidad didactica
    $b_det_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog_ud);
    while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
      // buscar matricula para obtener datos del estudiante
      $id_mat = $r_b_det_mat['id_matricula'];
      $b_mat = buscarMatriculaById($conexion, $id_mat);
      $r_b_mat = mysqli_fetch_array($b_mat);
      $id_estudiante = $r_b_mat['id_estudiante'];
      // buscar estudiante
      $b_estudiante = buscarEstudianteById($conexion, $id_estudiante);
      $r_b_estudiante = mysqli_fetch_array($b_estudiante);
      $array_estudiantes[] = $r_b_estudiante['apellidos_nombres'];
    }
    $aa = "SI";
  } else {
    $aa = "NO";
  }
  //echo $r_b_ud['descripcion']." - ".$aa."<br>";
}
$n_array_estudiantes = array_unique($array_estudiantes);
$collator = collator_create("es");
$collator->sort($n_array_estudiantes);

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reportes semestre <?php echo $r_b_sem['descripcion']; ?><?php include("../include/header_title.php"); ?></title>
  <!--icono en el titulo-->
  <link rel="shortcut icon" href="../img/favicon.ico">

  <style>
    p.verticalll {
      /* idéntico a rotateZ(45deg); */

      writing-mode: vertical-rl;
      transform: rotate(180deg);

    }

    .nota_input {
      width: 3em;
    }

    .text_s {
      font-size: 10px;
    }
  </style>
</head>

<body>
  <h3 align="center"><b>REPORTE CONSOLIDADO - <?php echo $r_b_pe['nombre'] . " - SEMESTRE " . $r_b_sem['descripcion']; ?></b></h3>
  <table BORDER="1" CELLPADDING="3" CELLSPACING="0" style="width:100%" class="text_s">
    <thead>
      <tr>
        <th rowspan="2">
          <center>Nro Orden</center>
        </th>
        <th rowspan="2">
          <center>DNI</center>
        </th>
        <th rowspan="2">
          <center>APELLIDOS Y NOMBRES</center>
        </th>
        <th colspan="<?php echo $cont_ud_sem; ?>">
          <center>UNIDADES DIDÁCTICAS</center>
        </th>
        <th rowspan="2">
          <p class="verticalll">PUNTAJE TOTAL</p>
        </th>
        <th rowspan="2">
          <p class="verticalll">PUNTAJE CRÉDITO</p>
        </th>
        <th rowspan="2">
        <center>CONDICIÓN</center>
        </th>
      </tr>

      <tr>
        <?php
        $b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);
        while ($r_bb_ud = mysqli_fetch_array($b_ud_pe_sem)) {
          $id_udd = $r_bb_ud['id'];
          //BUSCAR UD
          $b_uddd = buscarUdById($conexion, $id_udd);
          $r_b_udd = mysqli_fetch_array($b_uddd);
          //buscar capacidad
          $cont_ind_logro_cap_ud = 0;
          $b_cap_ud = buscarCapacidadesByIdUd($conexion, $id_udd);
          while ($r_b_cap_ud = mysqli_fetch_array($b_cap_ud)) {
            $id_cap_ud = $r_b_cap_ud['id'];
            // buscar indicadores de capacidad
            $b_ind_l_cap_ud = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_cap_ud);
            $cant_id_cap_ud = mysqli_num_rows($b_ind_l_cap_ud);
            $cont_ind_logro_cap_ud += $cant_id_cap_ud;
          }

        ?>
          <th style="height: 100px; text-align:center;">
            <p class="verticalll"><?php echo $r_b_udd['descripcion']; ?></p>
          </th>
        <?php
        }
        ?>

      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($n_array_estudiantes as $key => $val) {
        $key += 1;
        //buscar estudiante para su id
        $b_est = buscarEstudianteByApellidosNombres($conexion, $val);
        $r_b_est = mysqli_fetch_array($b_est);
        $id_est = $r_b_est['id'];
      ?>
        <tr>
          <td><?php echo $key; ?></td>
          <td><?php echo $r_b_est['dni']; ?></td>
          <td><?php echo $r_b_est['apellidos_nombres']; ?></td>
          <?php
          //buscar si estudiante esta matriculado en una unidad didactica
          $b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);
          $min_ud_desaprobar = round(mysqli_num_rows($b_ud_pe_sem) / 2, 0, PHP_ROUND_HALF_DOWN);
          $suma_califss = 0;
          $suma_ptj_creditos = 0;
          $cont_ud_desaprobadas = 0;

          while ($r_bb_ud = mysqli_fetch_array($b_ud_pe_sem)) {
            $id_udd = $r_bb_ud['id'];
            $b_prog_ud = buscarProgramacionByUd_Peridodo($conexion, $id_udd, $per_select);
            $r_b_prog_ud = mysqli_fetch_array($b_prog_ud);
            $id_prog = $r_b_prog_ud['id'];

            //buscar matricula de estudiante
            $b_mat_est = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $per_select);
            $r_b_mat_est = mysqli_fetch_array($b_mat_est);
            $id_mat_est = $r_b_mat_est['id'];
            //buscar detalle de matricula
            $b_det_mat_est = buscarDetalleMatriculaByIdMatriculaProgramacion($conexion, $id_mat_est, $id_prog);
            $r_b_det_mat_est = mysqli_fetch_array($b_det_mat_est);
            $cont_r_b_det_mat = mysqli_num_rows($b_det_mat_est);
            $id_det_mat = $r_b_det_mat_est['id'];
            if ($cont_r_b_det_mat > 0) {
              //echo "<td>SI</td>";

              //buscar las calificaciones
              $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);

              $suma_calificacion = 0;
              $cont_calif = 0;
              while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {

                $id_calificacion = $r_b_calificacion['id'];
                //buscamos las evaluaciones
                $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);

                $suma_calificacion += $suma_evaluacion;
                if ($suma_evaluacion > 0) {
                  $cont_calif += 1;
                }
              }
              if ($cont_calif > 0) {
                $suma_calificacion = round($suma_calificacion / $cont_calif);
              } else {
                $suma_calificacion = round($suma_calificacion);
              }
              if ($suma_calificacion != 0) {
                $calificacion = round($suma_calificacion);
              } else {
                $calificacion = "";
              }

              //buscamos si tiene recuperacion
              if ($r_b_det_mat_est['recuperacion'] != '') {
                $calificacion = $r_b_det_mat_est['recuperacion'];
              }

              if ($calificacion > 12) {
                echo '<td align="center" ><font color="blue">' . $calificacion . '</font></td>';
              } else {
                echo '<td align="center" ><font color="red">' . $calificacion . '</font></td>';
                $cont_ud_desaprobadas += 1;
              }
            } else {
              echo '<td></td>';
            }
            if (is_numeric($calificacion)) {
              $suma_califss += $calificacion;
              $suma_ptj_creditos += $calificacion*$r_bb_ud['creditos'];
            }else {
              $suma_ptj_creditos += 0*$r_bb_ud['creditos'];
            }
          }
          echo '<td align="center" ><font color="black">' . $suma_califss . '</font></td>';
          echo '<td align="center" ><font color="black">' . $suma_ptj_creditos . '</font></td>';
          if ($cont_ud_desaprobadas == 0) {
            echo '<td align="center" ><font color="black">Promovido</font></td>';
          } elseif ($cont_ud_desaprobadas <= $min_ud_desaprobar) {
            echo '<td align="center" ><font color="black">Repite U.D. del Módulo Profesional</font></td>';
          } else {
            echo '<td align="center" ><font color="black">Repite el Módulo Profesional</font></td>';
          }
          ?>
        </tr>
      <?php
      }
      ?>

    </tbody>
  </table>
  <?php echo "<script>
			window.print();
      setTimeout(function(){ 
        window.close();
    }, 3000);
		</script>
	"; ?>
</body>

</html>
<?php }