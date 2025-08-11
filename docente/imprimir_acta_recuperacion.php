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
} else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
    $r_b_docente = mysqli_fetch_array($b_docente);


    require_once('../tcpdf/tcpdf.php');

    setlocale(LC_ALL, "es_ES");
    $id_prog = $_POST['data'];
    $b_prog = buscarProgramacionById($conexion, $id_prog);
    $res_b_prog = mysqli_fetch_array($b_prog);
    if ($r_b_docente['id_cargo'] == 2 || ($res_b_prog['id_docente'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4)) {
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
                'M' => 1000,
                'CM' => 900,
                'D' => 500,
                'CD' => 400,
                'C' => 100,
                'XC' => 90,
                'L' => 50,
                'XL' => 40,
                'X' => 10,
                'IX' => 9,
                'V' => 5,
                'IV' => 4,
                'I' => 1
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
        $pdf->SetTitle("Acta de Evaluacion de Recuperacion - " . $r_b_ud['descripcion']);
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
        $pdf->AddPage('P', 'A4');





        $text_size = 8;

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

            if ($r_b_det_mat['recuperacion'] != "") {
                if ($r_b_det_mat['recuperacion'] > 12) {
                    $recuperacion = '<td align="center" ><font color="blue" size="' . $text_size . '">' . $r_b_det_mat['recuperacion'] . '</font></td>';
                } else {
                    $recuperacion = '<td align="center" ><font color="red" size="' . $text_size . '">' . $r_b_det_mat['recuperacion'] . '</font></td>';
                }
                $puntaje = $r_b_det_mat['recuperacion'] * $r_b_ud['creditos'];
                $contenido .= '
                <tr>
                    <td align="center" ><font size="' . $text_size . '">' . $ord . '</font></td>
                    <td align="center" ><font size="' . $text_size . '">' . $r_b_est['dni'] . '</font></td>
                    <td ><font size="' . $text_size . '"> ' . $r_b_est['apellidos_nombres'] . '</font></td>
                    ' . $recuperacion . '
                    <td align="center"><font size="' . $text_size . '"> ' . $r_b_ud['creditos'] . '</font></td>
                    <td align="center"><font size="' . $text_size . '"> ' . $puntaje . '</font></td>
                </tr>
            ';
                $ord += 1;
            }


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
            <td colspan="3" align="center"><b>ACTA DE EVALUACIÓN DE RECUPERACIÓN</b></td>
        </tr>
        
        <tr>
            <td width="30%"><font size="' . $text_size . '"><b>INSTITUCIÓN EDUCATIVA</b></font></td>
            <td width="5%">:</td>
            <td width="65%"><font size="' . $text_size . '">' . $r_b_datos_insti['nombre_institucion'] . '</font></td>
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
                    <td rowspan="2" width="45%"  align="center"><font size="' . $text_size . '"><b><br>APELLIDOS Y NOMBRES</b></font></td>
                    <td colspan="3" width="30%" align="center"><font size="' . $text_size . '"><b>LOGRO FINAL</b></font></td>
                </tr>
                <tr>
                    <td align="center"><font size="' . $text_size . '"><b>EN NUMEROS</b></font></td>
                    <td align="center"><font size="' . $text_size . '"><b>CRÉDITOS</b></font></td>
                    <td align="center"><font size="' . $text_size . '"><b>PUNTAJE</b></font></td>
                </tr>
          ';

        $content_one .= $contenido;
        $content_one .= '</table></tr></table>';
        $pdf->writeHTML($content_one);

        $region = ucfirst(strtolower($r_b_datos_insti['departamento']));

        $meses = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");

        $fechaaa = date('d') . " de " . $meses[date('n') - 1] . " del " . date('Y');
        $footer = '

        <table border="0" cellspacing="0" cellpadding="0.5">  
        <tr>
            <th width="50%"></th>
            <th align="right">' . $region . ', ' . $fechaaa . '</th>
        </tr>
        <tr>
            <td colspan="2" align="center"><br><br><br><br><br><br><br><br>...............................................<br>Docente</td>
        </tr>
        </table>

      ';
        $pdf->writeHTML($footer);








        $pdf->Output('Acta Ev. Recuperacion - ' . $r_b_ud['descripcion'] . '.pdf', 'I');
    }
}
