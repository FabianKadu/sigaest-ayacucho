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


  $id_periodo_select = $_SESSION['periodo'];
  $b_perido_act = buscarPeriodoAcadById($conexion, $id_periodo_select);
  $r_b_per_act = mysqli_fetch_array($b_perido_act);
  $fecha_actual = strtotime(date("d-m-Y"));
  $fecha_fin_per = strtotime($r_b_per_act['fecha_fin']);
  if ($fecha_fin_per >= $fecha_actual) {
    $agregar = 1;
  } else {
    $agregar = 0;
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

    <title>unidades didácticas<?php include("../include/header_title.php"); ?></title>
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

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        include("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">


          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="">
                  <h2 align="center">Solicitudes de Programación Unidades Didácticas</h2>

                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />

                  <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                      <tr>
                        <th>Nro</th>
                        <th>Unidad Didactica</th>
                        <th>Programa de Estudios</th>
                        <th>Semestre</th>
                        <th>Docente - Teoría</th>
                        <th>Docente - Práctica</th>
                        <th width="10%">Estado</th>
                        <th width="10%">Acciones</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                      $var_consulta = "";
                      $ejec_busc_prog = buscarSolicitudProgramacionEspecial($conexion, $var_consulta);
                      $contador = 0;
                      while ($res_busc_prog = mysqli_fetch_array($ejec_busc_prog)) {
                        $contador++;
                        $id_ud = $res_busc_prog['id_unidad_didactica'];
                        $b_ud = buscarUdById($conexion, $id_ud);
                        $res_b_ud = mysqli_fetch_array($b_ud);
                        ?>
                        <tr>
                          <td><?php echo $contador; ?></td>
                          <td><?php echo $res_b_ud['descripcion']; ?></td>
                          <?php
                          $id_carrera = $res_b_ud['id_programa_estudio'];
                          $ejec_busc_carrera = buscarCarrerasById($conexion, $id_carrera);
                          $res_busc_carrera = mysqli_fetch_array($ejec_busc_carrera);
                          ?>
                          <td><?php echo $res_busc_carrera['nombre']; ?></td>
                          <?php
                          $id_semestre = $res_b_ud['id_semestre'];
                          $ejec_busc_semestre = buscarSemestreById($conexion, $id_semestre);
                          $res_busc_semestre = mysqli_fetch_array($ejec_busc_semestre);
                          ?>
                          <td><?php echo $res_busc_semestre['descripcion']; ?></td>
                          <?php
                          $ejec_busc_docente = buscarDocenteById($conexion, $res_busc_prog['id_docente']);
                          $res_busc_docente = mysqli_fetch_array($ejec_busc_docente);
                          $ejec_busc_docente_prac = buscarDocenteById($conexion, $res_busc_prog['id_docente_practica']);
                          $res_busc_docente_practica = mysqli_fetch_array($ejec_busc_docente_prac);
                          ?>
                          <td><?php echo $res_busc_docente['apellidos_nombres']; ?></td>
                          <td>
                            <?php echo isset($res_busc_docente_practica['apellidos_nombres']) ? $res_busc_docente_practica['apellidos_nombres'] : $res_busc_docente['apellidos_nombres']; ?>
                          </td>
                          <?php switch ($res_busc_prog['estado']) {
                            case "Pendiente":
                              $clase_estado = "warning";
                              break;
                            case "Aprobado":
                              $clase_estado = "success";
                              break;
                            case "Rechazado":
                              $clase_estado = "danger";
                              break;
                          } ?>
                          <td align="center">
                            <p class="btn btn-<?php echo $clase_estado; ?> " style="pointer-events: none;">
                              <?php echo $res_busc_prog['estado']; ?>
                            </p>
                          </td>
                          <td align="center">
                            <?php if ($res_busc_prog['estado'] == 'Pendiente') { ?>
                              <button class="btn btn-success" data-toggle="modal" data-target="#aprobar_solicitud_<?php echo
                                $res_busc_prog['id']; ?>">Aceptar</button>
                              <button class="btn btn-danger" data-toggle="modal" data-target="#rechazar_solicitud_<?php echo
                                $res_busc_prog['id']; ?>">Rechazar</button>
                            <?php } else { ?>
                              <p></p>
                            <?php } ?>
                          </td>
                        </tr>
                        <?php
                        include('include/aprobar_solicitud.php');
                        include('include/rechazar_solicitud.php');
                      }
                      ;
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
      $(document).ready(function () {
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
    <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
    <script type="text/javascript">
      $(document).ready(function () {
        recargarlista();
        recargar_ud();
        $('#carrera_m').change(function () {
          recargarlista();
        });
        $('#modulo').change(function () {
          recargar_ud();
        });
        $('#semestre').change(function () {
          recargar_ud();
        });

      })
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
      function recargar_ud() {
        var carr = $('#modulo').val();
        var sem = $('#semestre').val();
        $.ajax({
          type: "POST",
          url: "operaciones/obtener_ud_sem.php",
          data: {
            id_modulo: carr,
            id_semestre: sem
          },
          success: function (r) {
            $('#unidad_didactica').html(r);
          }
        });
      }
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
