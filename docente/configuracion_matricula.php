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

  $id_periodo_select = $_SESSION['periodo'];

  $b_perido_act = buscarPeriodoAcadById($conexion, $id_periodo_select);
  $r_b_per_act = mysqli_fetch_array($b_perido_act);
  $fecha_actual = strtotime(date("d-m-Y"));
  $fecha_fin_per = strtotime($r_b_per_act['fecha_fin']);

  if ($fecha_fin_per >= $fecha_actual) {
    $agregar = 1;
  } else {
    $agregar = 0;
  }
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

    <title>Configuración de Matrícula<?php include("../include/header_title.php"); ?></title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

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
                    <h2 align="center">Configuración de Matrícula</h2>

                    <!-- <a href="matricula.php" class="btn btn-success"><i class="fa fa-plus-square"></i> Nuevo</a> -->
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>

                    <!--MODAL NUEVO-->
                    <div class="modal fade registrar" id="matriculaModal" tabindex="-1" role="dialog" aria-labelledby="matriculaModalLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            <h4 class="modal-title" id="matriculaModalLabel" align="center">Nueva Configuración de Matrícula</h4>
                          </div>
                          <form class="form-horizontal form-label-left" action="operaciones/guardar_configuracion_matricula.php" method="POST">
                            <div class="modal-body">
                              <div class="row">
                                <div class="col-md-6">
                                  <label for="periodo" class="control-label">Indicar periodo</label>
                          
                                  <select class="form-control" name="periodo" id="anio" required>
                                    <option value=""></option>
                                    <?php
                                      $periodos = buscarPeriodoAcademico($conexion);
                                      while ($periodo = mysqli_fetch_array($periodos)) {
                                    ?>
                                      <option value="<?php echo $periodo['id'] ?>"><?php echo $periodo['nombre'] ?></option>
                                    <?php } ?>
                                  </select>
                                </div>

                                <div class="col-md-6">
                                  <label for="creditos" class="control-label">Créditos permitidos</label>
                                  <input type="number" class="form-control" name="creditos" id="creditos" placeholder="Créditos">
                                </div>
                              </div>

                              <br>

                              <div class="row">
                                <div class="col-md-6">
                                  <label for="fechaInicio" class="control-label">Fecha de inicio</label>
                                  <input type="date" class="form-control" name="fechaInicio" id="fechaInicio">
                                </div>

                                <div class="col-md-6">
                                  <label for="ultimoDiaMatricula" class="control-label">Último día de matrícula</label>
                                  <input type="date" class="form-control" name="ultimoDiaMatricula" id="ultimoDiaMatricula">
                                </div>
                              </div>



                            </div>

                            <div class="modal-footer">
                              <center>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-success">Guardar Configuración</button>
                              </center>
                            </div>
                          </form>
                        </div>
                      </div>
                    </div>

                    <!-- FIN MODAL NUEVO-->
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>Periodo</th>
                          <th>Fecha Inicio</th>
                          <th>Ultimo día de matrícula</th>
                          <th>Cantidad Créditos Permitidos</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                        $cont_table = 0;
                        $ejec_busc_matricula = buscarConfiguracionMatriculas($conexion);

                        while ($res_busc_matricula = mysqli_fetch_array($ejec_busc_matricula)) {
                          $cont_table += 1;
                        ?>
                          <tr>
                            <td><?php echo $cont_table; ?></td>
                            <td>
                            <?php 
                            $re_periodo = buscarPeriodoAcadById($conexion, $res_busc_matricula['periodo']);
                            $res_periodo = mysqli_fetch_array($re_periodo);
                            echo $res_periodo['nombre'];
                            ?>
                            </td>

                            <td>
                            <?php echo $res_busc_matricula['fecha_inicio']; ?>

                            </td>

                            <td>
                            <?php echo $res_busc_matricula['ultimo_dia_matricula']; ?>

                            </td>

                            <td>
                            <?php echo $res_busc_matricula['creditos']; ?>

                            </td>

                            <td>
                              <button class="btn btn-warning" data-toggle="modal" data-target=".edit_<?php echo $res_busc_matricula['id'] ?>">
                                Editar &nbsp;
                                <i class="fa fa-pencil-square-o"></i>
                              </button  >
                            </td>
                          </tr>
                        <?php
                        include("include/acciones_ajustes_matricula.php");
                        };
                        ?>

                      </tbody>
                    </table>
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

    <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
    <script type="text/javascript">
      $(document).ready(function() {
        recargarlista();
        recargar_ud();
        $('#carrera_m').change(function() {
          recargarlista();
        });
        $('#modulo').change(function() {
          recargar_ud();
        });
        $('#semestre').change(function() {
          recargar_ud();
        });

      })
    </script>

    <script type="text/javascript">
      function recargarlista() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_modulos.php",
          data: "id_carrera=" + $('#carrera_m').val(),
          success: function(r) {
            $('#modulo').html(r);
          }
        });
      }
    </script>

    <script type="text/javascript">
      function recargar_ud() {
        var carr = $('#modulo').val();
        var sem = $('#semestre').val();
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_ud_sem.php",
          data: {
            id_modulo: carr,
            id_semestre: sem
          },
          success: function(r) {
            $('#unidad_didactica').html(r);
          }
        });
      }
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
