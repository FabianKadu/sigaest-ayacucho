<?php
session_start();
require_once('../tcpdf/tcpdf.php');
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");

$recursos = buscarRecursos($conexion);
$res_recursos = mysqli_fetch_array($recursos);
$logo = $res_recursos['img_logo_documento'];

$id_mat = $_GET['id'];

$b_mat = buscarMatriculaById($conexion, $id_mat);
$r_b_mat = mysqli_fetch_array($b_mat);
$id_est = $r_b_mat['id_estudiante'];
$id_periodo_select = $_SESSION['periodo'];

$periodo_academico = buscarPeriodoAcadById($conexion, $id_periodo_select);
$r_periodo_academico = mysqli_fetch_array($periodo_academico);
$nombre_periodo = $r_periodo_academico['nombre'];

$b_est = buscarEstudianteById($conexion, $id_est);
$r_b_est = mysqli_fetch_array($b_est);
$nombres = $r_b_est['apellidos_nombres'];
$dni = $r_b_est['dni'];
$id_semestre = $r_b_est['id_semestre'];

$semestre_res = buscarSemestreById($conexion, $id_semestre);
$semestre = mysqli_fetch_array($semestre_res);
$nombre_semestre = $semestre['descripcion'];


$programa = buscarCarrerasById($conexion, $r_b_mat['id_programa_estudio']);
$r_programa = mysqli_fetch_array($programa);
$nombre_programa = $r_programa['nombre'];
$plan = $r_programa['plan_estudio'];
$tipo_programa = $r_programa['tipo'];


$datos_instituto = buscarDatosGenerales($conexion);
$r_datos_instituto = mysqli_fetch_array($datos_instituto);
$cod_modular = $r_datos_instituto['cod_modular'];
$ruc = $r_datos_instituto['ruc'];
$departamento = $r_datos_instituto['departamento'];
$provincia = $r_datos_instituto['provincia'];
$distrito = $r_datos_instituto['distrito'];
$nombre_institucion = $r_datos_instituto['nombre_institucion'];

$b_perido_act = buscarPeriodoAcadById($conexion, $id_periodo_select);
$r_b_per_act = mysqli_fetch_array($b_perido_act);

$b_detalle_matricula = buscarDetalleMatriculaByIdMatricula($conexion, $r_b_mat['id']);



class MYPDF extends TCPDF
{
    // Encabezado personalizado
    public function Header()
    {
        $this->SetFont('helvetica', 'B', 12);
        $this->Cell(0, 10, 'FICHA DE MATRÍCULA REGULAR', 0, 1, 'C');
    }
}

$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle("Ficha de Matrícula Regular");
$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->SetDefaultMonospacedFont('helvetica');
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);
$pdf->SetAutoPageBreak(TRUE, 25);
$pdf->AddPage('P', 'A4');
$pdf->SetFont('helvetica', '', 8);

$html = '
        <table border="0" width="100%" cellspacing="3" cellpadding="0">
          <tr>
              
              <td width="40%"><img src="'.$logo.'" alt="" height="40px"></td>
              <td width="20%"></td>
              <td width="40%" align="rigth"><img src="../img/logo_minedu.jpeg" alt="" height="40px"></td>
              
          </tr>
          <tr>
              <td colspan="3"><p align="rigth"><b>Fecha y Hora de Emisión:</b> ' . date('d/m/Y h:i A') . '</p>  </td>
          </tr>
          <br>
          <tr>
              <td colspan="3" align="center"><font size="11"><b>SISTEMA DE GESTIÓN ACADÉMICA</b></font></td>
          </tr>
          <tr>
              <td colspan="3" align="center"><font size="10"><b>FICHA DE MATRÍCULA REGULAR</b></font></td>
          </tr>     
          
      </table><br /><br />
          <br /><br />
    ';
// Datos de la institución
$html .= '
        <table border="0.2" cellspacing="0" cellpadding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Apellidos y Nombres</font></th>
                <th width="40%"  ><font><b> '.$nombres.' </b></font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Número de documento</font></th>
                <th width="20%" ><font><b>'. $dni .'</b></font></th>
            </tr>
        </table>
        <h6></h6>
        ';

$html .= '
        <table border="0.2" cellspacing="0" cellpadding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Nombre de la Institución</font></th>
                <th width="40%"  ><font>'.$nombre_institucion.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>DRE</font></th>
                <th width="20%" ><font>'.$departamento.'</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Código Modular</font></th>
                <th width="40%"  ><font>'.$cod_modular.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Tipo de Gestión</font></th>
                <th width="20%" ><font>PÚBLICO</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Departamento</font></th>
                <th width="40%"  ><font>'.$departamento.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Provincia</font></th>
                <th width="20%" ><font>'.$provincia.'</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Distrito</font></th>
                <th width="40%"  ><font>'.$distrito.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font></font></th>
                <th width="20%" ><font></font></th>
            </tr>
        </table>
        <h6></h6>';



$html .= '
        
        <table border="0.2" cellspacing="0" cellpadding="2" padding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Programa de estudios</font></th>
                <th width="40%"  ><font>'.$nombre_programa.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo lectivo</font></th>
                <th width="20%" ><font>'.$nombre_periodo.'</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Nivel formativo</font></th>
                <th width="40%"  ><font>PROFESIONAL TÉCNICO</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo de clases</font></th>
                <th width="20%" ><font>'.$nombre_periodo.'</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Tipo de plan de estudios</font></th>
                <th width="40%"  ><font>'.$tipo_programa.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo académico</font></th>
                <th width="20%" ><font>'.$nombre_semestre.'</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Plan de estudios</font></th>
                <th width="40%"  ><font>PLAN '.$plan.'</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font></font></th>
                <th width="20%" ><font></font></th>
            </tr>
        </table>';

// Unidades Didácticas Regulares
$html .= '<br><h4 align="center">UNIDADES DIDÁCTICAS REGULARES</h4>
<table border="0.2" cellpadding="2">
    <tr>
        <th align="center" bgcolor="#CCCCCC" width="5%">NRO.</th>
        <th align="center" bgcolor="#CCCCCC" width="47%">UNIDAD DIDÁCTICA</th>
        <th align="center" bgcolor="#CCCCCC" width="18%">PERIODO ACADÉMICO</th>
        <th align="center" bgcolor="#CCCCCC" width="8%">HORAS</th>
        <th align="center" bgcolor="#CCCCCC" width="9%">CRÉDITOS</th>
        <th align="center" bgcolor="#CCCCCC" width="13%">CONDICIÓN</th>
    </tr>
';

$contador= 1;
$contador_irregular= 1;
$creditos_regulares = 0;
$horas_regulares = 0;
$creditos_irregulares = 0;
$horas_irregulares = 0;
$html_irregular = '';

while($detalle_matricula = mysqli_fetch_array($b_detalle_matricula)){
    $programacion_ud = buscarProgramacionById($conexion, $detalle_matricula['id_programacion_ud']);
    $programacion = mysqli_fetch_array($programacion_ud);
    $unidad_didactica = buscarUdById($conexion, $programacion['id_unidad_didactica']);
    $ud = mysqli_fetch_array($unidad_didactica);
    $nombre_ud = $ud['descripcion'];
    $semestre = $ud['id_semestre'];
    $horas = $ud['horas'];
    $creditos = $ud['creditos'];
    if( $semestre == $id_semestre){
        $creditos_regulares += $creditos;
        $horas_regulares += $horas;
        $html .= '
            <tr>
                <td align="center" width="5%">'.$contador++.'</td>
                <td width="47%">'.$nombre_ud.'</td>
                <td align="center" width="18%">'.$nombre_semestre.'</td>
                <td align="center" width="8%">'.$horas.'</td>
                <td align="center" width="9%">'.(double) $creditos.'</td>
                <td align="center" width="13%">REGULAR</td>
            </tr>';
    }if( $semestre > $id_semestre){
        $creditos_irregulares += $creditos;
        $horas_irregulares += $horas;
        $html_irregular .= '
            <tr>
                <td align="center" width="5%">'.$contador_irregular++.'</td>
                <td width="47%">'.$nombre_ud.'</td>
                <td align="center" width="18%">'.$nombre_semestre.'</td>
                <td align="center" width="8%">'.$horas.'</td>
                <td align="center" width="9%">'.(double) $creditos.'</td>
                <td align="center" width="13%">ADELANTO</td>
            </tr>';
    }
    if( $semestre < $id_semestre){
        $horas_irregulares += $horas;
        $creditos_irregulares += $creditos;
        $html_irregular .= '
            <tr>
                <td align="center" width="5%">'.$contador_irregular++.'</td>
                <td width="47%">'.$nombre_ud.'</td>
                <td align="center" width="18%">'.$nombre_semestre.'</td>
                <td align="center" width="8%">'.$horas.'</td>
                <td align="center" width="9%">'.(double)$creditos.'</td>
                <td align="center" width="13%">SUBSANACIÓN</td>
            </tr>';
    }
}

$html .= '
    <tr>
        <td align="center" width="5%"></td>
        <td width="47%">TOTAL</td>
        <td align="center" width="18%"></td>
        <td align="center" width="8%">'.$horas_regulares.'</td>
        <td align="center" width="9%">'.$creditos_regulares.'</td>
        <td align="center" width="13%"></td>
    </tr>
</table>';

$html .= '<br><h4 align="center">UNIDADES DIDÁCTICAS DE SUBSANACIÓN Y ADELANTO</h4>
<table border="0.2" cellpadding="2">
    <tr>
        <th align="center" bgcolor="#CCCCCC" width="5%">NRO.</th>
        <th align="center" bgcolor="#CCCCCC" width="47%">UNIDAD DIDÁCTICA</th>
        <th align="center" bgcolor="#CCCCCC" width="18%">PERIODO ACADÉMICO</th>
        <th align="center" bgcolor="#CCCCCC" width="8%">HORAS</th>
        <th align="center" bgcolor="#CCCCCC" width="9%">CRÉDITOS</th>
        <th align="center" bgcolor="#CCCCCC" width="13%">CONDICIÓN</th>
    </tr>
';

$html .= $html_irregular;

$html .= '
    <tr>
        <td align="center" width="5%"></td>
        <td width="47%">TOTAL</td>
        <td align="center" width="18%"></td>
        <td align="center" width="8%">'.$horas_irregulares.'</td>
        <td align="center" width="9%">'.$creditos_irregulares.'</td>
        <td align="center" width="13%"></td>
    </tr>
</table>';

// Firmas
$html .= '
<table border="0" cellpadding="10">
<br><br><br><br><br><br><br>
<tr>
<td align="center">_________________________________<br>DIRECTOR(A) GENERAL <br> Firma Post Firma y Sello</td>
<td align="center">_________________________________<br>SECRETARIO(A) ACADÉMICO <br> Firma Post Firma y Sello</td>
<td align="center">_________________________________<br>ESTUDIANTE <br> Firma </td>
</tr>
</table>';

$pdf->writeHTML($html, true, false, true, false, '');
$pdf->Output('FichaMatricula.pdf', 'I');
