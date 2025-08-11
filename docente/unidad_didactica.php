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
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>unidades didácticas<?php include("../include/header_title.php"); ?></title>
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
        include("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Unidades Didácticas</h2>
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i
                        class="fa fa-plus-square"></i> Nuevo</button>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Unidad Didactica</th>
                          <th>Programa de Estudios</th>
                          <th>Módulo</th>
                          <th>Semestre</th>
                          <th>Créditos</th>
                          <th>Horas</th>
                          <th>Tipo</th>
                          <th>Orden</th>
                          <th>Predecesoras</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $ejec_busc_ud = buscarUnidadDidactica($conexion);
                        while ($res_busc_ud = mysqli_fetch_array($ejec_busc_ud)) {

                          ?>
                          <tr>
                            <td><?php echo $res_busc_ud['id']; ?></td>
                            <td><?php echo $res_busc_ud['descripcion']; ?></td>
                            <?php
                            $id_carrera = $res_busc_ud['id_programa_estudio'];
                            $ejec_busc_carrera = buscarCarrerasById($conexion, $id_carrera);
                            $res_busc_carrera = mysqli_fetch_array($ejec_busc_carrera);
                            ?>
                            <td><?php echo $res_busc_carrera['nombre'] . " (" . $res_busc_carrera['plan_estudio'] . ")"; ?>
                            </td>
                            <?php
                            $id_modulo = $res_busc_ud['id_modulo'];
                            $ejec_busc_modulo = buscarModuloFormativoById($conexion, $id_modulo);
                            $res_busc_modulo = mysqli_fetch_array($ejec_busc_modulo);
                            ?>
                            <td><?php echo "M" . $res_busc_modulo['nro_modulo']; ?></td>
                            <?php
                            $id_semestre = $res_busc_ud['id_semestre'];
                            $ejec_busc_semestre = buscarSemestreById($conexion, $id_semestre);
                            $res_busc_semestre = mysqli_fetch_array($ejec_busc_semestre);
                            ?>
                            <td><?php echo $res_busc_semestre['descripcion']; ?></td>
                            <td><?php echo $res_busc_ud['creditos']; ?></td>
                            <td><?php echo $res_busc_ud['horas']; ?></td>
                            <td><?php echo $res_busc_ud['tipo']; ?></td>
                            <td><?php echo $res_busc_ud['orden']; ?></td>

                            <?php
                            $codigos_correlativos_predecesoras = $res_busc_ud['codigos_ud_predecesora'];
                            $array_codigos_predecesoras = explode(";", $codigos_correlativos_predecesoras);
                            ?>
                            <td>
                              <?php foreach ($array_codigos_predecesoras as $codigo) {
                                $unidad_didactica_pred = buscarUdByCodCor($conexion, $codigo);
                                $res_unidad_didactica_pred = mysqli_fetch_array($unidad_didactica_pred);
                                ?>
                                <li class="text-left list-unstyled">
                                  <?php echo $res_unidad_didactica_pred ? $res_unidad_didactica_pred['descripcion'] : 'no presenta'; ?>
                                </li>
                              <?php } ?>
                            </td>
                            <td>
                              <a class="btn btn-success" href="editar_ud.php?id=<?php echo $res_busc_ud['id']; ?>"><i
                                  class="fa fa-pencil-square-o"></i> Editar</a>
                            </td>
                          </tr>
                          <?php
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
                            <h4 class="modal-title" id="myModalLabel" align="center">Registrar Unidad Didáctica</h4>
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
                                <form role="form" action="operaciones/registrar_unidad_didactica.php"
                                  class="form-horizontal form-label-left input_mask" method="POST">
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Unidad Didáctica : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="ud" required="required"
                                        style="text-transform:uppercase;"
                                        onkeyup="javascript:this.value=this.value.toUpperCase();">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Programa de Estudios :
                                    </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="carrera_m" name="carrera_m" value=""
                                        required="required">
                                        <option></option>
                                        <?php
                                        $ejec_busc_carr = buscarCarreras($conexion);
                                        while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                                          $id_carr = $res__busc_carr['id'];
                                          $carr = $res__busc_carr['nombre'];
                                          ?>
                                          <option value="<?php echo $id_carr;
                                          ?>"><?php echo $carr . ' - ' . $res__busc_carr['plan_estudio']; ?></option>
                                          <?php
                                        }
                                        ?>
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Módulo Formativo : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="modulo" name="modulo" value="" required="required">
                                        <!--las opciones se cargan con ajax y javascript  dependiendo de la carrera elegida,verificar en la parte final-->
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Semestre : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="semestre" name="semestre" value=""
                                        required="required">
                                        <option></option>
                                        <?php
                                        $ejec_busc_sem = buscarSemestre($conexion);
                                        while ($res_busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                                          $id_sem = $res_busc_sem['id'];
                                          $sem = $res_busc_sem['descripcion'];
                                          ?>
                                          <option value="<?php echo $id_sem;
                                          ?>"><?php echo $sem; ?></option>
                                          <?php
                                        }
                                        ?>
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Créditos : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="decimal" class="form-control" name="creditos" required="required">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Horas (Semestral): </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="number" class="form-control" name="horas" required="required">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="tipo" name="tipo" value="" required="required">
                                        <option value=""></option>
                                        <option value="ESPECIALIDAD">ESPECIALIDAD</option>
                                        <option value="EMPLEABILIDAD">EMPLEABILIDAD</option>
                                        <option value="ALTERNATIVA">ALTERNATIVA</option>
                                        <option value="TRANSVERSAL">TRANSVERSAL</option>
                                      </select>
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
        $('#carrera_m').change(function () {
          recargarlista();
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

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }