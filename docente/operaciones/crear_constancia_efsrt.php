<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
require_once('../../tcpdf/tcpdf.php');

if (!verificar_sesion($conexion)) {
    echo "<script>
              alert('Error Usted no cuenta con permiso para acceder a esta página');
              window.location.replace('../login/');
          </script>";
    exit();
}

// Datos para el MODELO ÚNICO NACIONAL DE CERTIFICADO MODULAR PARA IESTP
$nombre_ies = "Nombre del IESTP";
$ruc = "123456789";
$nombre_estudiante = "Nombre del Estudiante";
$dni_estudiante = "12345678";
$programa_estudios = "Programa de Estudios";
$modulo_formativo = "Modulo Formativo";
$fecha_inicio = "01 de Enero de 2023";
$fecha_fin = "31 de Diciembre de 2023";
$lugar = "Ciudad";
$fecha_actual = date("d/m/Y");
$calificacion = "18";

$id_programa = 2;
$id_modulo = 1;

$result = buscarUdByModCarr($conexion, $id_modulo, $id_programa);
$total_horas = 0;
$total_creditos = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $total_horas += $row['horas'];
    $total_creditos += $row['creditos'];
}

$horas_Modulo_completo = $total_horas;
$creditos_Modulo_completo = $total_creditos;

// Crear primer PDF (Certificado Modular)
$pdf1 = new TCPDF('L', 'mm', 'A4', true, 'UTF-8', false);
$pdf1->AddPage();

// Agregar cabecera con imágenes
$pdf1->Image('../../img/logo.png', 10, 10, 80, 0, 'PNG');
$pdf1->Image('../../img/logo_minedu.png', 205, 10, 80, 0, 'PNG');

// Ajustar margen superior para evitar que el contenido se superponga con las imágenes
$pdf1->SetY(40);

$documento1 = '
<table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center"><h1>Instituto de Educacion Superior (publico/privado)</h1><br>' . $nombre_ies . '</td>
    </tr>
    <p></p>
    <p></p>
    <tr>
    <td align="center"><h2><b>CERTIFICADO MODULAR</b></h2></td>
    </tr>
    <p></p>
    <p></p>
    <p></p>
    <tr>
    <td align="justify">Otorgado a ' . $nombre_estudiante . ', DNI: ' . $dni_estudiante . ', por haber aprobado satisfactoriamente el módulo formativo ' . $modulo_formativo . ', correspondiente al programa de estudios ' . $programa_estudios . ', desarrollado del ' . $fecha_inicio . ' al ' . $fecha_fin . ', con un total de ' . $creditos_Modulo_completo . ' créditos, equivalente a ' . $horas_Modulo_completo . ' horas.</td>
    </tr>
    <p></p>
    <tr>
    <td align="left">Lugar y fecha: ' . $lugar . ', ' . $fecha_actual . '</td>
    </tr>
    <p></p>
    <p></p>
    <p></p>
    <p></p>
    <tr>
        <td align="center"><p></p><p></p>.....................................<br>DIRECTOR GENERAL<br>(sello, firma, posfirma)</td>
    </tr>
</table>';

$pdf1->writeHTML($documento1, true, false, true, false, '');

// Guardar el primer PDF
$certificado_path = realpath('../../documentos/efsrt/certificados/') . '/Certificado_Modular_' . $dni_estudiante . '.pdf';
$pdf1->Output($certificado_path, 'F');

// Crear segundo PDF (Constancia EFSRT)
class MYPDF extends TCPDF
{
    // Page footer
    public function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 8);
        $this->Cell(0, 10, 'Debe tenerse en cuenta que la nota aprobatoria es 13.', 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

$pdf2 = new MYPDF();
$pdf2->AddPage();

// Agregar cabecera con imagen
$pdf2->Image('C:/xampp/htdocs/sigaest_dev/img/cabeza.png', 10, 10, 190, 20, 'PNG');

// Ajustar margen superior para evitar que el contenido se superponga con la imagen
$pdf2->SetY(40);

$documento2 = '
<h2 align="center">CONSTANCIA DE EXPERIENCIAS FORMATIVAS EN SITUACIONES REALES DE TRABAJO</h2>
<p></p>
<div style="width: 70%; text-align: center;">
    <p>EL CENTRO DE PRODUCCIÓN Y/O EL INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO "' . $nombre_ies . '" CON RUC N.° ' . $ruc . '</p>
</div>
<p></p>
<p></p>
<p>HACE CONSTAR QUE:</p>
<p></p>
<p align="justify">' . $nombre_estudiante . ', identificado(a) con DNI N.° ' . $dni_estudiante . ', estudiante del IES/IEST “' . $nombre_ies . '” ha realizado las experiencias formativas en situaciones reales de trabajo, correspondientes al programa de estudios de ' . $programa_estudios . ' del nivel formativo de ' . $modulo_formativo . ', efectuadas desde el ' . $fecha_inicio . ' hasta el ' . $fecha_fin . ', con un total de ' . $horas_Modulo_completo . ' horas, en las que ha demostrado las competencias requeridas para el desarrollo de las actividades, por lo cual recibe una calificación de ' . $calificacion . '.</p>
<p></p>
<p>Se extiende la presente para los fines que estime convenientes.</p>
<p></p>
<p align="left">Lugar y fecha: ' . $lugar . ', ' . $fecha_actual . '</p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p></p>
<p align="center">………………………………………………………………………………………..<br>Firma y sello del representante del centro de producción o instituto</p>';

$pdf2->writeHTML($documento2, true, false, true, false, '');

// Guardar el segundo PDF
$constancia_path = realpath('../../documentos/efsrt/constancias/') . '/Constancia_EFSRT_' . $dni_estudiante . '.pdf';
$pdf2->Output($constancia_path, 'F');

echo "<script>
        window.location= '../efsrt_aperturado.php'
      </script>";

mysqli_close($conexion);
?>