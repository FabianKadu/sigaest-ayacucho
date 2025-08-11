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
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);
?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inicio<?php include("../include/header_title.php"); ?></title>
    <!--icono en el titulo-->
    <link rel="shortcut icon" href="../img/favicon.ico">
    <!-- Bootstrap -->
    <link href="../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- NProgress -->
    <link href="../Gentella/vendors/nprogress/nprogress.css" rel="stylesheet">
    <!-- iCheck -->
    <link href="../Gentella/vendors/iCheck/skins/flat/ .css" rel="stylesheet">
    <!-- bootstrap-progressbar -->
    <link href="../Gentella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../Gentella/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
  </head>
  <style>
    .img_fondo {
      width: 100%;
      height: 400px;
      object-fit: cover;
      background-repeat: no-repeat;
      background-position: center;
    }
  </style>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php
        include("include/menu_secretaria.php"); ?>
        <!-- page content -->
        <div class="right_col main" id="main" role="main">
          <!-- top tiles -->
          <div class="row tile_count">
            <div class="col-md-8 col-sm-7 col-xs-5">
              <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count blur">
                <br>
                <span class="count_top green"><i class="fa fa-calendar"></i> Periodo Académico</span>
                <?php $b_per = buscarPresentePeriodoAcad($conexion);
                $r_b_per = mysqli_fetch_array($b_per);

                $b_p = buscarPeriodoAcadById($conexion, $r_b_per['id_periodo_acad']);
                $r_b_p = mysqli_fetch_array($b_p);
                ?>
                <div class="count"><?php echo $r_b_p['nombre']; ?></div>

              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count blur">
                <span class="count_top green"><i class="fa fa-child"></i> Estudiantes</span>
                <div class="count  "><?php
                                      $b_est = buscarEstudiante($conexion);
                                      $count_est = mysqli_num_rows($b_est);
                                      echo $count_est; ?></div>
                <span class="count_bottom green"><a href="estudiante.php"><i class="green"><i class="fa fa-arrow-circle-right"></i> Ver detalles</i></a></span>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count blur">
                <span class="count_top green"><i class="fa fa-check-square-o"></i> Matrículas</span>
                <div class="count"><?php
                                    $b_mat_per = buscarMatriculaByIdPeriodo($conexion, $r_b_per['id_periodo_acad']);
                                    $count_b_mat = mysqli_num_rows($b_mat_per);
                                    echo $count_b_mat; ?></div>
                <span class="count_bottom green"><a href="matriculas.php"><i class="green"><i class="fa fa-arrow-circle-right"></i> Ver detalles</i></a></span>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count blur">
                <span class="count_top green"><i class="fa fa-book"></i>Unidades Didácticas Programadas</span>
                <div class="count"><?php
                                    $b_ud_prog = buscarProgramacionByIdPeriodo($conexion, $r_b_per['id_periodo_acad']);
                                    $count_b_prog = mysqli_num_rows($b_ud_prog);
                                    echo $count_b_prog; ?></div>
                <span class="count_bottom green"><a href="programacion.php"><i class="green"><i class="fa fa-arrow-circle-right"></i> Ver detalles</i></a></span>
              </div>
              <div class="col-md-4 col-sm-6 col-xs-12 tile_stats_count blur">
                <span class="count_top green"><i class="fa fa-pencil-square-o"></i> Evaluación</span>
                <div class="count  "><?php echo $count_b_prog; ?></div>
                <span class="count_bottom green"><a href="calificaciones_unidades_didacticas.php"><i class="green"><i class="fa fa-arrow-circle-right"></i> Ver detalles</i></a></span>
              </div>
            </div>
            <div class="col-md-4 col-sm-5 col-xs-7">
              <?php
              //anuncios o encuestas
              $res_anuncion = buscarAnunciosActivos($conexion);
              $cantidad_anuncio = mysqli_num_rows($res_anuncion);
              //capacitaciones
              $res_capacitaciones = buscarCapacitacionesActivos($conexion);
              $cantidad_capacitaciones = mysqli_num_rows($res_capacitaciones);

              $mostrar = true;
              if ($cantidad_anuncio != 0) {
                while ($anuncio = mysqli_fetch_array($res_anuncion)) {
                  $anuncio_cargo = $anuncio['usuarios'];
                  $cargos_seleccionados = explode('-', $anuncio_cargo);
                  if (in_array($r_b_docente['id_cargo'], $cargos_seleccionados)) {
                    $mostrar = false;
              ?>
                    <div class="row">
                      <div>
                        <div class="x_panel" style="background-color: #37809f30;">
                          <div class="x_title">
                            <div class="">
                              <h2>
                                <i class="fa fa-bullhorn blue">
                                  <b><?php echo $anuncio['tipo'] ?></b>
                                </i>
                              </h2>
                            </div class="">
                            <ul class="panel_toolbox" style="list-style: none;">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <b style="font-size: 14px;color: #37809f;"><?php echo $anuncio['titulo'] ?></b>
                              <p style="text-align: justify;
                                                font-size: 14px;">
                                <?php echo $anuncio['descripcion'] ?>
                              </p>
                            </div>
                            <?php if ($anuncio['enlace'] !== "") { ?>
                              <div class="text-right"><a class="btn btn-success" href="<?php echo $anuncio['enlace'] ?>" target="_blank">Ir al enlace</a></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </div>
                  <?php }
                }
              }
              if ($cantidad_capacitaciones != 0) {
                while ($capacitaciones = mysqli_fetch_array($res_capacitaciones)) {
                  $capacitacion_cargo = $capacitaciones['usuarios'];
                  $cargos_seleccionados = explode('-', $capacitacion_cargo);
                  if (in_array($r_b_docente['id_cargo'], $cargos_seleccionados)) {
                    $mostrar = false;
                  ?>
                    <div class="row">
                      <div>
                        <div class="x_panel" style="background-color: #37809f30;">
                          <div class="x_title">
                            <div class="">
                              <h2>
                                <i class="fa fa-bullhorn blue">
                                  <b>CAPACITACIÓN</b>
                                </i>
                              </h2>
                            </div class="">
                            <ul class="panel_toolbox" style="list-style: none;">
                              <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a>
                              </li>
                            </ul>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <div class="row">
                              <b style="font-size: 14px;color: #37809f;"><?php echo $capacitaciones['tema'] ?></b>
                              <p style="text-align: justify;
                                                font-size: 14px;">
                                <?php echo $capacitaciones['descripcion'] ?>
                              </p>
                              <span><strong>BAJO EL SIGUIENTE DETALLE:</strong></span>
                              <li><strong>FECHA Y HORA:</strong> <?php echo convertirFormatoFecha($capacitaciones['fecha']) . ' - ' . convertirHora($capacitaciones['hora']) ?></li>
                              <li><strong>DURACIÓN:</strong> <?php echo $capacitaciones['duracion'] ?> minutos</li>
                              <li><strong>PONENTE(S):</strong> <?php echo $capacitaciones['ponentes'] ?></li>
                            </div>
                            <?php if ($capacitaciones['enlace'] !== "") { ?>
                              <div class="text-right"><a class="btn btn-success" href="<?php echo $capacitaciones['enlace'] ?>" target="_blank">Ir al enlace</a></div>
                            <?php } ?>
                          </div>
                        </div>
                      </div>
                    </div>
              <?php }
                }
              }  ?>
            </div>
            <?php
            $res_recursos = buscarRecursos($conexion);
            $recursos = mysqli_fetch_array($res_recursos);
            if (isset($recursos['img_fondo']) && $mostrar) {
            ?>
              <!--<img src="<?php echo $recursos['img_fondo'] ?>" alt="" class="img_fondo">-->

            <?php } ?>
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
    <!-- Chart.js -->
    <script src="../Gentella/vendors/Chart.js/dist/Chart.min.js"></script>
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

    <script>
      const imageUpload = document.getElementById('imageUpload');
      const main = document.getElementById('main');
      const uploadForm = document.getElementById('uploadForm');

      imageUpload.addEventListener('change', function() {
        const file = this.files[0];

        if (!file) return;

        // Verificar que el archivo sea una imagen
        if (!file.type.startsWith('image/')) {
          alert('El archivo seleccionado debe ser una imagen.');
          return;
        }

        // Verificar que el archivo pese menos de 5 MB
        const maxSizeInMB = 2;
        if (file.size > maxSizeInMB * 1024 * 1024) {
          alert('La imagen debe pesar menos de 2 MB. Por favor, optimiza la imagen.');
          return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
          const img = new Image();
          img.src = e.target.result;

          img.onload = function() {
            // Verificar que la imagen sea horizontal
            if (img.width < img.height) {
              alert('La imagen debe ser horizontal. Por favor, selecciona una imagen con un mayor ancho que alto.');
              return;
            }

            // Si todas las validaciones se cumplen, se envía el formulario
            uploadForm.action = 'operaciones/guardar_imagen_fondo.php';
            uploadForm.method = 'POST';
            uploadForm.submit();
          };
        };
        reader.readAsDataURL(file);
      });
    </script>

  </body>

  </html>
<?php
}
