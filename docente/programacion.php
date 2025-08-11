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

    <title>Programación Unidades Didácticas<?php include("../include/header_title.php"); ?></title>
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
                    <h2 align="center">Programación Unidades Didácticas</h2>
                    <?php if ($agregar == 1) {
                      echo '<button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>';
                    } ?>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>Identificador</th>
                          <th style="width:20%">Programa de Estudios</th>
                          <th style="width:10%">Semestre</th>
                          <th style="width:20%">Unidad Didáctica</th>
                          <th style="width:15%">Docente - Teoría</th>
                          <th style="width:15%">Docente - Práctica</th>
                          <th style="width:20%">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $cont = 0;
                        $ejec_busc_programacion = buscarProgramacionByIdPeriodo($conexion, $id_periodo_select);
                        while ($res_busc_programacion = mysqli_fetch_array($ejec_busc_programacion)) {
                          $cont += 1;
                          ?>
                          <tr>
                            <td><?php echo $cont; ?></td>
                            <?php
                            $id_unidad_didactica = $res_busc_programacion['id_unidad_didactica'];
                            $busc_unidad_didactica = buscarUdById($conexion, $id_unidad_didactica);
                            $res_b_unidad_didactica = mysqli_fetch_array($busc_unidad_didactica);
                            $id_programa_estudio = $res_b_unidad_didactica['id_programa_estudio'];
                            $id_modulo_formativo = $res_b_unidad_didactica['id_modulo'];
                            $id_semestre = $res_b_unidad_didactica['id_semestre'];
                            $id_docente = $res_busc_programacion['id_docente'];
                            $id_docente_practica = $res_busc_programacion['id_docente_practica'];

                            $busc_semestre = buscarSemestreById($conexion, $id_semestre);
                            $res_b_semestre = mysqli_fetch_array($busc_semestre);

                            $ejec_busc_modulo = buscarModuloFormativoById($conexion, $id_modulo_formativo);
                            $res_busc_modulo = mysqli_fetch_array($ejec_busc_modulo);

                            $ejec_busc_carrera = buscarCarrerasById($conexion, $id_programa_estudio);
                            $res_busc_carrera = mysqli_fetch_array($ejec_busc_carrera);

                            $busc_docente = buscarDocenteById($conexion, $id_docente);
                            $res_b_docente = mysqli_fetch_array($busc_docente);
                            $busc_docente_practica = buscarDocenteById($conexion, $id_docente_practica);
                            $res_b_docente_practica = mysqli_fetch_array($busc_docente_practica);
                            ?>
                            <td><?php echo $res_busc_carrera['nombre']. " (".$res_busc_carrera['plan_estudio'].")"; ?></td>
                            <?php

                            ?>
                            <td><?php echo $res_b_semestre['descripcion']; ?></td>
                            <td><?php echo $res_b_unidad_didactica['descripcion']; ?></td>
                            <td><?php echo $res_b_docente['apellidos_nombres']; ?></td>
                            <td><?php echo isset($res_b_docente_practica['apellidos_nombres']) ? $res_b_docente_practica['apellidos_nombres'] : $res_b_docente['apellidos_nombres']; ?></td>
                            <td>
                              <?php if ($agregar == 1) {
                                echo '<a class="btn btn-success" data-toggle="tooltip" data-original-title="Editar programación" href="editar_programacion.php?id=' . $res_busc_programacion['id'] . '"><i class="fa fa-pencil-square-o"></i> </a>';
                                echo '<a class="btn btn-info" data-toggle="tooltip" data-original-title="Horario" href="horario.php?id=' . $res_busc_programacion['id'] . '"><i class="fa fa-calendar-o"></i> </a>';
                              } ?>
                              <a class="btn btn-dark" data-toggle="tooltip" data-original-title="Asistencia del docente"
                                href="asistencia_docente.php?id=<?php echo $res_busc_programacion['id'] ?>"><i
                                  class="fa fa-list-alt"></i> </a>
                            </td>
                          </tr>
                          <?php
                        }
                        ;
                        ?>

                      </tbody>
                    </table>
                    <?php if ($agregar == 1) {
                      ?>
                      <!--MODAL REGISTRAR-->
                      <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                          <div class="modal-content">

                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                              </button>
                              <h4 class="modal-title" id="myModalLabel" align="center">Registrar Programación de Unidad
                                Didáctica</h4>
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
                                  <form role="form" action="operaciones/registrar_programacion.php"
                                    class="form-horizontal form-label-left input_mask" method="POST">

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
                                            ?>">
                                              <?php echo $carr . ' - ' . $res__busc_carr['plan_estudio']; ?>
                                            </option>
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
                                          while ($res__busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                                            $id_sem = $res__busc_sem['id'];
                                            $sem = $res__busc_sem['descripcion'];
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
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Unidad Didáctica : </label>
                                      <div class="col-md-9 col-sm-9 col-xs-12">
                                        <select class="form-control" id="unidad_didactica" name="unidad_didactica" value=""
                                          required="required">
                                          <!--las opciones se cargan con ajax y javascript  dependiendo de la carrera elegida,verificar en la parte final-->
                                        </select>
                                        <br>
                                      </div>
                                    </div>
                                    <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Docente - Teoría : </label>
                                      <div class="col-md-9 col-sm-9 col-xs-12">
                                        <select class="form-control" id="docente-teoria" name="docente-teoria" value=""
                                          required="required">
                                          <option></option>
                                          <?php
                                          $ejec_busc_doc = buscarDocenteActivos($conexion);
                                          while ($res__busc_docente = mysqli_fetch_array($ejec_busc_doc)) {
                                            $id_doc = $res__busc_docente['id'];
                                            $doc = $res__busc_docente['apellidos_nombres'];

                                            ?>
                                            <option value="<?php echo $id_doc;
                                            ?>"><?php echo $doc; ?></option>
                                            <?php
                                          }
                                          ?>
                                        </select>
                                        <br>
                                      </div>
                                    </div>

                                    <div class="form-group">
                                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Docente - Práctica : </label>
                                      <div class="col-md-9 col-sm-9 col-xs-12">
                                        <select class="form-control" id="docente-practica" name="docente-practica" value=""
                                          required="required">
                                          <option></option>
                                          <?php
                                          $ejec_busc_doc = buscarDocenteActivos($conexion);
                                          while ($res__busc_docente = mysqli_fetch_array($ejec_busc_doc)) {
                                            $id_doc = $res__busc_docente['id'];
                                            $doc = $res__busc_docente['apellidos_nombres'];

                                            ?>
                                            <option value="<?php echo $id_doc;
                                            ?>"><?php echo $doc; ?></option>
                                            <?php
                                          }
                                          ?>
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
                      <?php
                    } ?>

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
        recargar_ud();
        $('#carrera_m').change(function () {
          recargarlista();
        });
        $('#modulo').change(function () {
          recargar_ud();
        });
        $('#semestre').change(function () {
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
          success: function (r) {
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
          success: function (r) {
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