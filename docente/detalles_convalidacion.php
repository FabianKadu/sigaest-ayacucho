<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;

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

    <title>Detalles Convalidaciones <?php include("../include/header_title.php"); ?></title>
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
                    <h2 align="center">Detalles de Convalidaciones</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">

                    <?php
                    $ejec_busc_est = buscarEstudianteById($conexion, $id);
                    $res_busc_est = mysqli_fetch_array($ejec_busc_est);
                    ?>

                    <br>
                    <div class="col-lg-2">
                      <div><b>DNI </b></div>
                      <div class="form-group ">
                        <input id="filtro_programa" class="form-control" value="<?php echo $res_busc_est['dni'] ?>"
                          readonly>
                      </div>
                    </div>

                    <div class="col-lg-5">
                      <div><b>Apellidos y Nombres </b></div>
                      <div class="form-group ">
                        <input id="filtro_programa" class="form-control"
                          value="<?php echo $res_busc_est['apellidos_nombres'] ?>" readonly>
                      </div>
                    </div>

                    <?php
                    $id_p_e = $res_busc_est['id_programa_estudios'];
                    $ejec_busc_p_e = buscarCarrerasById($conexion, $id_p_e);
                    $res_busc_p_e = mysqli_fetch_array($ejec_busc_p_e);
                    ?>

                    <div class="col-lg-5">
                      <div><b>Programa de Estudios </b></div>
                      <div class="form-group ">
                        <input id="filtro_programa" class="form-control" value="<?php echo $res_busc_p_e['nombre'] ?>"
                          readonly>
                      </div>
                    </div>

                    <br />

                    <table id="tabla-convalidaciones" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>Unidad Didactica a convalidar</th>
                          <th>Unidad didactica de origen</th>
                          <th>Resolución</th>
                          <th>Calificación</th>
                          <th>Detalle</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $ejec_busc_convs = buscarConvalidacionesPorEstudiante($conexion, $id);
                        $cantidad = 1;
                        while ($res_busc_convs = mysqli_fetch_array($ejec_busc_convs)) {
                        ?>
                          <tr>
                            <td><?php echo $cantidad++ ?></td>

                            <?php
                            $eje_busca_ud = buscarUdById($conexion, $res_busc_convs['id_unidad_didactica']);
                            $res_busca_ud = mysqli_fetch_array($eje_busca_ud)
                            ?>

                            <td><?php echo $res_busca_ud['descripcion']; ?></td>
                            <td><?php echo $res_busc_convs['unidad_didactica_origen']; ?></td>
                            <td><a
                                href="<?php echo "./documentos_convalidaciones/" . $res_busc_convs['archivo_resolucion']; ?>"
                                target="_blank"><?php echo $res_busc_convs['resolucion']; ?></a></td>
                            <td><?php echo decryptText($res_busc_convs['calificacion']); ?></td>
                            <td><?php echo $res_busc_convs['tipo']; ?></td>
                          </tr>
                        <?php
                        };
                        ?>

                      </tbody>
                    </table>
                    <div align="center">
                      <a href="convalidaciones.php" class="btn btn-danger">Regresar</a>
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
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>

    <script>
      $(document).ready(function() {
        var dni = "<?php echo $res_busc_est['dni']; ?>";
        var apellidosNombres = "<?php echo $res_busc_est['apellidos_nombres']; ?>";
        var title = "Acta de convalidaciones - " + dni + " - " + apellidosNombres;

        $('#tabla-convalidaciones').DataTable({
          paging: false, // Desactiva la paginación
          searching: false, // Desactiva el buscador
          info: false,
          dom: 'Bfrtip', // Aquí incluyes la configuración para los botones de exportación
          buttons: [{
              extend: 'excelHtml5',
              text: 'Exportar a Excel', // Cambia el texto del botón de exportar a Excel
              title: title,
              customize: function(xlsx) {
                var sheet = xlsx.xl.worksheets['sheet1.xml'];
                $('row c[r^="A1"]', sheet).attr('s', '42'); // Cambia el estilo de la celda A1
              }
            },
            // {
            //     extend: 'pdfHtml5',
            //     text: 'Exportar a PDF', // Cambia el texto del botón de exportar a PDF
            //     title: title,
            //     customize: function (doc) {
            //         doc.styles.title = {
            //             alignment: 'center',
            //             fontSize: 10
            //         };
            //     }
            // },
            {
              extend: 'print',
              text: 'Imprimir', // Cambia el texto del botón de imprimir
              title: '<h3 style="text-align:center;">' + title + '</h3>'
            }
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
          },
        });
      });
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
