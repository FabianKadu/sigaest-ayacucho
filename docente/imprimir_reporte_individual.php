<?php
require_once('../tcpdf/tcpdf.php');
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_coordinador.php';

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
            </script>";
    exit();
}

$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
$b_docente = buscarDocenteById($conexion, $id_docente_sesion);
$r_b_docente = mysqli_fetch_array($b_docente);

$id_est = $_POST['id_est'];
$per_select = $_SESSION['periodo'];

//buscar matricula de estudiante
$b_mat = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $per_select);
$r_b_mat = mysqli_fetch_array($b_mat);
$id_mat_est = $r_b_mat['id'];

$b_estudiante = buscarEstudianteById($conexion, $id_est);
$r_b_estudiante = mysqli_fetch_array($b_estudiante);

$b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
$cant_ud_mat = mysqli_num_rows($b_det_mat);
$cont_ind_capp = 0;
while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
    $id_prog = $r_b_det_mat['id_programacion_ud'];
    $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
    $r_b_prog = mysqli_fetch_array($b_prog_ud);
    $id_udd = $r_b_prog['id_unidad_didactica'];
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
    $cont_ind_capp += $cont_ind_logro_cap_ud;
}
$total_columnas = $cont_ind_capp + $cant_ud_mat + 3;

// Crear nuevo PDF
$pdf = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Nombre del Autor');
$pdf->SetTitle('Reporte Individual');
$pdf->SetSubject('Reporte Individual');
$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

// Establecer márgenes
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);


// Establecer saltos de página automáticos
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);


// Añadir una página
$pdf->AddPage();

$pdf->Image('../img/logo.jpg', 15, 5, 40, 0, 'JPG');
$pdf->Image('../img/logo_minedu.png', 230, 5, 50, 0, 'PNG');

$style = '
 <style>
            .pad-UD{
                padding: 0px 0px 0px 0px;
            }
            .verticalll {
                writing-mode: vertical-lr;
                transform: rotate(180deg);
            }

            table {
                width: 100%;
                border-collapse: collapse;
                justify-content: center;
            }


            /* Fijar las primeras dos columnas */
            tbody td:nth-child(1),
            tbody td:nth-child(2) {
                position: sticky;
                left: 0;
                background-color: #fff;
                /* Fondo para que no se vea afectado por el scroll */
                z-index: 100;
                /* Asegurarse que las columnas fijas queden encima del resto */
            }

            /* Ajustar las columnas que se desplazan para no superponer las fijas */
            tbody td:nth-child(2) {
                left: 70px;
            }
        </style>

';

// Establecer fuente
$pdf->SetFont('helvetica', '', 10);

// Contenido HTML
$html = $style . '
<h2 align="center"><b>REPORTE INDIVIDUAL - ' . $r_b_estudiante['dni'] . ' - ' . $r_b_estudiante['apellidos_nombres'] . '</b></h2>
<p></p>
<p></p>
<p></p>
<table cellpadding="1" border="1" align="center">
    <thead>
        <tr>
            <th colspan="' . $total_columnas + $cant_ud_mat + 5 . '" bgcolor="black" color="white" align="center"  >
                <center>CALIFICACIONES- UNIDADES DIDÁCTICAS</center>
            </th>
        </tr>
        <tr>';

$b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
    $id_prog = $r_b_det_mat['id_programacion_ud'];
    $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
    $r_b_prog = mysqli_fetch_array($b_prog_ud);
    $id_udd = $r_b_prog['id_unidad_didactica'];
    //BUSCAR UD
    $b_uddd = buscarUdById($conexion, $id_udd);
    $r_b_udd = mysqli_fetch_array($b_uddd);

    $b_Semestre = buscarSemestreById($conexion, $r_b_udd['id_semestre']);
    $r_b_semestre = mysqli_fetch_array($b_Semestre);
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

    $html .= '
    <th colspan="' . $cont_ind_logro_cap_ud . '">
                <p class="verticalll">
                    <center>' . $r_b_udd['descripcion'] . '<br>S-' . $r_b_semestre['descripcion'] . '</center>
                </p>
              </th>
              <th  colspan="2">
                <p class="verticalll">Prom.</p>
              </th>';
}

$html .= '<th colspan="2">
            <p class="verticalll">Ptj. Total</p>
          </th>
          <th  colspan="2">
            <p class="verticalll">Ptj. Créditos</p>
          </th>
          <th  colspan="4">
            <p>
                <center>CONDICIÓN</center>
            </p>
          </th>
        </tr>
    </thead>
    <tbody>';

$b_est = buscarEstudianteById($conexion, $id_est);
$r_b_est = mysqli_fetch_array($b_est);

$b_ud_pe_sem = buscarUdByCarSem($conexion, $r_b_est['id_programa_estudios'], $r_b_est['id_semestre']);
$min_ud_desaprobar = round(mysqli_num_rows($b_ud_pe_sem) / 2, 0, PHP_ROUND_HALF_DOWN);

$html .= '<tr>';

