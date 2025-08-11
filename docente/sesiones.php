<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_docente_coordinador.php';
if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);


  $id_prog = $_GET['id'];
  $b_prog = buscarProgramacionById($conexion, $id_prog);
  $res_b_prog = mysqli_fetch_array($b_prog);

  //condición para ocultar
  $mostrar = true;
  if (!($res_b_prog['id_docente'] == $id_docente_sesion)) {
    $mostrar = false;
  }

  if (!($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4 || $r_b_docente['id_cargo'] == 3 || $r_b_docente['id_cargo'] == 9)) {
    //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
    //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
    echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.history.back();
		</script>
	";
  } else {
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

      <title>Sesiones de Aprendizaje<?php include("../include/header_title.php"); ?></title>
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
      <!-- bootstrap-progressbar -->
      <link href="../Gentella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
      <!-- JQVMap -->
      <link href="../Gentella/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
      <!-- bootstrap-daterangepicker -->
      <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
      <!-- Custom Theme Style -->
      <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
      <!-- Script obtenido desde CDN jquery -->
      <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
      <script>
        function confirmard() {
          var r = confirm("Estas Seguro de Duplicar la sesion?");
          if (r == true) {
            return true;
          } else {
            return false;
          }
        }
      </script>
      <script>
        function confirmardelete() {
          var r = confirm("Estas Seguro de Eliminar la sesion?");
          if (r == true) {
            return true;
          } else {
            return false;
          }
        }
      </script>

    </head>

    <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <!--menu-->
          <?php
          $per_select = $_SESSION['periodo'];
          $id_docente = $id_docente_sesion;
          $director = false;
          $unidad = false;
          if ($r_b_docente['id_cargo'] == 1 and $r_b_docente['carga_academica'] == 1) {
            $director = true;
          }

          if ($r_b_docente['id_cargo'] == 3) {
            include("include/menu_jua.php");
          }

          if ($r_b_docente['id_cargo'] == 5 || $director || $unidad) { //si es docente
            include("include/menu_docente.php");

            $var_consulta = "WHERE id_docente=" . $id_docente . " AND id_periodo_acad=" . $per_select;
          } elseif ($r_b_docente['id_cargo'] == 4) {
            include("include/menu_coordinador.php");
            $var_consulta = "WHERE id_docente=" . $id_docente . " AND id_periodo_acad=" . $per_select;
          } elseif ($r_b_docente['id_cargo'] == 9) {
            include("include/menu_secretaria.php");
            $var_consulta = "WHERE id_docente=" . $id_docente . " AND id_periodo_acad=" . $per_select;
          }


          $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
          $r_b_ud = mysqli_fetch_array($b_ud);
          //buscar programa de estudio
          $b_pe = buscarCarrerasById($conexion, $r_b_ud['id_programa_estudio']);
          $r_b_pe = mysqli_fetch_array($b_pe);
          //buscar modulo profesional
          $b_mod = buscarModuloFormativoById($conexion, $r_b_ud['id_modulo']);
          $r_b_mod = mysqli_fetch_array($b_mod);
          //buscar semestre
          $b_sem = buscarSemestreById($conexion, $r_b_ud['id_semestre']);
          $r_b_sem = mysqli_fetch_array($b_sem);
          //buscamos el silabo y sus datos
          $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
          $r_b_silabo = mysqli_fetch_array($b_silabo);
          $id_silabo = $r_b_silabo['id'];

          ?>

          <!-- page content -->
          <div class="right_col" role="main">


            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Sesiones de Aprendizaje - <?php echo $r_b_ud['descripcion']; ?></h2>
                    <?php
                    if ($r_b_docente['id_cargo'] == 4) {
                    ?>
                      <a href="../docente/pe_calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                    <?php
                    } else {
                    ?>
                      <a href="../docente/calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                    <?php
                    }
                    ?>
                    <?php if ($mostrar) { ?>
                      <button class="btn btn-success" data-toggle="modal" data-target=".copiar">Copiar Sesiones</button>
                    <?php } ?>
                    <div class="clearfix"></div>
                    <!--MODAL COPIAR INFFORMACION-->
                    <div class="modal fade copiar" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel" align="center">Copiar Información de Sesiones de
                              Apredizaje</h4>
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
                                <form role="form" action="operaciones/copiar_informacion_sesiones.php" method="POST"
                                  class="form-horizontal form-label-left input_mask">
                                  <input type="hidden" name="myidactual" value="<?php echo $id_prog; ?>">
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Copiar Sesiones de : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="sesion_copi" name="sesion_copi" value=""
                                        required="required">
                                        <option></option>
                                        <?php
                                        $ejec_busc_prog = buscarProgramacionEspecial($conexion, $var_consulta);
                                        while ($res_busc_prog = mysqli_fetch_array($ejec_busc_prog)) {
                                          $id_prog_b = $res_busc_prog['id'];
                                          $id_ud = $res_busc_prog['id_unidad_didactica'];
                                          $b_ud = buscarUdById($conexion, $id_ud);
                                          $res_b_ud = mysqli_fetch_array($b_ud);

                                          $id_carrera = $res_b_ud['id_programa_estudio'];
                                          $ejec_busc_carrera = buscarCarrerasById($conexion, $id_carrera);
                                          $res_busc_carrera = mysqli_fetch_array($ejec_busc_carrera);

                                          $id_semestre = $res_b_ud['id_semestre'];
                                          $ejec_busc_semestre = buscarSemestreById($conexion, $id_semestre);
                                          $res_busc_semestre = mysqli_fetch_array($ejec_busc_semestre);
                                        ?>
                                          <option value="<?php echo $id_prog_b; ?>">
                                            <?php echo $res_b_ud['descripcion'] . " - "; ?>
                                            <?php echo $res_busc_carrera['nombre'] . " - "; ?>
                                            <?php echo $res_busc_semestre['descripcion']; ?>
                                          </option>
                                        <?php
                                        };
                                        ?>

                                      </select>
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

                    <!-- FIN MODAL COPIAR INFORMACION-->
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped  table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>Semana</th>
                          <th>Sesión</th>
                          <th>Unidad Didactica</th>
                          <th>Docente</th>
                          <th>% de avance</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        //buscamos las programaciones de actividades
                        $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
                        while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                          // buscamos la sesion que corresponde
                          $id_act = $res_b_prog_act['id'];
                          $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                          $contar = 0;
                          while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                            $contar += 1;

                            $id_sesion = $r_b_sesion['id'];
                            $id_docente = $res_b_prog['id_docente'];
                        ?>
                            <tr>
                              <td><?php echo $res_b_prog_act['semana']; ?></td>
                              <td><?php echo $contar; ?></td>
                              <td><?php echo $r_b_ud['descripcion']; ?></td>
                              <?php
                              $ejec_busc_docente = buscarDocenteById($conexion, $id_docente);
                              $res_busc_docente = mysqli_fetch_array($ejec_busc_docente);
                              ?>
                              <td><?php echo $res_busc_docente['apellidos_nombres']; ?></td>
                              <?php
                              $porcentaje = determinarPorcentajeAvence($conexion, $r_b_sesion['id']);
                              $color = obtenerColor($porcentaje);
                              echo "<td class='" . $color . "'>" . $porcentaje . "% de avance</td>";
                              ?>
                              <td>
                                <a title="Ver / Editar" class="btn btn-success"
                                  href="sesion_de_aprendizaje.php?id=<?php echo $r_b_sesion['id']; ?>"><i
                                    class="fa fa-pencil-square-o"></i></a>
                                <a title="Imprimir" class="btn btn-info" target="_blank"
                                  href="imprimir_sesion.php?data=<?php echo $r_b_sesion['id']; ?>"><i
                                    class="fa fa-print"></i></a>
                                <?php if ($mostrar) { ?>
                                  <a title="Duplicar Sesión de Aprendizaje" class="btn btn-warning"
                                    href="operaciones/duplicar_sesion.php?data=<?php echo $r_b_sesion['id']; ?>&data2=<?php echo $id_prog; ?>"
                                    onclick="return confirmard();"><i class="fa fa-plus-square"></i></a>
                                <?php }
                                if ($contar > 1 and $mostrar) { ?>
                                  <a title="Eliminar Sesión de Aprendizaje" class="btn btn-danger"
                                    href="operaciones/eliminar_sesion.php?data=<?php echo $r_b_sesion['id']; ?>&data2=<?php echo $id_prog; ?>"
                                    onclick="return confirmardelete();"><i class="fa fa-remove"></i></a>
                                <?php } ?>

                              </td>
                            </tr>
                        <?php
                          }
                        };
                        ?>

                      </tbody>
                    </table>

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
      <?php mysqli_close($conexion); ?>
    </body>

    </html>
<?php }
}
