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
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Documento_admision<?php include ("../include/header_title.php"); ?></title>
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
    <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

  <style>
    .upload-btn {
    display: inline-block;
    border: 2px solid #428bca; /* Color similar a btn-primary */
    padding: 10px 20px;
    cursor: pointer;
    border-radius: 5px;
    background: transparent; /* Color similar a btn-primary */
    color: #fff; /* Texto blanco */
    font-size: 16px;
    transition: background-color 0.3s ease;
    }

    .upload-btn:hover {
        background-color: #357ebd; /* Color similar a btn-primary al pasar el ratón */
    }

    .hidden {
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
                    <h2 align="center">Documentos de Admision - <?php echo $res_b_proc_adm['Periodo'] ?></h2>
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>
                    <a href="procesos_admision.php" class="btn btn-danger">Regresar</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>Descripción</th>
                          <th>Tipo</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          
                          $ejec_docs_adm = buscarDocumentosAdmisionPorIdProceso($conexion,$id_proc_adm);  
                          while ($res_docs_adm=mysqli_fetch_array($ejec_docs_adm)){
                  
                        ?>
                        <tr>
                          <td><?php echo $res_docs_adm['Id']; ?></td>
                          <td><?php echo $res_docs_adm['Descripcion']; ?></td>
                          <td><?php echo $res_docs_adm['Tipo']; ?></td>
                          <td align="center">
                            <button title="Editar documento" class="btn btn-success" data-toggle="modal" data-target=".edit_<?php echo $res_docs_adm['Id']; ?>"><i class="fa fa-pencil-square-o"></i> Editar</button>
                          </td>
                        </tr>  
                        <?php
                          include('include/acciones_documento_admision.php');
                          };
                        ?>
                      </tbody>
                    </table>
                    <!--MODAL REGISTRAR-->
                    <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Nuevo Documento de Admisión</h4>
                        </div>
                        <div class="modal-body">
  <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  <div class="" align="center">
                    <h2 ></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form role="form" action="operaciones/registrar_documento_admision.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $id_proc_adm; ?>" name="proceso_admision">

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción *: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="descripcion" required="required">
                            <br>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Destino de Archivo *: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="tipo" id="tipo" required="required">
                              <option value="" disabled selected>Seleccione</option>
                              <option value="Carpeta del Postulante">Carpeta del Postulante</option>
                              <option value="Imagen Publicitaria">Imagen Publicitaria</option>
                              <option value="Declaración Jurada">Declaración Jurada</option>
                        </select>
                        <br>
                        </div>
                    </div>
                    <!-- <div class="form-group" style="margin-bottom: 20px;">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargar Documento *: </label>
                      <div class="col-md-9 col-sm-9 col-xs-12">
                          <label for="file-upload" class="upload-btn">
                              <i class="fa fa-upload"></i> Cargar archivo
                          </label>
                          <input id="file-upload" type="file" name="carga_documento" class="hidden" accept=".pdf, .doc, .png, .jpeg, .jpg" />
                          
                        </div>
                    </div> -->
                    
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargar Documento *: </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                        <input type="file" name="carga_documento" class="form-control" id="file-3" data-multiple-caption="{count} archivos seleccionados" multiple  accept=".pdf, .doc .png, .jpeg, .jpg" />
                            <br>
                        </div>
                    </div>


                    <input type="hidden" name="estado" value="habilitado">
                    <div align="center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Guardar</button>
                    </div>
                </form>

                  </div>
                </div>
                          <!--FIN DE CONTENIDO DE MODAL-->
                  </div>
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

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
    <script>
    $(document).ready(function() {
    $('#example').DataTable({
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
