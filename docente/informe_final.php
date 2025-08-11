<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_docente_coordinador.php';
if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

$id_prog = $_GET['id'];
$b_prog = buscarProgramacionById($conexion, $id_prog);
$res_b_prog = mysqli_fetch_array($b_prog);
$condicion = "disabled";
if($r_b_docente["id_cargo"] == 5) $condicion = " ";
if (!($res_b_prog['id_docente'] == $id_docente_sesion) && $r_b_docente["id_cargo"] != 9) {
  //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
  //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
  echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.history.back();
		</script>
	";
} else {
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

    <title>Informe Final<?php include("../include/header_title.php"); ?></title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <!-- script para tags -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css" rel="stylesheet" />

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        $director = false;
        $unidad = false;
        if($r_b_docente['id_cargo']== 1 AND  $r_b_docente['carga_academica'] == 1){
          $director = true;
        }

        if($r_b_docente['id_cargo']== 3 AND  $r_b_docente['carga_academica'] == 1){
          $unidad = true;
        }

        if($r_b_docente['id_cargo'] ==5 || $director || $unidad ){//si es docente
          include ("include/menu_docente.php");
        }elseif($r_b_docente['id_cargo']==4) {
          include ("include/menu_coordinador.php");
        }elseif($r_b_docente['id_cargo']==9) {
          include ("include/menu_secretaria.php");
        }
        
        $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
        $r_b_ud = mysqli_fetch_array($b_ud);
        
        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center"><b>INFORME FINAL - <?php echo $r_b_ud['descripcion']; ?></b></h2>
                    <form action="imprimir_informe_final.php" method="POST" target="_blank">
                      <input type="hidden" name="data" value="<?php echo $id_prog; ?>">
                      <button type="submit" class="btn btn-info">Imprimir</button>
                    </form>
                    <a href="calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>

                    <form role="form" action="operaciones/actualizar_informe_final.php" class="form-horizontal form-label-left input_mask" method="POST">
                      <input type="hidden" name="id_prog" value="<?php echo $id_prog; ?>">
                      <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th colspan="2">
                                <center>SOBRE LA SUPERVISIÓN Y EVALUACIÓN</center>
                              </th>
                            </tr>
                            <tr>

                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td width="30%">Fue Supervisado</td>
                              <td>: 
                                <select name="fue_supervisado" id="fue_supervisado" <?php echo $condicion ?>>
                                  <option value="1" <?php if($res_b_prog['supervisado']==1){ echo "selected";} ?>>SI</option>
                                  <option value="0" <?php if($res_b_prog['supervisado']==0){ echo "selected";} ?>>NO</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <th colspan="2">
                                <center>DOCUMENTOS DE EVALUACIÓN UTILIZADAS</center>
                              </th>
                            </tr>
                            <tr>
                              <td>Registro de Evaluación</td>
                              <td>: 
                                <select name="reg_evaluacion" id="reg_evaluacion" <?php echo $condicion ?>>
                                  <option value="1" <?php if($res_b_prog['reg_evaluacion']==1){ echo "selected";} ?>>SI</option>
                                  <option value="0" <?php if($res_b_prog['reg_evaluacion']==0){ echo "selected";} ?>>NO</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Registro Auxiliar</td>
                              <td>: 
                                <select name="reg_auxiliar" id="reg_auxiliar" <?php echo $condicion ?>>
                                  <option value="1" <?php if($res_b_prog['reg_auxiliar']==1){ echo "selected";} ?>>SI</option>
                                  <option value="0" <?php if($res_b_prog['reg_auxiliar']==0){ echo "selected";} ?>>NO</option>
                                </select>
                            </tr>
                            <tr>
                              <td>Programación Curricular</td>
                              <td>: 
                                <select name="prog_curricular" id="prog_curricular" <?php echo $condicion ?>>
                                  <option value="1" <?php if($res_b_prog['prog_curricular']==1){ echo "selected";} ?>>SI</option>
                                  <option value="0" <?php if($res_b_prog['prog_curricular']==0){ echo "selected";} ?>>NO</option>
                                </select>
                              </td>
                            </tr>
                            <tr>
                              <td>Otros</td>
                              <td>: 
                                <select name="otros" id="otros" <?php echo $condicion ?>>
                                  <option value="1" <?php if($res_b_prog['otros']==1){ echo "selected";} ?>>SI</option>
                                  <option value="0" <?php if($res_b_prog['otros']==0){ echo "selected";} ?>>NO</option>
                                </select>
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th>
                                <center>LOGROS OBTENIDOS</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion ?> name="logros_obtenidos" style="width:100%; resize: none; height:auto;" rows="3"><?php echo $res_b_prog['logros_obtenidos'];?></textarea>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th>
                                <center>DIFICULTADES</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion ?> name="dificultades" style="width:100%; resize: none; height:auto;" rows="3"><?php echo $res_b_prog['dificultades'];?></textarea>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th>
                                <center>SUGERENCIAS</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                              <tr>
                                <td>
                                  <textarea <?php echo $condicion ?> name="sugerencias" style="width:100%; resize: none; height:auto;" rows="3"><?php echo $res_b_prog['sugerencias']; ?></textarea>
                                </td>
                              </tr>
                          </tbody>
                        </table>
                      </div>
                      
                      
                      <div align="center">
                        <br>
                        <br>
                        <a href="calificaciones_unidades_didacticas.php" class="btn btn-danger">Regresar</a>
                        <button type="submit" class="btn btn-success">Guardar</button>
                      </div>
                    </form>


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
    <script src=".../Gentella/vendors/iCheck/icheck.min.js"></script>
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

    <!-- para tags -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>

    <script>
      $(document).ready(function() {
        $('#example').DataTable({
          "order": [
            [1, "asc"]
          ],
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
<?php
}
}