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
  $id_estudiante = $_GET['id'];
  $ejec_busc_est = buscarEstudianteById($conexion, $id_estudiante);
  $res_busc_est = mysqli_fetch_array($ejec_busc_est);

  $id_programa = $res_busc_est['id_programa_estudios'];
  $ejec_busc_prog = buscarCarrerasById($conexion, $id_programa);
  $res_busc_prog = mysqli_fetch_array($ejec_busc_prog);

  $ejec_busc_efrst = buscarEfsrtAprobadosByEstudiante($conexion, $id_estudiante);


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

    <title>EFSRT <?php include("../include/header_title.php"); ?></title>
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

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">EFSRT Aperturados</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <table id="" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>Módulo formativo</th>
                          <th>Tutor</th>
                          <th>Empresa</th>
                          <th>Resolución</th>
                          <th>Periodo</th>
                          <th>Calificación</th>
                          <th>Documentos</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $cantidad = 1;
                        while ($efsrt = mysqli_fetch_array($ejec_busc_efrst)) { ?>
                          <tr>
                            <td><?php echo $cantidad++ ?></td>

                            <td>
                              <?php
                              $id_modulo = $efsrt['id_modulo'];
                              $ejec_busc_mod = buscarModuloFormativoById($conexion, $id_modulo);
                              $res_busc_mod = mysqli_fetch_array($ejec_busc_mod);
                              echo $res_busc_mod['descripcion'];
                              ?>
                            </td>
                            <td>
                              <?php
                              $id_docente = $efsrt['id_docente'];
                              $ejec_busc_doc = buscarDocenteById($conexion, $id_docente);
                              $res_busc_doc = mysqli_fetch_array($ejec_busc_doc);
                              echo $res_busc_doc['apellidos_nombres'];
                              ?>
                            </td>
                            <td><?php echo $efsrt['lugar']; ?></td>
                            <td><?php echo $efsrt['resolucion']; ?></td>
                            <td><?php echo $efsrt['periodo_lectivo']; ?></td>
                            <td><?php echo decryptText($efsrt['calificacion']); ?></td>
                            <td>
                              <a class="btn btn-primary" href="<?php echo $efsrt['carta_presentacion']; ?>"
                                target="_blank">Carta</a>
                              <a class="btn btn-success" href="<?php echo $efsrt['informe']; ?>" target="_blank">Informe</a>
                            </td>
                            <td align="center">
                              <a class="btn btn-primary"
                                href="../documentos/efsrt/constancias/Constancia_EFSRT_<?php echo $res_busc_est['dni']; ?>.pdf"
                                target="_blank">Constancia</a>
                              <a class="btn btn-warning"
                                href="../documentos/efsrt/certificados/Certificado_Modular_<?php echo $res_busc_est['dni']; ?>.pdf"
                                target="_blank">Certificado Modular</a>
                            </td>
                          </tr>
                        <?php }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div>

            <!-- FIN MODAL REGISTRAR-->

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
      $(document).ready(function() {
        $('#tabla-efsrt').DataTable({
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
          },

        });

      });
    </script>
    <script>
      $(document).ready(function() {
        var table = $('#tabla-egresados').DataTable();

        $.fn.dataTable.ext.search.push(
          function(settings, data, dataIndex) {
            var programa = $('#filtro_programa').val().trim();
            var programaCell = data[5] || ''; // Índice de columna para Programa de Estudios


            if ((programa === '' || programaCell === programa)) {
              return true;
            }
            return false;
          }
        );
        $('#filtro_programa').on('change', function() {
          table.draw();
        });
      });
    </script>
    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
} ?>