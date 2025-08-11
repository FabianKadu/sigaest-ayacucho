<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_secretaria.php");

if (!isset($_GET['codigo'])) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
    		</script>";
} else {

  $ejec_busc_tickets = buscarTicketByCodigo($conexion, $_GET['codigo']);
  $res_busc_ticket = mysqli_fetch_array($ejec_busc_tickets);
  // $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

  ?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tickets<?php include ("../include/header_title.php"); ?></title>
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
    <!-- Script obtenido desde CDN jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        // include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <!-- <div class="right_col" role="main"> -->
        <div class="">
          <div class="clearfix"></div>
          <div class="row">
            <div class="">
              <div class="x_panel">
                <div class="">
                  <h2 align="center">Revision de Tickets - Código: <?php echo $res_busc_ticket['codigo'] ?> </h2>
                  <hr>
                  <!-- <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i
                  class="fa fa-plus-square"></i> Nuevo</button> -->

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Estado: </label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <p class="form-control" rows="3"><?php echo $res_busc_ticket['estado']; ?></p>
                      <br><br />
                    </div>
                  </div>
                  <!-- 
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Comentario :</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <textarea class="form-control" rows="3" style="width: 100%; height: 165px;" name="comentario"
                        required="required"><?php echo $res_busc_ticket['comentario']; ?></textarea>
                      <br><br />
                    </div>
                  </div> -->
                  <?php
                  $fecha = date('Y-m-d', strtotime($res_busc_ticket['fecha_registro']));
                  $hora = date('H:i', strtotime($res_busc_ticket['fecha_registro']));
                  ?>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha :</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <p class="form-control" rows="3"><?php echo $fecha; ?></p>
                      <!-- <br><br /> -->
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Hora :</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <p class="form-control" rows="3"><?php echo $hora; ?></p>
                      <!-- <br><br /> -->
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción :</label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                      <p class="form-control" rows="3" style="width: 100%; height: 165px;" id="descripcion"
                        name="descripcion" required="required"><?php echo $res_busc_ticket['descripcion']; ?></p>
                      <br><br />
                    </div>
                  </div>

                  <h4 class="modal-title" id="myModalLabel" align="center">Capturas de pantalla:</h4>
                  <br><br />
                  <?php if (!empty($res_busc_ticket['imagen1'])): ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 1:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <img src=" ../../img/soporte_imagenes/<?php echo $res_busc_ticket['imagen1']; ?>"
                          class="img-responsive" alt="Imagen 1">
                        <br><br />
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($res_busc_ticket['imagen2'])): ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 2:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <img src=" ../../img/soporte_imagenes/<?php echo $res_busc_ticket['imagen2']; ?>"
                          class="img-responsive" alt="Imagen 2">
                        <br><br />
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($res_busc_ticket['imagen3'])): ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 3:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <img src=" ../../img/soporte_imagenes/<?php echo $res_busc_ticket['imagen3']; ?>"
                          class="img-responsive" alt="Imagen 3">
                        <br><br />
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($res_busc_ticket['imagen4'])): ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 4:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <img src=" ../../img/soporte_imagenes/<?php echo $res_busc_ticket['imagen4']; ?>"
                          class="img-responsive" alt="Imagen 4">
                        <br><br />
                      </div>
                    </div>
                  <?php endif; ?>

                  <?php if (!empty($res_busc_ticket['imagen5'])): ?>
                    <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Imagen 5:</label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                        <img src=" ../../img/soporte_imagenes/<?php echo $res_busc_ticket['imagen5']; ?>"
                          class="img-responsive" alt="Imagen 5">
                        <br><br />
                      </div>
                    </div>

                  <?php endif; ?>
                  <div align="center">
                    <?php $b_datos_sistema = buscarDatosSistema($conexion);
                    $r_b_datos_sistema = mysqli_fetch_array($b_datos_sistema); ?>

                    <a href="../docente/tickets.php" class="btn btn-primary">Ir a Atenderlo</a>
                  </div>

                  <!-- Repite lo anterior para las otras imágenes -->

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- /page content -->

    <!-- footer content -->
    <?php
    // include ("../include/footer.php");
    ?>
    <!-- /footer content -->
    </div>
    </div>
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
      $(document).ready(function () {
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
    <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
    <script type="text/javascript">
      $(document).ready(function () {
        recargarlista();
        recargar_ud();
        recargar_competencias();
        $('#carrera_m').change(function () {
          recargarlista();
        });
        $('#modulo').change(function () {
          recargar_ud();
        });
        $('#modulo').change(function () {
          recargar_competencias();
        });
      })
    </script>



    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
