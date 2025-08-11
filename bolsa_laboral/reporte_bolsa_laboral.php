<?php
include("../include/conexion.php");
include("../empresa/include/consultas.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_administrador.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {

    $fecha = $_GET['fecha'];
    $tipo = $_GET['tipo'];
    // Separar la fecha en año y mes
    list($year, $mes) = explode('-', $fecha);
  
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

    //FUNCIÓN PARA FORMATEAR FECHAS
    function obtenerFechas($rango) {
      // Dividir la cadena en dos partes usando el separador " - "
      list($inicio, $fin) = explode(' - ', $rango);
  
      // Convertir el formato de las fechas de DD/MM/YYYY a DD-MM-YYYY
      $fecha_inicio = DateTime::createFromFormat('d/m/Y', trim($inicio))->format('Y-m-d');
      $fecha_fin = DateTime::createFromFormat('d/m/Y', trim($fin))->format('Y-m-d');
  
      return array('fecha_inicio' => $fecha_inicio, 'fecha_fin' => $fecha_fin);
  }

  $fechas = obtenerFechas($fecha);
  $fecha_inicio = $fechas['fecha_inicio'];
  $fecha_fin = $fechas['fecha_fin'];

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
	  
    <title>Bolsa Laboral - Reporte - <?php echo $tipo; ?></title>
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
    
    <!-- Incluye los estilos de DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php 
          $res_docente = buscarDocenteById($conexion,$id_docente_sesion);
          $docente = mysqli_fetch_array($res_docente);
          if($docente['id_cargo'] == 7){
            include ("include/menu_administrador.php"); 
          }
          include("../docente/include/menu_secretaria.php"); ?>

          <!-- page content -->
          <div class="right_col" role="main">
            <div class="">
           
              <div class="clearfix"></div>
              <div class="row">
                <div class="col-md-12 col-sm-12 col-xs-12">
                  <div class="x_panel">
                    <div class="x_content">
                      <div class="">
                        <h2 align="center"><?php echo $tipo;?></h2>
                          <?php 
                            echo "<h2 align='center'>" . $fecha ."</h2>";
                          ?>
                      </div>
                      <a class="btn btn-danger" href="convocatoria_reportes.php">Regresar</a>
                      <div class="">
                        <div id="tab1" class="">
                          <br>
                            <?php 
                              if( $tipo == 'Total de ofertas laborales'){ ?>
                                  <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                      <tr>
                                          <th>N°</th>
                                          <th>Razón Social</th>
                                          <th>Título</th>
                                          <th>Lugar de trabajo</th>
                                          <th>Modalidad</th>
                                          <th>Turno</th>
                                          <th>Vacantes</th>
                                          <th>Fecha de Inicio</th>
                                          <th>Fecha Fin</th>
                                      </tr>
                                    </thead>
                                    <tbody>
                                      <?php 
                                        $cont = 0;
                                        $busc_conc_egr = buscarTotalOfertasReporte($conexion, $fecha_inicio, $fecha_fin); 
                                        while ($ofertas=mysqli_fetch_array($busc_conc_egr)){
                                      ?>
                                      <tr>
                                          <td><?php $cont = $cont + 1;
                                          echo $cont; ?></td>
                                          <td><?php echo $ofertas['empresa']; ?></td>
                                          <td><?php echo $ofertas['titulo']; ?></td>
                                          <td><?php echo $ofertas['ubicacion']; ?></td>
                                          <td><?php echo $ofertas['modalidad']; ?></td>
                                          <td><?php echo $ofertas['turno']; ?></td>
                                          <td><?php echo $ofertas['vacantes']; ?></td>
                                          <td><?php echo convertirFormatoFecha($ofertas['fecha_inicio']); ?></td>
                                          <td><?php echo convertirFormatoFecha($ofertas['fecha_fin']); ?></td>
                                      </tr>
                                      <?php } ?>
                                    </tbody>
                                  </table> 
                                    <?php } 
                                    if( $tipo == 'Conteo de postulantes por empresas'){ ?>
                                    <table id="empresa" class="table table-striped table-bordered" style="width:100%">
                                      <thead>
                                        <tr>
                                          <th>N°</th>
                                          <th>Estado</th>
                                          <th>Razón Social</th>
                                          <th>Ubicación</th>
                                          <th>Total de Postulantes</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                          $cont = 0;
                                          $busc_conc_egr_iestp = buscarEmpresasConvocatoriasInstitutoReporte($conexion, $fecha_inicio, $fecha_fin); 
                                          while ($empresa=mysqli_fetch_array($busc_conc_egr_iestp)){
                                        ?>
                                        <tr>
                                          <td><?php $cont = $cont + 1;
                                          echo $cont; ?></td>
                                          <td>Activo</td>
                                          <td><?php echo $empresa['empresa']; ?></td>
                                          <td><?php echo $empresa['ubicacion']; ?></td>
                                          <td><?php echo $empresa['postulantes']; ?></td>
                                        </tr>
                                        <?php } 
                                        $busc_conc_egr = buscarEmpresasReporte($conexion, $fecha_inicio, $fecha_fin); 
                                          while ($empresa=mysqli_fetch_array($busc_conc_egr)){
                                        ?>
                                        <tr>
                                          <td><?php $cont = $cont + 1;
                                          echo $cont; ?></td>
                                          <td><?php echo $empresa['estado']; ?></td>
                                          <td><?php echo $empresa['empresa']; ?></td>
                                          <td><?php echo $empresa['ubicacion']; ?></td>
                                          <td><?php echo $empresa['postulantes']; ?></td>
                                        </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table> 
                                    <?php } ?>
                                    <?php 
                                    if( $tipo == 'Conteo de postulantes por programas'){ ?>
                                    <table id="programa" class="table table-striped table-bordered" style="width:100%">
                                      <thead>
                                        <tr>
                                          <th>N°</th>
                                          <th>Código</th>
                                          <th>Programa de estudios</th>
                                          <th>Tipo</th>
                                          <th>Plan de estudios</th>
                                          <th>Total de Postulantes</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                          $cont = 0;
                                          $busc_conc_egr = buscarProgramasReporte($conexion, $fecha_inicio, $fecha_fin); 
                                          while ($programa=mysqli_fetch_array($busc_conc_egr)){
                                        ?>
                                        <tr>
                                          <td><?php $cont = $cont + 1;
                                            echo $cont; ?></td>
                                          <td><?php echo $programa['codigo']; ?></td>
                                          <td><?php echo $programa['nombre']; ?></td>
                                          <td><?php echo $programa['tipo']; ?></td>
                                          <td><?php echo $programa['plan_estudio']; ?></td>
                                          <td><?php echo $programa['postulantes']; ?></td>
                                        </tr>
                                        <?php } ?>
                                      </tbody>
                                    </table> 
                      
                                  <?php }
                                  if($tipo == 'Reporte detallado de postulantes'){ ?>
                                    
                                    <table id="programa" class="table table-striped table-bordered" style="width:100%">
                                      <thead>
                                        <tr>
                                          <th>N°</th>
                                          <th>DNI</th>
                                          <th>Apellidos y nombres</th>
                                          <th>Programa de estudios</th>
                                          <th>Plan de estudios</th>
                                          <th>Razón Social</th>
                                          <th>Ubicación de la empresa</th>
                                          <th>Título de la convocatoria</th>
                                          <th>Fecha de postulación</th>
                                        </tr>
                                      </thead>
                                      <tbody>
                                        <?php 
                                          $cont = 0;
                                          $busc_conc_egr = buscarPostulantesDetallado($conexion, $fecha_inicio, $fecha_fin); 
                                          while ($programa=mysqli_fetch_array($busc_conc_egr)){
                                        ?>
                                        <tr>
                                          <td><?php $cont = $cont + 1;
                                            echo $cont; ?></td>
                                          <td><?php echo $programa['dni']; ?></td>
                                          <td><?php echo $programa['apellidos_nombres']; ?></td>
                                          <td><?php echo $programa['nombre']; ?></td>
                                          <td><?php echo $programa['plan_estudio']; ?></td>
                                          <td><?php echo $programa['razon_social']; ?></td>
                                          <td><?php echo $programa['ubicacion']; ?></td>
                                          <td><?php echo $programa['titulo']; ?></td>
                                          <td><?php echo convertirFormatoFecha($programa['fecha_registro']); ?></td>
                                        </tr>
                                        <?php } 
                                        $busc_detalle = buscarPostulantesDetalladoInstituto($conexion, $fecha_inicio, $fecha_fin); 
                                        while ($programa=mysqli_fetch_array($busc_detalle)){
                                      ?>
                                      <tr>
                                        <td><?php $cont = $cont + 1;
                                          echo $cont; ?></td>
                                        <td><?php echo $programa['dni']; ?></td>
                                        <td><?php echo $programa['apellidos_nombres']; ?></td>
                                        <td><?php echo $programa['nombre']; ?></td>
                                        <td><?php echo $programa['plan_estudio']; ?></td>
                                        <td><?php echo $programa['empresa']; ?></td>
                                        <td><?php echo $programa['ubicacion']; ?></td>
                                        <td><?php echo $programa['titulo']; ?></td>
                                        <td><?php echo convertirFormatoFecha($programa['fecha_registro']); ?></td>
                                      </tr>
                                      <?php } ?>
                                      </tbody>
                                    </table> 

                                   <?php }?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        <?php
        include ("../include/footer.php"); 
        ?>
        <!-- /footer content -->
      </div>
    </div>

<!-- jQuery -->
    <!-- Datatables -->
    <!-- jQuery -->
    <script src="../Gentella/vendors/jquery/dist/jquery.min.js"></script>

    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>

    <!-- Bootstrap -->
    <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- FastClick -->
    <script src="../Gentella/vendors/fastclick/lib/fastclick.js"></script>
    <!-- NProgress -->
    <script src="../Gentella/vendors/nprogress/nprogress.js"></script>
    <!-- iCheck -->
    <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>

    <script>
       $('#example').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        info: false,
        dom: 'Bfrtip', // Aquí incluyes la configuración para los botones de exportación
        buttons: [
          {
                extend: 'excelHtml5',
                text: 'Exportar a Excel' // Cambia el texto del botón de exportar a Excel
            },
            // {
            //     extend: 'pdfHtml5',
            //     text: 'Exportar a PDF' // Cambia el texto del botón de exportar a PDF
            // },
            {
                extend: 'print',
                text: 'Imprimir' // Cambia el texto del botón de imprimir
            }
        ]
      });
    </script>
     <script>
       $('#empresa').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        info: false,
        dom: 'Bfrtip', // Aquí incluyes la configuración para los botones de exportación
        order: [1, 'asc'],
        buttons: [
          {
                extend: 'excelHtml5',
                text: 'Exportar a Excel' // Cambia el texto del botón de exportar a Excel
            },
            // {
            //     extend: 'pdfHtml5',
            //     text: 'Exportar a PDF' // Cambia el texto del botón de exportar a PDF
            // },
            {
                extend: 'print',
                text: 'Imprimir' // Cambia el texto del botón de imprimir
            }
        ]
      });
    </script>
     <script>
       $('#programa').DataTable({
        paging: false, // Desactiva la paginación
        searching: false, // Desactiva el buscador
        info: false,
        dom: 'Bfrtip', // Aquí incluyes la configuración para los botones de exportación
        order: [5, 'desc'],
        buttons: [
          {
                extend: 'excelHtml5',
                text: 'Exportar a Excel' // Cambia el texto del botón de exportar a Excel
            },
            // {
            //     extend: 'pdfHtml5',
            //     text: 'Exportar a PDF' // Cambia el texto del botón de exportar a PDF
            // },
            {
                extend: 'print',
                text: 'Imprimir' // Cambia el texto del botón de imprimir
            }
        ]
      });
    </script>
    
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php } ?>