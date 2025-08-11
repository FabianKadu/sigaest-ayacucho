<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ('include/verificar_sesion_administrador.php');

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
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


    <style>
      .ui-pnotify.dark {
        opacity: 0;
        display: none;
      }

      .dataTables_filter {
        display: none;
      }
    </style>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        include ("include/menu_administrador.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="container">
                    <h2 align="center">Tickets</h2>
                    <br>
                    <div class="row">
                      <div class="col-lg-9">
                        <button class="btn btn-success" data-toggle="modal" data-target=".registrar">
                          <i class="fa fa-plus-square"></i> Nuevo Ticket
                        </button>
                      </div>
                      <div class="col-lg-3">
                        <div><b>Filtrar Por Estado del Ticket: </b></div>
                        <div class="form-group ">
                          <select id="filtro" class="form-control">
                            <option value="">TODOS</option>
                            <option value="Pendiente de Atencion">Pendiente de Atencion</option>
                            <option value="En Proceso de Atencion">En Proceso de Atencion</option>
                            <option value="Finalizado">Finalizado</option>
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>


                  <div class="x_content">

                    <table id="example" class="table table-striped table-bordered" style="width:100%">

                      <thead>
                        <tr>
                          <th style="width:5%">Id</th>
                          <th style="width:10%">Código</th>
                          <th style="width:9%">Fecha</th>
                          <th style="width:25%">Descripción</th>
                          <!-- <th>Usuario</th> -->
                          <th style="width:20%">Comentario Soporte</th>
                          <th style="width:10%">Estado</th>
                          <!-- <th>Acciones</th> -->
                          <!-- <th>Acciones</th> -->
                        </tr>
                      </thead>
                      <tbody>

                        <?php
                        $busc_user_sesion = buscarDocenteById($conexion, $id_docente_sesion);
                        $res_b_u_sesion = mysqli_fetch_array($busc_user_sesion);
                        ?>
                        <?php

                        $ejec_busc_tickets = buscarTodosTickets($conexion, $res_b_u_sesion['apellidos_nombres']);
                        while ($res_busc_ticket = mysqli_fetch_array($ejec_busc_tickets)) {

                          ?>
                          <tr>
                            <td><?php echo $res_busc_ticket['id']; ?></td>
                            <td><?php echo $res_busc_ticket['codigo']; ?></td>
                            <?php
                            $id_ticket = $res_busc_ticket['id'];
                            $busc_modalidad = buscarTicketPorId($conexion, $id_ticket);
                            $res_busc_ticket_id = mysqli_fetch_array($busc_modalidad);
                            ?>
                            <td><?php echo $res_busc_ticket_id['fecha_registro']; ?></td>
                            <td><?php echo $res_busc_ticket_id['descripcion']; ?></td>
                            <!-- <td><?php echo $res_busc_ticket_id['usuario']; ?></td> -->
                            <td><?php echo $res_busc_ticket_id['comentario']; ?></td>
                            <td align="center">
                              <?php
                              $estado = $res_busc_ticket_id['estado'];
                              $bg_color = '';
                              $btn_class = '';

                              switch ($estado) {
                                case 'Pendiente de Atencion':
                                  $bg_color = 'bg-danger'; // Rojo
                                  $btn_class = 'btn-danger';
                                  break;
                                case 'En Proceso de Atencion':
                                  $bg_color = 'bg-warning'; // Azul
                                  $btn_class = 'btn-warning';
                                  break;
                                case 'Finalizado':
                                  $bg_color = 'bg-success'; // Verde
                                  $btn_class = 'btn-success';
                                  break;
                                default:
                                  $bg_color = ''; // Si no coincide con ninguno, no se aplica color
                                  $btn_class = 'btn-secondary'; // Color de botón predeterminado
                                  break;
                              }

                              echo '<button type="button" class="btn px-3 rounded-pill ' . $btn_class . '">' . $estado . '</button>';
                              ?>
                            </td>



                            <!-- <td align="center">
                              <button title="Editar Ticket" class="btn btn-success" data-toggle="modal"
                                data-target=".edit_<?php echo $res_busc_ticket['id']; ?>"><i
                                  class="fa fa-pencil-square-o"></i>
                              </button>
                            </td> -->
                          </tr>



                          <?php
                          // include ('../caja/include/acciones_ticket.php');
                        }
                        ; ?>
                      </tbody>
                    </table>

                    <!--MODAL REGISTRAR-->
                    <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel" align="center">Registrar Ticket</h4>
                          </div>
                          <div class="modal-body">
                            <!--INICIO CONTENIDO DE MODAL-->
                            <div class="x_panel">

                              <div class="" align="center">
                                <h2></h2>
                                <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                <form role="form" action="operaciones/registrar_ticket_bolsa_laboral.php"
                                  class="form-horizontal form-label-left input_mask" method="POST"
                                  enctype="multipart/form-data">

                                  <input type="hidden" class="form-control" value="<?php echo date('Y-m-d H:i:s'); ?>"
                                    name="fecha_registro" required="required">

                                  <?php
                                  $busc_user_sesion = buscarDocenteById($conexion, $id_docente_sesion);
                                  $res_b_u_sesion = mysqli_fetch_array($busc_user_sesion);
                                  ?>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre del Usuario: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="hidden" class="form-control"
                                        value="<?php echo $res_b_u_sesion['apellidos_nombres']; ?>" name="usuario"
                                        required="required">
                                      <p class="form-control" rows="3" style="width: 100%;" id="descripcion"
                                        name="descripcion" required="required">
                                        <?php echo $res_b_u_sesion['apellidos_nombres']; ?>
                                      </p>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Captura de Pantalla :</label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <p>Puede incluir 1 o mas imágenes que podrían ser útiles para ampliar aún más la
                                        información del ticket</p>
                                      <input type="file" class="form-control" name="imagen1" accept="image/*"><br>
                                      <input type="file" class="form-control" name="imagen2" accept="image/*"><br>
                                      <input type="file" class="form-control" name="imagen3" accept="image/*"><br>
                                      <input type="file" class="form-control" name="imagen4" accept="image/*"><br>
                                      <input type="file" class="form-control" name="imagen5" accept="image/*"><br>
                                      <p class="help-block">Por favor, cargue una imagen relacionada al problema (formato:
                                        JPG, JPEG,
                                        PNG, GIF, etc.).</p>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <textarea class="form-control" rows="3" style="width: 100%; height: 165px;"
                                        name="descripcion" required="required"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Enlace (opcional): </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="enlace">
                                    </div>
                                  </div>

                                  <?php
                                  $buscar = buscarDatosGenerales($conexion);
                                  $res = mysqli_fetch_array($buscar);
                                  ?>
                                  <input type="hidden" class="form-control"
                                    value="<?php echo $res['nombre_institucion']; ?>" name="instituto"
                                    required="required">

                                  <input type="hidden" class="form-control" value="Pendiente de Atencion" name="estado"
                                    required="required">

                                  <input type="hidden" class="form-control" value="Inexistente" name="comentario"
                                    required="required">

                                  <div align="center">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                  </div>
                                </form>

                              </div>
                            </div>
                            <!--FIN DE CONTENIDO DE MODAL-->
                          </div>
                        </div>
                      </div>
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
        var tabla = $('#example').DataTable({
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
          },
          "order": [1, "asc"],
          "searching": true,
        });

        // Capturar el cambio en el select y realizar la búsqueda
        $('#filtro').on('change', function () {
          var valorSeleccionado = $(this).val(); // Obtener el valor seleccionado del select
          tabla.search(valorSeleccionado).draw(); // Realizar la búsqueda en DataTables y dibujar la tabla
        });

      });
    </script>


    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
