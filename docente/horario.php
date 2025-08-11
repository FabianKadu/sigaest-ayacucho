<?php
include '../include/conexion.php';
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

  $id_programacion = $_GET['id'];
  $ejec_busc_prog = buscarProgramacionById($conexion, $id_programacion);
  $res_busc_prog = mysqli_fetch_array($ejec_busc_prog);
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

    <title>Editar Programación<?php include("../include/header_title.php"); ?></title>
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
    <!-- bootstrap-wysiwyg -->
    <link href="../Gentella/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
    <!-- Select2 -->
    <link href="../Gentella/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
    <!-- Switchery -->
    <link href="../Gentella/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
    <!-- starrr -->
    <link href="../Gentella/vendors/starrr/dist/starrr.css" rel="stylesheet">
    <!-- bootstrap-daterangepicker -->
    <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">


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

        $per_select = $_SESSION['periodo'];
        $director = false;
        if ($r_b_docente['id_cargo'] == 1 and $r_b_docente['carga_academica'] == 1) {
          $director = true;
        } elseif ($r_b_docente['id_cargo'] == 3) {
          $id_docente = $id_docente_sesion;
          include("include/menu_jua.php");
          $var_consulta = "WHERE id_docente_practica=" . $id_docente . " AND id_periodo_acad=" . $per_select;
        } elseif ($r_b_docente['id_cargo'] == 5 || $director) { //si es docente
          $back_page = "calificaciones_unidades_didacticas.php";

          $id_docente = $id_docente_sesion;
          include("include/menu_docente.php");
          $var_consulta = "WHERE id_docente=" . $id_docente . " AND id_periodo_acad=" . $per_select;
        } elseif ($r_b_docente['id_cargo'] == 2) { // si es secretario
          $back_page = "programacion.php";
          include("include/menu_secretaria.php");
          $var_consulta = "WHERE id_periodo_acad=" . $per_select;
        } elseif ($r_b_docente['id_cargo'] == 9) { // si es administrador
          $back_page = "programacion.php";
          include("include/menu_secretaria.php");
          $var_consulta = "WHERE id_periodo_acad=" . $per_select;
        } elseif ($r_b_docente['id_cargo'] == 4) { // si es coordinador de area
          $id_docente = $id_docente_sesion;
          include("include/menu_coordinador.php");
          $var_consulta = "WHERE id_docente=" . $id_docente . " AND id_periodo_acad=" . $per_select;
        } else {
          $var_consulta = "";
        }
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <?php
            $id_ud = $res_busc_prog['id_unidad_didactica'];
            $b_ud = buscarUdById($conexion, $id_ud);
            $res_busc_ud = mysqli_fetch_array($b_ud);

            $res_pe = buscarCarrerasById($conexion, $res_busc_ud['id_programa_estudio']);
            $programa = mysqli_fetch_array($res_pe);

            ?>
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center"><b>HORARIO DE CLASES</b></h2>
                    <h2 align="center"><?php echo $programa['nombre'] . ' - ' . $res_busc_ud['descripcion'] ?></h2>
                    <div class="clearfix"></div>
                  </div>

                  <a class="btn btn-danger" href="<?php echo $back_page ?>"><i class="fa fa-reply"></i> Regresar</a>
                  <div class="x_content">

                    <br />

                    <div class="col-md-5 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <h4><b>Registro de horario de clase</b></h4>
                        <form role="form" action="operaciones/registrar_horario.php"
                          class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $id_programacion; ?>">
                          <div class="form-group col-md-12 col-sm-12 col-xs-6">
                            <label class="control-label ">Dia de la semana: </label>
                            <div class="">
                              <select class="form-control" name="dia" required="required">
                                <option selected disabled>Seleccione</option>
                                <option value="Lunes">Lunes</option>
                                <option value="Martes">Martes</option>
                                <option value="Miercoles">Miercoles</option>
                                <option value="Jueves">Jueves</option>
                                <option value="Viernes">Viernes</option>
                                <option value="Sabado">Sabado</option>
                              </select>
                            </div>
                          </div>
                          <div class="form-group col-md-12 col-sm-12 col-xs-6">
                            <label class="control-label ">Fecha del primer control: </label>
                            <div class="">
                              <input type="date" class="form-control" name="fecha_control" required="required">
                            </div>
                          </div>
                          <div class="form-group col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label ">Hora de inicio: </label>
                            <div class="">
                              <input type="time" class="form-control" name="inicio" required="required">
                            </div>
                          </div>
                          <div class="form-group form-group col-md-6 col-sm-6 col-xs-6">
                            <label class="control-label">Hora de termino:
                            </label>
                            <div class="">
                              <input type="time" class="form-control" name="fin" required="required">
                              <br>
                            </div>
                          </div>
                          <div align="center">
                            <button type="submit" class="btn btn-primary">Registrar</button>
                          </div>
                        </form>
                      </div>
                    </div>
                    <div class="col-md-7 col-sm-12 col-xs-12">
                      <div class="x_panel">
                        <h4><b>Dia(s) de clase</b></h4>
                        <hr>
                        <table id="example2" class="table table-striped  table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>N°</th>
                              <th>Dia</th>
                              <th>Hora</th>
                              <th>Primer control</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $res_horario = buscarHorarioPorProgramacionId($conexion, $id_programacion);
                            $num = 0;
                            while ($horario = mysqli_fetch_array($res_horario)) {
                              $num++;
                              ?>
                              <tr>
                                <td><?php echo $num; ?></td>
                                <td><?php echo $horario['dia']; ?></td>
                                <td>De
                                  <?php echo substr($horario['hora_inicio'], 0, 5) . ' a ' . substr($horario['hora_fin'], 0, 5); ?>
                                </td>
                                <td><?php echo $horario['fecha_inicial']; ?></td>
                                <td>
                                  <a href="operaciones/eliminar_dia_clase.php?id=<?php echo $horario['id']; ?>"
                                    class="btn btn-danger">Eliminar</a>
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  </div>
                  <!--
                  <div class="x_panel">
                    <div class="x_content">
                        <table id="example2" class="table table-striped  table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Semana</th>
                                    <th>Fecha de Desarrollo</th>
                                    <th>Dia y Hora</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            //buscamos las programaciones de actividades
                            $res_horario = buscarHorarioPorProgramacion($conexion, $id_programacion);
                            while ($horario = mysqli_fetch_array($res_horario)) {
                              ?>
                                <tr>
                                    <td><?php echo $horario['semana']; ?></td>
                                    <td><?php echo $horario['fecha_desarrollo']; ?></td>
                                    <td><?php echo $horario['fecha']; ?></td>
                                </tr>
                            <?php
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                  </div>
                            -->
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
    <!-- bootstrap-progressbar -->
    <script src="../Gentella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
    <!-- iCheck -->
    <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>
    <!-- bootstrap-daterangepicker -->
    <script src="../Gentella/vendors/moment/min/moment.min.js"></script>
    <script src="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
    <!-- bootstrap-wysiwyg -->
    <script src="../Gentella/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
    <script src="../Gentella/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
    <script src="../Gentella/vendors/google-code-prettify/src/prettify.js"></script>
    <!-- jQuery Tags Input -->
    <script src="../Gentella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
    <!-- Switchery -->
    <script src="../Gentella/vendors/switchery/dist/switchery.min.js"></script>
    <!-- Select2 -->
    <script src="../Gentella/vendors/select2/dist/js/select2.full.min.js"></script>
    <!-- Parsley -->
    <script src="../Gentella/vendors/parsleyjs/dist/parsley.min.js"></script>
    <!-- Autosize -->
    <script src="../Gentella/vendors/autosize/dist/autosize.min.js"></script>
    <!-- jQuery autocomplete -->
    <script src="../Gentella/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
    <!-- starrr -->
    <script src="../Gentella/vendors/starrr/dist/starrr.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>


    <!--prueba tabla-->

    <script src="../include/tabla/jquery.dataTables.min.js"></script>
    <script src="../include/tabla/dataTables.bootstrap.min.js"></script>
    <script>
      $(document).ready(function () {
        $(' #example').DataTable({
          "language": {
            "processing": "Procesando...", "lengthMenu"
              : "Mostrar _MENU_ registros", "zeroRecords": "No se encontraron resultados", "emptyTable"
              : "Ningún dato disponible en esta tabla", "sInfo"
              : "Mostrando del _START_ al _END_ de un total de _TOTAL_ registros", "infoEmpty"
              : "Mostrando registros del 0 al 0 de un total de 0 registros", "infoFiltered"
              : "(filtrado de un total de _MAX_ registros)", "search": "Buscar:", "infoThousands": ","
            , "loadingRecords": "Cargando...", "paginate": {
              "first": "Primero", "last": "Último", "next"
                : "Siguiente", "previous": "Anterior"
            },
          }
        });
      }); </script>
    <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
    <script type="text/javascript">
      $(document).ready(function () {
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
<?php } ?>