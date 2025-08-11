<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  //PROCESO ADMISION
  $id_proceso_admision = $_GET['id'];
  $busc_proc_adm = buscarProcesoAdmisionPorId($conexion,$id_proceso_admision);
  $res_b_proc_adm = mysqli_fetch_array($busc_proc_adm);

  //POSTULANTES
  $res_total_postulantes = buscarTotalPostulantesPorProceso($conexion, $id_proceso_admision);
  $total_postulantes = mysqli_num_rows($res_total_postulantes);

  //ADMITIDOS
  $res_admitidos = buscarAdmitidosPorProceso($conexion, $id_proceso_admision);
  $total_admitidos = mysqli_num_rows($res_admitidos);

  //VACACNTES PROGRAMA
  $vacantes_programa = buscarTotalVacantesProgramaPorPeriodo($conexion, $res_b_proc_adm['Periodo']);
  $total_vacantes = 0;
  $total_programas = 0;
  while ($vacantes = mysqli_fetch_array($vacantes_programa)){
    if($vacantes['Total_Vacantes'] !== 0){
      $total_programas += 1;
    }
    $total_vacantes += $vacantes['Total_Vacantes'];
  }

  $buscar = buscarDatosGenerales($conexion);
  $res = mysqli_fetch_array($buscar);
  
  ?>

  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Estadisticas
      <?php include ("../include/header_title.php"); ?>
    </title>
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

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
        include ("include/menu_secretaria.php"); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <!-- top tiles -->
          <div align="center" class="row tile_count">
          <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-calendar"></i> <?php echo $res_b_proc_adm['Tipo']; ?></span>
                    <div class="count">
                        <?php echo $res_b_proc_adm['Periodo']; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href=""><i class="green">.</i></a></span> -->
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-check-square-o"></i>Vacantes del Periodo</span>
                    <div class="count">
                        <?php
              echo $total_vacantes; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href="matriculas.php"><i class="green">Ver</i></a></span> -->
                </div>  
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-child"></i> Postulantes</span>
                    <div class="count">
                        <?php
              echo $total_postulantes; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href="estudiante.php"><i class="green">Ver</i></a></span> -->
                </div>
                
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-book"></i> Programas de Estudio</span>
                    <div class="count">
                        <?php
              echo $total_programas; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href="programacion.php"><i class="green">Ver</i></a></span> -->
                </div>
                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-pencil-square-o"></i> Colegios</span>
                    <div class="count">
                        <?php echo $total_postulantes; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href="calificaciones_unidades_didacticas.php"><iclass="green">Ver</iclass=></a></span> -->
                </div>

                <div class="col-md-2 col-sm-4 col-xs-6 tile_stats_count">
                    <span class="count_top"><i class="fa fa-line-chart"></i> Admitidos</span>
                    <div class="count">
                        <?php
              echo $total_admitidos; ?>
                    </div>
                    <!-- <span class="count_bottom"><a href="reportes.php"><i class="green">Ver </i></a></span> -->
                </div>


          </div>
          <!-- GRAFICOS ESTADISTICOS  -->
          <div class="container">
            <div class="row">
              <div class="col-md-8 col-sm-6 col-xs-12">
                <!-- INICIO DEL GRAFICO -->
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Programas de Estudio <small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="height: 350px; display: flex;">
                      <canvas id="grafico2" style="margin:auto"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->

                <!-- INICIO DEL GRAFICO -->
                <div class="col-md-6 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Medios de Pago <small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="height: 350px; display: flex;">
                      <canvas id="grafico4" style="margin:auto"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->

                <!-- INICIO DEL GRAFICO -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Colegios <small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content" style="display: flex;">
                      <canvas id="grafico5" style="margin:auto 0;"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->
                
              </div>

              <div class="col-md-4 col-sm-6 col-xs-12">
                  <!-- INICIO DEL GRAFICO -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Modalidades<small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <canvas id="grafico3"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->

                <!-- INICIO DEL GRAFICO -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Genero<small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <canvas id="grafico7"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->

                <!-- INICIO DEL GRAFICO -->
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_title">
                      <h2>Medios de Difusión <small>(Postulantes)</small></h2>
                      <div class="clearfix"></div>
                    </div>
                    <div class="x_content">
                      <canvas id="grafico1"></canvas>
                    </div>
                  </div>
                </div>
                <!-- FIN DEL GRAFICO -->
                <!-- INICIO DEL GRAFICO -->
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Edades <small>(Postulantes)</small></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <canvas id="grafico8"></canvas>
                  </div>
                </div>
              </div>
              <!-- FIN DEL GRAFICO -->
              </div>
              <br />
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
      <!-- config estadisticas -->
      <script src="../docente/operaciones/estadisticas/estadisticas_reportes.js"></script>
      <!-- Bootstrap -->
      <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
      <!-- FastClick -->
      <script src="../Gentella/vendors/fastclick/lib/fastclick.js"></script>
      <!-- NProgress -->
      <script src="../Gentella/vendors/nprogress/nprogress.js"></script>
      <!-- gauge.js -->
      <script src="../Gentella/vendors/gauge.js/dist/gauge.min.js"></script>
      <!-- bootstrap-progressbar -->
      <script src="../Gentella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
      <!-- iCheck -->
      <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>
      <!-- Skycons -->
      <script src="../Gentella/vendors/skycons/skycons.js"></script>
      <!-- Flot -->
      <script src="../Gentella/vendors/Flot/jquery.flot.js"></script>
      <script src="../Gentella/vendors/Flot/jquery.flot.pie.js"></script>
      <script src="../Gentella/vendors/Flot/jquery.flot.time.js"></script>
      <script src="../Gentella/vendors/Flot/jquery.flot.stack.js"></script>
      <script src="../Gentella/vendors/Flot/jquery.flot.resize.js"></script>
      <!-- Flot plugins -->
      <script src="../Gentella/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
      <script src="../Gentella/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
      <script src="../Gentella/vendors/flot.curvedlines/curvedLines.js"></script>
      <!-- DateJS -->
      <script src="../Gentella/vendors/DateJS/build/date.js"></script>
      <!-- JQVMap -->
      <script src="../Gentella/vendors/jqvmap/dist/jquery.vmap.js"></script>
      <script src="../Gentella/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
      <script src="../Gentella/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
      <!-- bootstrap-daterangepicker -->
      <script src="../Gentella/vendors/moment/min/moment.min.js"></script>
      <script src="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

      <!-- Custom Theme Scripts -->
      <script src="../Gentella/build/js/custom.min.js"></script>

  </body>

  </html>
  <?php
}