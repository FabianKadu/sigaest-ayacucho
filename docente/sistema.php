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
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Datos de Sistema<?php include("../include/header_title.php"); ?></title>
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

  <body class="nav-md" onload="desactivar_controles();">
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
                    <h2 align="center">Datos de Sistema</h2>


                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div class="x_panel">
                      <?php
                      $buscar = buscarDatosSistema($conexion);
                      $res = mysqli_fetch_array($buscar);
                      ?>
                      <div class="x_content">
                        <br />
                        <form role="form" id="myform" action="operaciones/actualizar_datos_sistema.php"
                          class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Direccion URL de Página Web :
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="pagina" id="pagina" required=""
                                value="<?php echo $res['pagina']; ?>" placeholder="ejemplo.edu.pe">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Direccion URL de Sistema : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="dominio_sistema" id="dominio_sistema"
                                required="" value="<?php echo $res['dominio_sistema']; ?>"
                                placeholder="sispa.ejemplo.edu.pe">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Favicon : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="favicon" id="favicon" required=""
                                value="<?php echo $res['favicon']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Logo : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="logo" id="logo" required=""
                                value="<?php echo $res['logo']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Titulo Abreviado : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="titulo" id="titulo" required=""
                                value="<?php echo $res['titulo']; ?>" placeholder="IEST EJEMPLO">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">RUC : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="ruc" id="ruc" required=""
                                value="<?php echo $res['ruc']; ?>" placeholder="12345678901">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Región : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="region" id="region" required=""
                                value="<?php echo $res['region']; ?>" placeholder="Ejemplo Región">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Pie de pagina : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="pie_pagina" id="pie_pagina" required=""
                                value="<?php echo $res['pie_pagina']; ?>" placeholder="IEST EJEMPLO">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Host para Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="host_email" id="host_email" required=""
                                value="<?php echo $res['host_email']; ?>" placeholder="sispa.ejemplo.edu.pe">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="email_email" id="email_email" required=""
                                value="<?php echo $res['email_email']; ?>" placeholder="admin@sispa.ejemplo.edu.pe">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Password Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="password_email" id="password_email"
                                required="" value="<?php echo $res['password_email']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Puerto Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="puerto_email" id="puerto_email" required=""
                                value="<?php echo $res['puerto_email']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Color Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="color" class="form-control" name="color_correo" id="color_correo" required=""
                                value="<?php echo $res['color_correo']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">
                              <nav></nav>Términos y Condisiones - Admisión :
                            </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="req_admision" class="form-control" name="req_admision" id="req_admision"
                                required="" value="<?php echo $res['req_admision']; ?>">
                              <p>U.R.L. de los términos y condiciones para el tratamiento de información del postulante en
                                una inscripción en un proceso de admisión.</p>
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Soporte Email : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="email_soporte" id="email_soporte" required=""
                                value="<?php echo $res['email_soporte']; ?>" placeholder="soporte@sigaest.edu.pe">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Términos y Condisiones - Bolsa
                              Laboral : </label>
                            <div class="col-md-9 col-sm-9 col-xs-12">
                              <input type="req_bolsa_laboral" class="form-control" name="req_bolsa_laboral"
                                id="req_bolsa_laboral" required="" value="<?php echo $res['req_bolsa_laboral']; ?>">
                              <p>U.R.L. de los términos y condiciones para el tratamiento de información de empresas que
                                solicitan una cuenta en el modulo de bolsa laboral.</p>
                              <br>
                            </div>
                          </div>
                          <div align="center">
                            <button type="submit" class="btn btn-primary" id="btn_guardar">Guardar</button>
                            <button type="button" class="btn btn-warning" id="btn_cancelar"
                              onclick="desactivar_controles(); cancelar();">Cancelar</button>
                          </div>
                      </div>
                      </form>
                      <div align="center">
                        <button type="button" class="btn btn-success" id="btn_editar"
                          onclick="activar_controles();">Editar Datos</button>
                      </div>
                    </div>
                    <!--FIN DE CONTENIDO DE MODAL-->




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
    <script type="text/javascript">
      function desactivar_controles() {
        document.getElementById('pagina').disabled = true
        document.getElementById('dominio_sistema').disabled = true
        document.getElementById('favicon').disabled = true
        document.getElementById('logo').disabled = true
        document.getElementById('titulo').disabled = true
        document.getElementById('ruc').disabled = true
        document.getElementById('region').disabled = true
        document.getElementById('pie_pagina').disabled = true
        document.getElementById('host_email').disabled = true
        document.getElementById('email_email').disabled = true
        document.getElementById('password_email').disabled = true
        document.getElementById('puerto_email').disabled = true
        document.getElementById('color_correo').disabled = true
        document.getElementById('email_soporte').disabled = true
        document.getElementById('req_bolsa_laboral').disabled = true
        document.getElementById('req_admision').disabled = true

        document.getElementById('btn_cancelar').style.display = 'none'
        document.getElementById('btn_guardar').style.display = 'none'
        document.getElementById('btn_editar').style.display = ''
      };
      function activar_controles() {
        document.getElementById('pagina').disabled = false
        document.getElementById('dominio_sistema').disabled = false
        document.getElementById('favicon').disabled = false
        document.getElementById('logo').disabled = false
        document.getElementById('titulo').disabled = false
        document.getElementById('ruc').disabled = false
        document.getElementById('region').disabled = false
        document.getElementById('pie_pagina').disabled = false
        document.getElementById('host_email').disabled = false
        document.getElementById('email_email').disabled = false
        document.getElementById('password_email').disabled = false
        document.getElementById('puerto_email').disabled = false
        document.getElementById('color_correo').disabled = false
        document.getElementById('email_soporte').disabled = false
        document.getElementById('req_bolsa_laboral').disabled = false
        document.getElementById('req_admision').disabled = false

        document.getElementById('btn_cancelar').removeAttribute('style')
        document.getElementById('btn_guardar').removeAttribute('style')
        document.getElementById('btn_editar').style.display = 'none'
      };
      function cancelar() {
        document.getElementById('myform').reset();
      }
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }