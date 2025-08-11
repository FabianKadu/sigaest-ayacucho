<?php

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include "../caja/consultas.php";
include("../functions/funciones.php");
include("include/verificar_sesion_secretaria.php");

$tipo = $_POST['tipo'];
$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

if (!verificar_sesion($conexion)) {
    echo "<script>
  alert('Usted no tiene los privilegios para generar reportes.');
  window.location.replace('../include/login.php');
</script>";
} else {
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

    //DATOS


    $datos_iestp = buscarDatosSistema($conexion);
    $datos_iestp = mysqli_fetch_array($datos_iestp);
    $nombre_insti = str_replace("IESTP ", "", $datos_iestp['titulo']);

    require_once('../tcpdf/tcpdf.php');

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

    //CONFIGURACIÓN PDF
    $pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetTitle("REPORTE DE CAJA");
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->SetFont('helvetica', '', 11);
    $text_size = 9;

    $monto_total = 0.00;

    if ($tipo == 'ingresos') {
        $pdf->AddPage('L', 'A4');
        $documento = '
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="3" align="center"><font size="11"><b>REPORTE DE INGRESOS DEL ' . $inicio . ' AL ' . $fin . ' - IESTP "' . $nombre_insti . '"</b></font></td>
            </tr>        
        </table>
        <br />
        <br />
    ';

        $documento .= '
        <table border="1" cellspacing="0" cellpadding="3">
            <tr bgcolor="#DDDDDD">
                <th width="10%" align="center"><font size="7">COMPROBANTE</font></th>
                <th width="5%" align="center"><font size="7">SERIE-NÚMERO</font></th>
                <th width="5%" align="center"><font size="7">DNI/RUC</font></th>
                <th width="15%" align="center"><font size="7">NOMBRE CLIENTE </font></th>
                <th width="5%" align="center"><font size="7">CANT.</font></th>
                <th width="15%" align="center"><font size="7">CONCEPTO</font></th>
                <th width="5%" align="center"><font size="7">PRECIO UNIT.</font></th>
                <th width="5%" align="center"><font size="7">SUB TOTAL</font></th>
                <th width="10%" align="center"><font size="7">METODO PAGO</font></th>
                <th width="10%" align="center"><font size="7">FECHA REGISTRO </font></th>
                <th width="15%" align="center"><font size="7">USUARIO CAJA</font></th>
            </tr>
    ';

        $busc_ingr = buscarDetalleIngresosByFechas($conexion, $inicio, $fin);
        while ($ingresos = mysqli_fetch_array($busc_ingr)) {
            $documento .= '
            <tr>
                <td align="left"><font size="6">' . $ingresos['tipo_comprobante'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['codigo'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['dni'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['usuario'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['cantidad'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['concepto'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['monto'] . '</font></td>
                <td align="rigth"><font size="6">' . $ingresos['subtotal'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['metodo_pago'] . '</font></td>
                <td align="rigth"><font size="6">' . $ingresos['fecha_pago'] . '</font></td>
                <td align="left"><font size="6">' . $ingresos['responsable'] . '</font></td>
            </tr>
        ';
            $monto_total += $ingresos['subtotal'];
        }

        // Cerrar la tabla
        $documento .= '</table>
    <br> <br><br>  <tr>
        <td colspan = "11" align="center" ><b>RESÚMEN GENERAL</b></td>
    </tr>     <br>';


        $documento .= '
    <table border="1" cellspacing="0" cellpadding="3">
        <tr bgcolor="#DDDDDD">
            <th width="20%" align="center"><font size="8">FECHA INICIAL</font></th>
            <th width="20%" align="center"><font size="8">FECHA FINAL</font></th>
            <th width="40%" align="center"><font size="8">DESCRIPCIÓN</font></th>
            <th width="20%" align="center"><font size="8">MONTO TOTAL</font></th>

        </tr>
        <tr>
            <td align="center"><font size="8">' . $inicio . '</font></td>
            <td align="center"><font size="8">' . $fin . '</font></td>
            <td align="center"><font size="8"> REPORTE GENERAL DE ' . strtoupper($tipo) . '</font></td>
            <td align="center"><font size="8">' . number_format($monto_total, 2, '.', '') . '</font></td>
        </tr>        
    </table>
    ';

    }
    if ($tipo == 'egresos') {
        $pdf->AddPage('L', 'A4');
        $documento = '
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="3" align="center"><font size="11"><b>REPORTE DE EGRESOS DEL ' . $inicio . ' AL ' . $fin . ' - IESTP "' . $nombre_insti . '"</b></font></td>
            </tr>        
        </table>
        <br />
        <br />
    ';

        $documento .= '
        <table border="1" cellspacing="0" cellpadding="3">
            <tr bgcolor="#DDDDDD">
                <th width="10%" align="center"><font size="8">COMPROBANTE</font></th>
                <th width="10%" align="center"><font size="8">SERIE-NÚMERO</font></th>
                <th width="10%" align="center"><font size="8">CONCEPTO</font></th>
                <th width="10%" align="center"><font size="8">DNI / RUC</font></th>
                <th width="15%" align="center"><font size="8">NOMBRE PROVEEDOR</font></th>
                <th width="10%" align="center"><font size="8">MONTO TOTAL</font></th>
                <th width="10%" align="center"><font size="8">FECHA PAGO</font></th>
                <th width="10%" align="center"><font size="8">FECHA REGISTRO </font></th>
                <th width="15%" align="center"><font size="8">USUARIO CAJA</font></th>
            </tr>
    ';

        $busc_ingr = buscarEgresosByFechas($conexion, $inicio, $fin);
        while ($egresos = mysqli_fetch_array($busc_ingr)) {
            $documento .= '
            <tr>
                <td align="left"><font size="7">' . $egresos['tipo_comprobante'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['comprobante'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['concepto'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['ruc'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['empresa'] . '</font></td>
                <td align="rigth"><font size="7">' . $egresos['monto_total'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['fecha_pago'] . '</font></td>
                <td align="rigth"><font size="7">' . $egresos['fecha_registro'] . '</font></td>
                <td align="left"><font size="7">' . $egresos['responsable'] . '</font></td>
            </tr>
        ';
            $monto_total += $egresos['monto_total'];
        }

        // Cerrar la tabla
        $documento .= '</table>
    <br> <br><br>  <tr>
        <td colspan = "9" align="center" ><b>RESÚMEN GENERAL</b></td>
    </tr>     <br>';


        $documento .= '
    <table border="1" cellspacing="0" cellpadding="3">
        <tr bgcolor="#DDDDDD">
            <th width="20%" align="center"><font size="8">FECHA INICIAL</font></th>
            <th width="20%" align="center"><font size="8">FECHA FINAL</font></th>
            <th width="40%" align="center"><font size="8">DESCRIPCIÓN</font></th>
            <th width="20%" align="center"><font size="8">TOTAL INGRESOS</font></th>

        </tr>
        <tr>
            <td align="center"><font size="8">' . $inicio . '</font></td>
            <td align="center"><font size="8">' . $fin . '</font></td>
            <td align="center"><font size="8"> REPORTE GENERAL DE ' . strtoupper($tipo) . '</font></td>
            <td align="center"><font size="8">' . number_format($monto_total, 2, '.', '') . '</font></td>
        </tr>        
    </table>
    ';

    }
    if ($tipo == 'Flujo-Caja') {

        $pdf->AddPage('P', 'A4');

        $total_ingreso_encrypted = buscarTotalIngresoByFechas($conexion, $inicio, $fin);
        $total_ingreso = 0;
        foreach ($total_ingreso_encrypted as $monto_encrypted) {
            $total_ingreso += floatval(decryptText($monto_encrypted));
        }
        $total_egreso_encrypted = buscarTotalEgresoByFechas($conexion, $inicio, $fin);
        $total_egreso = 0;
        foreach ($total_egreso_encrypted as $monto_encrypted) {
            $total_egreso += floatval(decryptText($monto_encrypted));
        }
        $saldo_inicial = buscarSaldoIncialByFechaInicio($conexion, $inicio);
        $diferencia = $total_ingreso - $total_egreso;

        $documento = '
        <table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan="3" align="center"><font size="11"><b>REPORTE DE FLUJO DE CAJA DEL ' . $inicio . ' AL ' . $fin . ' - IESTP "' . $nombre_insti . '"</b></font></td>
            </tr>        
        </table>
        <br />
        <br />
    ';

        $documento .= '
        <table border="0.1" cellspacing="0" cellpadding="3">
            <tr bgcolor="#DDDDDD">
                <th width="25%" align="center"><font size="8">TIPO</font></th>
                <th width="50%" align="center"><font size="8">CONCEPTO</font></th>
                <th width="25%" align="center"><font size="8">TOTAL ACUMULADO</font></th>
            </tr>
    ';

        $busc_ingr = buscarDetalleFlujoCaja($conexion, $inicio, $fin);
        foreach ($busc_ingr as $flujo_caja) {
            $documento .= '
            <tr>
                <td align="left"><font size="7">' . $flujo_caja['tipo'] . '</font></td>
                <td align="left"><font size="7">' . $flujo_caja['concepto'] . '</font></td>
                <td align="left"><font size="7">' . $flujo_caja['suma_subtotales'] . '</font></td>
            </tr>
        ';
        }

        // Cerrar la tabla
        $documento .= '</table>
    <br> <br><br>  <tr>
        <td colspan = "8" align="center" ><b>RESÚMEN GENERAL</b></td>
    </tr>     <br>';


        $documento .= '
    <table border="1" cellspacing="0" cellpadding="3">
        <tr bgcolor="#DDDDDD">
            <th width="20%" align="center"><font size="8">SALDO INICIAL</font></th>
            <th width="20%" align="center"><font size="8">TOTAL INGRESO</font></th>
            <th width="20%" align="center"><font size="8">TOTAL EGRESO</font></th>
            <th width="20%" align="center"><font size="8">UTILIDAD DEL PERIODO</font></th>
            <th width="20%" align="center"><font size="8">SALDO FINAL</font></th>

        </tr>
        <tr>
            <td align="center"><font size="8">' . $saldo_inicial + 0 . '</font></td>
            <td align="center"><font size="8">' . $total_ingreso . '</font></td>
            <td align="center"><font size="8">' . $total_egreso . '</font></td>
            <td align="center"><font size="8">' . $diferencia . '</font></td>
            <td align="center"><font size="8">' . $saldo_inicial + $diferencia . '</font></td>
        </tr>        
    </table>
    ';
    }


    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($documento, true, false, true, false, '');
    $pdfContent = $pdf->Output('reporte-' . $tipo . '.pdf', 'D');
}
;
?>