$suma_califss = 0;
$suma_ptj_creditos = 0;
$cont_ud_desaprobadas = 0;
$b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
    $b_prog = buscarProgramacionById($conexion, $r_b_det_mat['id_programacion_ud']);
    $r_b_prog = mysqli_fetch_array($b_prog);
    $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
    $r_bb_ud = mysqli_fetch_array($b_ud);

    $id_det_mat = $r_b_det_mat['id'];

    $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);

    $suma_calificacion = 0;
    $cont_calif = 0;
    while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {
        $id_calificacion = $r_b_calificacion['id'];
        $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);

        if ($suma_evaluacion != 0) {
            $cont_calif += 1;
            $suma_calificacion += $suma_evaluacion;
            $suma_evaluacion = round($suma_evaluacion);
            if ($suma_evaluacion > 12) {
                $html .= '<td><center><font color="blue">' . $suma_evaluacion . '</font></center></td>';
            } else {
                $html .= '<td><center><font color="red">' . $suma_evaluacion . '</font></center></td>';
            }
        } else {
            $suma_evaluacion = "";
            $html .= '<th></th>';
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
    if ($r_b_det_mat['recuperacion'] != '') {
        $calificacion = $r_b_det_mat['recuperacion'];
    }

    if ($calificacion > 12) {
        $html .= '<th align="center" bgcolor="#BEBBBB" colspan="2"><font color="blue">' . $calificacion . '</font></th>';
    } else {
        $html .= '<th align="center" bgcolor="#BEBBBB" colspan="2"><font color="red">' . $calificacion . '</font></th>';
        $cont_ud_desaprobadas += 1;
    }
    if (is_numeric($calificacion)) {
        $suma_califss += $calificacion;
        $suma_ptj_creditos += $calificacion * $r_bb_ud['creditos'];
    } else {
        $suma_ptj_creditos += 0 * $r_bb_ud['creditos'];
    }
}
$html .= '<td align="center" colspan="2"><font color="black">' . $suma_califss . '</font></td>';
$html .= '<td align="center" colspan="2"><font color="black">' . $suma_ptj_creditos . '</font></td>';
if ($cont_ud_desaprobadas == 0) {
    $html .= '<td align="center"  colspan="4" ><font color="black">Promovido</font></td>';
} elseif ($cont_ud_desaprobadas <= $min_ud_desaprobar) {
    $html .= '<td align="center"  colspan="4" ><font color="black">Repite U.D. del Módulo Profesional</font></td>';
} else {
    $html .= '<td align="center"  colspan="4" ><font color="black">Repite el Módulo Profesional</font></td>';
}

$html .= '</tr>
    </tbody>
</table>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
';

// Añadir tablas de asistencia
$b_detalle_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
    $id_prog = $r_b_det_mat['id_programacion_ud'];
    $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
    $r_b_prog = mysqli_fetch_array($b_prog_ud);
    $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
    $r_b_ud = mysqli_fetch_array($b_ud);

    $html .= '<table border="1" cellpadding="1" align="center">
                <thead >
                    <tr>
                        <th bgcolor="black" color="white" colspan="20">
                            <center>ASISTENCIA - ' . $r_b_ud['descripcion'] . '</center>
                        </th>
                    </tr>
                    <tr >
                        <th colspan="3"><p class="verticalll">
                        <center>UNIDAD DIDÁCTICA</center>
                        </p></th>';

    $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
    $r_b_silabo = mysqli_fetch_array($b_silabo);
    $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
    while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
        $id_act = $res_b_prog_act['id'];
        $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
        while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
            $html .= '<th >
                        <p style="text-rotate:90;">' . $r_b_sesion['fecha_desarrollo'] . '</p>
                      </th>';
        }
    }

    $html .= '<th>Faltas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td colspan="3">' . $r_b_ud['descripcion'] . '</td>';

    $cont_inasistencia = 0;
    $cont_asis = 0;
    $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
    while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
        $id_act = $res_b_prog_act['id'];
        $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
        while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
            $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $id_est);
            $r_b_asistencia = mysqli_fetch_array($b_asistencia);
            $cont_asis += mysqli_num_rows($b_asistencia);
            if ($r_b_asistencia['asistencia'] == "P") {
                $html .= "<td><center><font color='blue'>" . $r_b_asistencia['asistencia'] . "</font></center></td>";
            } elseif ($r_b_asistencia['asistencia'] == "F") {
                $html .= "<td><center><font color='red'>" . $r_b_asistencia['asistencia'] . "</font></center></td>";
                $cont_inasistencia += 1;
            } else {
                $html .= "<td></td>";
            }
        }
    }
    if ($cont_inasistencia > 0) {
        $porcent_ina = $cont_inasistencia * 100 / $cont_asis;
    } else {
        $porcent_ina = 0;
    }
    if (round($porcent_ina) > 29) {
        $html .= "<td><font color='red'><center>" . round($porcent_ina) . "%</font></center></td>";
    } else {
        $html .= "<td><font color='blue'><center>" . round($porcent_ina) . "%</font></center></td>";
    }

    $html .= '</tr>
        </tbody>
    </table>';
}

$pdf->writeHTML($html, true, false, true, false, '');

// Guardar el PDF
$pdf->Output('reporte_individual.pdf', 'I');
?>