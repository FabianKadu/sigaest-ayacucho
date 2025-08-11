<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_docente.php");

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
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tickets<?php include ("../include/header_title.php"); ?></title>
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
        include ("include/menu_docente.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Preguntas Frecuentes</h2>
                    <hr style="width:25%">
                  </div>
                  <div class="x_content">

                      <?php
                      $res_preguntas = buscarPreguntasFrecuentes($conexion);
                      $cantidad_preguntas = mysqli_num_rows($res_preguntas);
                      $no_tiene_preguntas = true;

                      if ($cantidad_preguntas != 0) {
                        while ($pregunta = mysqli_fetch_array($res_preguntas)) {
                          $pregunta_roles = $pregunta['roles'];
                          $roles_seleccionados = explode('-', $pregunta_roles);

                          if (in_array($r_b_docente['id_cargo'], $roles_seleccionados)) {
                            $no_tiene_preguntas = false;
                            ?>
                            <div class="x_panel">
                              <a class="panel-heading collapsed" role="tab" id="heading<?php echo $pregunta['id']; ?>"
                                data-toggle="collapse" data-parent="#accordion" href="#collapse<?php echo $pregunta['id']; ?>"
                                aria-expanded="false" aria-controls="collapse<?php echo $pregunta['id']; ?>">
                                <h4 class="panel-title"><?php echo $pregunta['pregunta']; ?></h4>
                              </a>
                              <div id="collapse<?php echo $pregunta['id']; ?>" class="panel-collapse collapse" role="tabpanel"
                                aria-labelledby="heading<?php echo $pregunta['id']; ?>">
                                <div class="panel-body">
                                  <h3>Respuesta / Solución:</h3><br />
                                  <p><?php echo $pregunta['respuesta']; ?></p>
                                </div>
                              </div>
                            </div>
                          <?php
                          }
                        }
                      }

                      if ($no_tiene_preguntas) {
                        echo "NO HAY PREGUNTAS FRECUENTES PARA MOSTRAR";
                      }
                      ?>
<!-- 
                    <div class="x_panel">
                      <a class="panel-heading collapsed" role="tab" id="headingThree" data-toggle="collapse"
                        data-parent="#accordion" href="#collapseThree" aria-expanded="false"
                        aria-controls="collapseThree">
                        <h4 class="panel-title">¿Qué debo hacer si experimento algún problema técnico al completar la
                          encuesta?</h4>
                      </a>
                      <div id="collapseThree" class="panel-collapse collapse" role="tabpanel"
                        aria-labelledby="headingThree">
                        <div class="panel-body">
                          <h3>Respuesta / Solución:</h3><br />
                          </p>
                          <ol>
                            <li>Recarga la página: Intenta actualizar la página o reiniciar la aplicación donde estés
                              realizando la encuesta.</li><br />
                            <li>Verifica la conexión a internet: Asegúrate de estar conectado a una red estable y vuelve a
                              intentarlo.</li><br />
                            <li>Contacta al soporte técnico: Si el problema persiste, comunícate con nuestro equipo de
                              soporte técnico a través del correo electrónico <a
                                href="mailto:soporte@empresa.com">soporte@empresa.com</a> o por teléfono al <a
                                href="tel:+123456789">+123456789</a>.</li><br />
                            <li>Consulte la siguiente imagen para obtener más información:
                              <br />
                              <img src="https://via.placeholder.com/800x500" alt="Información adicional"
                                style="max-width: 100%;">
                            </li><br />
                            <li>Consulta la documentación detallada: Puedes encontrar más ayuda en nuestra <a
                                href="https://www.ejemplo.com/ayuda" style="color: blue;">página de ayuda</a>.</li><br />
                          </ol>
                        </div>
                      </div>
                    </div> -->

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
      include ("../include/footer.php");
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


    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
