<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

require_once('../tcpdf/tcpdf.php');

$id_prog = $_POST['data'];
$b_prog = buscarProgramacionById($conexion, $id_prog);
$res_b_prog = mysqli_fetch_array($b_prog);
if ($r_b_docente['id_cargo']==2 || ($res_b_prog['id_docente'] == $id_docente_sesion)) {
    $mostrar_archivo = 1;
} else {
    $mostrar_archivo = 0;
}

if (!($mostrar_archivo)) {
    //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
    //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
    echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.close();
		</script>
	";
} else {

    // Extend the TCPDF class to create custom Header and Footer
    class MYPDF extends TCPDF
    {



        // Page footer
        public function Footer()
        {
            // Position at 15 mm from bottom
            $this->SetY(-15);
            // Set font
            $this->SetFont('helvetica', 'I', 8);
            // Page number
            $this->Cell(0, 10, '´Página ' . $this->getAliasNumPage() . '/' . $this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
        }
    }

    //buscamos los datos para imprimir

    //buscar datos de institucion
    $b_datos_insti = buscarDatosGenerales($conexion);
    $r_b_datos_insti = mysqli_fetch_array($b_datos_insti);

    //buscar periodo 
    $b_perio = buscarPeriodoAcadById($conexion, $res_b_prog['id_periodo_acad']);
    $r_b_perio = mysqli_fetch_array($b_perio);
    //buscar unidad didactica
    $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
    $r_b_ud = mysqli_fetch_array($b_ud);
    //buscar programa de estudio
    $b_pe = buscarCarrerasById($conexion, $r_b_ud['id_programa_estudio']);
    $r_b_pe = mysqli_fetch_array($b_pe);
    //buscar modulo profesional
    $b_mod = buscarModuloFormativoById($conexion, $r_b_ud['id_modulo']);
    $r_b_mod = mysqli_fetch_array($b_mod);
    //buscar semestre
    $b_sem = buscarSemestreById($conexion, $r_b_ud['id_semestre']);
    $r_b_sem = mysqli_fetch_array($b_sem);
    //buscamos el silabo y sus datos
    $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
    $r_b_silabo = mysqli_fetch_array($b_silabo);
    $id_silabo = $r_b_silabo['id'];
    //buscar datos de docente
    $b_docente = buscarDocenteById($conexion, $res_b_prog['id_docente']);
    $r_b_docente = mysqli_fetch_array($b_docente);
    //buscar datos de coordinador de area
    $b_coordinador = buscarCoordinadorAreaByIdPe($conexion, $r_b_ud['id_programa_estudio']);
    $r_b_coordinador = mysqli_fetch_array($b_coordinador);
    //buscar datos de director
    $b_director = buscarDocenteById($conexion, $r_b_perio['director']);
    $r_b_director = mysqli_fetch_array($b_director);


    //funcion para cambia numeros a romanos
    function a_romano($integer, $upcase = true)
    {
        $table = array(
            'M' => 1000, 'CM' => 900, 'D' => 500, 'CD' => 400, 'C' => 100,
            'XC' => 90, 'L' => 50, 'XL' => 40, 'X' => 10, 'IX' => 9,
            'V' => 5, 'IV' => 4, 'I' => 1
        );
        $return = '';
        while ($integer > 0) {
            foreach ($table as $rom => $arb) {
                if ($integer >= $arb) {
                    $integer -= $arb;
                    $return .= $rom;
                    break;
                }
            }
        }
        return $return;
    }

    $n_modulo = a_romano($r_b_mod['nro_modulo']);



    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Acta de Evaluacion Final - " . $r_b_ud['descripcion']);
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(TRUE, 10);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage('P', 'A3');




    $text_title = 8;
    $text_size = 8;

    function num_letra($num){
        $numu="";
        switch ($num) {
            case 10: {
                    $numu = "DIEZ ";
                    break;
                }
            case 11: {
                    $numu = "ONCE ";
                    break;
                }
            case 12: {
                    $numu = "DOCE ";
                    break;
                }
            case 13: {
                    $numu = "TRECE ";
                    break;
                }
            case 14: {
                    $numu = "CATORCE ";
                    break;
                }
            case 15: {
                    $numu = "QUINCE ";
                    break;
                }
            case 16: {
                    $numu = "DIECISEIS ";
                    break;
                }
            case 17: {
                    $numu = "DIECISIETE ";
                    break;
                }
            case 18: {
                    $numu = "DIECIOCHO ";
                    break;
                }
            case 19: {
                    $numu = "DIECINUEVE ";
                    break;
                }
            case 20: {
                    $numu = "VEINTE";
                    break;
                }
            case 9: {
                    $numu = "NUEVE";
                    break;
                }
            case 8: {
                    $numu = "OCHO";
                    break;
                }
            case 7: {
                    $numu = "SIETE";
                    break;
                }
            case 6: {
                    $numu = "SEIS";
                    break;
                }
            case 5: {
                    $numu = "CINCO";
                    break;
                }
            case 4: {
                    $numu = "CUATRO";
                    break;
                }
            case 3: {
                    $numu = "TRES";
                    break;
                }
            case 2: {
                    $numu = "DOS";
                    break;
                }
            case 1: {
                    $numu = "UNO";
                    break;
                }
            case 0: {
                    $numu = "CERO";
                    break;
                }
            
        }
        return $numu;
    }
    //crear el contenido 

    $contenido = '';

    $b_det_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
    $ord = 1;
    while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {

        $id_mat = $r_b_det_mat['id_matricula'];
        $b_mat = buscarMatriculaById($conexion, $id_mat);
        $r_b_mat = mysqli_fetch_array($b_mat);
        $id_est = $r_b_mat['id_estudiante'];
        $b_est = buscarEstudianteById($conexion, $id_est);
        $r_b_est = mysqli_fetch_array($b_est);
        $notass = '';

        if ($r_b_mat['licencia'] != "") {
            $licencia = 1;
          }else {
            $licencia = 0;
          }



        $b_calif = buscarCalificacionByIdDetalleMatricula($conexion, $r_b_det_mat['id']);
        $suma_calificacion = 0;
        $cont_calif = 0;
        while ($r_b_calif = mysqli_fetch_array($b_calif)) {
            $suma_evaluacion = calc_evaluacion($conexion, $r_b_calif['id']);

            $suma_calificacion += $suma_evaluacion;
            if ($suma_evaluacion > 0) {
                $cont_calif += 1;
            }

            if ($suma_evaluacion != 0) {
                $calificacion = round($suma_evaluacion);
            } else {
                $calificacion = "";
            }
        }
        $obs = "";

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
        
        if ($r_b_det_mat['recuperacion'] != '') {
            $calificacion_final = $r_b_det_mat['recuperacion'];
            if ($r_b_det_mat['recuperacion'] > 12) {
                $obs = "Aprobado en Recuperación";
            }
        } else {
            $calificacion_final = $calificacion;
        }
        if ($calificacion_final > 12) {
            $promedio_final = '<td align="center" height="5px"><font color="blue" size="' . $text_size . '">' . $calificacion_final . '</font></td>';
        } else {
            $promedio_final = '<td align="center" height="5px"><font color="red" size="' . $text_size . '">' . $calificacion_final . '</font></td>';
            $obs = "Repite Unidad Didáctica";
        }
        if ($licencia) {
            $obs = "Licencia";
        }


        $letras = num_letra($calificacion_final);
        if (is_numeric($calificacion_final)) {
            $puntaje = $calificacion_final * $r_b_ud['creditos'];
        }else {
            $puntaje = 0 * $r_b_ud['creditos'];
        }
        
        $contenido .= '
                <tr>
                    <td height="5px" align="center" ><font size="' . $text_size . '">' . $ord . '</font></td>
                    <td height="5px" align="center" ><font size="' . $text_size . '">' . $r_b_est['dni'] . '</font></td>
                    <td height="5px" ><font size="' . $text_size . '"> ' . $r_b_est['apellidos_nombres'] . '</font></td>
                    ' . $promedio_final . '
                    <td height="5px" align="center"><font size="' . $text_size . '">' . $letras . '</font></td>
                    <td height="5px" align="center"><font size="' . $text_size . '"> ' . $r_b_ud['creditos'] . '</font></td>
                    <td height="5px" align="center"><font size="' . $text_size . '"> ' . $puntaje . '</font></td>
                    <td height="5px" align="center"><font size="' . $text_size . '"> ' . $obs . '</font></td>
                </tr>
            ';
        $ord += 1;
    }









    $content_one = '';
    $content_one .= '
    
        <table border="0" width="100%" cellspacing="0" cellpadding="3">
        <tr>
            <td width="40%"><img src="../img/logo_minedu.jpeg" alt="" height="40px"></td>
            <td width="10%"></td>
            <td width="50%" align="right"><img src="../img/logo.jpeg" alt="" height="40px"></td>
        </tr>
        <tr>
            <td colspan="3" align="center"><b>MINISTERIO DE EDUCACIÓN</b></td>
        </tr>
        <tr>
            <td colspan="3" align="center"><font size="' . $text_size . '"><b>DIRECCIÓN GENERAL DE EDUCACIÓN SUPERIOR TÉCNICO PROFESIONAL</b></font></td>
        </tr>
        <tr>
            <td colspan="3" align="center"><b>ACTA DE EVALUACIÓN FINAL DE LA UNIDAD DIDÁCTICA</b></td>
        </tr>
        
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>INSTITUCIÓN EDUCATIVA</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">'.$r_b_datos_insti['nombre_institucion'].'</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>PROGRAMA DE ESTUDIOS</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_pe['nombre'] . '</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>MÓDULO FORMATIVO</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_mod['descripcion'] . '</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>UNIDAD DIDÁCTICA</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_ud['descripcion'] . '</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>CRÉDITOS</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_ud['creditos'] . '</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>SEMESTRE ACADÉMICO</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_perio['nombre'] . '</font></td>
        </tr>
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>DOCENTE</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_docente['apellidos_nombres'] . '</font></td>
        </tr>
        <tr>
            <table border="0.2" cellspacing="0" cellpadding="0.5">
                <tr>
                    <td rowspan="2" width="10%" align="center"><font size="' . $text_size . '"><b><br>Nro Orden</b></font></td>
                    <td rowspan="2" width="15%" align="center"><font size="' . $text_size . '"><b><br>DNI</b></font></td>
                    <td rowspan="2" width="35%"  align="center"><font size="' . $text_size . '"><b><br>APELLIDOS Y NOMBRES</b></font></td>
                    <td colspan="5" width="40%" align="center"><font size="' . $text_size . '"><b>EVALUACIÓN FINAL</b></font></td>
                </tr>
                <tr>
                    <td align="center" width="5%"><font size="' . $text_size . '"><b>En Números</b></font></td>
                    <td align="center" width="8%"><font size="' . $text_size . '"><b>En Letras</b></font></td>
                    <td align="center" width="5%"><font size="' . $text_size . '"><b>Creditos</b></font></td>
                    <td align="center" width="5%"><font size="' . $text_size . '"><b>Puntaje</b></font></td>
                    <td align="center" width="17%"><font size="' . $text_size . '"><b>Observación</b></font></td>
                </tr>
          ';

    $content_one .= $contenido;
    $content_one .= '</table></tr><tr>
    <td></td>
</tr></table>';
    $pdf->writeHTML($content_one);

    $region = ucfirst(strtolower($r_b_datos_insti['departamento']));

    $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

    $fechaaa = date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
    $footer = '

        <table border="0" cellspacing="0" cellpadding="0.5">  
        <tr>
            <th width="50%"></th>
            <th align="right">'.$region.', ' . $fechaaa . '</th>
        </tr>
        <tr>
            <td colspan="2" align="center"><br><br><br><br><br><br><br><br>...............................................<br>Docente</td>
        </tr>
        </table>

      ';
    $pdf->writeHTML($footer);








    $pdf->Output('Acta Ev. Final - ' . $r_b_ud['descripcion'] . '.pdf', 'I');
}
}