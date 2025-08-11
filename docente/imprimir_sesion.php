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

    $id_sesion = $_GET['data'];
    $b_sesion = buscarSesionById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);
    $id_prog_act = $r_b_sesion['id_programacion_actividad_silabo'];
    // buscamos datos de la programacion de actividades
    $b_prog_act = buscarProgActividadesSilaboById($conexion, $id_prog_act);
    $r_b_prog_act = mysqli_fetch_array($b_prog_act);
    $id_silabo = $r_b_prog_act['id_silabo'];
    // buscamos datos de silabo
    $b_silabo = buscarSilaboById($conexion, $id_silabo);
    $r_b_silabo = mysqli_fetch_array($b_silabo);
    $id_prog = $r_b_silabo['id_programacion_unidad_didactica'];
    //buscamos datos de la programacion de unidad didactica
    $b_prog = buscarProgramacionById($conexion, $id_prog);
    $res_b_prog = mysqli_fetch_array($b_prog);
    if (!($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4 || $r_b_docente['id_cargo'] == 3)) {
        //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
        //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
        echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.close();
		</script>
	";
    } else {

        require '../fpdf185/fpdf.php';

        class PDF extends FPDF
        {
            function Header()
            {
                global $conexion;  // Usamos la conexión a la base de datos

                // Llamar a la función para buscar recursos
                $res_recursos = buscarRecursos($conexion);
                $recursos = mysqli_fetch_array($res_recursos);
                $img_cabeza = isset($recursos['img_cabeza']) ? $recursos['img_cabeza'] : '';

                // Verificar si img_cabeza no está vacío
                if (!empty($img_cabeza)) {
                    $this->Image($img_cabeza, 5, 3, 190);
                }

                $this->SetFont('Arial', 'B', 15);
                $this->Cell(30);
                $this->Cell(120, 10, '', 0, 0, 'C');
                $this->Ln(20);
            }

            function Footer()
            {
                global $conexion;  // Usamos la conexión a la base de datos

                // Llamar a la función para buscar recursos
                $res_recursos = buscarRecursos($conexion);
                $recursos = mysqli_fetch_array($res_recursos);
                $img_pie = isset($recursos['img_footer']) ? $recursos['img_footer'] : '';

                $this->SetY(-15);

                // Verificar si img_pie no está vacío
                if (!empty($img_pie)) {
                    $this->Image($img_pie, 15, 278, 181);
                }

                $this->SetFont('Arial', 'B', 10);
                $this->Cell(0, 10, 'Pag. ' . $this->PageNo() . '             ', 0, 0, 'R');
                // $this->Cell(0,10, 'Pag. '.$this->PageNo().'/{nb}',0,0,'R' );
            }




            var $widths;
            var $aligns;

            function SetWidths($w)
            {
                //Set the array of column widths
                $this->widths = $w;
            }

            function SetAligns($a)
            {
                //Set the array of column alignments
                $this->aligns = $a;
            }

            function Row($data)
            {
                //Calculate the height of the row
                $nb = 0;
                for ($i = 0; $i < count($data); $i++)
                    $nb = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
                $h = 5 * $nb;
                //Issue a page break first if needed
                $this->CheckPageBreak($h);
                //Draw the cells of the row
                for ($i = 0; $i < count($data); $i++) {
                    $w = $this->widths[$i];
                    $a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
                    //Save the current position
                    $x = $this->GetX();
                    $y = $this->GetY();
                    //Draw the border
                    $this->Rect($x, $y, $w, $h);
                    //Print the text
                    $this->MultiCell($w, 5, $data[$i], 0, $a);
                    //Put the position to the right of the cell
                    $this->SetXY($x + $w, $y);
                }
                //Go to the next line
                $this->Ln($h);
            }

            function CheckPageBreak($h)
            {
                //If the height h would cause an overflow, add a new page immediately
                if ($this->GetY() + $h > $this->PageBreakTrigger)
                    $this->AddPage($this->CurOrientation);
            }

            function NbLines($w, $txt)
            {
                //Computes the number of lines a MultiCell of width w will take
                $cw =& $this->CurrentFont['cw'];
                if ($w == 0)
                    $w = $this->w - $this->rMargin - $this->x;
                $wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
                $s = str_replace("\r", '', $txt);
                $nb = strlen($s);
                if ($nb > 0 and $s[$nb - 1] == "\n")
                    $nb--;
                $sep = -1;
                $i = 0;
                $j = 0;
                $l = 0;
                $nl = 1;
                while ($i < $nb) {
                    $c = $s[$i];
                    if ($c == "\n") {
                        $i++;
                        $sep = -1;
                        $j = $i;
                        $l = 0;
                        $nl++;
                        continue;
                    }
                    if ($c == ' ')
                        $sep = $i;
                    $l += $cw[$c];
                    if ($l > $wmax) {
                        if ($sep == -1) {
                            if ($i == $j)
                                $i++;
                        } else
                            $i = $sep + 1;
                        $sep = -1;
                        $j = $i;
                        $l = 0;
                        $nl++;
                    } else
                        $i++;
                }
                return $nl;
            }

        }




        // INICIAMOS A CREAR EL PDF

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
        //buscar capacidad de unidad didactica
        $b_capacidad = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
        $r_b_capacidad = mysqli_fetch_array($b_capacidad);
        //buscar competencias
        $b_competencia = buscarCompetenciasById($conexion, $r_b_capacidad['id_competencia']);
        $r_b_competencia = mysqli_fetch_array($b_competencia);

        //buscamos la cantidad de indicadores para definir la cantidad de calificaciones
        $b_capacidades = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
        $total_indicadores = 0;
        while ($r_b_capacidades = mysqli_fetch_array($b_capacidades)) {
            $b_indicador_capac = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $r_b_capacidades['id']);
            $cont_indicadores = mysqli_num_rows($b_indicador_capac);
            $total_indicadores = $total_indicadores + $cont_indicadores;
        }
        ;

        //fecha de hoy


        $pdf = new PDF();
        $pdf->AliasNbPages();

        $titulo = utf8_decode("Sesión " . $r_b_prog_act['semana'] . " - " . $r_b_ud['descripcion'] . ".pdf");
        $pdf->SetTitle($titulo);

        $pdf->AddPage();
        $pdf->SetLeftMargin(20);
        //$pdf->SetAutoPageBreak(1 , 15);
        $pdf->SetFillColor(232, 232, 232);
        $pdf->SetFont('Arial', 'B', 12);
        $pdf->Cell(180, 6, utf8_decode("SESIÓN DE APRENDIZAJE - Semana Nº " . $r_b_prog_act['semana']), 0, 1, 'C', 0);
        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. INFORMACIÓN GENERAL"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(50, 125));
        $pdf->Row(array(utf8_decode('Docente a cargo'), utf8_decode(": " . $r_b_docente['apellidos_nombres'])));
        $pdf->Row(array(utf8_decode('Periodo Académico'), utf8_decode(": " . $r_b_perio['nombre'])));
        $pdf->Row(array(utf8_decode('Programa de Estudios'), utf8_decode(": " . $r_b_pe['nombre'])));
        if ($r_b_competencia['tipo_competencia'] == "ESPECÍFICA") {
            $pdf->Row(array(utf8_decode('Competencia técnica o de especialidad'), utf8_decode(": " . $r_b_competencia['descripcion'])));
        }
        if ($r_b_competencia['tipo_competencia'] == "EMPLEABILIDAD") {
            $pdf->Row(array(utf8_decode('Competencia para la empleabilidad'), utf8_decode(": " . $r_b_competencia['descripcion'])));
        }
        $pdf->Row(array(utf8_decode('Módulo'), utf8_decode(": " . $r_b_mod['descripcion'])));
        $pdf->Row(array(utf8_decode('Unidad didáctica'), utf8_decode(": " . $r_b_ud['descripcion'])));
        $pdf->Row(array(utf8_decode('Capacidad'), utf8_decode(": " . $r_b_capacidad['descripcion'])));
        $pdf->Row(array(utf8_decode('Tema o Actividad'), utf8_decode(": " . $r_b_sesion['ta_sesion'])));
        $pdf->Row(array(utf8_decode('Actividades de tipo'), utf8_decode(": " . $r_b_sesion['tipo_actividad'])));
        $pdf->Row(array(utf8_decode('Tipo de sesión'), utf8_decode(": " . $r_b_sesion['tipo_sesion'])));
        $pdf->Row(array(utf8_decode('Fecha de desarrollo'), utf8_decode(": " . $r_b_sesion['fecha_desarrollo'])));

        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. PLANIFICACIÓN DEL APRENDIZAJE"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $b_ind_comp = buscarIndicadorLogroCompetenciasById($conexion, $r_b_sesion['id_ind_logro_competencia_vinculado']);
        $r_b_ind_comp = mysqli_fetch_array($b_ind_comp);
        $pdf->Row(array(utf8_decode('Indicador(es) de logro de competencia a la que se vincula.'), utf8_decode(": " . $r_b_ind_comp['descripcion'])));
        $b_ind_cap = buscarIndicadorLogroCapacidadById($conexion, $r_b_sesion['id_ind_logro_capacidad_vinculado']);
        $r_b_ind_cap = mysqli_fetch_array($b_ind_cap);
        $pdf->Row(array(utf8_decode('Indicador(es) de logro de capacidad vinculados a la sesión.'), utf8_decode(": " . $r_b_ind_cap['descripcion'])));

        /*$log_ses = explode(",", $r_b_sesion['logro_sesion']);
        $cont_log_ses = count($log_ses);
        $logro_sesion = "";
        for ($i=0; $i < $cont_log_ses; $i++) { 
            $logro_sesion = $logro_sesion.$log_ses[$i]."\r\n";
        }*/
        $pdf->Row(array(utf8_decode('Logro de la sesión'), utf8_decode(": " . $r_b_sesion['logro_sesion'])));


        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. SECUENCIA DIDÁCTICA"), 1, 1, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode("Momento"), 1, 0, 'C', 1);
        $pdf->Cell(99, 6, utf8_decode("Estrategías y Actividades"), 1, 0, 'C', 1);
        $pdf->Cell(36, 6, utf8_decode("Recursos Didácticas"), 1, 0, 'C', 1);
        $pdf->Cell(15, 6, utf8_decode("Tiempo"), 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(25, 99, 36, 15));
        $b_momentos_sesion = buscarMomentosSesionByIdSesion($conexion, $id_sesion);
        while ($r_b_momentos_sesion = mysqli_fetch_array($b_momentos_sesion)) {
            $estrategia_actividad = "* Estrategía: \r\n";
            $estrategia_actividad = $estrategia_actividad . "" . $r_b_momentos_sesion['estrategia'] . "\r\n";

            $estrategia_actividad = $estrategia_actividad . "* Actividades: \r\n";
            $estrategia_actividad = $estrategia_actividad . "" . $r_b_momentos_sesion['actividad'] . "\r\n";

            $recursos_didacticos = "";
            $recursos_didacticos = $recursos_didacticos . "" . $r_b_momentos_sesion['recursos'] . "\r\n";



            $pdf->Row(array(utf8_decode("\r\n \r\n \r\n  " . $r_b_momentos_sesion['momento']), utf8_decode($estrategia_actividad), utf8_decode($recursos_didacticos), utf8_decode("\r\n \r\n \r\n      " . $r_b_momentos_sesion['tiempo'])));
        }
        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);

        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. ACTIVIDADES DE EVALUACIÓN"), 1, 1, 'C', 1);
        $pdf->Cell(60, 6, utf8_decode("Indicadores de logro de la sesión"), 1, 0, 'C', 1);
        $pdf->Cell(25, 6, utf8_decode("Técnicas"), 1, 0, 'C', 1);
        $pdf->Cell(35, 6, utf8_decode("Instrumentos"), 1, 0, 'C', 1);
        $pdf->Cell(35, 6, utf8_decode("Peso o Porcentaje"), 1, 0, 'C', 1);
        $pdf->Cell(20, 6, utf8_decode("Momento"), 1, 1, 'C', 1);

        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(60, 25, 35, 35, 20));

        $b_actividades_eval = buscarActividadesEvaluacionByIdSesion($conexion, $id_sesion);
        while ($r_b_actividades_eval = mysqli_fetch_array($b_actividades_eval)) {
            $indicador_log = "";
            $indicador_log = $indicador_log . $r_b_actividades_eval['indicador_logro_sesion'] . "\r\n";
            $tecnicas = "";
            $tecnicas = $r_b_actividades_eval['tecnica'] . "\r\n";
            $instrumentos = "";
            $instrumentos = $r_b_actividades_eval['instrumentos'] . "\r\n";
            $pdf->Row(array(utf8_decode($indicador_log), utf8_decode($tecnicas), utf8_decode($instrumentos), utf8_decode("                " . $r_b_actividades_eval['peso']), utf8_decode($r_b_actividades_eval['momento'])));
        }
        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. BIBLIOGRAFÍA"), 1, 1, 'C', 1);
        $pdf->Cell(88, 6, utf8_decode("Para el docente"), 1, 0, 'C', 1);
        $pdf->Cell(87, 6, utf8_decode("Para el Estudiante"), 1, 1, 'C', 1);
        $pdf->Cell(88, 6, utf8_decode("Obligatoria"), 1, 0, 'C', 1);
        $pdf->Cell(87, 6, utf8_decode("Obligatoria"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $pdf->SetWidths(array(88, 87));
        $bib_doc_obl = "";
        $bib_doc_obl = $r_b_sesion['bibliografia_obligatoria_docente'] . "\r\n";

        $bib_est_obl = "";
        $bib_est_obl = $r_b_sesion['bibliografia_obligatoria_estudiantes'] . "\r\n";

        $pdf->Row(array(utf8_decode($bib_doc_obl), utf8_decode($bib_est_obl)));
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(88, 6, utf8_decode("Opcional"), 1, 0, 'C', 1);
        $pdf->Cell(87, 6, utf8_decode("Opcional"), 1, 1, 'C', 1);
        $pdf->SetFont('Arial', '', 10);
        $bib_doc_opc = "";
        $bib_doc_opc = $r_b_sesion['bibliografia_opcional_docente'] . "\r\n";

        $bib_est_opc = "";
        $bib_est_opc = $r_b_sesion['bibliografia_opcional_estudiante'] . "\r\n";

        $pdf->Row(array(utf8_decode($bib_doc_opc), utf8_decode($bib_est_opc)));

        $pdf->Cell(180, 3, '', 0, 1, 'C', 0);
        $pdf->SetFont('Arial', 'B', 10);
        $pdf->Cell(175, 6, utf8_decode("I. ANEXOS"), 1, 1, 'C', 1);
        $anexos = "";
        $anexos = $r_b_sesion['anexos'] . "\r\n";

        $pdf->MultiCell(175, 6, utf8_decode($anexos), 1, 'J', 0);


        $pdf->Output($titulo, 'I');

    }
}