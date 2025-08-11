<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_caja.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('login/');
    		</script>";
} else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
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
	  
    <title>Caja <?php include ("../include/header_title.php"); ?></title>
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
    <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        $res_docente = buscarDocenteById($conexion, $id_docente_sesion);
        $docente = mysqli_fetch_array($res_docente);
        if ($docente['id_cargo'] == 6) {
          include("include/menu_caja.php");
        }
        include("../docente/include/menu_secretaria.php"); ?>
        <!-- page content -->
        <div class="right_col" role="main">
        <div class="">
          <div class="page-title">
            <div class="title_left">
              <h3>Reportes de Caja</h3>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
                <label>Rango de Fechas:</label>
                <div class="control-group ">
                  <div class="controls">
                    <div class="input-prepend input-group">
                      <span class="add-on input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                      <input type="text" style="width: 200px" name="fecha" id="reservation" class="form-control" />
                    </div>
                  </div>
                </div>
            </div>
          </div>
          <div class="clearfix"></div>
          <br><br>
          <!-- Contenido de la página -->
            <div class="row">
              <!-- Card de Ingresos -->
              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Reporte de Ingresos</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Se tomará en cuenta solo los ingresos que no fueron anulados.</p>
                          <button  onclick="generarReporte('ingresos')" class="btn btn-primary">Generar Reporte</button>
                        </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Reporte de Egresos</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Se tomará en cuenta solo los egresos que no fueron anulados.</p>
                          <button  onclick="generarReporte('egresos')" class="btn btn-primary">Generar Reporte</button>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Reporte de Flujo de Caja</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Reporte general de ingresos y egresos que no fueron anulados.</p>
                          <button  onclick="generarReporte('Flujo-Caja')" class="btn btn-primary">Generar Reporte</button>
                      </div>
                  </div>
              </div>

            </div>
          </div>
          <!-- Fin Contenido de la página -->
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
    function generarReporte(tipo) {
        // Obtener valores de los campos de fecha
        var fecha = document.getElementById('reservation').value;

        // Redirigir a la página de generación de reporte con las fechas y tipo como parámetros
        window.location.href = 'generar_reporte.php?fecha=' + fecha + '&tipo=' + tipo;
    }
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
    <script src="../Gentella/vendors/moment/min/moment.min.js"></script>
    <script src="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#reservation').daterangepicker({
                startDate: moment(), // Establecer fecha inicial a hoy
                endDate: moment().add(1, 'days'), // Establecer fecha final a un día después de hoy (opcional)
                locale: {
                    format: 'DD/MM/YYYY', // Formato de fecha
                    separator: " - ",
                    applyLabel: "Aplicar",
                    cancelLabel: "Cancelar",
                    fromLabel: "Desde",
                    toLabel: "Hasta",
                    customRangeLabel: "Personalizado",
                    weekLabel: "S",
                    daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
                    monthNames: [
                        "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", 
                        "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"
                    ],
                    firstDay: 1
                }
            });
        });
    </script>
    <script>
        $(function() {

          // Restaurar el rango de fechas si está almacenado en localStorage
          var savedDateRange = localStorage.getItem('savedDateRange');
          if (savedDateRange) {
            $('#reservation').val(savedDateRange);
          }
        
          // Guardar el rango de fechas en localStorage cuando se selecciona uno
          $('#reservation').on('change', function() {
                localStorage.setItem('savedDateRange', $(this).val());
            });
        });
    </script>
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php }