<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");

include_once("../PHP_XLSXWriter/xlsxwriter.class.php");

$id_prog = $_POST['id_prog'];
$cont_asis = $_POST['cont_asis'];
$ud = $_POST['ud'];

include 'include/verificar_sesion_docente_coordinador.php';
if (!(verificar_sesion($conexion) || $res_b_prog['id_docente_practica'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {


  /*header ("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
  header ("Content-Disposition: attachment; filename=plantilla.xls");*/

  $fecha_actual = date('Y-m-d');

  $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
  $r_b_det_mat = mysqli_fetch_array($b_detalle_mat);
  $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
  $r_b_matricula = mysqli_fetch_array($b_matricula);
  $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
  $r_b_silabo = mysqli_fetch_array($b_silabo);
  $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
  while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
    // buscamos la sesion que corresponde
    $id_act = $res_b_prog_act['id'];
    $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
    while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
      $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_matricula['id_estudiante']);
      $r_b_asistencia = mysqli_fetch_array($b_asistencia);

      //FECHAS DE DESARROLLO DE LAS SESIONES
      $fechas_sesiones[] = $r_b_sesion['fecha_desarrollo'];
    }
  }



  $titulo_archivo = "ASISTENCIA - " . $ud;
  // Título de la unidad


  //generamos excel
  ini_set('display_errors', 0);
  ini_set('log_errors', 1);
  error_reporting(E_ALL & ~E_NOTICE);

  $filename = $titulo_archivo . ".xlsx";
  header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
  header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
  header('Content-Transfer-Encoding: binary');
  header('Cache-Control: must-revalidate');
  header('Pragma: public');
  $writer = new XLSXWriter();

  $styles1 = array('font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom');

  // Escribir el título en la primera fila
  $writer->writeSheetRow('Plantilla', [$titulo_archivo], ['font' => 'Calibri', 'font-size' => 14, 'font-style' => 'bold', 'halign' => 'center']);
  $writer->markMergedCell('Plantilla', $start_row = 0, $start_col = 0, $end_row = 0, $end_col = $cont_asis + 2);

  // Escribir la fila que une las primeras 3 celdas con "Datos de estudiante"
  $writer->writeSheetRow('Plantilla', ['DATOS DE ESTUDIANTE', '', '', 'SESIONES'], ['font' => 'Calibri', 'font-size' => 12, 'font-style' => 'bold', 'halign' => 'center']);
  $writer->markMergedCell('Plantilla', $start_row = 1, $start_col = 0, $end_row = 1, $end_col = 2);
  $writer->markMergedCell('Plantilla', $start_row = 1, $start_col = 3, $end_row = 1, $end_col = $cont_asis + 2);


  $ord = 1;
  $header = array(
    'N°' => 'N°',
    'DNI' => 'DNI', //text
    'ALUMNO' => 'ALUMNO',
    //CONTINUAR CON LA CANTIDAD DE FECHAS DE DESARROLLO DE LAS SESIONES
  );

  foreach ($fechas_sesiones as $index => $fecha) {
    $header["Fecha " . ($index + 1)] = $fecha;
  }

  $writer->writeSheetRow('Plantilla', $header, $styles1);

  $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
  while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
    $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
    $r_b_matricula = mysqli_fetch_array($b_matricula);
    $asistencias = [];
    $b_estudiante = buscarEstudianteById($conexion, $r_b_matricula['id_estudiante']);
    $r_b_estudiante = mysqli_fetch_array($b_estudiante);
    $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
    $r_b_silabo = mysqli_fetch_array($b_silabo);
    $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);

    while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
      // buscamos la sesion que corresponde

      $id_act = $res_b_prog_act['id'];
      $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
      while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
        $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_matricula['id_estudiante']);
        $r_b_asistencia = mysqli_fetch_array($b_asistencia);
        $fecha_sesion = strtotime($r_b_sesion['fecha_desarrollo']);
        //CONDICIÓN PARA LISTAR SOLO LAS ASISTENCIAS QUE SEAN MENOR O IGUAL A LA FECHA ACTUAL, USAR FECHA SESIÓN
        if ($r_b_sesion['fecha_desarrollo'] <= $fecha_actual) {
          $asistencias[] = $r_b_asistencia['asistencia'];
        } else {
          $asistencias[] = '';
        }
      }
    }

    $rowdata = array(
      $ord,
      $r_b_estudiante['dni'],
      $r_b_estudiante['apellidos_nombres'],
    );

    // Combina la información del estudiante con las asistencias
    $rowdata = array_merge($rowdata, $asistencias);

    $writer->writeSheetRow('Plantilla', $rowdata, $styles9);

    $ord++;
  }

  $writer->writeToStdOut();

  exit(0);
}
?>