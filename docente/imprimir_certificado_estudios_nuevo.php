<?php

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include "../caja/consultas.php";
include("../functions/funciones.php");
include("include/verificar_sesion_secretaria.php");

$dni = $_POST['dni'];
$comprobante = $_POST['comprobante'];

if (!verificar_sesion($conexion)) {
  echo "<script>
  alert('Ah ocuurido un error, revice los datos ingresados y vuelva a intentarlo.');
  window.location.replace('certificado.php');
</script>";
}else{
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

//DATOS

$res = buscarEstudianteByDni($conexion,$dni);
$estudiante_res = mysqli_fetch_array($res);
$estudiante = $estudiante_res["apellidos_nombres"];
$correo = $estudiante_res['correo'];

$id_programa = $estudiante_res['id_programa_estudios'];
$programa = buscarCarrerasById($conexion, $id_programa);
$programa = mysqli_fetch_array($programa);
$programa = $programa["nombre"];

$not = getCalificacionFinalByDni($conexion, $dni);

$director = buscarDirector_All($conexion);
$director = mysqli_fetch_array($director);

$usuario = buscarDocenteById($conexion, $id_docente_sesion);
$usuario = mysqli_fetch_array($usuario);
$usuario = $usuario['apellidos_nombres'];

$director = buscarDirector_All($conexion);
$director = mysqli_fetch_array($director);
$nombre_director = $director['apellidos_nombres'];

$datos_iestp = buscarDatosSistema($conexion);
$datos_iestp = mysqli_fetch_array($datos_iestp);
$nombre_insti = str_replace("IESTP ", "", $datos_iestp['titulo']);


$datos_lugar = buscarDatosGenerales($conexion);
$datos_lugar = mysqli_fetch_array($datos_lugar);
$lugar = ucwords(strtolower($datos_lugar['distrito']));

$sistema = buscarDatosSistema($conexion);
$sistema = mysqli_fetch_array($sistema);

$nombre_doc = 'Certificado de estudios - ' . $estudiante.'.pdf';

//CODIGO DE VERIFICACIÓN DE DOCUMENTO
$codigo = uniqid();
$url = $sistema['dominio_sistema'];
$ruta_qr = generarQRBoleta( $url."/verificar.php?codigo=".$codigo ,'CE_'.$estudiante);

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
    $pdf->SetTitle("Certificado de Estudios - " . $estudiante);
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(TRUE,25);
    $pdf->SetFont('helvetica', '', 11);
    $pdf->AddPage('P', 'A4');
    $text_size = 9;



$documento = '
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td width="40%"><img src="../img/logo.jpg" alt="" height="30px"></td>
            <td width="10%"></td>
            <td width="50%" align="right"><img src="../img/logo_minedu.jpeg" alt="" height="30px"></td>
        </tr>
        <br />
        <tr>
            <td colspan="3" align="center"><b>CERTIFICADO DE ESTUDIOS DE EDUCACIÓN SUPERIOR TECNOLÓGICA</b></td>
        </tr>
        <tr>
            <td colspan="3" align="center"><font size="' . $text_size . '"><b>INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</b></font></td>
        </tr>
        <tr>
            <td colspan="3" align="center"><font size="12"><b>"'. $nombre_insti .'"</b></font></td>
        </tr>
        <tr>
            <br />
            <td colspan="3" align="center"><font size="16"><b>CERTIFICA</b></font></td>
        </tr>
        <br />
        <tr>
            <td colspan = "3">Que: <b>'. $estudiante .'</b></td>
        </tr>
        <br />
        <tr>
            <td colspan = "3">Ha cursado las Unidades Didácticas / Asignaturas o Cursos que se indican en la carrera:</td>
        </tr>
        <tr>
            <td colspan = "3">Profesional técnico de <b>"'. $programa . '"</b>, siendo el resultado final de las evaluaciones lo siguiente:</td>
        </tr>
        
    </table>
    <br />
    <br />
';

$documento .= '
    <table border="0.2" cellspacing="0" cellpadding="3">
        <tr bgcolor="#CCCCCC">
            <th width="46%" align="center"><font size="10">Unidad Didáctica/Cursos o Asignaturas</font></th>
            <th width="10%" align="center"><font size="10">N° de Créditos</font></th>
            <th width="10%" align="center"><font size="10">Nota (número)</font></th>
            <th width="10%" align="center"><font size="10">Nota (letra)</font></th>
            <th width="12%" align="center"><font size="10">Periodo Lectivo</font></th>
            <th width="12%" align="center"><font size="10">Periodo Académico</font></th>
        </tr>
';

// Iterar notas del estudiante
while ($notas = mysqli_fetch_array($not)) {
    $documento .= '
        <tr>
            <td><font size="10">' . $notas['descripcion'] . '</font></td>
            <td align="right"><font size="10">' . $notas['creditos'] . '</font></td>
            <td align="right"><font size="10">' . $notas['promedio_final'] . '</font></td>
            <td align="center"><font size="9">' . convertirNumeroALetra($notas['promedio_final']) . '</font></td>
            <td align="center"><font size="10">' . $notas['periodo'] . '</font></td>
            <td align="center"><font size="9">' . determinarParidad($notas['periodo']). '</font></td>
        </tr>
    ';
}

// Cerrar la tabla
$documento .= '</table>';

$documento .= '<br /><br />
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
            <td align="rigth">'. $lugar .', '. obtenerFecha() . '</td>
        </tr>
        <tr>
            <td colspan="2" align="center"><br><br><br><br><br>...............................................<br>'. $nombre_director .'<br>Director General</td>
        </tr>
        <tr>
                <br><br><br><br>
                <td width="100%" align="rigth"><img src="'. $ruta_qr .'" alt="" height="60px"></td>
            </tr>
            <tr>
                <td width="100%" align="rigth"> Verifíque la integridad del documento, mediante este código QR.</td>
            </tr>
    </table>
';

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($documento, true, false, true, false, ''); 
    $rutaArchivo = '../documentos/certificado_de_estudios/'. $nombre_doc;
    // Guardar el contenido en el archivo
    $pdfContent = $pdf->Output('', 'S');
    // Enviar el PDF al navegador
    file_put_contents($rutaArchivo, $pdfContent);

    $consulta = "INSERT INTO certificado_estudios (codigo ,nombre_usuario, dni_estudiante, apellidos_nombres, programa_estudio,ruta_documento,num_comprobante, fecha_emision) 
    VALUES ('$codigo' ,'$usuario','$dni', '$estudiante' ,'$programa','$rutaArchivo','$comprobante', CURRENT_TIMESTAMP())";
    mysqli_query($conexion, $consulta);

};
?>

