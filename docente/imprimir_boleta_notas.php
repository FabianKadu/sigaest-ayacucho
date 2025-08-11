<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("../functions/funciones.php");
require_once('../tcpdf/tcpdf.php');
include("include/verificar_sesion_secretaria.php");

//OPTENIENDO DATOS DEL FORMULARIO
$dni = $_POST['dni'];
$id_periodo = $_POST['periodo'];
$num_comprobante = $_POST['comprobante'];
$res = buscarEstudianteByDni($conexion, $dni);
$cont = mysqli_num_rows($res);

if (!verificar_sesion($conexion) || !verificarDatos($conexion, $dni, $id_periodo)) {
  echo "<script>
                alert('Es probable que el estudiante no se haya matriculado en el periodo seleccionado.');
                window.location.replace('boleta_de_notas.php');
    		</script>";
} else {

  if ($cont == 0) {
    echo "<script>
      alert('El alumno no exíste en la base de datos.');
      window.location.replace('certificado.php');
    </script>";
  } else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

    //OPTENCIÓN DE DATOS
    $estudiante_res = buscarEstudianteByDni($conexion, $dni);
    $r_b_est = mysqli_fetch_array($estudiante_res);
    $nombres = $r_b_est['apellidos_nombres'];
    $dni = $r_b_est['dni'];
    $id_semestre = $r_b_est['id_semestre'];

    $semestre_res = buscarSemestreById($conexion, $id_semestre);
    $semestre = mysqli_fetch_array($semestre_res);
    $nombre_semestre = $semestre['descripcion'];


    $programa = buscarCarrerasById($conexion, $r_b_est['id_programa_estudios']);
    $r_programa = mysqli_fetch_array($programa);
    $nombre_programa = $r_programa['nombre'];
    $plan = $r_programa['plan_estudio'];
    $tipo_programa = $r_programa['tipo'];

    $recursos = buscarRecursos($conexion);
    $res_recursos = mysqli_fetch_array($recursos);
    $logo = $res_recursos['img_logo_documento'];

    $datos_instituto = buscarDatosGenerales($conexion);
    $r_datos_instituto = mysqli_fetch_array($datos_instituto);
    $cod_modular = $r_datos_instituto['cod_modular'];
    $ruc = $r_datos_instituto['ruc'];
    $departamento = $r_datos_instituto['departamento'];
    $provincia = $r_datos_instituto['provincia'];
    $distrito = $r_datos_instituto['distrito'];
    $nombre_institucion = $r_datos_instituto['nombre_institucion'];

    $usuario = buscarDocenteById($conexion, $id_docente_sesion);
    $usuario = mysqli_fetch_array($usuario);
    $usuario = $usuario['apellidos_nombres'];

    $periodo_academico = buscarPeriodoAcadById($conexion, $id_periodo);
    $r_periodo_academico = mysqli_fetch_array($periodo_academico);
    $nombre_periodo = $r_periodo_academico['nombre'];

    $res_sistema =buscarDatosSistema($conexion);
    $sistema = mysqli_fetch_array($res_sistema);

    $res_cursos_matriculados = getCalificacionFinalByIdAndPeriodo($conexion, $r_b_est['id'], $id_periodo);


    $ordMer     = "-";
    $nombre_doc = 'BN_' . $nombres . '_' . $id_periodo . '.pdf';

    //CODIGO DE VERIFICACIÓN DE DOCUMENTO
    $codigo = uniqid();
    $url = $sistema['dominio_sistema'];
    $ruta_qr = generarQRBoleta($url . "/verificar.php?codigo=" . $codigo, 'BN_' . $nombres . '_' . $id_periodo);

    //INICIO DE LA CREACIÓN DE PDF
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
    $pdf->SetTitle("Boleta de Notas - " . $nombres);
    $pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
    $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
    $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetDefaultMonospacedFont('helvetica');
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    $pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(true);
    $pdf->SetAutoPageBreak(TRUE, 25);
    $pdf->SetFont('helvetica', '', 8);
    $pdf->AddPage('P', 'A4');
    $text_size = 8;
    $contador = 0;

    //TABLA DE LA BOLETA CON LAS NOTAS
    $tabla = '
        <table border="0.2" cellspacing="0" cellpadding="2">
            <tr bgcolor="#CCCCCC">
                <th width="10%" align="center"><font size="9">N°</font></th>
                <th width="70%" align="center"><font size="9">UNIDAD DIDÁCTICA</font></th>
                <th width="20%" align="center"><font size="9">NOTA FINAL</font></th>
            </tr>
    ';

    // Iterar notas del estudiante

    $acumuladorNota = 0;
    $acumuladorCreditos = 0;
    while ($curso_matriculado = mysqli_fetch_array($res_cursos_matriculados)) {
      $contador = $contador + 1;
      $calificacion_final = obtenerCalificacionFinal($conexion, $curso_matriculado['id_detalle_matricula']);

      $puntos = $curso_matriculado['creditos'] * $calificacion_final;
      $acumuladorNota += $puntos;
      $acumuladorCreditos += $curso_matriculado['creditos'];

      $color = ($calificacion_final < 13) ? 'color: red;' : '';
      $tabla .= '
            <tr>
                <td align="center"><font size="10">' . $contador . '</font></td>
                <td><font size="10">' . $curso_matriculado['descripcion'] . '</font></td>
                <td align="center" style="' . $color . '"><font size="10">' . $calificacion_final . '</font></td>
            </tr>';
    };
    $promedio =  $acumuladorNota / $acumuladorCreditos;

    //CONTENIDO DE LA CABECERA DEL PDF
    $documento = '
        <table border="0" width="100%" cellspacing="3" cellpadding="0">
          <tr>
              
              <td width="40%"><img src="' . $logo . '" alt="" height="40px"></td>
              <td width="20%"></td>
              <td width="40%" align="rigth"><img src="../img/logo_minedu.jpeg" alt="" height="40px"></td>
              
          </tr>
          <tr>
              <td colspan="3"><p align="rigth"><b>Fecha y Hora de Emisión:</b> ' . date('d/m/Y h:i A') . '</p>  </td>
          </tr>
          <br>
          <tr>
              <td colspan="3" align="center"><font size="11"><b>SISTEMA DE GESTIÓN ACADÉMICA</b></font></td>
          </tr>
          <tr>
              <td colspan="3" align="center"><font size="10"><b>FICHA DE MATRÍCULA REGULAR</b></font></td>
          </tr>     
          
      </table><br /><br />
          <br /><br />
    ';
    // Datos de la institución
    $documento .= '
        <table border="0.2" cellspacing="0" cellpadding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Apellidos y Nombres</font></th>
                <th width="40%"  ><font><b> ' . $nombres . ' </b></font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Número de documento</font></th>
                <th width="20%" ><font><b>' . $dni . '</b></font></th>
            </tr>
        </table>
        <h6></h6>
        ';

    $documento .= '
        <table border="0.2" cellspacing="0" cellpadding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Nombre de la Institución</font></th>
                <th width="40%"  ><font>' . $nombre_institucion . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>DRE</font></th>
                <th width="20%" ><font>' . $departamento . '</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Código Modular</font></th>
                <th width="40%"  ><font>' . $cod_modular . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Tipo de Gestión</font></th>
                <th width="20%" ><font>PÚBLICO</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Departamento</font></th>
                <th width="40%"  ><font>' . $departamento . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Provincia</font></th>
                <th width="20%" ><font>' . $provincia . '</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Distrito</font></th>
                <th width="40%"  ><font>' . $distrito . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font></font></th>
                <th width="20%" ><font></font></th>
            </tr>
        </table>
        <h6></h6>';



    $documento .= '
        
        <table border="0.2" cellspacing="0" cellpadding="2" padding="2">
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Programa de estudios</font></th>
                <th width="40%"  ><font>' . $nombre_programa . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo lectivo</font></th>
                <th width="20%" ><font>' . $nombre_periodo . '</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Nivel formativo</font></th>
                <th width="40%"  ><font>PROFESIONAL TÉCNICO</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo de clases</font></th>
                <th width="20%" ><font>' . $nombre_periodo . '</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Tipo de plan de estudios</font></th>
                <th width="40%"  ><font>' . $tipo_programa . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font>Periodo académico</font></th>
                <th width="20%" ><font>' . $nombre_semestre . '</font></th>
            </tr>
            <tr>
                <th width="20%"  bgcolor="#CCCCCC"><font>Plan de estudios</font></th>
                <th width="40%"  ><font>PLAN ' . $plan . '</font></th>
                <th width="20%"  bgcolor="#CCCCCC"><font></font></th>
                <th width="20%" ><font></font></th>
            </tr>
        </table>';

    $documento .= $tabla;


    // Cerrar la tabla
    $documento .= '</table>';

    //Agregar fecha al documento

    // Escribir el contenido HTML en el PDF
    $pdf->writeHTML($documento, true, false, true, false, '');
    $rutaArchivo = '../documentos/boletas_de_notas/' . $nombre_doc;
    // Guardar el contenido en el archivo
    $pdfContent = $pdf->Output('', 'S');
    // Enviar el PDF al navegador
    file_put_contents($rutaArchivo, $pdfContent);

    $consulta = "INSERT INTO boleta_notas (codigo ,nombre_usuario, dni_estudiante, apellidos_nombres, programa_estudio, periodo_acad ,ruta_documento,num_comprobante) 
    VALUES ('$codigo' ,'$usuario','$dni', '$nombres' ,'$id_programa','$periodo','$rutaArchivo','$num_comprobante')";
    mysqli_query($conexion, $consulta);
  }
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

  <title>Estudiantes <?php include("../include/header_title.php"); ?></title>
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
      include("include/menu_secretaria.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="">
                  <h2 align="center">Boleta de Nota</h2>
                  <a href="boleta_de_notas.php" class="btn btn-danger">Regresar</a>
                  <div class="clearfix"></div>
                </div>
                <div class="">
                  <br>
                  <input type="email" id="correoInput" class="form-control" style="width:300px; margin-bottom:2px;" value="<?= $correo ?>">

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
    include("../include/footer.php");
    ?>
    <!-- /footer content -->
  </div>
  </div>

  <script>
    document.getElementById('enviarCorreoBtn').addEventListener('click', function() {
      // Obtiene el valor del campo de entrada
      var correoValue = document.getElementById('correoInput').value;

      // Construye la URL con el valor del correo
      var url = "./login/enviar_boleta_correo.php?documento=<?= $rutaArchivo ?>&dni=<?= $dni ?>&correo=" + encodeURIComponent(correoValue);

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
        "language": {
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

    });
  </script>
  <?php mysqli_close($conexion); ?>
</body>

</html>