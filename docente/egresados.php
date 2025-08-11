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

    <title>Estudiantes <?php include("../include/header_title.php"); ?></title>
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
                    <h2 align="center">Egresados</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <div class="col-lg-4">
                      <div><b>Filtrar Por Programa de Estudios: </b></div>
                      <div class="form-group ">
                        <select id="filtro_programa" class="form-control">
                          <option value="">TODOS</option>
                          <?php
                          $ejec_busc_carr = buscarCarreras($conexion);
                          while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                            $id_carr = $res__busc_carr['id'];
                            $carr = $res__busc_carr['nombre'];
                            $plan = $res__busc_carr['plan_estudio'];
                            ?>
                            <option value="<?php echo $carr." (".$plan.")";
                            ?>"><?php echo $carr." (".$plan.")"; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <br />
                    <table id="tabla-egresados" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>DNI</th>
                          <th>Apellidos y Nombres</th>
                          <th>Correo</th>
                          <th>Celular</th>
                          <th>Programa</th>
                          <th <?php if ($mostrar_menu) { ?>
                            width="23%" <?php } ?> width="14%">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $ejec_busc_est = buscarEgresado($conexion);
                        $cantidad = 1;
                        while ($res_busc_est = mysqli_fetch_array($ejec_busc_est)) {
                        ?>
                          <tr>
                            <td><?php echo $cantidad++; ?></td>
                            <td><?php echo $res_busc_est['dni']; ?></td>
                            <td><?php echo $res_busc_est['apellidos_nombres']; ?></td>
                            <td><?php echo $res_busc_est['correo']; ?></td>
                            <td><?php echo $res_busc_est['telefono']; ?></td>
                            <?php
                            $id_p_e = $res_busc_est['id_programa_estudios'];
                            $ejec_busc_p_e = buscarCarrerasById($conexion, $id_p_e);
                            $res_busc_p_e = mysqli_fetch_array($ejec_busc_p_e);
                            ?>
                            <td><?php echo $res_busc_p_e['nombre']. " (".$res_busc_p_e['plan_estudio'].")"; ?></td>
                            <td>
                              <a class="btn btn-success" href="editar_estudiante.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Editar" data-placement="bottom"><i
                                  class="fa fa-pencil-square-o"></i></a>
                              <a class="btn btn-info" href="informacion_academica.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Información Académica" data-placement="bottom"><i
                                  class="fa fa-list-alt"></i></a>
                              <a class="btn btn-dark" href="seguimiento_egresado.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Seguimiento Egresado" data-placement="bottom"><i
                                  class="fa fa-mortar-board"></i></a>
                              <a class="btn btn-warning" href="../bolsa_laboral/documentos_egresado.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Documentos Egresado" data-placement="bottom"><i
                                  class="fa fa-folder-open"></i></a>
                              <?php if ($mostrar_menu) { ?>
                                <!-- <a class="btn btn-primary" href="../bolsa_laboral/documentos_egresado.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Documentos Egresado" data-placement="bottom"><i
                                  class="fa fa-folder-open"></i></a> -->
                                <button class="btn btn-primary" data-toggle="modal" data-target=".edit_password<?php echo $res_busc_est['id']; ?>"><a data-toggle="tooltip" data-original-title="Cambiar contraseña" data-placement="bottom"><i class="fa fa-key" style="color: white"></i></a></button>
                                <button class="btn btn-danger" data-toggle="modal" data-target=".delete_user<?php echo $res_busc_est['id']; ?>"><a data-toggle="tooltip" data-original-title="Eliminar" data-placement="bottom"><i class="fa fa-trash" style="color: white"></i></a></button>
                              <?php } ?>
                            </td>

                          </tr>
                        <?php
                          include('include/acciones_password.php');
                          include('include/acciones_eliminar_estudiante.php');
                        };
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
        $('#tabla-egresados').DataTable({
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
}
