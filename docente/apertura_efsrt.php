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
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EFSRT<?php include("../include/header_title.php"); ?></title>
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
    <script
      src="https://code.jquery.com/jquery-3.6.0.js"
      integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
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



            <div class="row">
              <div class="">
                <br><br>
                <h2 align="center">Apertura de EFSRT</h2>
                <div class="clearfix"></div>
                <br>
              </div>
              <form role="form" id="myform" action="operaciones/registrar_efsrt.php" class="form-horizontal form-label-left input_mask" method="POST">
                <div class="col-md-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <br />

                      <div class="form-group">
                        <label class="control-label">DNI: </label>
                        <br>
                        <div class="col-md-10 col-sm-12 col-xs-12">
                          <input class="form-control" type="number" name="dni_est" id="dni_est">
                        </div>
                        <div class="col-md-2 col-sm-12 col-xs-12">
                          <button type="button" class="btn btn-success" onclick="recargarest();">Buscar</button>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Alumno: </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <input type="hidden" id="id_est" name="id_est">
                          <input type="hidden" id="id_pe" name="id_pe">
                          <input class="form-control" type="text" name="estudiante" id="estudiante" readonly>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Programa: </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <select class="form-control" id="carrera_m" name="carrera_m" value="" required="required">
                            <option></option>
                            <!-- datos a traer segun los datos del estudiante -->
                          </select>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Módulo: </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <select class="form-control" id="modulo" name="modulo" value="" required="required">
                            <!--las opciones se cargan con ajax y javascript  dependiendo de la carrera elegida,verificar en la parte final-->
                          </select>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <div class="form-group">
                        <label class="control-label">Lugar de experiencia : </label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" name="lugar" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Cargo de la persona que aceptará la carta: </label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" name="cargo" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Nombres y apellidos de responsable: </label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" name="responsable" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Fecha de inicio: </label>
                        <div class="col-md-12">
                          <input type="date" class="form-control" name="inicio" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Tutor: </label>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <select class="form-control" id="docente-teoria" name="tutor" value=""
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
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="form-group text-right">
                    <a href="efsrt_aperturado.php" class="btn btn-danger">Salir</a>
                    <button type="submit" class="btn btn-success">Registrar e imprimir carta</button>
                  </div>
                </div>
              </form>
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
    <!--script para obtener los datos dependiendo del dni-->

    <script type="text/javascript">
      function recargarest() {
        // funcion para traer datos del estudiante
        // Creando el objeto para hacer el request
        var request = new XMLHttpRequest();
        request.responseType = 'json';
        // Objeto PHP que consultaremos
        request.open("POST", "operaciones/obtener_estudiante.php");
        // Definiendo el listener
        request.onreadystatechange = function() {
          // Revision si fue completada la peticion y si fue exitosa
          if (this.readyState === 4 && this.status === 200) {
            // Ingresando la respuesta obtenida del PHP
            document.getElementById("id_est").value = this.response.id_est;
            document.getElementById("estudiante").value = this.response.nombre;
            document.getElementById("id_pe").value = this.response.pe;
            cargarpe();

          }
        };
        // Recogiendo la data del HTML
        var myForm = document.getElementById("myform");
        var formData = new FormData(myForm);
        // Enviando la data al PHP
        request.send(formData);
      }
    </script>
    <script type="text/javascript">
      function cargarpe() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_pe.php",
          data: "id=" + $('#id_pe').val(),
          success: function(r) {
            $('#carrera_m').html(r);
            recargarlista();
          }
        });
      }
    </script>
    <script type="text/javascript">
      function recargarlista() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_modulos.php",
          data: "id_carrera=" + $('#carrera_m').val(),
          success: function(r) {
            $('#modulo').html(r);
          }
        });
      }
    </script>


    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
