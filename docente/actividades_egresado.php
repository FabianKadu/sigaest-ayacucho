<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $id_estudiante = $_GET['id'];

  $res_estudiante = buscarEstudianteById($conexion, $id_estudiante);
  $estudiante = mysqli_fetch_array($res_estudiante);

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

    <title>Modalidades<?php include ("../include/header_title.php"); ?></title>
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
        include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Actividades del Egresado</h2>
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i
                        class="fa fa-plus-square"></i> Nuevo</button>
                    <a href="seguimiento_egresado.php?id=<?php echo $id_estudiante ?>" class="btn btn-danger">Regresar</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th style="width:1%">Id</th>
                          <th>Tipo</th>
                          <th>Cargo</th>
                          <th style="width:1%">Institución/Organización</th>
                          <th>Lugar</th>
                          <th>Descripcion</th>
                          <th style="width:10%">Fecha Inicio</th>
                          <th style="width:10%">Fecha Fin</th>
                          <th style="width:20%">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $res_actividad = buscarActividadesById($conexion, $id_estudiante);
                        while ($actividad = mysqli_fetch_array($res_actividad)) {

                          ?>
                          <tr>
                            <td><?php echo $actividad['id']; ?></td>
                            <td><?php echo $actividad['tipo']; ?></td>
                            <td><?php echo $actividad['nombre_cargo']; ?></td>
                            <td><?php echo $actividad['nombre_organizacion']; ?></td>
                            <td><?php echo $actividad['lugar']; ?></td>
                            <td><?php echo $actividad['descripcion']; ?></td>
                            <td><?php echo $actividad['fecha_inicio']; ?></td>
                            <td><?php echo $actividad['fecha_fin']; ?></td>
                            <td align="center">
                              <button title="Editar Actividad" class="btn btn-success" data-toggle="modal"
                                data-target=".edit_<?php echo $actividad['id']; ?>"><i class="fa fa-pencil-square-o"></i>
                                Editar</button>
                              <form action="operaciones/eliminar_actividad_egresado.php" method="POST"
                                onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta actividad?');">
                                <input type="hidden" name="id_actividad" value="<?php echo $actividad['id']; ?>">
                                <input type="hidden" name="id_estudiante" value="<?php echo $estudiante['id']; ?>">
                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                              </form>
                            </td>
                          </tr>
                          <?php
                          include ('include/acciones_actividades_egresado.php');
                        }
                        ;
                        ?>

                      </tbody>
                    </table>
                    <!--MODAL REGISTRAR-->
                    <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel" align="center">Registrar Actividad de Egresado</h4>
                          </div>
                          <div class="modal-body">
                            <!--INICIO CONTENIDO DE MODAL-->
                            <div class="x_panel">

                              <div class="" align="center">
                                <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                <form role="form" action="operaciones/registrar_actividad_egresado.php"
                                  class="form-horizontal form-label-left input_mask" method="POST"
                                  enctype="multipart/form-data">

                                  <input type="hidden" class="form-control" value="<?php echo $estudiante['id']; ?>"
                                    name="id_estudiante" required="required">

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" name="tipo" value="" id="tipo" required="required">
                                        <option></option>
                                        <option value="Evento">Evento</option>
                                        <option value="Voluntariado">Voluntariado</option>
                                        <option value="Profesional">Profesional</option>
                                        <option value="Academica">Academica</option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Funcion Desempeñada:
                                      (cargo)</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="nombre_cargo" required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre organización: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="nombre_organizacion"
                                        required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Lugar: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="lugar" required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <textarea class="form-control" rows="3" style="width: 100%; height: 165px;"
                                        name="descripcion" required="required"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group ">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 ">Fecha inicio : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="date" class="form-control" name="fecha_inicio" required="required">
                                    </div>
                                  </div>

                                  <div class="form-group ">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 ">Fecha Fin : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="date" class="form-control" name="fecha_fin" required="required">
                                    </div>
                                  </div>

                                  <div align="center">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                  </div>
                                </form>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--FIN DE CONTENIDO DE MODAL-->


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
    <script type="text/javascript">
      function recargarlista() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_modulos.php",
          data: "id_carrera=" + $('#carrera_m').val(),
          success: function (r) {
            $('#modulo').html(r);
          }
        });
      }
    </script>
    <script type="text/javascript">
      function recargar_ud() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_ud.php",
          data: "id_modulo=" + $('#modulo').val(),
          success: function (r) {
            $('#unidad_didactica').html(r);
          }
        });
      }
    </script>
    <script type="text/javascript">
      function recargar_competencias() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_competencias.php",
          data: "id_modulo=" + $('#modulo').val(),
          success: function (r) {
            $('#competencia').html(r);
          }
        });
      }
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
