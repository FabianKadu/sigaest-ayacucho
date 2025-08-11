<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
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
          <div class="page-title">
            <div class="title_left">
              <h3>Reportes de Caja</h3>
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
            </div>
            <div class="col-md-3 col-sm-3 col-xs-6">
                <label>Mes y Año:</label>
                <input type="month" id="fecha" class="form-control">
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
                          <h2>Total de ofertas laborales</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Reportar la cantidad de ofertas laborales.</p>
                          <button  onclick="generarReporte('Total de ofertas laborales')" class="btn btn-primary">Generar Reporte</button>
                        </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Ranking de empresas</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Reportar de empresas con mayor demanda.</p>
                          <button  onclick="generarReporte('Ranking de empresas')" class="btn btn-primary">Generar Reporte</button>
                      </div>
                  </div>
              </div>

              <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                  <div class="x_panel">
                      <div class="x_title">
                          <h2>Total de postulantes por programas</h2>
                          <div class="clearfix"></div>
                      </div>
                      <div class="x_content">
                          <p>Reporte general de postulantes a las diferentes convocaorias.</p>
                          <button  onclick="generarReporte('Total de postulantes')" class="btn btn-primary">Generar Reporte</button>
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
      // Obtener la fecha actual
      const fechaActual = new Date();

      // Obtener el año y el mes
      const year = fechaActual.getFullYear();
      const month = fechaActual.getMonth() + 1; // Los meses van de 0 a 11, así que sumamos 1

      // Formatear la fecha en el formato YYYY-MM
      const fechaFormateada = `${year}-${month.toString().padStart(2, '0')}`;

      // Establecer el valor del campo de fecha
      document.getElementById('fecha').value = fechaFormateada;
  </script>

    <script>
    function generarReporte(tipo) {
        // Obtener valores de los campos de fecha
        var fecha = document.getElementById('fecha').value;
        console.log(fecha);
        // Redirigir a la página de generación de reporte con las fechas y tipo como parámetros
        window.location.href = 'reporte_bolsa_laboral.php?fecha=' + fecha + '&tipo=' + tipo;
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
<?php }