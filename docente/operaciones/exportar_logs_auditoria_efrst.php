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

// Actualiza la consulta para incluir "nombre_usuario" de la auditoría
$query = "SELECT 
            ea.id AS audit_id,
            ea.accion,
            ea.fecha_creacion,
            ea.nombre_usuario,
            e.lugar,
            e.fecha_inicio,
            e.calificacion,
            e.estado,
            d.dni,
            d.apellidos_nombres
          FROM efsrt_auditoria ea
          INNER JOIN efsrt e ON ea.id_efsrt = e.id
          INNER JOIN docente d ON ea.id_usuario = d.id";
$result = mysqli_query($conexion, $query);

// Crea el objeto Spreadsheet, establece la fuente por defecto y obtiene la hoja activa
$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
$sheet = $spreadsheet->getActiveSheet();

// Agrega título al Excel en la primera fila
$title = 'Logs de Auditoría - EFRST';
$sheet->setCellValue('A1', $title);
$sheet->mergeCells('A1:J1');
$sheet->getStyle('A1')->applyFromArray([
    'font' => ['bold' => true, 'size' => 16, 'color' => ['argb' => 'FF000000']],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FFEEEEEE']],
]);

// Define fila de encabezado en la fila 3 y deja la fila 2 vacía
$headerRow = 3;
$headers = [
    'ID Auditoria',
    'Acción',
    'Fecha Creación',
    'Nombre Usuario Audit',
    'Lugar',
    'Fecha Inicio',
    'Calificación',
    'Estado',
    'DNI Docente',
    'Nombre Docente'
];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $headerRow, $header);
    $col++;
}

// Aplica formato a los encabezados: fondo celeste, negrita, bordes y alineación
$headerRange = "A{$headerRow}:J{$headerRow}";
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF87CEEB']],
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
    $sheet->setCellValue("E{$row}", $data['lugar']);
    $sheet->setCellValue("F{$row}", $data['fecha_inicio']);
    $sheet->setCellValue("G{$row}", $data['calificacion']);
    $sheet->setCellValue("H{$row}", $data['estado']);
    $sheet->setCellValue("I{$row}", $data['dni']);
    $sheet->setCellValue("J{$row}", $data['apellidos_nombres']);
    $row++;
}

// Formatea las columnas de fecha: "Fecha Creación" (C) y "Fecha Inicio" (F)
$highestRow = $sheet->getHighestRow();
$sheet->getStyle("C4:C{$highestRow}")
    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);
$sheet->getStyle("F4:F{$highestRow}")
    ->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_YYYYMMDD);

// Aplica bordes a todas las celdas de datos
$dataRange = "A4:J{$highestRow}";
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Centra de forma específica las columnas "ID Auditoria" (A) y "DNI Docente" (I)
foreach (['A', 'I'] as $colLetter) {
    $sheet->getStyle("{$colLetter}4:{$colLetter}{$highestRow}")
        ->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Ajusta el ancho de las columnas
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}
// Opcional: aumentar manualmente el ancho de la columna "Nombre Usuario Audit" (columna D)
$sheet->getColumnDimension('D')->setWidth(30);

// Configura los headers HTTP para descarga del archivo XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="efrst_logs.xlsx"');
header('Cache-Control: max-age=0');

// Escribe el archivo y lo envía a la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
