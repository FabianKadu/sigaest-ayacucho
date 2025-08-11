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
  if (!($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4 || $r_b_docente['id_cargo'] == 3 || $r_b_docente['id_cargo'] == 9)) {
    //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
    //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
    echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.history.back();
		</script>
	";
  } else {
    $condicion = "disabled";
    $actualizar = false;
    if (($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion)) {
      $condicion = "";
      $actualizar = true;
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

      <title>Sílabo<?php include("../include/header_title.php"); ?></title>
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
      <!-- script para tags -->
      <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
        rel="stylesheet" />

    </head>

    <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <!--menu-->
          <?php
          $director = false;
          $unidad = false;
          if ($r_b_docente['id_cargo'] == 1 and $r_b_docente['carga_academica'] == 1) {
            $director = true;
          }

          if ($r_b_docente['id_cargo'] == 3) {
            include("include/menu_jua.php");
          }

          if ($r_b_docente['id_cargo'] == 5 || $director || $unidad) {//si es docente
            include("include/menu_docente.php");
          } elseif ($r_b_docente['id_cargo'] == 4) {
            include("include/menu_coordinador.php");
          }
          elseif ($r_b_docente['id_cargo'] == 9) {
            include("include/menu_secretaria.php");
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
          //buscamos la cantidad de indicadores para definir la cantidad de calificaciones
          $b_capacidades = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
          $total_indicadores = 0;
          while ($r_b_capacidades = mysqli_fetch_array($b_capacidades)) {
            $b_indicador_capac = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $r_b_capacidades['id']);
            $cont_indicadores = mysqli_num_rows($b_indicador_capac);
            $total_indicadores = $total_indicadores + $cont_indicadores;
          }
          ;

          //horas semanales
          $res_horas_sem = buscarHorasSemanalesPorIdPud($conexion, $id_prog);
          $horas_sem = mysqli_fetch_array($res_horas_sem);
          $horas_semanal = $horas_sem['total_horas'] . ' horas';
          if ($horas_sem['total_minutos'] != 0) {
            $horas_semanal = $horas_sem['total_horas'] . ' horas y ' . $horas_sem['total_minutos'] . ' minutos';
          }
          ?>



          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">

              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="">
                      <h2 align="center"><b>Sílabo - <?php echo $r_b_ud['descripcion']; ?></b></h2>
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
                      <br>
                      <form action="imprimir_silabo.php" method="POST" target="_blank"><input type="hidden" name="data"
                          value="<?php echo $id_prog; ?>">
                        <button type="submit" class="btn btn-info">Imprimir</button>
                      </form>
                      <?php if ($actualizar) { ?>
                        <br>
                        <?php
                        $documento = $r_b_silabo['documento'];
                        ?>
                        <b>Cuenta con el sílabo en formato PDF?, puede subirlo aquí:</b>
                        <form action="operaciones/cargar_silabo.php" method="POST" class="row" enctype="multipart/form-data">
                          <input type="hidden" name="id_silabo" value="<?php echo $id_silabo; ?>">
                          <div class="col-md-4 col-sm-4 col-xs-12 form-group">
                            <input type="file" name="documento" class="form-control" accept=".pdf">
                          </div>
                          <div class="col-md-8 col-sm-8 col-xs-12">
                            <?php
                            if (is_null($documento)) { ?>
                              <button type="submit" class="btn btn-success">Cargar documento</button>
                            <?php } else {
                              if (!is_null($documento)) { ?>
                                <button type="submit" class="btn btn-success">Actualizar Documento</button>
                                <a href="<?php echo $documento; ?>" target="_blank" class="btn btn-primary">Ver silabo cargado</a>
                              <?php }
                            } ?>
                          </div>
                        </form>
                      <?php } else { ?>
                        <a href="<?php echo $documento; ?>" target="_blank" class="btn btn-primary">Ver silabo cargado</a>
                      <?php } ?>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <br>

                      <form role="form" action="operaciones/actualizar_silabo.php"
                        class="form-horizontal form-label-left input_mask" method="POST">
                        <input type="hidden" name="id_prog" value="<?php echo $id_prog; ?>">
                        <input type="hidden" name="id_silabo" value="<?php echo $id_silabo; ?>">
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered jambo_table bulk_action">
                            <thead>
                              <tr>

                                <th colspan="2">
                                  <center>INFORMACIÓN GENERAL</center>
                                </th>

                              </tr>
                              <tr>

                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td width="30%">Programa de Estudios</td>
                                <td>: <?php echo $r_b_pe['nombre']; ?></td>
                              </tr>
                              <tr>
                                <td>Módulo Profesional</td>
                                <td>: <?php echo $r_b_mod['descripcion']; ?></td>
                              </tr>
                              <tr>
                                <td>Coordinador de Área</td>
                                <td>:
                                  <select <?php echo $condicion; ?> name="coordinador" id="coordinador">
                                    <option value=""></option>
                                    <?php
                                    $b_coordinador = buscarCoordinadorArea_All($conexion);
                                    while ($r_b_coordinador = mysqli_fetch_array($b_coordinador)) { ?>
                                      <option value="<?php echo $r_b_coordinador['id']; ?>" <?php if ($r_b_coordinador['id'] == $r_b_silabo['id_coordinador']) {
                                           echo "selected";
                                         } ?>>
                                        <?php echo $r_b_coordinador['apellidos_nombres']; ?>
                                      </option>
                                      <?php
                                    }
                                    ?>
                                  </select>
                              </tr>
                              <tr>
                                <?php
                                $b_docente_teo = buscarDocenteById($conexion, $res_b_prog['id_docente']);
                                $r_b_docente_teo = mysqli_fetch_array($b_docente_teo)
                                  ?>
                                <td>Docente de Teoría</td>
                                <td>: <?php echo $r_b_docente_teo['apellidos_nombres'] ?></td>
                              </tr>
                              <tr>
                                <?php
                                $b_docente_prac = buscarDocenteById($conexion, $res_b_prog['id_docente_practica']);
                                $r_b_docente_prac = mysqli_fetch_array($b_docente_prac)
                                  ?>
                                <td>Docente de prácticas</td>
                                <td>: <?php echo $r_b_docente_prac['apellidos_nombres'] ?></td>
                              </tr>
                              <tr>
                                <td>Unidad Didáctica</td>
                                <td>: <?php echo $r_b_ud['descripcion']; ?></td>
                              </tr>
                              <tr>
                                <td>Créditos</td>
                                <td>: <?php echo $r_b_ud['creditos']; ?></td>
                              </tr>
                              <tr>
                                <td>Semestre Académico</td>
                                <td>: <?php echo $r_b_sem['descripcion']; ?></td>
                              </tr>
                              <tr>
                                <td>Nro de Horas Semanal</td>
                                <td>: <?php echo $horas_semanal; ?></td>
                              </tr>
                              <tr>
                                <td>Nro de Horas Semestral</td>
                                <td>: <?php echo $r_b_ud['horas']; ?></td>
                              </tr>
                              <tr>
                                <td>Horario</td>
                                <td>
                                  <?php
                                  $res_horario = buscarHorarioPorProgramacionId($conexion, $id_prog);

                                  while ($horario = mysqli_fetch_array($res_horario)) {
                                    echo ' | ' . $horario['dia'] . ' de ' . substr($horario['hora_inicio'], 0, 5) . ' a ' . substr($horario['hora_fin'], 0, 5);
                                  }
                                  ?>
                                </td>

                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered jambo_table bulk_action">
                            <thead>
                              <tr>
                                <th colspan="5">
                                  <center>ORGANIZACIÓN DE ACTIVIDADES Y CONTENIDOS BÁSICOS</center>
                                </th>
                              </tr>
                              <tr>
                                <th>
                                  <center>Semana</center>
                                </th>
                                <th>
                                  <center>Elemento de Capacidad</center>
                                </th>
                                <th>
                                  <center>Actividades Aprendizaje</center>
                                </th>
                                <th>
                                  <center>Contenidos Básicos</center>
                                </th>
                                <th>
                                  <center>Tareas Previas</center>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              //buscar programacion de actividades del silabo
                              $b_act_silabo = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
                              $cant_actividades = mysqli_num_rows($b_act_silabo);
                              while ($r_b_act_silabo = mysqli_fetch_array($b_act_silabo)) {
                                ?>
                                <tr>
                                  <td>
                                    <center>
                                      <?php echo $r_b_act_silabo['semana'] . "<br>"; ?><!--<input type="date" name="fecha_<?php echo $r_b_act_silabo['id']; ?>" value="<?php echo $r_b_act_silabo['fecha']; ?>">-->
                                      <?php if ($r_b_act_silabo['semana'] > 16) { ?>
                                        <!--<button type="button" class="btn btn-danger">Eliminar</button>-->
                                      <?php } ?>
                                    </center>
                                  </td>
                                  <td><textarea <?php echo $condicion; ?> name="elemento_<?php echo $r_b_act_silabo['id']; ?>"
                                      style="width:100%; resize: none; height:auto;"
                                      rows="3"><?php echo $r_b_act_silabo['elemento_capacidad']; ?></textarea></td>
                                  <td><textarea <?php echo $condicion; ?>
                                      name="actividad_<?php echo $r_b_act_silabo['id']; ?>"
                                      style="width:100%; resize: none; height:auto;"
                                      rows="3"><?php echo $r_b_act_silabo['actividades_aprendizaje']; ?></textarea></td>
                                  <td><textarea <?php echo $condicion; ?>
                                      name="contenidos_<?php echo $r_b_act_silabo['id']; ?>"
                                      style="width:100%; resize: none; height:auto;"
                                      rows="3"><?php echo $r_b_act_silabo['contenidos_basicos']; ?></textarea></td>
                                  <td><textarea <?php echo $condicion; ?> name="tareas_<?php echo $r_b_act_silabo['id']; ?>" 
                                      style="width:100%; resize: none; height:auto;"
                                      rows="3"><?php echo $r_b_act_silabo['tareas_previas']; ?></textarea></td>
                                </tr>
                                <?php
                              }
                              ?>
                              <input type="hidden" name="cant_actividades" value="<?php echo $cant_actividades; ?>">
                            </tbody>

                          </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered jambo_table bulk_action">
                            <thead>
                              <tr>
                                <th>METODOLOGÍA</th>
                              </tr>

                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="metodologia"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="5"><?php echo $r_b_silabo['metodologia']; ?></textarea>
                                </td>
                              </tr>
                              <thead>
                                <tr>
                                  <th>RECURSOS DIDÁCTICOS</th>
                                </tr>
                              </thead>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="recursos_didacticos"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="5"><?php echo $r_b_silabo['recursos_didacticos']; ?></textarea>
                                </td>
                              </tr>
                              <thead>
                                <tr>
                                  <th>SISTEMA DE EVALUACIÓN</th>
                                </tr>
                              </thead>
                              <tr>
                                <td>
                                  <div class="control-group">
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                      <textarea <?php echo $condicion; ?> name="sistema_evaluacion" id="sistema_evaluacion"
                                        rows="8"
                                        style="width:100%; resize: none; height:auto;"><?php echo $r_b_silabo['sistema_evaluacion']; ?></textarea>
                                    </div>
                                  </div>
                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered jambo_table bulk_action">
                            <thead>
                              <tr>
                                <th colspan="2" width="100%">
                                  <center>ESTRATEGÍAS DE EVALUACIÓN</center>
                                </th>
                              </tr>
                              <tr>
                                <th width="50%">
                                  <center>INDICADORES</center>
                                </th>
                                <th width="50%">
                                  <center>TÉCNICAS (Instrumentos)</center>
                                </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="indicadores_estrategias"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="8"><?php echo $r_b_silabo['estrategia_evaluacion_indicadores']; ?></textarea>

                                </td>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="tecnicas_estrategias"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="8"><?php echo $r_b_silabo['estrategia_evaluacion_tecnica']; ?></textarea>

                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="table-responsive">
                          <table class="table table-striped table-bordered jambo_table bulk_action">
                            <tbody>
                              <thead>
                                <tr>
                                  <th>RECURSOS BIBLIOGRAFICOS - Impresos</th>
                                </tr>
                              </thead>

                              <tr>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="recursos_bib_imp"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="5"><?php echo $r_b_silabo['recursos_bibliograficos_impresos']; ?></textarea>

                              </tr>
                              <thead>
                                <tr>
                                  <th>RECURSOS BIBLIOGRAFICOS - Digitales</th>
                                </tr>
                              </thead>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion; ?> name="recursos_bib_digi"
                                    style="width:100%; resize: none; height:auto;"
                                    rows="5"><?php echo $r_b_silabo['recursos_bibliograficos_digitales']; ?></textarea>

                                </td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div align="center">
                          <br>
                          <br>
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
                          <button type="submit" class="btn btn-success">Guardar</button>
                        </div>
                      </form>


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
      <script src=".../Gentella/vendors/iCheck/icheck.min.js"></script>
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

      <!-- para tags -->
      <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>

      <script>
        $(document).ready(function () {
          $('#example').DataTable({
            "order": [
              [1, "asc"]
            ],
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
    <?php
  }
}