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

// Consulta con LEFT JOIN para obtener datos de auditoría, criterio y docente
$query = "SELECT 
            cae.id,
            cae.accion,
            cae.fecha_creacion,
            cae.nombre_usuario,
            ce.orden,
            ce.detalle,
            ce.ponderado,
            ce.calificacion,
            d.dni,
            d.apellidos_nombres
          FROM criterio_evaluacion_auditoria cae
          LEFT JOIN criterio_evaluacion ce ON cae.id_criterio_evaluacion = ce.id
          LEFT JOIN docente d ON cae.id_usuario = d.id";
$result = mysqli_query($conexion, $query);

// Crea el objeto Spreadsheet, establece la fuente por defecto y obtiene la hoja activa
$spreadsheet = new Spreadsheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('Arial')->setSize(10);
$sheet = $spreadsheet->getActiveSheet();

// Agrega título al Excel en la primera fila
$title = 'Logs de Auditoría - Criterio de Evaluación';
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
    'Orden',
    'Detalle',
    'Ponderado',
    'Calificación',
    'DNI Docente',
    'Nombre Docente'
];
$col = 'A';
foreach ($headers as $header) {
    $sheet->setCellValue($col . $headerRow, $header);
    $col++;
}

// Aplica formato a la fila de encabezados: fondo verde, negrita, bordes y alineación
$headerRange = "A{$headerRow}:J{$headerRow}";
$sheet->getStyle($headerRange)->applyFromArray([
    'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['argb' => 'FF4CAF50']],
    'borders' => [
        'allBorders' => ['borderStyle' => Border::BORDER_THIN, 'color' => ['argb' => 'FF000000']]
    ],
    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
]);

// Rellena con datos a partir de la fila 4
$row = 4;
while ($data = mysqli_fetch_assoc($result)) {
    // Se muestran los datos, teniendo en cuenta que algunos FK pueden ser nulos
    $sheet->setCellValue("A{$row}", $data['id']);
    $sheet->setCellValue("B{$row}", $data['accion']);
    $sheet->setCellValue("C{$row}", $data['fecha_creacion']);
    $sheet->setCellValue("D{$row}", $data['nombre_usuario']);
    $sheet->setCellValue("E{$row}", $data['orden']);
    $sheet->setCellValue("F{$row}", $data['detalle']);
    $sheet->setCellValue("G{$row}", $data['ponderado']);
    $sheet->setCellValue("H{$row}", $data['calificacion']);
    $sheet->setCellValue("I{$row}", $data['dni']);
    $sheet->setCellValue("J{$row}", $data['apellidos_nombres']);
    $row++;
}

// Formatea "Fecha Creación" (columna C)
$highestRow = $sheet->getHighestRow();
$sheet->getStyle("C4:C{$highestRow}")->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_DATE_DATETIME);

// Aplica bordes a todas las celdas de datos
$dataRange = "A4:J{$highestRow}";
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

// Centra de forma específica las columnas "ID Auditoria" (A) y "DNI Docente" (I)
foreach (['A', 'I'] as $colLetter) {
    $sheet->getStyle("{$colLetter}4:{$colLetter}{$highestRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

// Ajusta el ancho de las columnas
foreach (range('A', 'J') as $columnID) {
    $sheet->getColumnDimension($columnID)->setAutoSize(true);
}

// Configura los headers HTTP para descarga del archivo XLSX
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="criterio_evaluacion_logs.xlsx"');
header('Cache-Control: max-age=0');

// Escribe el archivo y lo envía a la salida
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit();
