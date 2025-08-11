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

  //DOCENTE O SECRETARIO
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

  $id_proceso_admision = $_GET['id'];

  //PROCESO DE ADMISIÓN
  $res_proceso_admision = buscarProcesoAdmisionPorId($conexion, $id_proceso_admision);
  $proceso_admision = mysqli_fetch_array($res_proceso_admision);
  
  //MODALIDADES
  $res_modalidades = buscarModalidadPorPeriodo($conexion, $proceso_admision['Periodo']);
  $modalidades_exonerados = mysqli_num_rows($res_modalidades);
  $modalidades_exonerados = $modalidades_exonerados - 1;

  //PROGRAMAS DE ESTUDIO
  $res_programas = buscarCarreras($conexion);

  

  //TITULO DE PAGINA
  $titulo_pagina = $proceso_admision['Periodo'];

  $estado_proceso = determinarPeriodosActivos($conexion, $proceso_admision['Periodo']);
  $estado_proceso = mysqli_fetch_array($estado_proceso);
  $editable = False;
  if($estado_proceso['cantidad_procesos'] == 0){
    $editable = True;
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

      <title>Cuadro de Vacantes <?php include("../include/header_title.php"); ?></title>
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
      <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

      <style>
        p.verticalll {
          /* idéntico a rotateZ(45deg); */

          writing-mode: vertical-lr;
          transform: rotate(180deg);
        }

        .nota_input {
          width: 3em;
        }
      </style>

    </head>

    <body class="nav-md">
      <div class="container body">
        <div class="main_container">
          <!--menu-->
          <?php
            include("include/menu_secretaria.php");
          ?>

          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">

              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                      <div class="">
                        <h2 align="center"><b>Cuadro de Vacantes - <?php echo $titulo_pagina; ?></b></h2>
                       <br>
                      </div>
                      <div class="x_content">
                          <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th rowspan="2">
                                    <center>PROGRAMAS DE ESTUDIO</center>
                                  </th>
                                  <th rowspan="2">
                                    <center>TOTAL VACANTES </center>
                                  </th>
                                  <th colspan="<?php echo $modalidades_exonerados; ?>">
                                    <center>VACANTES POR EXONERADOS</center>
                                  </th>
                                  <th rowspan="2">
                                    <center>
                                      VACANTES POR EXAMEN ORDINARIO
                                    </center>
                                  </th>
                                  <?php if($editable){ ?>
                                  <th rowspan="2">
                                    <center>ACCIONES</center>
                                  </th>
                                <?php } ?>
                                </tr>
                                <tr>
                                  <?php
                                  while ($modalidad = mysqli_fetch_array($res_modalidades)) {
                                    if($modalidad['Descripcion'] == "Ordinario"){
                                      continue;
                                    }
                                  ?>
                                    <th>
                                      <center><?php echo $modalidad['Descripcion']; ?>
                                      </center>
                                    </th>
                                  <?php
                                  }
                                  ?>
                                  
                                </tr>
                              </thead>
                              <tbody>
                              <?php while ($programa = mysqli_fetch_array($res_programas)) { ?>
                                  <tr>
                                      <th><?php echo $programa['nombre']; ?></th>
                                      <form action="operaciones/registrar_vacante.php" method="POST">
                                          <input type="hidden" name="id_programa" value="<?php echo $programa['id']; ?>">
                                          <input type="hidden" name="periodo" value="<?php echo $proceso_admision['Periodo']; ?>">
                                          <?php 
                                          //VACANTE DEFAULT
                                          $total_vacantes_programa = buscarTotalVacantesPorPeriodoPrograma($conexion, $proceso_admision['Periodo'], $programa['id']);
                                          $vacantes_programa = mysqli_fetch_array($total_vacantes_programa);
                                          $vacante_meta_default = $vacantes_programa['total_vacante_programa'];
                                          ?>
                                          <th><center><span style="display:none;"><?php echo $vacante_meta_default; ?></span> <input <?php if(!$editable) echo "readonly"; ?> type="number" name="total_vacante" class="total_vacante" value="<?php echo $vacante_meta_default; ?>" min="0" max="99"></center></th>
                                          <?php
                                          //CUADRO DE VACANTES
                                          $res_cuadro_vacante = buscarCuadroVacantesPorPeriodoPrograma($conexion, $proceso_admision['Periodo'], $programa['id']);
                                          while($cuadro_vacantes = mysqli_fetch_array($res_cuadro_vacante)){ ?>
                                              <input type="hidden" name="id_cvs[]" class="vacantes_modalidad"  value="<?php echo $cuadro_vacantes['Id']; ?>">
                                              <th><center>
                                              <span style="display:none;"><?php echo $cuadro_vacantes['Vacantes']; ?>  </span>
                                              <input <?php if(!$editable) echo "readonly"; ?> type="number" name="vacantes_modalidad[]" class="vacantes_modalidad"  value="<?php echo $cuadro_vacantes['Vacantes']; ?>" min="0" max="50"
                                              <?php if($cuadro_vacantes['Descripcion'] == "Ordinario") echo "readonly"; ?>>
                                             </center></th>
                                          <?php } 
                                          
                                          if($editable){?>
                                          <th><button type="submit" title="Guardar Cambios" class="btn btn-success"><i class="fa fa-save"></i></button></th>
                                          <?php } ?>
                                      </form>
                                  </tr>
                              <?php } ?>
                              </tbody>
                            </table>
                          </div>
                          <div align="center">
                            <br>
                            <br>
                            <a href="procesos_admision.php" class="btn btn-danger">Regresar</a>
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
    $(document).ready(function() {
      var tabla = $('#example').DataTable({
        "searching": false,
        "paging": false,
        "info": false,
        "dom": 'Bfrtip',
        "buttons": [
          {
                extend: 'print',
                text: 'Imprimir Cuadro',
                title: 'Cuadro de vacantes' 
            }
        ]
      });

      // Capturar el cambio en el select y realizar la búsqueda
      $('#filtro').on('change', function() {
          var valorSeleccionado = $(this).val(); // Obtener el valor seleccionado del select
          tabla.search(valorSeleccionado).draw(); // Realizar la búsqueda en DataTables y dibujar la tabla
      });

      } );


    </script>

      <?php mysqli_close($conexion); ?>
    </body>

    </html>
<?php
  }
?>