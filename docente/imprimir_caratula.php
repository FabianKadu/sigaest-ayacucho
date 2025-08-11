<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_docente_coordinador.php';
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
    $id_prog = $_GET['id'];
    $b_prog = buscarProgramacionById($conexion, $id_prog);
    $res_b_prog = mysqli_fetch_array($b_prog);
    if (isset($_SESSION['id_secretario']) || ($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion)) {
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
        //buscar datos de docente de teoria
        $b_docente = buscarDocenteById($conexion, $res_b_prog['id_docente']);
        $r_b_docente = mysqli_fetch_array($b_docente);
        //buscar datos de docente de practica
        $b_docente_prac = buscarDocenteById($conexion, $res_b_prog['id_docente_practica']);
        $r_b_docente_prac = mysqli_fetch_array($b_docente_prac);
        //buscar datos de coordinador de area
        $b_coordinador = buscarCoordinadorAreaByIdPe($conexion, $r_b_ud['id_programa_estudio']);
        $r_b_coordinador = mysqli_fetch_array($b_coordinador);
        //buscar datos de director
        $b_director = buscarDocenteById($conexion, $r_b_perio['director']);
        $r_b_director = mysqli_fetch_array($b_director);

        $res_horas_sem = buscarHorasSemanalesPorIdPud($conexion, $id_prog);
        $horas_sem = mysqli_fetch_array($res_horas_sem);
        $horas_semanal = $horas_sem['total_horas'] . ' horas';
        if($horas_sem['total_minutos'] != 0){
            $horas_semanal = $horas_sem['total_horas'] . ' : '. $horas_sem['total_minutos'];
        }

        $res_horario = buscarHorarioPorProgramacionId($conexion, $id_prog);
        $horario2 = "";
        while($horario = mysqli_fetch_array($res_horario)){
          $horario2 = $horario2 . ' | '.$horario['dia'].' de '. substr($horario['hora_inicio'],0,5) . ' a '. substr($horario['hora_fin'],0,5) ;
        }

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
        $pdf->SetTitle("Caratula Portafolio - " . $r_b_ud['descripcion']);
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

        $content_one = '';
        $content_one .= '
    
        <table border="0" width="100%" cellspacing="0" cellpadding="3">
        <tr>
            <td width="40%"><img src="../img/logo_minedu.jpeg" alt="" height="40px"></td>
            <td width="10%"></td>
            <td width="50%" align="right"><img src="../img/logo.jpeg" alt="" height="40px"></td>
        </tr>
        <tr>
            <br>
            <td colspan="3" align="center"><b>' . $r_b_datos_insti['nombre_institucion'] . '</b></td>
        </tr>
        
        <tr>
            <br><br><br>
            <td width="20%"></td>
            <td width="60%" border="2" align="center" height="60px"><font size="20"><b><br>REGISTRO ACADÉMICO<br></b></font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <br>
            <td width="20%"></td>
            <td width="60%" align="center"><font size="12"><b>PROGRAMA DE ESTUDIOS</b></font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <td width="100%" align="center"><font size="15">' . $r_b_pe['nombre'] . '</font></td>
        </tr>
        <tr>
            <br><br>
            <td width="20%"></td>
            <td width="60%" align="center"><font size="12"><b>MÓDULO FORMATIVO</b></font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <td width="20%"></td>
            <td width="60%" align="center"><font size="12">' . $r_b_mod['descripcion'] . '</font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <br><br>
            <td width="20%"></td>
            <td width="60%" align="center" border="1"><font size="12"><b>UNIDAD DIDÁCTICA</b></font><br><font size="12">' . $r_b_ud['descripcion'] . '</font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <br>
            <td width="20%"></td>
            <td width="60%" align="center" border="1"><font size="12"><b>HORARIO</b></font><br><font size="12">' . substr($horario2,2) . '</font></td>
            <td width="20%"></td>
        </tr>
        <tr>
            <br><br>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>HORAS SEMANAL</b></font></td>
            <td width="10%"><font size="10">: ' . $horas_semanal . '</font></td>
            <td width="45%"><font size="10">SEMESTRAL : ' . $r_b_ud['horas'] . '</font></td>
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>CRÉDITOS</b></font></td>
            <td width="10%"><font size="10">: ' . $r_b_ud['creditos'] . '</font></td>
            <td width="45%"></td>
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>SECCIÓN</b></font></td>
            <td width="10%"><font size="10">: ÚNICA</font></td>
            <td width="45%"></td>
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>SEMESTRE</b></font></td>
            <td width="10%"><font size="10">: ' . $r_b_sem['descripcion'] . '</font></td>
            <td width="45%"></td>
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>DOCENTE - TEORÍA</b></font></td>
            <td width="55%"><font size="10">: ' . $r_b_docente['apellidos_nombres'] . '</font></td>
            
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>DOCENTE - PRÁCTICA</b></font></td>
            <td width="55%"><font size="10">: ' . $r_b_docente_prac['apellidos_nombres'] . '</font></td>
            
        </tr>
        <tr>
            <td width="15%"></td>
            <td width="30%"><font size="10"><b>DIRECTOR</b></font></td>
            <td width="55%"><font size="10">: ' . $r_b_director['apellidos_nombres'] . '</font></td>
            
        </tr>
        <tr>
            <br><br><br><br><br><br>
            <td width="20%"></td>
            <td width="60%" align="center">...................................................</td>
            <td width="20%"></td>
        </tr>
        <tr>
            
            <td width="100%" align="center"><font size="10">PERIODO ' . $r_b_perio['nombre'] . '</font></td>
            
        </tr>
        
        
          ';

        $content_one .= '</table>';
        $pdf->writeHTML($content_one);




        $pdf->Output('Caratula - ' . $r_b_ud['descripcion'] . '.pdf', 'I');
    }
}