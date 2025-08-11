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

    <title>Programa de Estudio<?php include("../include/header_title.php"); ?></title>
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
                    <h2 align="center">Programas de Estudio</h2>
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i
                        class="fa fa-plus-square"></i> Nuevo</button>
                    <button class="btn btn-primary" data-toggle="modal" data-target=".carga-plan"><i
                        class="fa fa-upload"></i> Carga de plan</button>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Código</th>
                          <th>Tipo</th>
                          <th>Plan de Estudio</th>
                          <th>Nombre</th>
                          <th>Resolución</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $busc_carrera = buscarCarreras($conexion);
                        while ($res_busc_carrera = mysqli_fetch_array($busc_carrera)) {
                          ?>
                          <tr>
                            <td><?php echo $res_busc_carrera['id']; ?></td>
                            <td><?php echo $res_busc_carrera['codigo']; ?></td>
                            <td><?php echo $res_busc_carrera['tipo']; ?></td>
                            <td><?php echo $res_busc_carrera['plan_estudio']; ?></td>
                            <td><?php echo $res_busc_carrera['nombre']; ?></td>
                            <td><?php echo $res_busc_carrera['resolucion']; ?></td>
                            <td>
                              <button class="btn btn-success" data-toggle="modal"
                                data-target=".edit_<?php echo $res_busc_carrera['id']; ?>"><i
                                  class="fa fa-pencil-square-o"></i> Editar</button>
                            </td>
                          </tr>
                          <?php
                          include('include/acciones_programa_estudio.php');
                        }
                        ;
                        ?>

                      </tbody>
                    </table>


                  </div>
                  <!--MODAL REGISTRAR-->
                  <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Registrar Programa de Estudio</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                          <div class="x_panel">

                            <div class="" align="center">
                              <h2></h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <form role="form" action="operaciones/registrar_programa_estudio.php"
                                class="form-horizontal form-label-left input_mask" method="POST">
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Código : </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="codigo" required="required"
                                      style="text-transform:uppercase;"
                                      onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    <br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo : </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <select class="form-control" name="tipo" value="" required="required">
                                      <option></option>
                                      <option value="Modular">Modular</option>
                                      <option value="Transversal">Transversal</option>
                                      <option value="Empleabilidad">Empleabilidad</option>
                                    </select>
                                    <br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Plan de Estudios : </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="plan_estudio" value=""
                                      required="required">
                                    <br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre : </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="nombre" required="required"
                                      style="text-transform:uppercase;"
                                      onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    <br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Resolución de Creación :
                                  </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <input type="text" class="form-control" name="resolucion" required="required"
                                      style="text-transform:uppercase;"
                                      onkeyup="javascript:this.value=this.value.toUpperCase();">
                                    <br>
                                  </div>
                                </div>
                                <div class="form-group">
                                  <label class="control-label col-md-3 col-sm-3 col-xs-12">Perfil de Egresado : </label>
                                  <div class="col-md-9 col-sm-9 col-xs-12">
                                    <textarea class="form-control" rows="3" style="width: 100%; height: 165px;"
                                      name="perfil_egreso" required="required"></textarea>

                                    <br>
                                    <br>
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
                        <!--FIN DE CONTENIDO DE MODAL-->
                      </div>
                    </div>
                  </div>
                  <!-- FIN MODAL REGISTRAR-->

                  <!--MODAL CARGA DE PLAN-->
                  <div class="modal fade carga-plan" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span
                              aria-hidden="true">×</span></button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Carga de plan de estudios</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                          <div class="x_panel">
                            <div class="" align="center">
                              <h2></h2>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                              <br />
                              <form id="formCargaPlan" role="form" action="../composer/cargar_plan_estudio.php"
                                class="form-horizontal form-label-left input_mask" method="POST"
                                enctype="multipart/form-data">
                                <div class="form-group width-100">
                                  <p>Una vez cargado el documento, espere mientras se realiza la importación de datos en
                                    la base de datos.</p>
                                  <div class="well">
                                    <input type="file" class="form-control" name="plan_estudio" required="required">
                                    <br>
                                    <div align="center">
                                      <button type="submit" class="btn btn-primary"> Importar plan de estudios </button>
                                    </div>
                                    <br>
                                    <div id="waitMessage" style="display: none; text-align: center; color: #007bff;">
                                      Espere mientras se importa el plan de estudios...
                                    </div>
                                    <div class="progress">
                                      <div id="progressBar" class="custom-progress-bar" role="progressbar"
                                        style="width: 0%"></div>
                                    </div>
                                  </div>
                                </div>
                              </form>
                            </div>
                            <!--FIN DE CONTENIDO DE MODAL-->
                          </div>
                        </div>
                      </div>

                    </div>
                  </div>
                  <!-- FIN MODAL CARGA DE PLAN-->

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
    include("../include/footer.php");
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

    <style>
      .custom-progress-bar {
        height: 20px;
        background-color: #007bff;
        transition: width 0.3s;
        color: white;
        text-align: center;
        line-height: 20px;
        /* Same as height to center text vertically */
        border-radius: 0.25rem;
      }

      .progress {
        background-color: #e9ecef;
        border-radius: 0.25rem;
        height: 20px;
        overflow: hidden;
        display: none;
        /* Oculta la barra de progreso inicialmente */
      }
    </style>

    <script>
      document.getElementById('formCargaPlan').addEventListener('submit', function (event) {
        event.preventDefault(); // Evita el envío inmediato del formulario
        var progressBarContainer = document.querySelector('.progress');
        var progressBar = document.getElementById('progressBar');
        var waitMessage = document.getElementById('waitMessage');
        progressBarContainer.style.display = 'block'; // Muestra la barra de progreso
        waitMessage.style.display = 'block'; // Muestra el mensaje de espera
        progressBar.style.width = '0%';
        progressBar.innerHTML = '0%';

        var width = 0;
        var interval = setInterval(function () {
          if (width >= 100) {
            clearInterval(interval);
            document.getElementById('formCargaPlan').submit();
          } else {
            width += 10;
            progressBar.style.width = width + '%';
            progressBar.innerHTML = width + '%';
          }
        }, 300); // Incrementa cada 300ms para un total de 3 segundos
      });
    </script>
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

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }