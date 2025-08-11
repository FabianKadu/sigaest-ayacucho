<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../caja/consultas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $inicio = $_GET['fechaInicio'];
  $fin = $_GET['fechaFin'];
  $tipo = $_GET['tipo'];

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

    <title>Caja <?php include("../include/header_title.php"); ?></title>
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
                  <div class="x_content">
                    <div class="">
                      <h2 align="center">Reporte de <?php echo $tipo ?></h2>
                      <?php
                      if ($inicio == $fin) {
                        echo "<h2 align='center'>Reporte del día " . date('d/m/Y', strtotime($inicio)) . "</h2>";
                      } else {
                        echo "<h2 align='center'>Desde " . date('d/m/Y', strtotime($inicio)) . ' hasta ' . date('d/m/Y', strtotime($fin)) . "</h2>";
                      }
                      ?>
                    </div>
                    <a class="btn btn-danger" href="reportes_movimientos.php">Regresar</a>
                    <form action="imprimir_reporte_caja.php" method="post">
                      <div>
                        <input type="hidden" value="<?php echo $tipo ?>" name="tipo">
                        <input type="hidden" value="<?php echo $inicio ?>" name="inicio">
                        <input type="hidden" value="<?php echo $fin ?>" name="fin">
                        <input type="submit" class="btn btn-primary" value="Descargar PDF">
                      </div>
                    </form>
                    <form action="imprimir_excel_caja.php" method="post">
                      <div>
                        <input type="hidden" value="<?php echo $tipo ?>" name="tipo">
                        <input type="hidden" value="<?php echo $inicio ?>" name="inicio">
                        <input type="hidden" value="<?php echo $fin ?>" name="fin">
                        <input type="submit" class="btn btn-success" value="Descargar EXCEL">
                      </div>
                    </form>
                    <div class="tab-content">
                      <div id="tab1" class="tab-pane fade in active">
                        <br>
                        <?php
                        $total_ingreso = 0;
                        if ($tipo == 'ingresos') { ?>
                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th>COMPROBANTE </th>
                                <th>SERIE-NÚMERO</th>
                                <th>DNI / RUC</th>
                                <th>NOMBRE CLIENTE</th>
                                <th>MONTO TOTAL</th>
                                <th>METODO DE PAGO</th>
                                <th>FECHA REGISTRO</th>
                                <th>USUARIO CAJA</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $busc_ingr = buscarIngresosByFechas($conexion, $inicio, $fin);
                              while ($ingresos = mysqli_fetch_array($busc_ingr)) {
                                ?>
                                <tr>
                                  <td><?php echo $ingresos['tipo_comprobante']; ?></td>
                                  <td><?php echo $ingresos['codigo']; ?></td>
                                  <td><?php echo $ingresos['dni']; ?></td>
                                  <td><?php echo $ingresos['usuario']; ?></td>
                                  <td><?php echo $ingresos['monto_total']; ?></td>
                                  <td><?php echo $ingresos['metodo_pago']; ?></td>
                                  <td><?php echo $ingresos['fecha_pago']; ?></td>
                                  <td><?php echo $ingresos['responsable']; ?></td>
                                </tr>
                                <?php
                                $total_ingreso += $ingresos['monto_total'];
                              }
                              ;
                              ?>

                            </tbody>
                          </table>
                          <br>
                          <div>
                            <h4>El total de ingresos acumulados en este periodo es en total
                              <?php echo number_format($total_ingreso, 2, '.', '') . " nuevos soles." ?>
                            </h4>
                          </div>
                        <?php } ?>
                        <?php
                        $total_egreso = 0;
                        if ($tipo == 'egresos') { ?>
                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th>COMPROBANTE </th>
                                <th>SERIE-NÚMERO</th>
                                <th>DNI / RUC</th>
                                <th>NOMBRE PROVEEDOR</th>
                                <th>CONCEPTO EGRESO</th>
                                <th>MONTO TOTAL</th>
                                <th>FECHA EGRESO</th>
                                <th>FECHA REGISTRO</th>
                                <th>USUARIO CAJA</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $busc_ingr = buscarEgresosByFechas($conexion, $inicio, $fin);
                              while ($egresos = mysqli_fetch_array($busc_ingr)) {
                                ?>
                                <tr>
                                  <td><?php echo $egresos['tipo_comprobante']; ?></td>
                                  <td><?php echo $egresos['comprobante']; ?></td>
                                  <td><?php echo $egresos['ruc']; ?></td>
                                  <td><?php echo $egresos['empresa']; ?></td>
                                  <td><?php echo $egresos['concepto']; ?></td>
                                  <td><?php echo $egresos['monto_total']; ?></td>
                                  <td><?php echo $egresos['fecha_pago']; ?></td>
                                  <td><?php echo $egresos['fecha_registro']; ?></td>
                                  <td><?php echo $egresos['responsable']; ?></td>
                                </tr>
                                <?php
                                $total_egreso += $egresos['monto_total'];
                              }
                              ;
                              ?>

                            </tbody>
                          </table>
                          <br>
                          <div>
                            <h4>El total de egresos acumulados en este periodo es en total
                              <?php echo number_format($total_egreso, 2, '.', '') . " nuevos soles." ?>
                            </h4>
                          </div>
                        <?php } ?>
                        <?php

                        $total_ingreso_encrypted = buscarTotalIngresoByFechas($conexion, $inicio, $fin);
                        $total_ingreso = 0;
                        foreach ($total_ingreso_encrypted as $monto_encrypted) {
                          $total_ingreso += floatval(decryptText($monto_encrypted));
                        }
                        $total_egreso_encrypted = buscarTotalEgresoByFechas($conexion, $inicio, $fin);
                        $total_egreso = 0;
                        foreach ($total_egreso_encrypted as $monto_encrypted) {
                          $total_egreso += floatval(decryptText($monto_encrypted));
                        }
                        $saldo_inicial = buscarSaldoIncialByFechaInicio($conexion, $inicio);
                        $diferencia = $total_ingreso - $total_egreso;

                        $ingreso_data = [];
                        $egreso_data = [];
                        $ingreso_target = [];
                        $egreso_target = [];

                        if ($tipo == 'Flujo-Caja') { ?>
                          <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                              <tr>
                                <th>TIPO</th>
                                <th>CONCEPTO</th>
                                <th>TOTAL ACUMULADO</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $busc_ingr = buscarDetalleFlujoCaja($conexion, $inicio, $fin);
                              foreach ($busc_ingr as $flujo_caja) {
                                ?>
                                <tr>
                                  <td><?php echo $flujo_caja['tipo']; ?></td>
                                  <td><?php echo $flujo_caja['concepto']; ?></td>
                                  <td><?php echo $flujo_caja['suma_subtotales']; ?></td>
                                </tr>
                              <?php }
                              ; ?>
                            </tbody>
                          </table>
                          <?php

                          $busc_ingr = buscarDetalleFlujoCaja($conexion, $inicio, $fin);
                          foreach ($busc_ingr as $flujo_caja) {
                            if ($flujo_caja['tipo'] == 'INGRESO') {
                              array_push($ingreso_data, $flujo_caja['suma_subtotales']);
                              array_push($ingreso_target, $flujo_caja['concepto']);
                            } else {
                              array_push($egreso_data, $flujo_caja['suma_subtotales']);
                              array_push($egreso_target, $flujo_caja['concepto']);
                            }
                          }
                          ;

                          $datosJSONEgreso = json_encode($egreso_data);
                          $etiquetasJSONEgreso = json_encode($egreso_target);
                          $datosJSONIngreso = json_encode($ingreso_data);
                          $etiquetasJSONIngreso = json_encode($ingreso_target);
                          ?>
                          <br>

                          <div class="x_content" style="color: black;">
                            <div class="row">
                              <div class="col-md-6">
                                <div class="x_panel" align="center">
                                  <h4>INGRESOS</h4>
                                  <div style="width: 80%; margin: auto;">
                                    <canvas id="miDonutIngresos"></canvas>
                                  </div>

                                  <!-- Script para el gráfico de donut de egresos -->
                                  <script>
                                    // Configuración del gráfico de donut para egresos
                                    var datosIngresos = <?php echo $datosJSONIngreso; ?>;
                                    var etiquetasIngresos = <?php echo $etiquetasJSONIngreso; ?>;

                                    var ctxIngresos = document.getElementById('miDonutIngresos').getContext('2d');
                                    var miDonutIngresos = new Chart(ctxIngresos, {
                                      type: 'pie',
                                      data: {
                                        labels: etiquetasIngresos,
                                        datasets: [{
                                          data: datosIngresos,
                                          backgroundColor: [
                                            'rgba(255, 99, 132, 0.7)',
                                            'rgba(54, 162, 235, 0.7)',
                                            'rgba(255, 206, 86, 0.7)'
                                          ],
                                          borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)'
                                          ],
                                          borderWidth: 1
                                        }]
                                      },
                                      options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        title: {
                                          display: false,
                                          text: 'Gráfico de Donut de Ingresos',
                                          fontSize: 18
                                        },
                                        legend: {
                                          position: 'chartArea' // Cambiado de 'bottom' a 'left'
                                        }
                                      }
                                    });
                                  </script>
                                </div>
                                <div class="x_panel" align="center">
                                  <h4>EGRESOS</h4>
                                  <div style="width: 80%; margin: auto;">
                                    <canvas id="miDonutEgreso"></canvas>
                                  </div>

                                  <!-- Script para el gráfico de donut de ingresos -->
                                  <script>
                                    // Configuración del gráfico de donut para ingresos
                                    var datosEgresos = <?php echo $datosJSONEgreso; ?>;
                                    var etiquetasEgresos = <?php echo $etiquetasJSONEgreso; ?>;

                                    var ctxEgresos = document.getElementById('miDonutEgreso').getContext('2d');
                                    var miDonutEgresos = new Chart(ctxEgresos, {
                                      type: 'pie',
                                      data: {
                                        labels: etiquetasEgresos,
                                        datasets: [{
                                          data: datosEgresos,
                                          backgroundColor: [
                                            'rgba(255, 99, 132, 0.7)',
                                            'rgba(54, 162, 235, 0.7)',
                                            'rgba(255, 206, 86, 0.7)'
                                          ],
                                          borderColor: [
                                            'rgba(255, 99, 132, 1)',
                                            'rgba(54, 162, 235, 1)',
                                            'rgba(255, 206, 86, 1)'
                                          ],
                                          borderWidth: 1
                                        }]
                                      },
                                      options: {
                                        responsive: true,
                                        maintainAspectRatio: false,
                                        legend: {
                                          position: 'chartArea' // Cambiado de 'bottom' a 'left'
                                        },
                                      },
                                    });
                                  </script>
                                </div>
                              </div>
                              <div class="col-md-6">
                                <h4>RESUMEN GENERAL</h4>
                                <table class="table table-striped">
                                  <tbody>
                                    <tr>
                                      <td>SALDO INICIAL</td>
                                      <td><b><?php echo $saldo_inicial + 0 ?></b></td>
                                    </tr>
                                    <tr>
                                      <td>TOTAL INGRESO</td>
                                      <td><b><?php echo $total_ingreso ?></b></td>
                                    </tr>
                                    <tr>
                                      <td>TOTAL EGRESO</td>
                                      <td><b><?php echo $total_egreso ?></b></td>
                                    </tr>
                                    <tr>
                                      <td>UTILIDAD DEL PERIODO</td>
                                      <td><b><?php echo $diferencia ?></b></td>
                                    </tr>
                                    <tr>
                                      <td>SALDO FINAL</td>
                                      <td><b><?php echo $saldo_inicial + $diferencia ?></b></td>
                                    </tr>
                                  </tbody>
                                </table>
                              </div>
                            </div>
                          </div>

                        <?php } ?>
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
    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php } ?>