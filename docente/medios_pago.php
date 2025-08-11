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
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

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

    <title>Procesos de admisión
        <?php include ("../include/header_title.php"); ?>
    </title>
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
    <!-- bootstrap-progressbar -->
    <link href="../Gentella/vendors/bootstrap-progressbar/css/bootstrap-progressbar-3.3.4.min.css" rel="stylesheet">
    <!-- JQVMap -->
    <link href="../Gentella/vendors/jqvmap/dist/jqvmap.min.css" rel="stylesheet" />
    <!-- bootstrap-daterangepicker -->
    <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
    <!-- Script obtenido desde CDN jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <style>
      .comenzar{
        background-color: #337AB7;
      }
      .proceso{
        background-color: #26B99A;
      }
    </style>
</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <!--menu-->
            <?php
              include ("include/menu_secretaria.php");
            ?>

            <!-- page content -->
            <div class="right_col" role="main">


                <div class="clearfix"></div>
                <div class="row">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="x_panel">
                            <div class="">
                              <h2 align="center">Medios de Pago</h2>
                              <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>
                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <br />

                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Nro</th>
                                            <th>Forma de Pago</th>
                                            <th>Banco o Entidad</th>
                                            <th>Número de Cuenta</th>
                                            <th>CCI</th>
                                            <th>Titular o Responsable</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                          $medio_pagos = buscarTodosMetodosPago($conexion);
                          $contador = 0; 
                          while ($medio_pago=mysqli_fetch_array($medio_pagos)){
                            $contador++;
                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $contador; ?>
                                            </td>
                                            <td>
                                                <?php echo $medio_pago['Metodo']; ?>
                                            </td>
                                            <td>
                                                <?php echo $medio_pago['Banco']; ?>
                                            </td>
                                            <td>
                                                <?php echo $medio_pago['Cuenta']; ?>
                                            </td>
                                            <td>
                                                <?php echo $medio_pago['CCI']; ?>
                                            </td>
                                            <td>
                                                <?php echo $medio_pago['Titular']; ?>
                                            </td>
                                            <td>
                                                <button title="Editar" class="btn btn-success"
                                                data-toggle="modal" data-target=".edit_<?php echo $medio_pago['Id']; ?>"><i
                                                        class="fa fa-edit"></i></button>
                                            </td>
                                        </tr>
                                        <?php
                                        include('include/acciones_medio_pago.php');
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
                                                <h4 class="modal-title" id="myModalLabel" align="center">Nuevo Medio de Pago</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!--INICIO CONTENIDO DE MODAL-->
                                                <div class="x_panel">
                                                    <div class="x_content">
                                                        <form role="form" action="operaciones/registrar_medio_pago.php"
                                                            class="form-vertical form-label-right input_mask" method="POST" id="formularioMedioPago">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Medio de Pago *: </label>
                                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                                    <select class="form-control" name="medio" value="" id="medioPago" required="required">
                                                                        <option></option>
                                                                        <option value="Efectivo">Efectivo</option>
                                                                        <option value="Yape">Yape</option>
                                                                        <option value="Depósito">Depósito</option>
                                                                        <option value="Transferencia Interbancaria">Transferencia Interbancaria</option>
                                                                        <option value="Plin">Plin</option>
                                                                    </select>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Banco o Entidad*:
                                                                </label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="banco" id="banco" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label ">Número de Cuenta *: </label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="cuenta" id="cuenta" required="required" >
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label"> CCI :
                                                                </label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="cci" id="cci" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label ">Nombre del Titular o Responsable*: </label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="titular">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                        
                                                            <div align="center">
                                                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                                <button type="submit" class="btn btn-primary">Guardar</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--FIN MODAL-->
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
        $(document).ready(function(){
            // Función para manejar cambios en el campo "Medio de Pago"
            $('#medioPago').change(function(){
                var medioSeleccionado = $(this).val();
                if(medioSeleccionado === 'Efectivo') {
                    // Si es Efectivo, Yape o Plin, establecer "No requiere" en los campos Banco y CCI
                    $('#cuenta').val('No requiere');
                    $('#cci').val('No requiere');
                    $('#banco').val('');
                }
                else if(medioSeleccionado === 'Yape' || medioSeleccionado === 'Plin') {
                    // Si es Efectivo, Yape o Plin, establecer "No requiere" en los campos Banco y CCI
                    $('#banco').val('No requiere');
                    $('#cci').val('No requiere');
                    $('#cuenta').val('');
                }
                else {
                    // Si no es Efectivo, Yape ni Plin, vaciar los campos Banco y CCI y habilitarlos
                    $('#banco').val('');
                    $('#cuenta').val('');
                    $('#cci').val('');
                }
            });
        });
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
<?php }