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
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  
  function generarCodigo($prefijo, $longitud, $numero) {
    // Crear el formato del número con ceros a la izquierda
    $numeroFormateado = sprintf('%0' . $longitud . 'd', $numero);

    // Combinar el prefijo con el número formateado
    $codigoCompleto = $prefijo. "-" . $numeroFormateado;

    return $codigoCompleto;
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
	  
    <title>Caja <?php include ("../include/header_title.php"); ?></title>
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
    
    <style>
      .comenzar{
        background-color: #337AB7;
      }
      .proceso{
        background-color: #26B99A;
      }
      .finalizar{
        background-color: #F0AD4E;
      }
      .Finalizado{
        background-color: #D9534F;
      }
    </style>

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
                    <h2 align="center">Convocatorias Laborales</h2>
                  </div>
                  <div class="x_content">
                  <a href="nueva_convocatoria.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Nueva Convocatoria</a>
                  <br><br>
                    <div class="">
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
                              ?>
                              <option value="<?php echo $carr;
                              ?>"><?php echo $carr; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                    
                      <div class="col-lg-3">
                        <div><b>Filtrar Por Estado: </b></div>
                        <div class="form-group ">
                          <select id="filtro_estado" class="form-control">
                            <option value="">TODOS</option>
                            <option value="Por comenzar">POR COMENZAR</option>
                            <option value="En proceso">EN PROCESO</option>
                            <option value="Finalizado">FINALIZADO</option>
                          </select>
                        </div>
                      </div>
                      <br><br><br><br>
                      <table id="empresas" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>N°</th>
                            <th>Razón Social</th>
                            <th>Título</th>
                            <th>Dirigido a Programas</th>
                            <th>Finaliza</th>
                            <th>Estado</th>
                            <th width="20%">Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $res = buscarTotalOfertas($conexion);
                            $cantidad = 0;
                            while ($ofertas=mysqli_fetch_array($res)){
                              $cantidad++;
                          ?>
                          <tr>
                            <td><?php echo $cantidad; ?></td>
                            <td><?php echo $ofertas['empresa']; ?></td>
                            <td><?php echo $ofertas['titulo']; ?></td>
                            
                            <?php 
                            if($ofertas['propietario'] == 0){
                              $programas = buscarProgramasByIdOferta($conexion, $ofertas['id']);
                              $nombre_programas = [];
                              while ($programa = $programas->fetch_assoc()) {
                                $nombres_programas[] = $programa['nombre'];
                              }

                              $programas_string = implode(', ', $nombres_programas);

                              echo '<td>'.$programas_string.'</td>';
                              $programas_string = "";
                            }else{
                              $programas_ies = buscarProgramasByIdOfertaIestp($conexion, $ofertas['id']);
                              $nombre_programas_iestp = [];
                              while ($programa_ies = $programas_ies->fetch_assoc()) {
                                $nombre_programas_iestp[] = $programa_ies['nombre'];
                              }

                              $nombres_programas_string = implode(', ', $nombre_programas_iestp);
                              echo '<td>'.$nombres_programas_string.'</td>';
                              $nombres_programas_string = "";
                            }
                            ?>
                            <td><?php echo convertirFormatoFecha($ofertas['fecha_fin'])?></td>
                            <td>
                                <span class="badge <?php echo determinarEstado($ofertas['fecha_inicio'], $ofertas['fecha_fin'])?>"><?php echo determinarEstado($ofertas['fecha_inicio'], $ofertas['fecha_fin']) ?></span>
                            </td>
                            
                            <td>

                            <?php if($ofertas['propietario'] == 0){
                              echo '
                              <a href="detalle_convocatoria_empresa.php?id='. $ofertas['id'] . '" class="btn btn-success" data-toggle="tooltip" data-original-title="Ver Detalles" data-placement="bottom"><i class="fa fa-eye"></i></a>
                              <button class="btn btn-danger" data-toggle="modal" title="Archivar" data-placement="bottom" data-target=".anular'. $ofertas['id'].'"><i class="fa fa-archive"></i></button>
                              </td> ';

                            }else{ 
                              echo '
                              <a href="detalle_convocatoria.php?id='. $ofertas['id'] . '" class="btn btn-success" data-toggle="tooltip" data-original-title="Ver Detalles" data-placement="bottom"><i class="fa fa-eye"></i></a>
                              <a href="convocatoria_documento.php?id='. $ofertas['id'] . '" class="btn btn-primary" data-toggle="tooltip" data-original-title="Ver Documentos" data-placement="bottom"><i class="fa fa-file"></i></a>
                              <a href="editar_convocatoria.php?id='. $ofertas['id'] . '" class="btn btn-warning" data-toggle="tooltip" data-original-title="Editar" data-placement="bottom"><i class="fa fa-edit"></i></a>
                              <button class="btn btn-danger" data-toggle="modal" title="Archivar" data-placement="bottom" data-target=".archivar'. $ofertas['id'].'"><i class="fa fa-archive"></i></button>
                              </td> '; }?>
                          </tr>  

                          <div class="modal fade anular<?php echo $ofertas['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel" align="center">Archivar Convocatoria</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!--INICIO CONTENIDO DE MODAL-->
                                        <div class="x_panel">

                                            <div class="" align="center">
                                                <h2></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <b>Tenga en consideración que se recomienda archivar las convocatorias que ya hayan finalizado.
                                                  ¿Desea archivar la convocatoria "<?php echo $ofertas['titulo']; ?>"?</b>
                                                <br /><br>
                                                <form role="form" action="operaciones/archivar_convocatoria_empresa.php" class="form-horizontal form-label-left input_mask" method="POST">
                                                  <input type="hidden" name="id" value="<?php echo $ofertas['id']; ?>">
                                                  <div align="center">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                                                      <input type="submit" class="btn btn-primary" value="Archivar">
                                                  </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade archivar<?php echo $ofertas['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel" align="center">Archivar Convocatoria</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!--INICIO CONTENIDO DE MODAL-->
                                        <div class="x_panel">

                                            <div class="" align="center">
                                                <h2></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <b>Tenga en consideración que se recomienda archivar las convocatorias que ya hayan finalizado.
                                                  ¿Desea archivar la convocatoria "<?php echo $ofertas['titulo'];?>"?</b>
                                                <br /><br>
                                                <form role="form" action="operaciones/archivar_convocatoria.php" class="form-horizontal form-label-left input_mask" method="POST">
                                                  <input type="hidden" name="id" value="<?php echo $ofertas['id']; ?>">
                                                  <div align="center">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                                                      <input type="submit" class="btn btn-primary" value="Archivar">
                                                  </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

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
    <script>
    $(document).ready(function() {
    $('#empresas').DataTable({
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
      "order": [ 4, 'desc']
    });

    } );
    </script>

  <script>
      $(document).ready(function () {
        var table = $('#empresas').DataTable();

        // Custom filter for Programa de Estudios
        $('#filtro_programa').on('change', function () {
          var filtro = $(this).val();
          table.column(3).search(filtro).draw();
        }    
      );
      });
    </script>

    <script>
      $(document).ready(function () {
        var table = $('#empresas').DataTable();

        // Custom filter for Programa de Estudios
        $('#filtro_administrado').on('change', function () {
          var filtro = $(this).val();
          table.column(4).search(filtro).draw();
        }    
      );
      });
    </script>

    <script>
      $(document).ready(function () {
        var table = $('#empresas').DataTable();
        // Filtro por estado
        $('#filtro_estado').on('change', function () {
          var filtro = $(this).val();
          table.column(5).search(filtro).draw();
        }
                
      );
      });
    </script>

     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php }