<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Estudiantes <?php include ("../include/header_title.php"); ?></title>
    <!--icono en el titulo-->
    <link rel="shortcut icon" href="../img/favicon.ico">
    <!-- Bootstrap -->
    <link href="../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Gentella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Gentella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
          include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                <div class="">
                    <a href="certificado.php" class="btn btn-danger">Regresar</a>
                    <div class="clearfix"></div>
                </div>
                <div class="">
                  <br>
                  <input type="email" id="correoInput" class="form-control" style="width:300px; margin-bottom:2px;" value="<?= $correo?>">

                  <!-- Agrega un ID al enlace para facilitar la referencia desde JavaScript -->
                  <a href="#" id="enviarCorreoBtn" class="btn btn-success"><i class="fa fa-plus-square"></i> Enviar por Correo</a>
                </div>
                    <iframe src="<?php echo $rutaArchivo ?>" width="100%" height="600px"></iframe>
                  </div>
                </div>
              </div>
            </div>


          </div>
        </div>
        <!-- /page content -->

         <!-- footer content -->
         <?php
        include ("../include/footer.php"); 
        ?>
        <!-- /footer content -->
      </div>
    </div>

    <script>
    document.getElementById('enviarCorreoBtn').addEventListener('click', function() {
        // Obtiene el valor del campo de entrada
        var correoValue = document.getElementById('correoInput').value;

        // Construye la URL con el valor del correo
        var url = "./login/enviar_certificado_correo.php?documento=<?= $rutaArchivo ?>&dni=<?= $dni ?>&correo=" + encodeURIComponent(correoValue);

        // Redirecciona a la nueva URL
        window.location.href = url;
    });
  </script>

    <!-- jQuery -->
   <script src="../Gentella/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Gentella/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Gentella/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>
    <!-- Datatables -->
    <script src="../Gentella/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
    <script src="../Gentella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
    <script src="../Gentella/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
    <script src="../Gentella/vendors/jszip/dist/jszip.min.js"></script>
    <script src="../Gentella/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../Gentella/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
    <script>
    $(document).ready(function() {
    $('#example').DataTable({
      "language":{
    "processing": "Procesando...",
    "lengthMenu": "Mostrar _MENU_ registros",
    "zeroRecords": "No se encontraron resultados",
    "emptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros",
    "infoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
    "infoFiltered": "(filtrado de un total de _MAX_ registros)",
    "search": "Buscar:",
    "infoThousands": ",",
    "loadingRecords": "Cargando...",
    "paginate": {
        "first": "Primero",
        "last": "Último",
        "next": "Siguiente",
        "previous": "Anterior"
    },
      }
    });

    } );
    </script>
     <?php mysqli_close($conexion); ?>
  </body>
</html>