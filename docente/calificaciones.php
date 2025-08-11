<?php
include("../include/conexion.php");
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

  $id_prog = $_GET['id'];
  $b_prog = buscarProgramacionById($conexion, $id_prog);
  $cont_res = mysqli_num_rows($b_prog);
  $res_b_prog = mysqli_fetch_array($b_prog);
  if ($r_b_docente['id_cargo'] == 2 || ($res_b_prog['id_docente'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4 || $r_b_docente['id_cargo'] == 3)) {
    $mostrar_archivo = 1;
  } else {
    $mostrar_archivo = 0;
  }
  $b_periodo_acad = buscarPeriodoAcadById($conexion, $res_b_prog['id_periodo_acad']);
  $r_per_acad = mysqli_fetch_array($b_periodo_acad);
  $fecha_actual = strtotime(date("d-m-Y"));
  $fecha_fin_per = strtotime($r_per_acad['fecha_fin']);
  if ($fecha_actual <= $fecha_fin_per) {
    $editar_doc = 1;
  } else {
    $editar_doc = 0;
  }
  if (!($mostrar_archivo)) {
    //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
    //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
    echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.history.back();
		</script>
	";
  } else {
    $mostrar_imprimible = false;
    $condicion = "readonly";
    if ($res_b_prog['id_docente'] == $id_docente_sesion) {
      $mostrar_imprimible = true;
    }
    if ($res_b_prog['id_docente'] == $id_docente_sesion or $id_docente_sesion == 2) {
      $condicion = " ";
    }
    //ORDENAMIENTO DE ESTUDIANTES
    $array_in = [];
    $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
    while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
      $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
      $r_b_mat = mysqli_fetch_array($b_matricula);
      $b_estudiante = buscarEstudianteById($conexion, $r_b_mat['id_estudiante']);
      $r_b_est = mysqli_fetch_array($b_estudiante);
      //echo $r_b_det_mat['orden']." - ".$r_b_est['apellidos_nombres']."<br>";
      $array_in[] = $r_b_est['apellidos_nombres'];
    }


    //echo "<br><br><br><br>LISTA ORDENADA <br>";
    //$collator = collator_create("es");
    //$collator->sort($array_in);


    /*foreach($array_in as $key => $val){
    $key+=1;
    echo $key." = $val"."<br>";
}*/
    $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
    while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
      $id_det_mat = $r_b_det_mat['id'];
      $mostrar_calif_final = $r_b_det_mat['mostrar_calificacion'];
      $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
      $r_b_mat = mysqli_fetch_array($b_matricula);
      $b_estudiante = buscarEstudianteById($conexion, $r_b_mat['id_estudiante']);
      $r_b_est = mysqli_fetch_array($b_estudiante);
      $indice = array_search($r_b_est['apellidos_nombres'], $array_in) + 1;
      //echo $indice." - ".$r_b_est['apellidos_nombres']."<br>";
      $consulta = "UPDATE detalle_matricula_unidad_didactica SET orden='$indice' WHERE id='$id_det_mat'";
      mysqli_query($conexion, $consulta);
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

      <title>Calificaciones<?php include("../include/header_title.php"); ?></title>
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
        p.verticalll {
          /* idéntico a rotateZ(45deg); */

          writing-mode: vertical-lr;
          transform: rotate(180deg);
        }

        .nota_input {
          width: 3em;
        }
      </style>

    </head>

    <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <!--menu-->
          <?php
          if ($r_b_docente['id_cargo'] == 5) {
            include("include/menu_docente.php");
          } elseif ($r_b_docente['id_cargo'] == 2 || $r_b_docente['id_cargo'] == 9) {
            include("include/menu_secretaria.php");
          } elseif ($r_b_docente['id_cargo'] == 4) {
            include("include/menu_coordinador.php");
          } elseif ($r_b_docente['id_cargo'] == 3) {
            include("include/menu_jua.php");
          }

          ?>

          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">

              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <?php
                    if ($cont_res == 0) {
                      echo "<h2>No Existen Registros</h2>";
                    } else {

                      $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
                      $r_b_ud = mysqli_fetch_array($b_ud);
                      //buscamos la cantidad de indicadores para definir la cantidad de calificaciones
                      $b_capacidades = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
                      $total_indicadores = 0;
                      while ($r_b_capacidades = mysqli_fetch_array($b_capacidades)) {
                        $b_indicador_capac = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $r_b_capacidades['id']);
                        $cont_indicadores = mysqli_num_rows($b_indicador_capac);
                        $total_indicadores = $total_indicadores + $cont_indicadores;
                      }
                      ;

                      ?>

                      <div class="">

                        <h2 align="center"><b>Calificaciones - <?php echo $r_b_ud['descripcion']; ?></b></h2>
                        <?php if ($mostrar_imprimible) { ?>
                          <form action="imprimir_calificaciones.php" method="POST" target="_blank">
                            <input type="hidden" name="data" value="<?php echo $id_prog; ?>">
                            <button type="submit" class="btn btn-info">Imprimir Registro Oficial</button>
                          </form>
                          <form action="imprimir_acta_final.php" method="POST" target="_blank">
                            <input type="hidden" name="data" value="<?php echo $id_prog; ?>">
                            <button type="submit" class="btn btn-success">Imprimir Acta Final</button>
                          </form>
                          <form action="imprimir_acta_recuperacion.php" method="POST" target="_blank">
                            <input type="hidden" name="data" value="<?php echo $id_prog; ?>">
                            <button type="submit" class="btn btn-primary">Imprimir Acta Recuperacion</button>
                          </form>
                          <form action="generar_reporte_excel_calificaciones.php" method="POST" target="_blank">
                            <input type="hidden" name="data" value="<?php echo $id_prog; ?>">
                            <button type="submit" class="btn btn-warning">Reporte Registra</button>
                          </form>

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
                          <div class="clearfix"></div>
                        <?php } ?>
                      </div>

                      <div class="x_content">
                        <br>
                        <form role="form" action="operaciones/actualizar_ponderado_calificacion.php"
                          class="form-horizontal form-label-left input_mask" method="POST">
                          <input type="hidden" name="id_prog" value="<?php echo $id_prog; ?>">
                          <div class="table-responsive">
                            <table id="" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th rowspan="2">

                                    <center>Nro Orden</center>
                                  </th>
                                  <th rowspan="2">
                                    <center>DNI</center>
                                  </th>
                                  <th rowspan="2">
                                    <center>APELLIDOS Y NOMBRES</center>
                                  </th>
                                  <th colspan="<?php echo $total_indicadores; ?>">
                                    <center>INDICADORES DE LOGRO</center>
                                  </th>
                                  <th rowspan="2">
                                    <center>
                                      <p class="verticalll">RECUPERACION</p>
                                    </center>
                                  </th>
                                  <th rowspan="2">
                                    <center>
                                      <p class="verticalll">PROMEDIO FINAL</p>
                                      mostrar:
                                      <input type="checkbox" name="mostrar_calif_final" <?php if ($mostrar_calif_final == 1) {
                                        echo "checked";
                                      } ?>>
                                    </center>
                                  </th>
                                </tr>
                                <tr>
                                  <?php
                                  $b_capacidades = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
                                  $cont_ind = 1;

                                  $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
                                  $r_b_det_mat = mysqli_fetch_array($b_detalle_mat);
                                  $b_calificacion = buscarCalificacionByIdDetalleMatricula($conexion, $r_b_det_mat['id']);

                                  while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
                                    ?>
                                    <th>
                                      <center>Indicador - <?php echo $cont_ind; ?>
                                        <?php if ($editar_doc) { ?>
                                          <a class="btn btn-primary"
                                            href="evaluacion_b.php?data=<?php echo $id_prog; ?>&data2=<?php echo $cont_ind; ?>"><i
                                              class="fa fa-edit"></i> Evaluar</a>
                                          <br>Mostrar:
                                          <input <?php echo $condicion; ?> type="checkbox"
                                            name="mostrar_calif_<?php echo $cont_ind; ?>" <?php if ($r_b_calificacion['mostrar_calificacion']) {
                                                 echo "checked";
                                               } ?>>
                                          <!--<br>Ponderado:
                                        <input type="number" class="nota_input" name="ponderad_<?php echo $cont_ind; ?>" value="<?php echo $r_b_calificacion['ponderado']; ?>" min="0" max="100">%-->
                                        <?php } else { ?>
                                          <a class="btn btn-primary"
                                            href="evaluacion_b.php?data=<?php echo $id_prog; ?>&data2=<?php echo $cont_ind; ?>"><i
                                              class="fa fa-eye"></i> Ver</a>
                                          <!--<br>Ponderado: <?php echo $r_b_calificacion['ponderado'] . "%"; ?>-->
                                        <?php } ?>
                                      </center>
                                    </th>
                                    <?php
                                    $cont_ind += 1;
                                  }
                                  ?>

                                  <?php

                                  ?>

                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);

                                while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {

                                  $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
                                  $r_b_mat = mysqli_fetch_array($b_matricula);

                                  $b_estudiante = buscarEstudianteById($conexion, $r_b_mat['id_estudiante']);
                                  $r_b_est = mysqli_fetch_array($b_estudiante);

                                  $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
                                  $r_b_silabo = mysqli_fetch_array($b_silabo);

                                  $cont_inasistencia = contar_inasistencia($conexion, $r_b_silabo['id'], $r_b_est['id']);
                                  if ($cont_inasistencia > 0) {
                                    $porcent_ina = round($cont_inasistencia * 100 / 16);
                                  } else {
                                    $porcent_ina = 0;
                                  }

                                  if ($r_b_mat['licencia'] != "") {
                                    $licencia = 1;
                                    $fila_si_licencia = ' style="background-color:pink"';
                                    $si_licencia = '  title="Licencia"';
                                    $text = " (Licencia)";
                                    $nom_ap = '<font color="red">' . $r_b_est['apellidos_nombres'] . $text . '</font>';
                                    $dni = '<font color="red">' . $r_b_est['dni'] . '</font>';
                                  } elseif ($porcent_ina >= 30) {
                                    $licencia = 0;
                                    $fila_si_licencia = ' style="background-color:pink"';
                                    $si_licencia = '  title="Inasistencia mayor de 30%"';
                                    $text = " (Inasistencia)";
                                    $nom_ap = '<font color="red">' . $r_b_est['apellidos_nombres'] . $text . '</font>';
                                    $dni = '<font color="red">' . $r_b_est['dni'] . '</font>';
                                  } else {
                                    $licencia = 0;
                                    $fila_si_licencia = "";
                                    $si_licencia = "";
                                    $nom_ap = $r_b_est['apellidos_nombres'];
                                    $dni = $r_b_est['dni'];
                                  }

                                  ?>
                                  <tr <?php echo $fila_si_licencia; ?>>
                                    <td>
                                      <center><?php echo $r_b_det_mat['orden']; ?></font>
                                      </center>
                                    </td>
                                    <td><?php echo $dni; ?></td>
                                    <td><?php echo $nom_ap; ?></td>
                                    <?php
                                    //buscar las calificaciones
                                    $id_det_mat = $r_b_det_mat['id'];
                                    //tamaño de texto para mostrar las calificaciones
                                    $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);
                                    $suma_calificacion = 0;
                                    $cont_calif = 0;

                                    while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {
                                      $id_calificacion = $r_b_calificacion['id'];
                                      //buscamos las evaluaciones
                                      $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);
                                      $suma_calificacion += $suma_evaluacion;
                                      if ($suma_evaluacion > 0) {
                                        $cont_calif += 1;
                                      }
                                      if ($suma_evaluacion != 0) {
                                        $calificacion = round($suma_evaluacion);
                                      } else {
                                        $calificacion = "";
                                      }

                                      if ($calificacion > 12) {
                                        echo '<td align="center"><font color="blue" >' . $calificacion . '</font></td>';
                                      } else {
                                        echo '<td align="center"><font color="red">' . $calificacion . '</font></td>';
                                      }
                                    }

                                    if ($cont_calif > 0) {
                                      $suma_calificacion = round($suma_calificacion / $cont_calif);
                                    } else {
                                      $suma_calificacion = round($suma_calificacion);
                                    }
                                    if ($suma_calificacion != 0) {
                                      $calificacion_final = round($suma_calificacion);
                                    } else {
                                      $calificacion_final = "";
                                    }


                                    if ($calificacion_final <= 12 && $calificacion_final >= 10) {
                                      if ($r_b_det_mat['recuperacion'] > 12) {
                                        if ($editar_doc) {
                                          echo '<td><input ' . $si_licencia . ' type="number" ' . $condicion . ' style="color:blue;" class="nota_input" name="recuperacion_' . $r_b_det_mat['id'] . '" value="' . $r_b_det_mat['recuperacion'] . '" min="0" max="20" ></td>';
                                        } else {
                                          echo '<td><center><font color="blue">' . $r_b_det_mat['recuperacion'] . '</font></center></td>';
                                        }
                                      } else {
                                        if ($editar_doc) {
                                          echo '<td><input ' . $si_licencia . ' type="number" ' . $condicion . ' style="color:red;" class="nota_input" name="recuperacion_' . $r_b_det_mat['id'] . '" value="' . $r_b_det_mat['recuperacion'] . '" min="0" max="20" ></td>';
                                        } else {
                                          echo '<td><center><font color="red">' . $r_b_det_mat['recuperacion'] . '</font></center></td>';
                                        }
                                      }
                                    } else {
                                      echo '<td></td>';
                                    }




                                    if ($r_b_det_mat['recuperacion'] != '') {
                                      $calificacion_final = $r_b_det_mat['recuperacion'];
                                    }
                                    if ($calificacion_final > 12) {
                                      echo '<th><center><font color="blue">' . $calificacion_final . '</font></center></th>';
                                    } else {
                                      echo '<th><center><font color="red">' . $calificacion_final . '</font></center></th>';
                                    }


                                    ?>


                                  </tr>
                                <?php } ?>
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
                            <?php if ($editar_doc) { ?>
                              <button type="submit" class="btn btn-success">Guardar</button>
                            <?php } ?>


                          </div>
                        </form>


                      </div>
                    <?php } ?>
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
