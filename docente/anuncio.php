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
      .roles .cargo{
        color: black !important;
      }
    </style>
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
                    <h2 align="center">Administración de Anuncios</h2>
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />

                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>Título</th>
                          <th>Dirigido a</th>
                          <th>Fecha Inicial</th>
                          <th>Fecha Final</th>
                          <th>Estado</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php 
                          $res_anuncios = buscarAnuncios($conexion); 
                          while ($anuncio=mysqli_fetch_array($res_anuncios)){
                        ?>
                        <tr>
                          <td><?php echo $anuncio['id']; ?></td>
                          <td><?php echo $anuncio['titulo']; ?></td>
                          <td><?php
                          $cargos_seleccionados = explode('-', $anuncio['usuarios']);
                          $cargos_mostrar = '';
                          foreach ($cargos_seleccionados as $cargo_id) {
                            if($cargo_id == 0){
                              $cargos_mostrar .= 'ESTUDIANTES, ';
                            }else{
                              $res_cargo = buscarCargoById($conexion, $cargo_id);
                              $cargo = mysqli_fetch_array($res_cargo);
                              $cargos_mostrar .= $cargo['descripcion'].', ';
                          
                            }
                          }
                          echo substr($cargos_mostrar, 0, strlen($cargos_mostrar)-2) ?></td>
                          <td><?php echo $anuncio['fecha_activa_inicio']; ?></td>
                          <td><?php echo $anuncio['fecha_activa_fin']; ?></td>
                          <td>
                          <span 
                          class="badge <?php echo determinarEstadoAdmision($anuncio['fecha_activa_inicio'], $anuncio['fecha_activa_fin']) ?>">
                          <?php echo determinarEstadoAdmision($anuncio['fecha_activa_inicio'], $anuncio['fecha_activa_fin']) ?></span>
                          </td>
                         
                          <td>
                            <button class="btn btn-success" data-toggle="modal" data-target=".edit_<?php echo $anuncio['id']; ?>"><i class="fa fa-pencil-square-o"></i> Editar</button>
                        </tr>  
                        <?php
                         include('include/acciones_anuncio.php');
                          };
                        ?>

                      </tbody>
                    </table>
                    

                    <!--MODAL REGISTRAR-->
                    <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-mg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Registrar Nuevo Anuncio</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                    <div class="x_panel">
                        <div class="x_content">
                        <form role="form" action="operaciones/registrar_anuncio.php" class="form-horizontal form-label-left input_mask" method="POST" >
                          <input type="hidden" name="tipo" value="COMUNICADO">
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Asunto : </label>
                                <div class="">
                                <input type="text" class="form-control" name="titulo" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Descripción : </label>
                                <div class="">
                                <textarea type="text" class="form-control" name="descripcion" required="required" rows="7"></textarea>
                                </div>
                            </div>
                            
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label ">¿Desde cuándo sera visible? : </label>
                                <div class="">
                                    <input type="date" class="form-control" name="inicio" required="required" >
                                </div>
                            </div>
                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                <label class="control-label">¿Hasta cuándo sera visible? :
                                </label>
                                <div class="">
                                    <input type="date" class="form-control" name="fin" required="required">
                                </div>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                <label class="control-label">Enlace (Opcional) : </label>
                                <div class="">
                                <input type="text" class="form-control" name="enlace" >
                                </div>
                                <br>
                            </div>
                            <div class="form-group col-md-12 col-sm-12 col-xs-12 roles">
                                <label class="control-label">¿Quiénes deben de ver el anuncio? : </label>
                                <div class="">
                                <label class="col-md-6 col-sm-6 col-xs-12 cargo rol"><input type="checkbox" name="cargo[]" value="<?php echo 0 ?>" checked onchange="validarCheckbox()"> ESTUDIANTES</label>
                                  <?php
                                  $res_cargos = buscarCargo($conexion);
                                  while($cargo = mysqli_fetch_array($res_cargos)){
                                  ?>
                                  <label class="col-md-6 col-sm-6 col-xs-12 cargo rol"><input type="checkbox" name="cargo[]" value="<?php echo $cargo['id'];?>" checked onchange="validarCheckbox()"><?php echo $cargo['descripcion']; ?></label>
                                  <?php } ?>
                                </div>
                                <br>
                                <br>
                            </div>
                            <div align="center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <button type="submit" class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                        </div>
                    </div>
                    </div>
                          <!--FIN DE CONTENIDO DE MODAL-->
                </div>
                </div>
            </div>
            </div>

            <!-- FIN MODAL REGISTRAR-->

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

    <script>
        function validarCheckbox() {
            // Obtiene todos los checkboxes
            var checkboxes = document.querySelectorAll('.cargo input[type="checkbox"]');
            
            // Verifica cuántos están marcados
            var marcados = 0;
            checkboxes.forEach(function(checkbox) {
                if (checkbox.checked) {
                    marcados++;
                }
            });
            
            // Si ninguno está marcado, muestra una alerta
            if (marcados === 0) {
                alert('Se requiere marcar al menos un checkbox.');
                checkboxes[0].checked = true
            }
        }
    </script>

    <script>
      function validateInputNum(input, tamanio) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
          inputValue = inputValue.slice(0, tamanio);
          // Actualiza el valor del campo de entrada
          input.value = inputValue.toUpperCase();
      }
      
      function validateInputText(input, tamanio) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^a-zA-Z\s!"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]+/g, '');
          inputValue = inputValue.slice(0, tamanio);
          // Actualiza el valor del campo de entrada
          input.value = inputValue.toUpperCase();
      }
      function limitarTamanio(input, tamanio) {
        let inputValue = input.value;

        // Remueve cualquier carácter no permitido (en este caso, letras)
        //inputValue = inputValue.replace(/[^a-zA-Z\s!"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]+/g, '');
        inputValue = inputValue.slice(0, tamanio);
        // Actualiza el valor del campo de entrada
        input.value = inputValue.toUpperCase();
        }
    </script>
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