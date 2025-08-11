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
  
  $buscar = buscarDatosSistema($conexion);
  $res = mysqli_fetch_array($buscar);

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
                              <h2 align="center">Procesos de Admisión</h2>
                              <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>

                              <div class="clearfix"></div>
                            </div>
                            <div class="x_content">
                                <p><b>¿Donde visualizar el portal de admisión? Use el siguiente link cuando exista un proceso de admisión activo:</b> <br><a target="_blank" href="https://www.<?php  echo $res['dominio_sistema']; ?>admision/portal.php"> https://www.<?php  echo $res['dominio_sistema']; ?>admision/portal.php</a></p>
                                <br />

                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>Periodo</th>
                                            <th>Tipo</th>
                                            <th>Inicio</th>
                                            <th>Fin</th>
                                            <th>Estado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                          $procesos = buscarTodosProcesosAdmision($conexion);
                          $contador = 0; 
                          while ($proceso=mysqli_fetch_array($procesos)){
                            $contador++;
                        ?>
                                        <tr>
                                            <td>
                                                <?php echo $proceso['Periodo']; ?>
                                            </td>
                                            <td>
                                                <?php echo $proceso['Tipo']; ?>
                                            </td>
                                            <td>
                                                <?php echo $proceso['Fecha_Inicio']; ?>
                                            </td>
                                            <td>
                                                <?php echo $proceso['Fecha_Fin']; ?>
                                            </td>
                                            <td>
                                                <?php $estado = determinarEstadoAdmision($proceso['Fecha_Inicio'], $proceso['Fecha_Fin']);?>
                                                <span class="badge <?php echo $estado ?>"><?php echo $estado?></span>
                                            </td>
                                            <td>

                                                <?php if ($estado !== "Terminado") {
                              ?>
                                                <button title="Editar" class="btn btn-success"
                                                data-toggle="modal" data-target=".edit_<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-edit"></i></button>
                                                <?php
                            } ?>
                                                  <a title="Cuadro de Vacantes" class="btn btn-dark"
                                                    href="cuadro_vacantes.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-cubes"></i></a>
                                                  <a title="Documentos" class="btn btn-dark"
                                                    href="documento_admision.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-folder-o"></i></a>
                                                  <a title="Ajustes" class="btn btn-dark"
                                                    href="ajustes_admision.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-cog"></i></a>
                                                  <a title="Postulantes" class="btn btn-info"
                                                    href="postulantes_admision.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-users"></i></a>
                                                  <a title="Resultados Examen" class="btn btn-primary"
                                                    href="importar_resultados_admision.php?id=<?php echo $proceso['Id']; ?>"><i

                                                        class="fa fa-file-excel-o"></i></a>
                                                 <a title="Reportes" class="btn btn-warning"
                                                    href="estadisticas_reportes.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-bar-chart"></i></a> 
                                                <?php $estado = determinarEstadoAdmision($proceso['Fecha_Inicio'], $proceso['Fecha_Fin']);
                                                
                                                if($estado == "Terminado"){?>
                                                  <a title="Ajudicar Aptos" class="btn btn-danger"
                                                    href="operaciones/adjudicar.php?id=<?php echo $proceso['Id']; ?>"><i
                                                        class="fa fa-refresh"></i></a>
                                                  <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                        include('include/acciones_proceso_admision.php');
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
                                                <h4 class="modal-title" id="myModalLabel" align="center">Nuevo Proceso de Admisión</h4>
                                            </div>
                                            <div class="modal-body">
                                                <!--INICIO CONTENIDO DE MODAL-->
                                                <div class="x_panel">
                                                    <div class="x_content">
                                                        <form role="form" action="operaciones/registrar_proceso_admision.php"
                                                            class="form-vertical form-label-right input_mask formulario" method="POST">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Periodo *: </label>
                                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                                    <select class="form-control" name="periodo_anio" value="" id="periodo" onchange="recargarlista()" required="required">
                                                                        <?php $anio = date("Y"); ?>
                                                                        <option></option>
                                                                        <option value="<?php echo $anio-1; ?>"><?php echo $anio-1?></option>
                                                                        <option value="<?php echo $anio;?>"><?php echo $anio ?></option>
                                                                        <option value="<?php echo $anio+1 ?>"><?php echo $anio+1 ?></option>
                                                                    </select>
                                                                    <br>
                                                                </div>
                                                                <div class="col-md-6 col-sm-6 col-xs-6">
                                                                    <select class="form-control" name="periodo_unidad" value="" id="periodo" onchange="recargarlista()" required="required">
                                                                        <?php $anio = date("Y"); ?>
                                                                        <option></option>
                                                                        <option value="I">I</option>
                                                                        <option value="II">II</option>
                                                                        <option value="III">III</option>
                                                                    </select>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="control-label col-md-12 col-sm-12 col-xs-12">Tipo de Proceso *: </label>
                                                                <div class="col-md-12 col-sm-12 col-xs-12">
                                                                    <select class="form-control" name="tipo" value="" id="tipo" required="required" onchange="recargarlista()">
                                                                        <option></option>    
                                                                        <option value="ORDINARIO">Ordinario</option>
                                                                        <option value="EXONERADO">Exonerado</option>
                                                                    </select>
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label ">Inicio de Proceso *: </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="inicio" required="required" >
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Fin de Proceso *:
                                                                </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="fin" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label ">Inicio de Inscripciones *: </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="inicio_ins" required="required" >
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Fin de Inscripciones *:
                                                                </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="fin_ins" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label ">Inicio Extemporaneo : </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="inicio_ext">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Fin Extemporaneo :
                                                                </label>
                                                                <div class="">
                                                                    <input type="date" class="form-control" name="fin_ext">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Fecha de Examen de Admisión *:
                                                                </label>
                                                                <div class="">
                                                                    <input type="datetime-local" class="form-control" name="fecha_examen" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>
                                                            
                                                            <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                                                <label class="control-label">Lugar de Examen de Admisión *:
                                                                </label>
                                                                <div class="">
                                                                    <input type="text" class="form-control" name="lugar_examen" required="required">
                                                                    <br>
                                                                </div>
                                                            </div>

                                                            <div class="form-group form-group col-md-12 col-sm-12 col-xs-12" align="center">
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

    <script type="text/javascript">
        function recargarlista() {
            $.ajax({
                type: "POST",
                url: "operaciones/verificar_repetido.php",
                data: {
                    periodo: $('#periodo').val(),
                    tipo: $('#tipo').val()
                },
                success: function (r) {
                    // Verificar si r es "True" (existe) o "False" (no existe)
                    if (r == "True") {
                        // Proceso de admisión existe
                        alert("El proceso de admisión ya existe para el período y tipo especificados.");
                    }
                }
            });
        }
    </script>

    <script>
        $(document).ready(function(){
            // Función para validar fechas de proceso
            $('.formulario').find('[name="fin"]').change(function(){
                var finProceso = new Date($(this).val());
                var inicioProceso = new Date($('.formulario').find('[name="inicio"]').val());

                // Verificar si la fecha de inicio es posterior a la fecha de fin
                if (inicioProceso > finProceso) {
                    alert('La fecha de inicio de proceso debe ser anterior a la fecha de fin de proceso.');
                    $(this).val(''); // Limpiar el campo de fecha de inicio
                }
            });
        });
    </script>

    <script>
        $(document).ready(function(){
            // Función para validar fechas de inscripcion
            $('.formulario').find('[name="inicio_ins"]').change(function(){
                var inicioInscripcion = new Date($(this).val());
                var inicioProceso = new Date($('.formulario').find('[name="inicio"]').val());
                var finProceso = new Date($('.formulario').find('[name="fin"]').val());

                // Verificar si la fecha de inicio es posterior a la fecha de fin
                if (inicioProceso > inicioInscripcion || finProceso < inicioInscripcion) {
                    alert('La fecha de inicio de proceso debe ser anterior a la fecha de fin de proceso.');
                    $(this).val(''); // Limpiar el campo de fecha de inicio
                }
            });
            // Función para validar fechas de inscripcion
            $('.formulario').find('[name="fin_ins"]').change(function(){
                var finInscripcion = new Date($(this).val());
                var inicioProceso = new Date($('.formulario').find('[name="inicio"]').val());
                var finProceso = new Date($('.formulario').find('[name="fin"]').val());

                // Verificar si la fecha de inicio es posterior a la fecha de fin
                if (inicioProceso > finInscripcion || finProceso < finInscripcion) {
                    alert('La fecha de inicio de proceso debe ser anterior a la fecha de fin de proceso.');
                    $(this).val(''); // Limpiar el campo de fecha de inicio
                }
            });
        });
    </script>

    <script>
        $(document).ready(function(){
            // Función para validar fechas de extemporaneo
            $('.formulario').find('[name="inicio_ext"]').change(function(){
                var inicioExtemporaneo = new Date($(this).val());
                var inicioProceso = new Date($('.formulario').find('[name="inicio"]').val());
                var finProceso = new Date($('.formulario').find('[name="fin"]').val());
                var finInscripcion = new Date($('.formulario').find('[name="fin_ins"]').val());

                // Verificar si la fecha de inicio es posterior a la fecha de fin
                if (inicioProceso > inicioExtemporaneo || finProceso < inicioExtemporaneo || finInscripcion > inicioExtemporaneo) {
                    alert('La fecha de inicio de proceso debe ser anterior a la fecha de fin de proceso.');
                    $(this).val(''); // Limpiar el campo de fecha de inicio
                }
            });
            // Función para validar fechas de extemporaneo
            $('.formulario').find('[name="fin_ext"]').change(function(){
                var finExtemporaneo = new Date($(this).val());
                var inicioProceso = new Date($('.formulario').find('[name="inicio"]').val());
                var finProceso = new Date($('.formulario').find('[name="fin"]').val());
                var finInscripcion = new Date($('.formulario').find('[name="fin_ins"]').val());

                // Verificar si la fecha de inicio es posterior a la fecha de fin
                if (inicioProceso > finExtemporaneo || finProceso < finExtemporaneo || finInscripcion > Fin_Extemporaneo) {
                    alert('La fecha de inicio de proceso debe ser anterior a la fecha de fin de proceso.');
                    $(this).val(''); // Limpiar el campo de fecha de inicio
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
                },
                "order": [2, 'desc']
            });

        });
    </script>


    <?php mysqli_close($conexion); ?>
</body>

</html>
<?php }