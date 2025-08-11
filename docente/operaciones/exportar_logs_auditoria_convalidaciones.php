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

// Consulta con INNER JOIN para obtener datos de auditoría, convalidación y docente
$query = "SELECT 
            ca.id AS audit_id,
            ca.accion,
            ca.fecha_creacion,
            ca.nombre_usuario,
            c.resolucion,
            c.archivo_resolucion,
            c.tipo,
            c.programa_estudios_origen,
            d.dni,
            d.apellidos_nombres
          FROM convalidacion_auditoria ca
          INNER JOIN convalidacion c ON ca.id_convalidacion = c.id
          INNER JOIN docente d ON ca.id_usuario = d.id";
$result = mysqli_query($conexion, $query);

// Crea el objeto Spreadsheet y configura la hoja activa
$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
$sheet = $spreadsheet->getActiveSheet();

// Agrega título al Excel en la primera fila
$title = 'Logs de Convalidaciones';
$sheet->setCellValue('A1', $title);
$sheet->mergeCells('A1:J1');
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF000000']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEEEEE']],
]);

// Define fila de encabezado en la fila 3
$headerRow = 3;
$headers = [
    'ID Auditoría',
    'Acción',
    'Fecha Creación',
    'Nombre Usuario Audit',
    'Resolución',
    'Archivo Resolución',
    'Tipo',
    'Programa Origen',
    'DNI Docente',
    'Nombre Docente'
];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $headerRow, $header);
    $col++;
}

// Aplica formato a los encabezados: fondo dorado, negrita, bordes y alineación
$headerRange = "A{$headerRow}:J{$headerRow}";
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFFFD700']],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

// Rellena con datos a partir de la fila 4
$row = 4;
while ($data = mysqli_fetch_assoc($result)) {
    $sheet->setCellValue("A{$row}", $data['audit_id']);
    $sheet->setCellValue("B{$row}", $data['accion']);
    $sheet->setCellValue("C{$row}", $data['fecha_creacion']);
    $sheet->setCellValue("D{$row}", $data['nombre_usuario']);
    $sheet->setCellValue("E{$row}", $data['resolucion']);
    $sheet->setCellValue("F{$row}", $data['archivo_resolucion']);
    $sheet->setCellValue("G{$row}", $data['tipo']);
    $sheet->setCellValue("H{$row}", $data['programa_estudios_origen']);
    $sheet->setCellValue("I{$row}", $data['dni']);
    $sheet->setCellValue("J{$row}", $data['apellidos_nombres']);
    $row++;
}

// Aplica formato a las columnas de fecha: "Fecha Creación" (columna C)
$highestRow = $sheet->getHighestRow();
$sheet->getStyle("C4:C{$highestRow}")
    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);

// Aplica bordes a todas las celdas de datos
$dataRange = "A4:J{$highestRow}";
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Opcional: centrar columnas "ID Auditoría" (A) y "DNI Docente" (I)
foreach (['A', 'I'] as $colLetter) {
    $sheet->getStyle("{$colLetter}4:{$colLetter}{$highestRow}")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Ajusta el ancho de las columnas
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Configura los headers HTTP para la descarga del archivo XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="convalidaciones_logs.xlsx"');
header('Cache-Control: max-age=0');

// Escribe el archivo y lo envía a la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
