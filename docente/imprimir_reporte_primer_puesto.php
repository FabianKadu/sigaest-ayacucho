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

require_once('../tcpdf/tcpdf.php');
setlocale(LC_ALL, "es_ES");

$id_pe = $_POST['car_consolidado'];
$id_sem = $_POST['sem_consolidado'];

$per_select = $_SESSION['periodo'];

if ($_SESSION['id_sesion']) {
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

    $b_pe = buscarCarrerasById($conexion, $id_pe);
    $r_b_pe = mysqli_fetch_array($b_pe);

    $b_sem = buscarSemestreById($conexion, $id_sem);
    $r_b_sem = mysqli_fetch_array($b_sem);

    $per_select = $_SESSION['periodo'];

    $b_per = buscarPeriodoAcadById($conexion, $per_select);
    $r_b_per = mysqli_fetch_array($b_per);
    $array_estudiantes = [];
    // armar la nomina de estudiantes para poder mostrar todos los estudiantes del semestre
    $b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);
    $cont_ud_sem = mysqli_num_rows($b_ud_pe_sem);
    $cont_ind_capp = 0;
    $suma_creditos = 0;
    while ($r_b_ud = mysqli_fetch_array($b_ud_pe_sem)) {
        $id_ud = $r_b_ud['id'];
        $suma_creditos += $r_b_ud['creditos'];
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


    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Reporte Primeros Puestos - " . $r_b_pe['nombre'] . " - " . $r_b_sem['descripcion']);
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


    //crear el contenido 

    $contenido = '';

    $primeros_puestos = [];
    foreach ($n_array_estudiantes as $key => $val) {
        $key += 1;
        //buscar estudiante para su id
        $b_est = buscarEstudianteByApellidosNombres($conexion, $val);
        $r_b_est = mysqli_fetch_array($b_est);
        $id_est = $r_b_est['id'];

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
                //aqui se trae los convalidados
                $res_convalidados = buscarConvalidacionesPorEstudiantePeriodoSemestre($conexion, $id_est,$per_select, $id_sem);
                while ($convalidaciones = mysqli_fetch_array($res_convalidados)) {

                    $suma_evaluacion = (int) encryptText($convalidaciones['calificacion']);
                    //buscamos las evaluaciones
                    $suma_calificacion += $suma_evaluacion;
                    if ($suma_evaluacion > 0) {
                        $cont_calif += 1;
                    }
                }
                if ($cont_calif > 0) {
                    $calificacion = round($suma_calificacion / $cont_calif);
                } else {
                    $calificacion = round($suma_calificacion);
                }
                if ($calificacion != 0) {
                    $calificacion = round($calificacion);
                } else {
                    $calificacion = "";
                }
                //buscamos si tiene recuperacion
                if ($r_b_det_mat_est['recuperacion'] != '') {
                    $calificacion = $r_b_det_mat_est['recuperacion'];
                }
                if ($calificacion > 12) {
                } else {
                    $cont_ud_desaprobadas += 1;
                }
            } else {
                $calificacion = 0;
                //echo '<td></td>';
            }
            if (is_numeric($calificacion)) {
                $suma_califss += $calificacion;
                $suma_ptj_creditos += $calificacion * $r_bb_ud['creditos'];
            } else {
                $suma_ptj_creditos += 0 * $r_bb_ud['creditos'];
            }
        }
        $primeros_puestos[$id_est] = $suma_ptj_creditos;
    }
    arsort($primeros_puestos);

    //los estudiantes que desaprobaron alguna UD se pasan al final de la lista
    foreach ($primeros_puestos as $key => $value) {
        $cant_ud_desaprobado = calc_ud_desaprobado_sin_recuperacion($conexion, $key, $per_select, $id_pe, $id_sem);
       if ($cant_ud_desaprobado>0) {
            $id_est_des = $key;
            $ptj_est_des = $value;
            unset($primeros_puestos[$key]);
            $primeros_puestos[$id_est_des] = $ptj_est_des;
       }
       
    }
    // los estudiantes de repitencia pasan al ultimo del ranking
    foreach ($primeros_puestos as $key => $value) {
        $mat_todos = calcular_mat_ud($conexion, $key, $per_select, $id_pe, $id_sem);
        if ($mat_todos == 0) {
            $id_est_des = $key;
            $ptj_est_des = $value;
            unset($primeros_puestos[$key]);
            $primeros_puestos[$id_est_des] = $ptj_est_des;
        }
    }
    
    $cont = 0;
    foreach ($primeros_puestos as $key => $value) {
        $cont += 1;
        $b_estt = buscarEstudianteById($conexion, $key);
        $r_b_estt = mysqli_fetch_array($b_estt);
        $contenido .= '
        <tr>
            <td border="0.2" align="center">' . $cont . ' º Puesto</td>
            <td border="0.2" align="center">' . $r_b_estt['dni'] . '</td>
            <td border="0.2">' . $r_b_estt['apellidos_nombres'] . '</td>
            <td border="0.2" align="center">' . $value . '</td>
            <td border="0.2" align="center">' . round($value/$suma_creditos, 2) . '</td>
        </tr>
        ';
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
            <td colspan="4" align="center"><b>REPORTE PRIMEROS PUESTOS - ' . $r_b_pe['nombre'] . ' - SEMESTRE ' . $r_b_sem['descripcion'] . ' ' . $r_b_per['nombre'] . '</b></td>
        </tr>
        <tr bgcolor="#CCCCCC">
            <td width="10%" align="center" border="0.2"><b>ORDEN DE MÉRITO</b></td>
            <td width="12%" align="center" border="0.2"><b>DNI</b></td>
            <td width="50%" align="center" border="0.2"><b>APELLIDOS Y NOMBRES</b></td>
            <td width="15%" align="center" border="0.2"><b>PUNTAJE TOTAL CRÉDITOS</b></td>
            <td width="13%" align="center" border="0.2"><b>PROMEDIO PONDERADO</b></td>
        </tr>
                
          ';

    $content_one .= $contenido;
    $content_one .= '</table>';
    $pdf->writeHTML($content_one);

    $footer = '

        <table border="0" cellspacing="0" cellpadding="0.5">  
        <tr>
            <th><b>NOTA:</b></th>
            
        </tr>
        <tr>
            <td >- Los estudiantes que tienen 1 o más unidades didácticas desaprobadas no participan el en ranking de los primeros puestos, Aún teniendo el más alto puntaje.</td>
        </tr>
        <tr>
            <td >- Los estudiantes matriculados en unidades didácticas de repitencia no participan el en ranking de los primeros puestos.</td>
        </tr>
        </table>

      ';
    $pdf->writeHTML($footer);








    $pdf->Output('Reporte Primeros Puestos - ' . $r_b_pe['nombre'] . ' '.$r_b_sem['descripcion'].'.pdf', 'I');
}
}