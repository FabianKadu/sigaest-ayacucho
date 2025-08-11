<?php

include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");
include ("../functions/funciones.php");

$id_docente = $_GET['id_doc'];
$id_periodo_acad = $_GET['id_per'];

$res_resultado = buscarProgramacionByIdDocentePeriodoCompleto($conexion, $id_docente, $id_periodo_acad);

$contador = mysqli_num_rows($res_resultado);


if ($contador <= 0) {
    echo "<script>
    alert('El docente no tiene carga academica');
    window.history.back();
  </script>";
} else {

    //DATOS

    $datos_docente = mysqli_fetch_array($res_resultado);
    $profesion = $datos_docente['profesion'];
    $apellidos_nombres = $datos_docente['apellidos_nombres'];
    $periodo_academico = $datos_docente['nombre'];

    $datos_iestp = buscarDatosSistema($conexion);
    $datos_iestp = mysqli_fetch_array($datos_iestp);
    $nombre_insti = str_replace("IESTP ", "", $datos_iestp['titulo']);

    $datos_lugar = buscarDatosGenerales($conexion);
    $datos_lugar = mysqli_fetch_array($datos_lugar);
    $lugar = ucwords(strtolower($datos_lugar['distrito']));
    $iestp = $datos_lugar['nombre_institucion'];

    require_once ('../tcpdf/tcpdf.php');

    class MYPDF extends TCPDF
    {

    }

    //CONFIGURACIÓN PDF
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Carga Académica");
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins('30', '20', '30');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage('P', 'A4');
    $text_size = 9;


    // Encabezado del documento
    $documento = '
          <table border="0" width="100%" cellspacing="0" cellpadding="4">
              <tr>
                  <td width="50%" align="left"><img src="../img/logo.jpg" alt="logo" height="35px"></td>
                  <td width="50%" align="right"><img src="../img/logo_minedu.jpeg" alt="logo_minedu" height="35px"></td>
              </tr>
              <tr>
                  <td colspan="2" align="center"><b>CARGA ACADÉMICA DEL DOCENTE</b></td>
              </tr>
              <br />
              <tr>
                  <td colspan="2"><b>Docente:</b> ' . $profesion . ' ' . $apellidos_nombres . '</td>
              </tr>
              <tr>
                  <td colspan="2"><b>Periodo Académico:</b> ' . $periodo_academico . '</td>
              </tr>
              <br />
              <tr>
                  <td colspan="2" align="left"><font size="10" style="line-height: 2; text-align: justify;">
                  En el presente documento se detalla la carga académica asignada al docente ' . $profesion . ' ' . $apellidos_nombres . ' para el periodo académico ' . $periodo_academico . '. A continuación, se presenta la tabla con las unidades didácticas y las horas correspondientes:
                  </font></td>
              </tr>
          </table>
      ';

    // Tabla de carga académica
    $documento .= '
          <br />
          <table border="1" width="92%" cellspacing="0" cellpadding="4">
              <thead>
                  <tr style="background-color: #f2f2f2;">
                      <th align="center" width="10%"><b>N°</b></th>
                      <th align="center" width="50%"><b>UNIDADES DIDACTICAS</b></th>
                      <th align="center" width="20%"><b>HORAS</b></th>
                      <th align="center" width="30%"><b>TIPO</b></th>
                  </tr>
              </thead>
              <tbody>';

    $numero = 1;
    $total_horas = '00:00';
    mysqli_data_seek($res_resultado, 0); // Reiniciar el puntero del resultado para recorrerlo de nuevo

    while ($fila = mysqli_fetch_array($res_resultado)) {
        $res_TH = calcularTotalHoras($conexion, $fila['id_programacion_unidad']);
        $horas = mysqli_fetch_assoc($res_TH);

        if ($horas && $horas['total_horas'] !== null) {
            $horas_PS = substr($horas['total_horas'], 0, 5); // Solo horas y minutos
            $documento .= '
                      <tr>
                          <td align="center" width="10%">' . $numero . '</td>
                          <td align="left" width="50%">' . $fila['descripcion'] . '</td>
                          <td align="center" width="20%">' . $horas_PS . '</td>
                          <td align="center" width="30%">' . $fila['tipo'] . '</td>
                      </tr>';
            $total_horas = sumTimes($total_horas, $horas_PS); // Sumar solo horas y minutos
        } else {
            $documento .= '
                      <tr>
                          <td align="center" width="10%">' . $numero . '</td>
                          <td align="left" width="50%">' . $fila['descripcion'] . '</td>
                          <td align="center" width="20%">' . '00:00' . '</td>
                          <td align="center" width="30%">' . $fila['tipo'] . '</td>
                      </tr>';
        }

        $numero++;

    }

    $documento .= '
                  <tr style="background-color: #f2f2f2;">
                      <td align="center" colspan="2"><b>TOTAL HORAS</b></td>
                      <td align="center"><b>' . $total_horas . '</b></td>
                      <td align="center"></td>
                  </tr>
              </tbody>
          </table>';

    $documento .= '<br /><br />
          <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                  <td align="right">' . $lugar . ', ' . obtenerFecha() . '</td>
              </tr>
          </table>';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($documento, true, false, true, false, '');
    // Guardar el contenido en el archivo
    $pdf->Output('Constancia de carga académica.pdf', 'I');
}

function sumTimes($time1, $time2)
{
    // Convertir tiempo 1 a minutos
    list($hours1, $minutes1) = explode(':', $time1);
    $totalMinutes1 = $hours1 * 60 + $minutes1;

    // Convertir tiempo 2 a minutos
    list($hours2, $minutes2) = explode(':', $time2);
    $totalMinutes2 = $hours2 * 60 + $minutes2;

    // Sumar los minutos
    $totalMinutes = $totalMinutes1 + $totalMinutes2;

    // Convertir de vuelta a HH:MM
    $hours = floor($totalMinutes / 60);
    $minutes = $totalMinutes % 60;

    return sprintf('%02d:%02d', $hours, $minutes);
}

mysqli_close($conexion);
?>