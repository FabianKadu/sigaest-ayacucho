<?php

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");

$id_postulante = $_GET['id'];
$res_resultado = buscarResultadosExamenPorIdPostulante($conexion, $id_postulante);
$contador = mysqli_num_rows($res_resultado);
$alcanzo_vacante = false;
if($contador > 0){
  $resultado = mysqli_fetch_array($res_resultado);
  $alcanzo_vacante = $resultado['Condicion'];
  if($alcanzo_vacante == 1){
      $alcanzo_vacante = true;
  } 
}

if (!$alcanzo_vacante) {
  echo "<script>
    alert('Usted no logro alcanzar una vacante');
    window.history.back();
  </script>";
}else{

  //DATOS

  $res_postulante = buscarPostulantePorId($conexion, $id_postulante);
  $postulante = mysqli_fetch_array($res_postulante);
  $postulante_nombre = $postulante['Apellido_Paterno'].' '.$postulante['Apellido_Materno'].' '.$postulante['Nombres'];
  $dni = $postulante['Dni'];
  $res_detalle_post = buscarDetallePostulacionPorIdPostulante($conexion, $id_postulante);
  $detalle_post = mysqli_fetch_array($res_detalle_post);
  $codigo = $detalle_post['Codigo_Unico'];

  $res_modalidad = buscarModalidadPorId($conexion, $detalle_post['Id_Modalidad']);
  $modalidad = mysqli_fetch_array($res_modalidad);
  $modalidad = $modalidad['Descripcion'];

  $res_programa = buscarCarrerasById($conexion, $resultado['Id_Programa']);
  $programa = mysqli_fetch_array($res_programa);
  $carrera = $programa['nombre'];

  $res_proceso = buscarProcesoAdmisionPorId($conexion, $resultado['Id_Proceso_Admision']);
  $proceso = mysqli_fetch_array($res_proceso);
  $periodo = $proceso['Periodo'];

  $director = buscarDirector_All($conexion);
  $director = mysqli_fetch_array($director);
  $nombre_director = $director['apellidos_nombres'];

  $datos_iestp = buscarDatosSistema($conexion);
  $datos_iestp = mysqli_fetch_array($datos_iestp);
  $nombre_insti = str_replace("IESTP ", "", $datos_iestp['titulo']);
  
  $datos_lugar = buscarDatosGenerales($conexion);
  $datos_lugar = mysqli_fetch_array($datos_lugar);
  $lugar = ucwords(strtolower($datos_lugar['distrito']));
  $iestp = $datos_lugar['nombre_institucion'];

  require_once('../tcpdf/tcpdf.php');

  class MYPDF extends TCPDF
      {
  
      }

      //CONFIGURACIÓN PDF
      $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
      $pdf->SetCreator(PDF_CREATOR);
      $pdf->SetTitle("Constancia de ingreso");
      $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
      $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
      $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
      $pdf->SetDefaultMonospacedFont('helvetica');
      $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
      $pdf->SetMargins('30', '20', '30');
      $pdf->setPrintHeader(false);
      $pdf->setPrintFooter(true);
      $pdf->SetAutoPageBreak(TRUE,25);
      $pdf->SetFont('helvetica', '', 11);
      $pdf->AddPage('P', 'A4');
      $text_size = 9;

      $documento = '
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                  <td width="100%" align="center"><img src="../img/logo.jpg" alt="" height="30px"></td>
              </tr>
              <br /><br />
              <tr>
                  <td align="center"><b>CONSTANCIA DE INGRESO</b></td>
              </tr>
              <br /><br />
              <tr>
                  <td align="left"><font size="10" style="line-height: 2; text-align: justify;">El Sr.(a) '.strtoupper($postulante_nombre).' con D.N.I. '.$dni.' y código de postulante '.strtoupper($codigo).', ha ingresado al '.strtoupper($iestp).', en la modalidad '.strtoupper($modalidad).' del proceso de admisión '.strtoupper($periodo).', para seguir estudios superiores
                   en el programa de estudios de '.strtoupper($carrera).'.</font></td>
              </tr>
             
          </table>
      ';

      $documento .= '<br /><br />
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                  <td align="rigth">'. $lugar .', '. obtenerFecha() . '</td>
              </tr>
              <tr>
                  <td colspan="2" align="center"><br><br><br><br><br><br><br><br>...............................................<br>'. $nombre_director .'<br>Director General</td>
              </tr>
          </table>
      ';

      // Escribir el contenido HTML en el PDF
      $pdf->writeHTML($documento, true, false, true, false, ''); 
      // Guardar el contenido en el archivo
      $pdfContent = $pdf->Output('Constancia de ingreso.pdf', 'I');
  };
  
  mysqli_close($conexion); ?>