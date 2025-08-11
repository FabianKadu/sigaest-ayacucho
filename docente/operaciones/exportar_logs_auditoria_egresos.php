<?php
// Asegurarse de tener instalada la biblioteca PhpSpreadsheet via composer
require_once '../../vendor/autoload.php';
include("../../include/conexion.php");

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

// Consulta los datos de la tabla egresos
$query = "SELECT id, empresa, ruc, concepto, tipo_comprobante, comprobante, fecha_pago, fecha_registro, monto_total, estado, responsable FROM egresos";
$result = mysqli_query($conexion, $query);

// Crea el objeto Spreadsheet, establece la fuente por defecto y obtiene la hoja activa
$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
$sheet = $spreadsheet->getActiveSheet();

// Agrega título al Excel en la primera fila
$title = 'Logs de Egresos';
$sheet->setCellValue('A1', $title);
$sheet->mergeCells('A1:K1');
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF000000']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEEEEE']],
]);

// Deja una fila vacía (fila 2) y define la fila de encabezado en la fila 3
$headerRow = 3;
$headers = ['ID', 'Empresa', 'RUC', 'Concepto', 'Tipo Comprobante', 'Comprobante', 'Fecha de Pago', 'Fecha Registro', 'Monto Total', 'Estado', 'Responsable'];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $headerRow, $header);
    $col++;
}

// Aplica formato a la fila de encabezados: fondo rojo, negrita, bordes y alineación
$headerRange = "A{$headerRow}:K{$headerRow}";
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFF0000']],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

// Rellena con datos a partir de la fila 4
$row = 4;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A{$row}", $data['id']);
    $sheet->setCellValue("B{$row}", $data['empresa']);
    $sheet->setCellValue("C{$row}", $data['ruc']);
    $sheet->setCellValue("D{$row}", $data['concepto']);
    $sheet->setCellValue("E{$row}", $data['tipo_comprobante']);
    $sheet->setCellValue("F{$row}", $data['comprobante']);
    $sheet->setCellValue("G{$row}", $data['fecha_pago']);
    $sheet->setCellValue("H{$row}", $data['fecha_registro']);
    $sheet->setCellValue("I{$row}", $data['monto_total']);
    $sheet->setCellValue("J{$row}", $data['estado']);
    $sheet->setCellValue("K{$row}", $data['responsable']);
    $row++;
}

// Rangos para datos
$highestRow = $sheet->getHighestRow();
$dataRange = "A4:K{$highestRow}";

// Aplica bordes a todas las celdas de datos
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Aplica formato de fecha para "Fecha de Pago" (columna G) y de fecha/hora para "Fecha Registro" (columna H), y formato numérico para "Monto Total" (columna I)
$sheet->getStyle("G4:G{$highestRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);
$sheet->getStyle("H4:H{$highestRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);
$sheet->getStyle("I4:I{$highestRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_00);

// Centra de forma específica las columnas "ID" (A), "Comprobante" (F), "Monto Total" (I) y "Estado" (J)
foreach (['A', 'F', 'I', 'J'] as $colLetter) {
    $sheet->getStyle("{$colLetter}4:{$colLetter}{$highestRow}")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Ajusta el ancho de las columnas
foreach (range('A', 'K') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Configura los headers HTTP para descarga del archivo XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="egresos_logs.xlsx"');
header('Cache-Control: max-age=0');

// Escribe el archivo y lo envía a la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
