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
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

  $proceso = $_GET["id"];

  $res_postulantes =  buscarTodosPostulantesPorProceso($conexion, $proceso);
  $res_postulantes_aptos = buscarTodosPostulantesPorProcesoAptos($conexion, $proceso);
  $res_postulantes_no_aptos = buscarTodosPostulantesPorProcesoNoAptos($conexion, $proceso);
  $res_postulantes_subsanados = buscarTodosPostulantesPorProcesoSubsanados($conexion, $proceso);

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
	  
    <title>Estudiantes <?php include ("../include/header_title.php"); ?></title>
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
    <link href="../Gentella/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

    <style>
      .ui-pnotify.dark {
          opacity: 0;
          display: none;
      }
    </style>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
          include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <a href="procesos_admision.php" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab1">Postulantes Inscritos</a></li>
                        <li><a data-toggle="tab" href="#tab2" class="green">Postulantes aptos</a></li>
                        <li><a data-toggle="tab" href="#tab3" class="red">Postulantes Observados</a></li>
                        <li><a data-toggle="tab" href="#tab4" class="green">Postulantes Subsanados</a></li>
                    </ul>
                    <!-- Contenido de los Tabs -->
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                        <br />

                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Apellidos</th>
                                    <th>Nombres</th>
                                    <th>DNI</th>
                                    <th>Fecha Inscripción</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php           
                        $contador = 0; 
                        while ($postulantes=mysqli_fetch_array($res_postulantes)){
                        $contador++;
                        ?>
                                <tr>
                                    <td>
                                        <?php echo $contador; ?>
                                    </td>
                                    <td>
                                        <?php echo $postulantes['Apellido_Paterno'] . " ". $postulantes['Apellido_Materno']; ?>
                                    </td>
                                    <td>
                                        <?php echo $postulantes['Nombres']; ?>
                                    </td>
                                    <td>
                                        <?php echo $postulantes['Dni']; ?>
                                    </td>
                                    <td>
                                        <?php echo $postulantes['Fecha']; ?>
                                    </td>
                                    <td>
                                          <a title="Detalle de Postulación" class="btn btn-dark"
                                            href="detalle_postulacion.php?id=<?php echo $postulantes['Id']; ?>"><i
                                                class="fa fa-cubes"></i></a>
                                    </td>
                                </tr>
                                <?php
                                };
                                ?>


                            </tbody>
                        </table>
                        </div>
                        <div id="tab2" class="tab-pane fade">
                          <br />
                          <a class="btn btn-info" href="imprimir_excel_relacion_postulantes.php?id=<?php echo $proceso; ?>"><i class="fa fa-file-excel-o"></i>  Descargar Excel</a>
                          <br>
                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                  <tr>
                                      <th>N°</th>
                                      <th>DNI</th>
                                      <th>Apellidos</th>
                                      <th>Nombres</th>
                                      <th>Fecha Aceptada</th>
                                      <th>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php           
                          $contador = 0; 
                          while ($postulantes=mysqli_fetch_array($res_postulantes_aptos)){
                          $contador++;
                          ?>
                                  <tr>
                                      <td>
                                          <?php echo $contador; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Dni']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Apellido_Paterno'] . " ". $postulantes['Apellido_Materno']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Nombres']; ?>
                                      </td>
                                      
                                      <td>
                                          <?php echo $postulantes['Fecha']; ?>
                                      </td>
                                      <td>
                                            <a title="Detalle de Postulación" class="btn btn-dark"
                                              href="detalle_postulacion.php?id=<?php echo $postulantes['Id']; ?>"><i
                                                  class="fa fa-cubes"></i></a>
                                      </td>
                                  </tr>
                                  <?php
                                  };
                                  ?>


                              </tbody>
                          </table>
                        </div>
                        <div id="tab3" class="tab-pane fade">
                        <br />

                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                  <tr>
                                      <th>N°</th>
                                      <th>DNI</th>
                                      <th>Apellidos</th>
                                      <th>Nombres</th>
                                      <th>Fecha Observada</th>
                                      <th>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php           
                          $contador = 0; 
                          while ($postulantes=mysqli_fetch_array($res_postulantes_no_aptos)){
                          $contador++;
                          ?>
                                  <tr>
                                      <td>
                                          <?php echo $contador; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Dni']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Apellido_Paterno'] . " ". $postulantes['Apellido_Materno']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Nombres']; ?>
                                      </td>
                                      
                                      <td>
                                          <?php echo $postulantes['Fecha']; ?>
                                      </td>
                                      <td>
                                            <a title="Detalle de Postulación" class="btn btn-dark"
                                              href="detalle_postulacion.php?id=<?php echo $postulantes['Id']; ?>"><i
                                                  class="fa fa-cubes"></i></a>
                                      </td>
                                  </tr>
                                  <?php
                                  };
                                  ?>


                              </tbody>
                          </table>
                        </div>

                        <div id="tab4" class="tab-pane fade">
                        <br />

                          <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                  <tr>
                                      <th>N°</th>
                                      <th>DNI</th>
                                      <th>Apellidos</th>
                                      <th>Nombres</th>
                                      <th>Fecha Subsanada</th>
                                      <th>Acciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  <?php           
                          $contador = 0; 
                          while ($postulantes=mysqli_fetch_array($res_postulantes_subsanados)){
                          $contador++;
                          ?>
                                  <tr>
                                      <td>
                                          <?php echo $contador; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Dni']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Apellido_Paterno'] . " ". $postulantes['Apellido_Materno']; ?>
                                      </td>
                                      <td>
                                          <?php echo $postulantes['Nombres']; ?>
                                      </td>
                                      
                                      <td>
                                          <?php echo $postulantes['Fecha']; ?>
                                      </td>
                                      <td>
                                            <a title="Detalle de Postulación" class="btn btn-dark"
                                              href="detalle_postulacion.php?id=<?php echo $postulantes['Id']; ?>"><i
                                                  class="fa fa-cubes"></i></a>
                                      </td>
                                  </tr>
                                  <?php
                                  };
                                  ?>


                              </tbody>
                          </table>
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
        include ("../include/footer.php"); 
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

    <script src="../Gentella/vendors/pnotify/dist/pnotify.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.nonblock.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}
