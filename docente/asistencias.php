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
    $condicion = " ";
    $guardar = true;
    if (!($res_b_prog['id_docente'] == $id_docente_sesion || $res_b_prog['id_docente_practica'] == $id_docente_sesion)) {
      $condicion = "disabled";
      $guardar = false;
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

      <title>Asistencia<?php include("../include/header_title.php"); ?></title>
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

        table {
          width: 100%;
          border-collapse: collapse;
        }


        /* Fijar las primeras dos columnas */
        tbody td:nth-child(1),
        tbody td:nth-child(2) {
          position: sticky;
          left: 0;
          background-color: #fff;
          /* Fondo para que no se vea afectado por el scroll */
          z-index: 100;
          /* Asegurarse que las columnas fijas queden encima del resto */
        }

        /* Ajustar las columnas que se desplazan para no superponer las fijas */
        tbody td:nth-child(2) {
          left: 70px;
        }
      </style>

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

          $b_d_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
          $r_b_d_mat = mysqli_fetch_array($b_d_mat);
          $b_mat = buscarMatriculaById($conexion, $r_b_d_mat['id_matricula']);
          $r_b_mat = mysqli_fetch_array($b_mat);
          //buscamos el silabo y sus datos
          $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
          $r_b_silabo = mysqli_fetch_array($b_silabo);
          $id_silabo = $r_b_silabo['id'];
          $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
          $cont_asis = 0;

          while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
            // buscamos la sesion que corresponde
            $id_act = $res_b_prog_act['id'];
            $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
            while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
              $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_mat['id_estudiante']);
              $r_b_asistencia = mysqli_fetch_array($b_asistencia);
              $cont_asis += mysqli_num_rows($b_asistencia);
            }
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



          ?>

          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">

              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="">
                      <h2 align="center"><b>Asistencia - <?php echo $r_b_ud['descripcion']; ?></b></h2>
                      <a href="../docente/calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                      <div class="clearfix"></div>
                      <form action="imprimir_excel_asistencia.php" method="POST">
                        <input type="hidden" name="id_prog" value="<?php echo $id_prog; ?>">
                        <input type="hidden" name="ud" value="<?php echo $r_b_ud['descripcion']; ?>">
                        <input type="hidden" name="cont_asis" value="<?php echo $cont_asis; ?>">
                        <input class="btn btn-primary" type="submit" value="Imprimir asistencia">
                      </form>
                    </div>
                    <div class="x_content">
                      <br>
                      <form role="form" action="operaciones/actualizar_asistencia.php"
                        class="form-horizontal form-label-left input_mask" method="POST">
                        <input type="hidden" name="id_prog" value="<?php echo $id_prog; ?>">
                        <input type="hidden" name="cant_as" value="<?php echo $cont_asis; ?>">
                        <div class="table-responsive">
                          <table id="" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                              <tr class="grupo1">

                                <th rowspan="2">
                                  <center>DNI</center>
                                </th>
                                <th rowspan="2">
                                  <center>APELLIDOS Y NOMBRES</center>
                                </th>
                                <th colspan="<?php echo $cont_asis; ?>">
                                  <center>CONTROL DE ASISTENCIA</center>
                                </th>
                                <th rowspan="2">
                                  <center>INASISTENCIA</center>
                                </th>
                              </tr>
                              <tr class="grupo1">
                                <?php


                                $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
                                $r_b_det_mat = mysqli_fetch_array($b_detalle_mat);
                                $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
                                $r_b_matricula = mysqli_fetch_array($b_matricula);

                                $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
                                $r_b_silabo = mysqli_fetch_array($b_silabo);
                                $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);

                                while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                  // buscamos la sesion que corresponde
                                  $id_act = $res_b_prog_act['id'];
                                  $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                  while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                    $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_mat['id_estudiante']);
                                    $r_b_asistencia = mysqli_fetch_array($b_asistencia);
                                    ?>
                                    <th>
                                      <p class="verticalll" id=""><?php echo $r_b_sesion['fecha_desarrollo']; ?></p>
                                    </th>
                                    <?php
                                  }
                                }
                                ?>

                              </tr>
                            </thead>
                            <tbody>

                              <?php
                              $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
                              while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
                                echo '<tr>';
                                $b_matricula = buscarMatriculaById($conexion, $r_b_det_mat['id_matricula']);
                                $r_b_matricula = mysqli_fetch_array($b_matricula);
                                if ($r_b_matricula['licencia'] != '') {
                                  $si_licencia = ' disabled';
                                } else {
                                  $si_licencia = '';
                                }
                                $b_estudiante = buscarEstudianteById($conexion, $r_b_matricula['id_estudiante']);
                                $r_b_estudiante = mysqli_fetch_array($b_estudiante);

                                $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
                                $r_b_silabo = mysqli_fetch_array($b_silabo);
                                $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
                                echo "<td>" . $r_b_estudiante['dni'] . "</td>";
                                echo "<td>" . $r_b_estudiante['apellidos_nombres'] . "</td>";
                                $cont_inasistencia = 0;
                                while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                  // buscamos la sesion que corresponde
                                  $id_act = $res_b_prog_act['id'];
                                  $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                  while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                    $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_matricula['id_estudiante']);
                                    $r_b_asistencia = mysqli_fetch_array($b_asistencia);
                                    $fecha_fin_sesion = strtotime($r_b_sesion['fecha_desarrollo']);
                                    if ($editar_doc == 0) {
                                      if ($r_b_asistencia['asistencia'] == "P") { ?>
                                        <td>
                                          <font color="blue"><?php echo $r_b_asistencia['asistencia']; ?></font>
                                        </td>
                                      <?php } elseif ($r_b_asistencia['asistencia'] == "F") { ?>
                                        <td>
                                          <font color="red"><?php echo $r_b_asistencia['asistencia']; ?></font>
                                        </td>
                                      <?php } else { ?>
                                        <td></td>
                                      <?php }
                                      ?>

                                      <?php
                                    } else {

                                      ?>
                                      <td>

                                        <select <?php echo $condicion; ?>
                                          name="<?php echo $r_b_estudiante['dni'] . "_" . $r_b_asistencia['id'] ?>"
                                          id="<?php echo $r_b_estudiante['dni'] . "_" . $r_b_asistencia['id'] ?>" <?php echo " " . $si_licencia; ?>>
                                          <option></option>
                                          <option value="P" <?php if ($r_b_asistencia['asistencia'] == "P") {
                                            echo "selected ";
                                          } ?>>P</option>
                                          <option value="F" <?php if ($r_b_asistencia['asistencia'] == "F") {
                                            echo "selected ";
                                          } ?>>F</option>
                                        </select>
                                      </td>
                                      <?php

                                    }
                                    if ($r_b_asistencia['asistencia'] == "F") {
                                      $cont_inasistencia += 1;
                                    }
                                  }
                                }
                                if ($cont_inasistencia > 0) {
                                  $porcent_ina = $cont_inasistencia * 100 / $cont_asis;
                                } else {
                                  $porcent_ina = 0;
                                }
                                if (round($porcent_ina) > 29) {
                                  echo "<td><font color='red'>" . round($porcent_ina) . "%</font></td>";
                                } else {
                                  echo "<td><font color='blue'>" . round($porcent_ina) . "%</font></td>";
                                }

                                echo "</tr>";
                              }




                              ?>

                            </tbody>
                          </table>
                        </div>
                        <?php if ($guardar) { ?>
                          <div align="center">
                            <br>
                            <br>
                            <a href="../docente/calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                            <?php if ($editar_doc) { ?>
                              <button type="submit" class="btn btn-success">Guardar</button>
                            <?php } ?>

                          </div>
                        <?php } ?>
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
