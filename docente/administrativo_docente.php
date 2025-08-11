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

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
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

    <title>Docentes<?php include("../include/header_title.php"); ?></title>
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
        include("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Administrativos y Docentes</h2>
                    <?php if ($mostrar_menu) { ?>
                      <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>
                    <?php } ?>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>
                    <div class="col-lg-3">
                      <div><b>Filtrar Por Cargo: </b></div>
                      <div class="form-group ">
                        <select id="filtro" class="form-control">
                          <option value="">TODOS</option>
                          <?php
                          $ejec_busc_car = buscarCargo($conexion);
                          while ($res__busc_car = mysqli_fetch_array($ejec_busc_car)) {
                            $id_car = $res__busc_car['id'];
                            $cargo = $res__busc_car['descripcion'];
                          ?>
                            <option value="<?php echo $cargo;
                                            ?>"><?php echo $cargo; ?></option>
                          <?php
                          }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-lg-3">
                      <div><b>Filtrar por Estado: </b></div>
                      <div class="form-group ">
                        <select id="filtro_estado" class="form-control">
                          <option value="">TODOS</option>
                          <option value="SI">ACTIVOS</option>
                          <option value="NO">NO ACTIVOS</option>
                        </select>
                      </div>
                    </div>
                    <br />

                    <table id="tabla-usuarios" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>#</th>
                          <th>DNI</th>
                          <th>Apellidos y Nombres</th>
                          <th>Celular</th>
                          <th>Correo</th>
                          <th>Cargo</th>
                          <th>Activo</th>
                          <th
                            <?php if ($mostrar_menu) { ?>
                            width="22%" <?php } ?> width="14%">Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $contr = 1;
                        $ejec_busc_doc = buscarDocenteOrdesByApellidosNombres($conexion);
                        while ($res_busc_doc = mysqli_fetch_array($ejec_busc_doc)) {
                        ?>
                          <tr>
                            <td><?php echo $contr++; ?></td>
                            <td><?php echo $res_busc_doc['dni']; ?></td>
                            <td><?php echo $res_busc_doc['apellidos_nombres']; ?></td>
                            <td><?php echo $res_busc_doc['telefono']; ?></td>
                            <td><?php echo $res_busc_doc['correo']; ?></td>
                            <?php
                            $id_cargo = $res_busc_doc['id_cargo'];
                            $ejec_busc_carg = buscarCargoById($conexion, $id_cargo);
                            $res_busc_carg = mysqli_fetch_array($ejec_busc_carg);
                            ?>
                            <td><?php echo $res_busc_carg['descripcion']; ?></td>
                            <td><?php if ($res_busc_doc['activo'] == 0) {
                                  echo "NO";
                                } else {
                                  echo "SI";
                                } ?></td>
                            <td>
                              <button class="btn btn-success" data-toggle="modal" data-target=".edit_<?php echo $res_busc_doc['id']; ?>"><a data-toggle="tooltip" data-original-title="Editar" data-placement="bottom"><i class="fa fa-pencil-square-o" style="color: white"></i></a></button>
                              <a href="./imprimir_carga_academica.php?id_doc=<?php echo $res_busc_doc['id']; ?>&id_per=<?php echo $_SESSION['periodo']; ?>" data-toggle="tooltip" target="_blank" data-original-title="Ver Carga Académica" data-placement="bottom" class="btn btn-dark"><i class="fa fa-list-alt"></i></a>
                              <?php if ($mostrar_menu) { ?>
                                <button class="btn btn-primary" data-toggle="modal" data-target=".edit_password<?php echo $res_busc_doc['id']; ?>"><a data-toggle="tooltip" data-original-title="Cambiar contraseña" data-placement="bottom"><i class="fa fa-key" style="color: white"></i></a></button>
                                <button class="btn btn-danger" data-toggle="modal" data-target=".delete_user<?php echo $res_busc_doc['id']; ?>"><a data-toggle="tooltip" data-original-title="Eliminar" data-placement="bottom"><i class="fa fa-trash" style="color: white"></i></a></button>
                              <?php
                              }
                              if (!is_null($res_busc_doc['hoja_vida'])) {
                              ?>

                                <a href="<?php echo $res_busc_doc['hoja_vida']; ?>" target="_blank" data-toggle="tooltip" data-original-title="Ver Hoja de Vida" data-placement="bottom" class="btn btn-info"><i class="fa fa-file-text"></i></a>
                              <?php } ?>
                            </td>
                          </tr>
                        <?php
                          include('include/acciones_password_adm.php');
                          include('include/acciones_eliminar_docente.php');
                          include('include/acciones_docentes.php');
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
                            <h4 class="modal-title" id="myModalLabel" align="center">Registrar Usuario</h4>
                          </div>
                          <div class="modal-body">
                            <!--INICIO CONTENIDO DE MODAL-->
                            <div class="x_panel">

                              <div class="" align="center">
                                <h2></h2>
                                <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                <br />
                                <form role="form" action="operaciones/registrar_docente.php" class="form-horizontal form-label-left input_mask" method="POST">
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">DNI : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="dni" id="dni" required="required" maxlength="8">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Apellidos y Nombres : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="nom_ap" id="nom_ap" required="required">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Condición Laboral : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" name="cond_laboral" value="" required="required">
                                        <option></option>
                                        <option value="CONTRATADO">CONTRATADO</option>
                                        <option value="NOMBRADO">NOMBRADO</option>
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="date" class="form-control" name="fecha_nac" required="required">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Profesión : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="profesion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nivel de Formación : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="niv_formacion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="Number" class="form-control" name="telefono" required="required" maxlength="15">
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="email" class="form-control" name="email" required="required">
                                      <br>

                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="direccion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                      <br>

                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Género : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="genero" name="genero" value="" required="required">
                                        <option></option>
                                        <?php
                                        $ejec_busc_gen = buscarGenero($conexion);
                                        while ($res_busc_gen = mysqli_fetch_array($ejec_busc_gen)) {
                                          $id_gen = $res_busc_gen['id'];
                                          $gen = $res_busc_gen['genero'];
                                        ?>
                                          <option value="<?php echo $id_gen;
                                                          ?>"><?php echo $gen; ?></option>
                                        <?php
                                        }
                                        ?>
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargo : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="cargo" name="cargo" value="" required="required">
                                        <option></option>
                                        <?php
                                        $ejec_busc_car = buscarCargo($conexion);
                                        while ($res__busc_car = mysqli_fetch_array($ejec_busc_car)) {
                                          $id_car = $res__busc_car['id'];
                                          $car = $res__busc_car['descripcion'];
                                        ?>
                                          <option value="<?php echo $id_car;
                                                          ?>"><?php echo $car; ?></option>
                                        <?php
                                        }
                                        ?>
                                      </select>
                                      <br>
                                    </div>
                                  </div>
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Programa de Estudios : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="pe" name="pe" value="" required="required">
                                        <option></option>
                                        <?php
                                        $b_busc_car = buscarCarreras($conexion);
                                        while ($res_b_busc_car = mysqli_fetch_array($b_busc_car)) {
                                          $id_pe = $res_b_busc_car['id'];
                                          $pe = $res_b_busc_car['nombre'];
                                        ?>
                                          <option value="<?php echo $id_pe;
                                                          ?>"><?php echo $pe . ' - ' . $res_b_busc_car['plan_estudio']; ?></option>
                                        <?php
                                        }
                                        ?>
                                      </select>
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
        $('#tabla-usuarios').DataTable({
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

    <script>
      document.addEventListener('DOMContentLoaded', function() {
        const dniInput = document.getElementById('dni');
        const nameInput = document.getElementById('nom_ap');

        let timeoutId = null;

        dniInput.addEventListener('input', function() {
          const dni = dniInput.value;

          // Si el valor no tiene 8 dígitos, limpiamos el timeout y retornamos
          if (dni.length !== 8) {
            if (timeoutId) {
              clearTimeout(timeoutId);
              nameInput.value = "";
            }
            return;
          }

          // Limpiamos cualquier timeout anterior
          if (timeoutId) {
            clearTimeout(timeoutId);
          }

          // Establecemos un nuevo timeout de 1 segundo
          timeoutId = setTimeout(() => {
            fetch(`https://dni.biblio-ideas.com/api/dni/${dni}`)
              .then(response => response.json())
              .then(data => {
                nameInput.value = data.apellidoPaterno + ' ' + data.apellidoMaterno + ' ' + data.nombres
              })
              .catch(error => {

              });
          }, 500);
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        var table = $('#tabla-usuarios').DataTable();

        // Custom filter for Programa de Estudios
        $('#filtro').on('change', function() {
          var filtro = $(this).val();
          table.column(5).search(filtro).draw();
        });
      });
    </script>

    <script>
      $(document).ready(function() {
        var table = $('#tabla-usuarios').DataTable();
        // Filtro por estado
        $('#filtro_estado').on('change', function() {
            var filtro = $(this).val();
            table.column(6).search(filtro).draw();
          }

        );
      });
    </script>


    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
