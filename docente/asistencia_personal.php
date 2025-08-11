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

  function buscarAsistenciaAdministrativoHoyPorId($conexion, $id_docente)
  {
    $sql = "SELECT * FROM asistencia_administrativo 
            WHERE docente_id = '$id_docente' 
            AND DATE(fecha_asistencia) = CURDATE()
            ORDER BY fecha_asistencia DESC, hora_asistencia DESC";
    return mysqli_query($conexion, $sql);
  }

  function buscarAsistenciaConDatosPersonales($conexion)
  {
    $sql = "SELECT 
                d.apellidos_nombres,
                c.descripcion as cargo,
                a.fecha_asistencia,
                a.hora_asistencia,
                a.permiso,
                a.foto_url
            FROM asistencia_administrativo a
            INNER JOIN docente d ON a.docente_id = d.id
            INNER JOIN cargo c ON d.id_cargo = c.id
            WHERE DATE(a.fecha_asistencia) = CURDATE()
            ORDER BY a.fecha_asistencia DESC, a.hora_asistencia DESC";

    return mysqli_query($conexion, $sql);
  }

  function buscarAsistenciaConDatosPersonalesPorFecha($conexion, $fecha)
  {
    $sql = "SELECT 
                d.apellidos_nombres,
                c.descripcion as cargo,
                a.fecha_asistencia,
                a.hora_asistencia,
                a.permiso,
                a.foto_url
            FROM asistencia_administrativo a
            INNER JOIN docente d ON a.docente_id = d.id
            INNER JOIN cargo c ON d.id_cargo = c.id
            WHERE DATE(a.fecha_asistencia) = ?
            ORDER BY a.fecha_asistencia DESC, a.hora_asistencia DESC";

    $stmt = mysqli_prepare($conexion, $sql);
    mysqli_stmt_bind_param($stmt, "s", $fecha);
    mysqli_stmt_execute($stmt);
    return mysqli_stmt_get_result($stmt);
  }
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

    <title> Asistencia de Personal <?php include("../include/header_title.php"); ?></title>
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

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="">

                <center>
                  <h4><b>Asistencia de Personal</b></h4>
                </center>

                <center>
                  <form id="formBusqueda" method="GET">
                    <div class="form-group" style="max-width: 300px;">
                      <div class="input-group">
                        <input
                          type="date"
                          class="form-control"
                          id="fecha_asistencia"
                          name="fecha_asistencia"
                          value="<?php echo isset($_GET['fecha_asistencia']) ? $_GET['fecha_asistencia'] : date('Y-m-d'); ?>"
                          max="<?php echo date('Y-m-d'); ?>"
                          required>
                        <span class="input-group-btn">
                          <button class="btn btn-default" type="submit">
                            <i class="fa fa-search"></i>
                          </button>
                        </span>
                      </div>
                    </div>
                  </form>
                </center>

                <div class="clearfix"></div>
              </div>

              <br>

              <div class="x_content">
                <table id="tabla-administrativo" class="table table-striped table-bordered" style="width:100%">
                  <thead>
                    <tr>
                      <th>N°</th>
                      <th>Apellidos y Nombres</th>
                      <th>Cargo</th>
                      <th>Acciones</th>
                    </tr>
                  </thead>

                  <tbody>
                    <?php
                    $fecha_busqueda = isset($_GET['fecha_asistencia']) ? $_GET['fecha_asistencia'] : date('Y-m-d');
                    $es_hoy = $fecha_busqueda == date('Y-m-d');
                    $contr = 1;
                    $ejec_busc_doc = buscarDocenteOrdesByApellidosNombres($conexion);
                    while ($res_busc_doc = mysqli_fetch_array($ejec_busc_doc)) {
                      $id_docente = $res_busc_doc['id'];
                      $sql = "SELECT * FROM asistencia_administrativo 
            WHERE docente_id = ? AND DATE(fecha_asistencia) = ?";
                      $stmt = mysqli_prepare($conexion, $sql);
                      mysqli_stmt_bind_param($stmt, "is", $id_docente, $fecha_busqueda);
                      mysqli_stmt_execute($stmt);
                      $asistencia = mysqli_stmt_get_result($stmt);

                      if (mysqli_num_rows($asistencia) == 0) {
                    ?>
                        <tr>
                          <td><?php echo $contr++; ?></td>
                          <td><?php echo $res_busc_doc['apellidos_nombres']; ?></td>
                          <?php
                          $id_cargo = $res_busc_doc['id_cargo'];
                          $ejec_busc_carg = buscarCargoById($conexion, $id_cargo);
                          $res_busc_carg = mysqli_fetch_array($ejec_busc_carg);
                          ?>
                          <td><?php echo $res_busc_carg['descripcion']; ?></td>
                          <td>
                            <?php if ($es_hoy) { ?>
                              <button type="button"
                                class="btn btn-warning btn-permiso"
                                data-dni="<?php echo $res_busc_doc['dni']; ?>"
                                data-toggle="tooltip"
                                data-original-title="Marcar con permiso">
                                <i class="fa fa-exclamation"></i> Dar Permiso
                              </button>
                            <?php } else { ?>
                              <span class="label label-danger">NO SE REGISTRÓ</span>
                            <?php } ?>
                          </td>
                        </tr>
                    <?php
                      }
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <hr>

          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="x_title">
                  <h2>
                    <?php
                    $fecha_busqueda = isset($_GET['fecha_asistencia']) ? $_GET['fecha_asistencia'] : date('Y-m-d');
                    $es_hoy = $fecha_busqueda == date('Y-m-d');

                    if ($es_hoy) {
                      echo "Asistencias del día de hoy (" . date("d/m/Y", strtotime($fecha_busqueda)) . ")";
                    } else {
                      echo "Asistencias de la fecha " . date("d/m/Y", strtotime($fecha_busqueda));
                    }
                    ?>
                  </h2>

                  <button type="button" class="btn btn-success pull-right" onclick="location.href='operaciones/exportar_asistencia.php?fecha=<?php echo $fecha_busqueda; ?>'">
                    Descargar en Excel &nbsp;
                    <i class="fa fa-download"></i>
                  </button>
                  <div class="clearfix"></div>
                </div>

                <div class="x_content">
                  <table id="tabla-asistencia-dia" class="table table-striped table-bordered">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Apellidos y Nombres</th>
                        <th>Cargo</th>
                        <th>Fecha</th>
                        <th>Hora</th>
                        <th>Detalle</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                      $fecha_busqueda = isset($_GET['fecha_asistencia']) ? $_GET['fecha_asistencia'] : date('Y-m-d');
                      $contador = 1;
                      $ejec_busc_asistencia = buscarAsistenciaConDatosPersonalesPorFecha($conexion, $fecha_busqueda);
                      while ($res_busc_asistencia = mysqli_fetch_array($ejec_busc_asistencia)) {
                      ?>
                        <tr>
                          <td><?php echo $contador++; ?></td>
                          <td><?php echo $res_busc_asistencia['apellidos_nombres']; ?></td>
                          <td><?php echo $res_busc_asistencia['cargo']; ?></td>
                          <td><?php echo date("d/m/Y", strtotime($res_busc_asistencia['fecha_asistencia'])); ?></td>
                          <td><?php echo date("h:i:s A", strtotime($res_busc_asistencia['hora_asistencia'])); ?></td>
                          <td>
                            <?php if ($res_busc_asistencia['permiso'] == 1) { ?>
                              <span class="label label-warning">CON PERMISO</span>
                            <?php } else { ?>
                              <button type="button"
                                class="btn btn-info ver-captura"
                                data-toggle="modal"
                                data-target="#modalCaptura"
                                data-foto="../documentos/asistencia/<?php echo $res_busc_asistencia['foto_url']; ?>">
                                <i class="fa fa-camera"></i> Ver Captura
                              </button>
                            <?php } ?>
                          </td>
                        </tr>
                      <?php } ?>
                    </tbody>
                  </table>

                  <!-- Modal para mostrar la captura -->
                  <div class="modal fade" id="modalCaptura" tabindex="-1" role="dialog">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                          </button>
                          <h4 class="modal-title">Captura de Asistencia</h4>
                        </div>
                        <div class="modal-body">
                          <img src="" id="imagenCaptura" class="img-responsive" alt="Captura de asistencia" style="margin: 0 auto;">
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                        </div>
                      </div>
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

    <!-- Asistencia de Personal -->
    <script>
      $(document).ready(function() {
        $('#tabla-administrativo').DataTable({
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

      $(document).ready(function() {
        var table = $('#tabla-administrativo').DataTable();

        // Custom filter for Programa de Estudios
        $('#filtro').on('change', function() {
          var filtro = $(this).val();
          table.column(5).search(filtro).draw();
        });
      });

      $(document).ready(function() {
        var table = $('#tabla-administrativo').DataTable();
        // Filtro por estado
        $('#filtro_estado').on('change', function() {
            var filtro = $(this).val();
            table.column(6).search(filtro).draw();
          }

        );
      });
    </script>

    <!-- Asistencia del día -->
    <script>
      $(document).ready(function() {
        $('.ver-captura').click(function(e) {
          e.preventDefault();
          var rutaFoto = $(this).data('foto');

          // Verificar si la imagen existe antes de mostrarla
          var img = new Image();
          img.onload = function() {
            $('#imagenCaptura').attr('src', rutaFoto);
            $('#modalCaptura').modal('show');
          };
          img.onerror = function() {
            alert('La imagen no está disponible');
          };
          img.src = rutaFoto;
        });

        // Limpiar src de la imagen al cerrar el modal
        $('#modalCaptura').on('hidden.bs.modal', function() {
          $('#imagenCaptura').attr('src', '');
        });
      });

      $(document).ready(function() {
        $('.btn-permiso').click(function() {
          var dni = $(this).data('dni');

          // Confirmar antes de dar permiso
          if (confirm('¿Está seguro de registrar permiso para este docente?')) {
            $.ajax({
              url: 'operaciones/guardar_asistencia.php',
              method: 'POST',
              contentType: 'application/json',
              data: JSON.stringify({
                registro: {
                  dni: dni,
                  es_permiso: true
                }
              }),
              success: function(response) {
                if (response.success) {
                  alert(response.message);
                  location.reload(); // Recargar la página para actualizar la lista
                } else {
                  alert('Error: ' + response.message);
                }
              },
              error: function() {
                alert('Error al procesar la solicitud');
              }
            });
          }
        });
      });

      $(document).ready(function() {
        $('#tabla-asistencia-dia').DataTable({
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

      $(document).ready(function() {
        var table = $('#tabla-asistencia-dia').DataTable();

        // Custom filter for Programa de Estudios
        $('#filtro').on('change', function() {
          var filtro = $(this).val();
          table.column(5).search(filtro).draw();
        });
      });

      $(document).ready(function() {
        var table = $('#tabla-asistencia-dia').DataTable();
        // Filtro por estado
        $('#filtro_estado').on('change', function() {
            var filtro = $(this).val();
            table.column(6).search(filtro).draw();
          }

        );
      });
    </script>

    <script>
      $(document).ready(function() {
        $('#formBusqueda').on('submit', function(e) {
          const fechaSeleccionada = new Date($('#fecha_asistencia').val());
          const hoy = new Date();
          hoy.setHours(0, 0, 0, 0);

          if (fechaSeleccionada > hoy) {
            e.preventDefault();
            alert('No puede seleccionar fechas futuras');
            return false;
          }
        });
      });
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
