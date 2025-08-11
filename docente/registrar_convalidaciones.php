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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
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
                <h2 align="center">Registrar Convalidación</h2>
                <div class="clearfix"></div>
                <br>
              </div>
              <form role="form" id="myform" action="operaciones/registrar_convalidacion.php"
                class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                <div class="col-md-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <br />

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">DNI: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input class="form-control" type="number" name="dni_est" id="dni_est">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12"></label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <button type="button" class="btn btn-success" onclick="recargarest();">Buscar</button>

                          <br>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Alumno: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="hidden" id="id_est" name="id_est">
                          <input type="hidden" id="id_pe" name="id_pe">
                          <input type="hidden" id="id_sem" name="id_sem">
                          <input class="form-control" type="text" name="estudiante" id="estudiante" readonly>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Programa: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="carrera_m" name="carrera_m" value="" required="required">
                            <option></option>
                            <!-- datos a traer segun los datos del estudiante -->
                          </select>
                          <br>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Periodo Lectivo: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="periodo_lec" name="periodo_lec" required="required">
                            <option value="">Seleccione</option>
                            <?php
                            $periodos = buscarPeriodoAcademico($conexion);
                            while ($periodo = mysqli_fetch_array($periodos)) {
                              echo "<option value='{$periodo['id']}'>{$periodo['nombre']}</option>";
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>


                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Resolución: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" id="resolucion" name="resolucion" required="required">
                          <br>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Documento: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="file" name="documento" class="form-control" id="documento" required="required"
                            data-multiple-caption="{count} archivos seleccionados" multiple accept=".pdf, .doc" />
                          <br>
                        </div>
                      </div>

                    </div>
                  </div>
                </div>

                <div class="col-md-6 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <div class="form-group">
                        <label class="control-label">Tipo de Convalidación : </label>
                        <div class="col-md-12 col-sm-9 col-xs-12">
                          <select class="form-control" name="tipo" id="tipo" required="required"
                            onchange="toggleFields()">
                            <option value="">Seleccione</option>
                            <option value="Traslado Interno">Traslado Interno</option>
                            <option value="Traslado Externo">Traslado Externo</option>
                            <option value="Por Unidad Didactica">Por Unidad Didactica</option>
                          </select>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label">Semestre: </label>
                        <div class="col-md-12 col-sm-9 col-xs-12">
                          <select class="form-control" id="semestre" name="semestre" value="" required="required">
                            <option value="">Seleccione</option>
                            <?php
                            $eje_sem = buscarSemestre($conexion);
                            while ($res_sem = mysqli_fetch_array($eje_sem)) {
                              ?>
                              <option value="<?php echo $res_sem['id']; ?>"><?php echo $res_sem['descripcion']; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>

                      <div class="form-group">
                        <label class="control-label">Unidad Didactica a convalidar: </label>
                        <div class="col-md-12 col-sm-9 col-xs-12">
                          <select class="form-control" id="U_D_convalidar" name="U_D_convalidar" required="required">
                            <option value="">Seleccione</option>
                            <!-- Opciones a cargar dinámicamente -->
                          </select>
                        </div>
                      </div>

                    </div>

                    <div class="form-group" id="programa_estudios_origen_group">
                      <label class="control-label">Programa de Estudios - origen: </label>
                      <div class="col-md-12 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" id="Programa_estudios_origen"
                          name="Programa_estudios_origen" required="required">
                      </div>
                    </div>
                    <div class="form-group" id="unidad_didactica_origen_group">
                      <label class="control-label">Unidad Didactica - origen: </label>
                      <div class="col-md-12 col-sm-9 col-xs-12">
                        <input type="text" class="form-control" id="Unidad_didactica_origen"
                          name="Unidad_didactica_origen" required="required">
                      </div>
                    </div>

                    <div class="form-group" id="programa_estudios_origen_select_group" style="display: none;">
                      <label class="control-label">Programa de Estudios - origen: </label>
                      <div class="col-md-12 col-sm-9 col-xs-12">
                        <select class="form-control" name="Programa_estudios_origen_select"
                          id="Programa_estudios_origen_select" onchange="loadUnidadesDidacticas()">
                          <option value="">Seleccione</option>
                          <?php
                          $programas = obtenerTodoProgramaEstudios($conexion);
                          while ($programa = mysqli_fetch_array($programas)) {
                            echo "<option value='{$programa['nombre']}' data-id='{$programa['id']}'>{$programa['nombre']}</option>";
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="form-group" id="unidad_didactica_origen_select_group" style="display: none;">
                      <label class="control-label">Unidad Didactica - origen: </label>
                      <div class="col-md-12 col-sm-9 col-xs-12">
                        <select class="form-control" name="Unidad_didactica_origen_select"
                          id="Unidad_didactica_origen_select">
                          <option value="">Seleccione</option>
                          <!-- Opciones a cargar dinámicamente -->
                        </select>
                      </div>
                    </div>

                    <div class="form-group">
                      <label class="control-label">Calificación: </label>
                      <div class="col-md-12 col-sm-9 col-xs-12">
                        <input type="number" class="form-control" id="calificacion" name="calificacion"
                          required="required" step="0.01" min="0" max="20">
                      </div>
                    </div>

                  </div>
                </div>
            </div>
            <div align="center">
              <button type="reset" class="btn btn-danger">Cancelar</button>
              <button type="submit" class="btn btn-primary">Guardar</button>
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
        request.onreadystatechange = function () {
          // Revision si fue completada la peticion y si fue exitosa
          if (this.readyState === 4 && this.status === 200) {
            // Ingresando la respuesta obtenida del PHP
            document.getElementById("id_est").value = this.response.id_est;
            document.getElementById("estudiante").value = this.response.nombre;
            document.getElementById("id_pe").value = this.response.pe;
            cargarpe();
            listarUnidadesDidacticas(this.response.pe);
          }
        };
        // Recogiendo la data del HTML
        var myForm = document.getElementById("myform");
        var formData = new FormData(myForm);
        // Enviando la data al PHP
        request.send(formData);
      }

      function listarUnidadesDidacticas(idProgramaEstudios) {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_unidades_didacticas_with_Id.php",
          data: { id_programa_estudios: idProgramaEstudios },
          success: function (response) {
            $('#U_D_convalidar').html(response);
          }
        });
      }
    </script>
    <script type="text/javascript">
      function listar_uds() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_unidades_didacticas.php",
          data: "id_sem=" + $('#semestre').val(),
          success: function (r) {
            $('#U_D_convalidar').html(r);
          }
        });
      }
    </script>
    <script type="text/javascript">
      function cargarpe() {
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_pe.php",
          data: "id=" + $('#id_pe').val(),
          success: function (r) {
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
          success: function (r) {
            $('#modulo').html(r);
          }
        });
      }
    </script>


    <script type="text/javascript">
      function toggleFields() {
        var tipo = document.getElementById("tipo").value;
        var programaEstudiosOrigenGroup = document.getElementById("programa_estudios_origen_group");
        var unidadDidacticaOrigenGroup = document.getElementById("unidad_didactica_origen_group");
        var programaEstudiosOrigenSelectGroup = document.getElementById("programa_estudios_origen_select_group");
        var unidadDidacticaOrigenSelectGroup = document.getElementById("unidad_didactica_origen_select_group");

        if (tipo === "Traslado Externo") {
          programaEstudiosOrigenGroup.style.display = "block";
          unidadDidacticaOrigenGroup.style.display = "block";
          programaEstudiosOrigenSelectGroup.style.display = "none";
          unidadDidacticaOrigenSelectGroup.style.display = "none";

          document.getElementById("Programa_estudios_origen").required = true;
          document.getElementById("Unidad_didactica_origen").required = true;
          document.getElementById("Programa_estudios_origen_select").required = false;
          document.getElementById("Unidad_didactica_origen_select").required = false;
        } else {
          programaEstudiosOrigenGroup.style.display = "none";
          unidadDidacticaOrigenGroup.style.display = "none";
          programaEstudiosOrigenSelectGroup.style.display = "block";
          unidadDidacticaOrigenSelectGroup.style.display = "block";

          document.getElementById("Programa_estudios_origen").required = false;
          document.getElementById("Unidad_didactica_origen").required = false;
          document.getElementById("Programa_estudios_origen_select").required = true;
          document.getElementById("Unidad_didactica_origen_select").required = true;
        }
      }

      function loadUnidadesDidacticas() {
        var selectElement = document.getElementById("Programa_estudios_origen_select");
        var idPrograma = selectElement.options[selectElement.selectedIndex].getAttribute('data-id');
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_unidades_didacticas.php",
          data: { id_programa_estudios: idPrograma },
          success: function (response) {
            $('#Unidad_didactica_origen_select').html(response);
          }
        });
      }

      // Call toggleFields on page load to set the initial state
      window.onload = toggleFields;
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
  <?php
}
