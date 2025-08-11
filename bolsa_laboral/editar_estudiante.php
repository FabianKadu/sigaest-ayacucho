<?php
include ("../include/conexion.php");
include ("../empresa/include/consultas.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_administrador.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

$id_estudiante = $_GET['id'];
$ejec_busc_est = buscarEstudianteById($conexion, $id_estudiante);
$res_busc_est = mysqli_fetch_array($ejec_busc_est);
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

  <title>Editar Estudiante<?php include("../include/header_title.php"); ?></title>
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
  <!-- bootstrap-wysiwyg -->
  <link href="../Gentella/vendors/google-code-prettify/bin/prettify.min.css" rel="stylesheet">
  <!-- Select2 -->
  <link href="../Gentella/vendors/select2/dist/css/select2.min.css" rel="stylesheet">
  <!-- Switchery -->
  <link href="../Gentella/vendors/switchery/dist/switchery.min.css" rel="stylesheet">
  <!-- starrr -->
  <link href="../Gentella/vendors/starrr/dist/starrr.css" rel="stylesheet">
  <!-- bootstrap-daterangepicker -->
  <link href="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">


  <!-- Custom Theme Style -->
  <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
  <!-- Script obtenido desde CDN jquery -->
  <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

</head>

<body class="nav-md">
  <div class="container body">
    <div class="main_container">
      <!--menu-->
      <?php
      include ("include/menu_administrador.php"); ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">

          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="">
                  <h2 align="center">Editar Datos de Estudiante</h2>


                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                  <br />



                  <div class="x_panel">


                    <div class="x_content">
                      <br />
                      <form role="form" action="operaciones/actualizar_estudiante.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id" value="<?php echo $id_estudiante; ?>">
                        <input type="hidden" name="dni_a" value="<?php echo $res_busc_est['dni']; ?>">
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dni : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="number" class="form-control" name="dni" required="" maxlength="8" value="<?php echo $res_busc_est['dni']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Apellidos y Nombres : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="ap_nom" required="" value="<?php echo $res_busc_est['apellidos_nombres']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Género : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" id="genero" name="genero" value="<?php echo $res_busc_est['id_genero']; ?>" required="required">
                              <option></option>
                              <?php
                              $ejec_busc_gen = buscarGenero($conexion);
                              while ($res_busc_gen = mysqli_fetch_array($ejec_busc_gen)) {
                                $id_gen = $res_busc_gen['id'];
                                $gen = $res_busc_gen['genero'];
                              ?>
                                <option value="<?php echo $id_gen;
                                                ?>" <?php if ($res_busc_est['id_genero'] == $id_gen) {
                                    echo "selected";
                                  } ?>><?php echo $gen; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="date" class="form-control" name="fecha_nac" required="" value="<?php echo $res_busc_est['fecha_nac']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="direccion" required="required"  value="<?php echo $res_busc_est['direccion']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="email" class="form-control" name="email" required="required" value="<?php echo $res_busc_est['correo']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="number" class="form-control" name="telefono" required="" maxlength="15" value="<?php echo $res_busc_est['telefono']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Año de Ingreso : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select name="anio_ingreso" id="" class="form-control" value="<?php echo $res_busc_est['anio_ingreso']; ?>">
                            <?php
                            $anio = date('Y');
                            $aanio = $anio - 5;
                            $tanio = $anio +5;
                            for ($i=$aanio; $i <= $tanio; $i++) { 
                              ?>
                                <option value="<?php echo $i; ?>"<?php if($res_busc_est['anio_ingreso'] == $i){ echo " selected";} ?>><?php echo $i; ?></option>
                              <?php
                            }
                            ?>
                            </select>
                            
                            
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Carrera Profesional : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" id="carrera" name="carrera" value="<?php echo $res_busc_est['id_programa_estudios']; ?>" required="required">
                              <option></option>
                              <?php
                              $ejec_busc_carr = buscarCarreras($conexion);
                              while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                                $id_carr = $res__busc_carr['id'];
                                $carr = $res__busc_carr['nombre'];
                              ?>
                                <option value="<?php echo $id_carr;
                                                ?>" <?php if ($res_busc_est['id_programa_estudios'] == $id_carr) {
                                    echo "selected";
                                  } ?>><?php echo $carr; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Semestre / Ciclo : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" id="semestre" name="semestre" value="<?php echo $res_busc_est['id_semestre']; ?>" required="required">
                              <option></option>
                              <?php
                              $ejec_busc_sem = buscarSemestre($conexion);
                              while ($res_busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                                $id_sem = $res_busc_sem['id'];
                                $sem = $res_busc_sem['descripcion'];
                              ?>
                                <option value="<?php echo $id_sem;
                                                ?>" <?php if ($res_busc_est['id_semestre'] == $id_sem) {
                                    echo "selected";
                                  } ?>><?php echo $sem; ?></option>
                              <?php
                              }
                              ?>
                            </select>
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Sección : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <input type="text" class="form-control" name="seccion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="1" value="<?php echo $res_busc_est['seccion']; ?>">
                            <br>
                          </div>
                        </div>
                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Turno : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="turno" value="" required="required">
                              <option value="MAÑANA" <?php if ("MAÑANA" == $res_busc_est['turno']) :
                                                    echo 'selected';
                                                  endif ?>>MAÑANA</option>
                              <option value="TARDE" <?php if ("TARDE" == $res_busc_est['turno']) :
                                                    echo 'selected';
                                                  endif ?>>TARDE</option>
                            </select>
                            <br>
                          </div>
                        </div>

                        <div class="form-group">
                          <label class="control-label col-md-3 col-sm-3 col-xs-12">Discapacidad : </label>
                          <div class="col-md-9 col-sm-9 col-xs-12">
                            <select class="form-control" name="discapacidad" value="" required="required">
                              <option></option>
                              <option value="SI" <?php if ("SI" == $res_busc_est['discapacidad']) :
                                                    echo 'selected';
                                                  endif ?>>SI</option>
                              <option value="NO" <?php if ("NO" == $res_busc_est['discapacidad']) :
                                                    echo 'selected';
                                                  endif ?>>NO</option>
                            </select>
                            <br>
                          </div>
                        </div>


                        <div align="center">
                          <a class="btn btn-danger" href="javascript:history.back()"> Cancelar</a>
                          <button type="submit" class="btn btn-success">Guardar</button>
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
  <!-- bootstrap-progressbar -->
  <script src="../Gentella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
  <!-- iCheck -->
  <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>
  <!-- bootstrap-daterangepicker -->
  <script src="../Gentella/vendors/moment/min/moment.min.js"></script>
  <script src="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>
  <!-- bootstrap-wysiwyg -->
  <script src="../Gentella/vendors/bootstrap-wysiwyg/js/bootstrap-wysiwyg.min.js"></script>
  <script src="../Gentella/vendors/jquery.hotkeys/jquery.hotkeys.js"></script>
  <script src="../Gentella/vendors/google-code-prettify/src/prettify.js"></script>
  <!-- jQuery Tags Input -->
  <script src="../Gentella/vendors/jquery.tagsinput/src/jquery.tagsinput.js"></script>
  <!-- Switchery -->
  <script src="../Gentella/vendors/switchery/dist/switchery.min.js"></script>
  <!-- Select2 -->
  <script src="../Gentella/vendors/select2/dist/js/select2.full.min.js"></script>
  <!-- Parsley -->
  <script src="../Gentella/vendors/parsleyjs/dist/parsley.min.js"></script>
  <!-- Autosize -->
  <script src="../Gentella/vendors/autosize/dist/autosize.min.js"></script>
  <!-- jQuery autocomplete -->
  <script src="../Gentella/vendors/devbridge-autocomplete/dist/jquery.autocomplete.min.js"></script>
  <!-- starrr -->
  <script src="../Gentella/vendors/starrr/dist/starrr.js"></script>
  <!-- Custom Theme Scripts -->
  <script src="../Gentella/build/js/custom.min.js"></script>


  <!--prueba tabla-->

  <script src="../include/tabla/jquery.dataTables.min.js"></script>
  <script src="../include/tabla/dataTables.bootstrap.min.js"></script>
  <script>
    $(document).ready(function() {
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
  <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
  <script type="text/javascript">
    $(document).ready(function() {
      $('#carrera_m').change(function() {
        recargarlista();
      });
    })
  </script>
  <script type="text/javascript">
    function recargarlista() {
      $.ajax({
        type: "POST",
        url: "operaciones/obtener_modulos.php",
        data: "id_carrera=" + $('#carrera_m').val(),
        success: function(r) {
          $('#modulo').html(r);
        }
      });
    }
  </script>

  <?php mysqli_close($conexion); ?>
</body>

</html>
<?php
}