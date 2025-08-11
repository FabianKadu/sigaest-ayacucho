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

  $id_proc_adm = $_GET['id'];

  $busc_proc_adm = buscarProcesoAdmisionPorId($conexion,$id_proc_adm);
  $res_b_proc_adm = mysqli_fetch_array($busc_proc_adm);

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
      .dataTables_filter{
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
                        <li class="active"><a data-toggle="tab" href="#tab1">Resultados</a></li>
                        <li><a data-toggle="tab" href="#tab2">Importar Resultados</a></li>
                    </ul>
                    <!-- Contenido de los Tabs -->
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                          <h4 align="center"><b>Resultados del Proceso de Admisión - <?php echo $res_b_proc_adm['Periodo'] ?></b></h4>
                          <div class=""><b>Filtrar Por Programa: </b></div>
                          <div class="form-group col-lg-4 col-sm-6 col-xs-12">
                              <select id="filtro" class="form-control">
                                  <option value="" selected >TODOS</option>
                                  <?php 
                                    $carreras = buscarCarreras($conexion);
                                    while($carrera = mysqli_fetch_array($carreras)){
                                    ?>
                                  <option value="<?php echo $carrera['nombre'] ?>"><?php echo $carrera['nombre'] ?></option>
                                  <?php } ?>
                              </select>
                          </div>
                          <section class="">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <!-- <th>Identificador</th> -->
                                  <th>Dni</th>
                                  <th>Apellido Paterno</th>
                                  <th>Apellido Materno</th>
                                  <th>Nombres</th>
                                  <th>Puntaje</th>
                                  <th>Orden de Merito</th>
                                  <th>Programa De Estudio</th>
                                  <th>Condición</th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php 
                                  $ejec_busc_Postulantes = obtenerDatosPostulantePorProcesoAdmision($conexion,$id_proc_adm);  
                                  while ($res_busc_postulantes=mysqli_fetch_array($ejec_busc_Postulantes)){
                          
                                ?>
                                <tr>
                                  <!-- <td><?php echo $res_busc_postulantes['Id']; ?></td> -->
                                  <td><?php echo $res_busc_postulantes['Dni']; ?></td>
                                  <td><?php echo $res_busc_postulantes['Apellido_Paterno']; ?></td>
                                  <td><?php echo $res_busc_postulantes['Apellido_Materno']; ?></td>
                                  <td><?php echo $res_busc_postulantes['Nombres']; ?></td>
                                  <td align="center"><?php echo $res_busc_postulantes['Puntaje']; ?></td>
                                  <td align="center"><?php echo $res_busc_postulantes['Orden_Merito']; ?></td>
                                  <td><?php echo $res_busc_postulantes['nombre']; ?></td>
                                  <td align="center" class="<?php echo $res_busc_postulantes['Condicion'] === '1' ? 'text-success' : 'text-danger'; ?>">
                                      <b><?php echo $res_busc_postulantes['Condicion'] === "1" ? 'ALCANZÓ VACANTE' : 'NO ALCANZÓ VACANTE'; ?></b>
                                  </td>
                                </tr>  
                                <?php
                                  };
                                ?>

                              </tbody>
                            </table>
                          </section>
                        </div>

                        <div id="tab2" class="tab-pane fade">
                        <h4><b>Formato en Excel</b></h4>
                          <a class="blue" href="../admision/utils/FormatoResultadoExamen.xlsx" download><i class="fa fa-file-excel-o"></i> Descargar Archivo</a>
                          <br>
                          <h4><b>Importante!!</b></h4>
                          <p>Subir el formato de excel con las información completa. Subir separado por programa.
                          </p>
                          <br>
                          <section class="">
                            <form action="../composer/importar_resultados_admision.php" method="post" enctype="multipart/form-data">
                              <input type="hidden" name="id_proceso" value="<?php echo $id_proc_adm; ?>">
                            <div class="col-md-5 col-sm-5">
                              <label class="control-label ">Programas de Estudio *: </label>
                                <div class="">
                                    <select class="form-control" id="programa" name="programa" value="" required="required">
                                        <option value="" disabled selected>Seleccionar</option>
                                        <?php 
                                          $carreras = buscarCarreras($conexion);
                                          while($carrera = mysqli_fetch_array($carreras)){
                                          ?>
                                        <option value="<?php echo $carrera['id'] ?>"><?php echo $carrera['nombre'] ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-7 col-sm-7">
                              <h4><b>Subir Documento</b></h4>
                                <label for="estudaintes">Seleccionar el archivo excel:</label>
                                <input class="form-control" type="file" name="resultados" id="resultados" required="required" accept=".xlsx">
                                <br>
                                <input class="btn btn-success" type="submit" value="Importar">
                            </div>
                            </form>
                          </section>
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

    <script>
    $(document).ready(function() {
      var tabla = $('#example').DataTable({
        "language":{
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
        "order": [ 5, "asc" ],
        "searching": true,
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
