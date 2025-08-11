<?php

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");

function generarSolicitudMatricula($id_solicitud_matricula, $conexion)
{

    $solicitud_matricula = buscarSolicitudeMatriculaId($conexion, $id_solicitud_matricula);
    $solicitud_matricula = mysqli_fetch_array($solicitud_matricula);
    //datos alumno
    $id_estudiante = $solicitud_matricula['id_estudiante'];
    $estudiante = buscarEstudianteById($conexion, $id_estudiante);
    $estudiante = mysqli_fetch_array($estudiante);
    //datos programa
    $id_programa = $estudiante['id_programa_estudios'];
    $programa = buscarCarrerasById($conexion, $id_programa);
    $programa = mysqli_fetch_array($programa);

    //programas seleccionados
    $programas = explode(",", $solicitud_matricula['programas']);

    require_once('../tcpdf/tcpdf.php');

    class MYPDF extends TCPDF {}

    //CONFIGURACIÓN PDF
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("Soliciutd de matrícula");
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

    $documento = '
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="center"><b>SOLICITUD DE MATRÍCULA</b></td>
                </tr>
                <br />
                <tr>
                    <td><b>Datos del alumno</b></td>
                </tr>
                <tr>
                    <td align="left">
                    <font size="10" style="line-height: 2; text-align: justify;">DNI: ' . strtoupper($estudiante['dni']) . ' </font></td>
                </tr>
                <tr>
                    <td align="left">
                    <font size="10" style="line-height: 2; text-align: justify;">Apellidos y nombres: ' . strtoupper($estudiante['apellidos_nombres']) . ' </font></td>
                </tr>
                <tr>
                    <td align="left">
                    <font size="10" style="line-height: 2; text-align: justify;">Celular: ' . strtoupper($estudiante['telefono']) . ' </font></td>
                </tr>
                <br />
                <tr>
                    <td><b>Datos académicos</b></td>
                </tr>
                <tr>
                    <td align="left">
                    <font size="10" style="line-height: 2; text-align: justify;">Programa: ' . strtoupper($programa['nombre']) . ' </font></td>
                </tr>
                <tr>
                    <td align="left">
                    <font size="10" style="line-height: 2; text-align: justify;">Plan de estudios: ' . strtoupper($programa['plan_estudio']) . ' </font></td>
                </tr>
               <br />
                <tr>
                    <td><b>Unidades didácticas seleccionadas</b></td>
                </tr>
            </table>
        ';

    $documento .= '
                    
    <br />
    <br />
    <table border="1" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <td align="center" width="10%"><b>N°</b></td>
                        <td align="center" width="60%"><b>Unidad didáctica</b></td>
                        <td align="center" width="15%"><b>Semestre</b></td>
                        <td align="center" width="15%"><b>Créditos</b></td>
                    </tr>';

    //iterar programas}
    $total_creditos = 0;
    for ($i = 0; $i < count($programas); $i++) {
        $programa_res = buscarUdById($conexion, $programas[$i]);
        $programa = mysqli_fetch_array($programa_res);
        $documento .= '
                    
                    <tr>
                        <td>  ' . ($i + 1) . '</td>
                        <td>  ' . strtoupper($programa['descripcion']) . '</td>
                        <td>  ' . strtoupper($programa['id_semestre']) . '</td>
                        <td>  ' . strtoupper($programa['creditos']) . '</td>
                    </tr>';
        $total_creditos += $programa['creditos'];
    }

    $documento .= '</table>
        <br /><br />
       <table>
            <tr>
                <td><b>Resumen</b></td>
            </tr>
            <tr>
                <td align="left">
                <font size="10" style="line-height: 2; text-align: justify;">Cantidad de cursos: ' . strtoupper(count($programas)) . ' </font></td>
            </tr>
            <tr>
                <td align="left">
                <font size="10" style="line-height: 2; text-align: justify;">Total de créditos: ' . $total_creditos . ' </font></td>
            </tr>
            <tr>
                <td align="left">
                <font size="10" style="line-height: 2; text-align: justify;">Fecha de solcicitud: ' . convertirFormatoFecha($solicitud_matricula['create_at']) . ' </font></td>
            </tr>
       </table>

    ';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($documento, true, false, true, false, '');
    // Guardar el contenido en el archivo
    $pdfContent = $pdf->Output('Solicitud de matricula.pdf', 'I');
}

if ($_GET['id']) {
    generarSolicitudMatricula($_GET['id'], $conexion);
}

mysqli_close($conexion);
