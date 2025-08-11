<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");
include("include/verificar_sesion_caja.php");

include_once("../PHP_XLSXWriter/xlsxwriter.class.php");

$tipo = $_POST['tipo'];
$inicio = $_POST['inicio'];
$fin = $_POST['fin'];

if (!verificar_sesion($conexion)) {
    echo "<script>
  alert('Usted no tiene los privilegios para generar reportes.');
  window.location.replace('../include/login.php');
    </script>";
} else {

    /*header ("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header ("Content-Disposition: attachment; filename=plantilla.xls");*/

    $titulo_archivo = "Reporte_" . $tipo . "_" . $inicio . "_" . $fin;

    //generamos excel
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
    error_reporting(E_ALL & ~E_NOTICE);

    $filename = $titulo_archivo . ".xlsx";
    header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
    header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
    header('Content-Transfer-Encoding: binary');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    $writer = new XLSXWriter();

    $styles1 = array('font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom');

    if ($tipo == 'ingresos') {
        $ord = 1;
        $header = array(
            'N°' => 'N°',
            'COMPROBANTE' => 'COMPROBANTE', //text
            'SERIE' => 'SERIE',
            'NRO' => 'NRO',
            'DNI / RUC' => 'DNI / RUC', //text
            'NOMBRE CLIENTE' => 'NOMBRE CLIENTE', //text
            'CANTIDAD' => 'CANTIDAD', //text
            'CONCEPTO' => 'CONCEPTO', //text
            'PRECIO UNIT.' => 'PRECIO UNIT.', //text
            'SUB TOTAL' => 'SUB TOTAL',
            'METODO PAGO' => 'METODO PAGO',
            'FECHA REGISTRO' => 'FECHA REGISTRO',
            'USUARIO CAJA' => 'USUARIO CAJA',
        );

        //imprime encabezado
        $writer->writeSheetRow('Plantilla', $header, $styles1);

        $busc_ingr = buscarDetalleIngresosByFechas($conexion, $inicio, $fin);
        while ($ingresos = mysqli_fetch_array($busc_ingr)) {
            //imprime contenido
            $codigo = explode("-", $ingresos['codigo']);

            $writer->writeSheetRow('Plantilla', $rowdata = array(
                $ord,
                $ingresos['tipo_comprobante'],
                $codigo[0],
                $codigo[1],
                $ingresos['dni'],
                $ingresos['usuario'],
                $ingresos['cantidad'],
                $ingresos['concepto'],
                floatval(decryptText($ingresos['monto'])),
                floatval(decryptText($ingresos['subtotal'])),
                $ingresos['metodo_pago'],
                $ingresos['fecha_pago'],
                $ingresos['responsable']
            ), $styles9);

            $ord += 1;
        }


        $writer->writeToStdOut();

    }
    if ($tipo == 'egresos') {
        $ord = 1;
        $header = array(
            'N°' => 'N°',
            'COMPROBANTE' => 'COMPROBANTE', //text
            'SERIE' => 'SERIE',
            'NRO' => 'NRO',
            'DNI / RUC' => 'DNI / RUC', //text
            'NOMBRE PROVEEDOR' => 'NOMBRE PROVEEDOR', //text
            'MONTO TOTAL' => 'MONTO TOTAL',
            'METODO EGRESO' => 'FECHA EGRESO',
            'FECHA REGISTRO' => 'FECHA REGISTRO',
            'USUARIO CAJA' => 'USUARIO CAJA',
        );

        //imprime encabezado
        $writer->writeSheetRow('Plantilla', $header, $styles1);
        $busc_ingr = buscarEgresosByFechas($conexion, $inicio, $fin);
        while ($egresos = mysqli_fetch_array($busc_ingr)) {
            $codigo = explode("-", $egresos['comprobante']);

            $writer->writeSheetRow('Plantilla', $rowdata = array(
                $ord,
                $egresos['tipo_comprobante'],
                $codigo[0],
                $codigo[1],
                $egresos['ruc'],
                $egresos['usuario'],
                floatval(decryptText($egresos['monto_total'])),
                $egresos['fecha_pago'],
                $egresos['fecha_registro'],
                $egresos['responsable']
            ), $styles9);

            $ord += 1;
        }
        $writer->writeToStdOut();

    }
    if ($tipo == 'Flujo-Caja') {

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

        $ord = 1;

        $header = array(
            'N°' => 'N°',
            'TIPO' => 'TIPO',
            'CONCEPTO' => 'CONCEPTO',
            'TOTAL ACUMULADO' => 'TOTAL ACUMULADO', //text
        );

        //imprime encabezado
        $writer->writeSheetRow('Plantilla', $header, $styles1);
        $busc_ingr = buscarDetalleFlujoCaja($conexion, $inicio, $fin);
        foreach ($busc_ingr as $flujo_caja) {
            $codigo = explode("-", $flujo_caja['comprobante']);

            $writer->writeSheetRow('Plantilla', $rowdata = array(
                $ord,
                $flujo_caja['tipo'],
                $flujo_caja['concepto'],
                $flujo_caja['suma_subtotales']
            ), $styles9);

            $ord += 1;
        }

        // Agrega datos adicionales al final
        $writer->writeSheetRow('Plantilla', array('', ''), $styles9);

        $writer->writeSheetRow('Plantilla', array('SALDO INICIAL', $saldo_inicial + 0), $styles9);
        $writer->writeSheetRow('Plantilla', array('TOTAL INGRESOS', $total_ingreso), $styles9);
        $writer->writeSheetRow('Plantilla', array('TOTAL EGRESOS', $total_egreso), $styles9);
        $writer->writeSheetRow('Plantilla', array('DIFERENCIA', $diferencia), $styles9);
        $writer->writeSheetRow('Plantilla', array('SALDO FINAL', $saldo_inicial + $diferencia), $styles9);


        $writer->writeToStdOut();

    }
    exit(0);
}
?>