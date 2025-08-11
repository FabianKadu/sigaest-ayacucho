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
	  
    <title>Caja<?php include ("../include/header_title.php"); ?></title>
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
                  <div class="">
                    <h2 align="center">Empresas</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <a href="registrar_empresa.php" class="btn btn-primary"><i class="fa fa-plus-square"></i> Registrar Empresa</a> <br><br>
                    <table id="empresas" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Estado</th>
                          <th>Logo</th>
                          <th>Razón Social</th>
                          <th>RUC</th>
                          <th>Ubicación</th>
                          <th>Correo Electrónico</th>
                          <th>Celular/Teléfono</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $busc_conc_egr = buscarEmpresas($conexion); 
                          $cantidad = 1;
                          while ($empresa=mysqli_fetch_array($busc_conc_egr)){
                        ?>
                        <tr>
                          <td><?php echo $cantidad; ?></td>
                          <td><?php echo $empresa['estado']; ?></td>
                          <td>
                            <img height="45" src=" <?php echo '../empresa/'.$empresa['ruta_logo']; ?>" alt="">  
                          </td>
                          <td><?php echo $empresa['razon_social']; ?></td>
                          <td><?php echo $empresa['ruc']; ?></td>
                          <td><?php echo $empresa['ubicacion']; ?></td>
                          <td><?php echo $empresa['correo_institucional']; ?></td>
                          <td><?php echo $empresa['celular_telefono']; ?></td>
                          <td>
                            <?php echo '
                            <a href="editar_empresa.php?id='. $empresa['id'] . '" class="btn btn-warning" data-toggle="tooltip" data-original-title="Editar" data-placement="bottom"><i class="fa fa-edit"></i></a>';
                            if($empresa['estado'] == 'Activo'){
                              echo '<button class="btn btn-danger" data-toggle="modal" title="Deshabilitar" data-placement="bottom" data-target=".deshabilitar'. $empresa['id'].'"><i class="fa fa-lock"></i></button>';}
                            if ($empresa['estado'] == 'Inactivo'){
                              echo '<button class="btn btn-success" data-toggle="modal" title="Habilitar" data-placement="bottom" data-target=".habilitar'. $empresa['id'].'"><i class="fa fa-check-square"></i></button>
                             </td> ';
                              }
                              $cantidad++;
                             ?>
                          </tr>  

                          <div class="modal fade deshabilitar<?php echo $empresa['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel" align="center">Deshabilitar Empresa</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!--INICIO CONTENIDO DE MODAL-->
                                        <div class="x_panel">

                                            <div class="" align="center">
                                                <h2></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <b>Tenga en consideración que al aceptar, la empresa ya no tendra acceso a publicar ofertas laborales.
                                                  ¿Desea deshabilitar la empresa "<?php echo $empresa['razon_social']; ?>"?</b>
                                                <br /><br>
                                                <form role="form" action="operaciones/deshabilitar_empresa.php" class="form-horizontal form-label-left input_mask" method="POST">
                                                  <input type="hidden" name="id" value="<?php echo $empresa['id']; ?>">
                                                  <div align="center">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                                                      <input type="submit" class="btn btn-primary" value="Deshabilitar">
                                                  </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade habilitar<?php echo $empresa['id'] ?>" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                        </button>
                                        <h4 class="modal-title" id="myModalLabel" align="center">Habilitar Empresa</h4>
                                    </div>
                                    <div class="modal-body">
                                        <!--INICIO CONTENIDO DE MODAL-->
                                        <div class="x_panel">

                                            <div class="" align="center">
                                                <h2></h2>
                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="x_content">
                                                <b>Tenga en consideración que al aceptar, la empresa tendrá acceso a publicar ofertas laborales.
                                                  ¿Desea habilitar la empresa "<?php echo $empresa['razon_social']; ?>"?</b>
                                                <br /><br>
                                                <form role="form" action="operaciones/habilitar_empresa.php" class="form-horizontal form-label-left input_mask" method="POST">
                                                  <input type="hidden" name="id" value="<?php echo $empresa['id']; ?>">
                                                  <div align="center">
                                                      <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>

                                                      <input type="submit" class="btn btn-primary" value="Habilitar">
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
        <?php
        include ("../include/footer.php"); 
        ?>
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
      }
    });

    } );
    </script>
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php }