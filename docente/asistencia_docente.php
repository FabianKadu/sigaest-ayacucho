<?php
include '../include/conexion.php';
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $res_docente_log = buscarDocenteById($conexion, $id_docente_sesion);
    $docente_log = mysqli_fetch_array($res_docente_log);
    
    $id_programacion = $_GET['id'];
    $ejec_busc_prog = buscarProgramacionById($conexion, $id_programacion);
    $res_busc_prog = mysqli_fetch_array($ejec_busc_prog);

    $res_docente = buscarDocenteById($conexion, $res_busc_prog['id_docente']);
    $docente = mysqli_fetch_array($res_docente);
    $nombre_docente = $docente['apellidos_nombres'];

    $res_horario = buscarHorarioPorProgramacionId($conexion, $id_programacion);

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

  <title>Editar Programación<?php include("../include/header_title.php"); ?></title>
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
      if($docente_log['id_cargo'] == 2){
        include("include/menu_secretaria.php"); 
      }elseif($docente_log['id_cargo'] == 5){
        include("include/menu_docente.php");
      }elseif($docente_log['id_cargo'] == 9){
        include("include/menu_secretaria.php");
      }else{
        include("include/menu_coordinador.php");
      }
      ?>

      <!-- page content -->
      <div class="right_col" role="main">
        <div class="">
        <?php 
            $id_ud = $res_busc_prog['id_unidad_didactica'];
            $b_ud = buscarUdById($conexion, $id_ud);
            $res_busc_ud = mysqli_fetch_array($b_ud);

            $res_pe = buscarCarrerasById($conexion, $res_busc_ud['id_programa_estudio']);
            $programa = mysqli_fetch_array($res_pe);

        ?>
          <div class="clearfix"></div>
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_panel">
                <div class="">
                    <h2 align="center"><b>ASISTENCIA DEL DOCENTE: <?php echo $nombre_docente ?></b></h2>
                    <h2 align="center"><?php echo $programa['nombre'].' - '.$res_busc_ud['descripcion'] ?></h2>
                  <div class="clearfix"></div>
                </div>
                <div class="x_content">
                </div>
                <div class="x_content">
                    <br>
                  <div class="x_panel">
                    <div class="x_content">
                        <table id="example" class="table table-striped  table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Semana</th>
                                    <th>Fecha de desarrollo</th>
                                    <th>Dia y horas de desarrollo</th>
                                    <th>Fecha de registro del docente</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            //buscamos las programaciones de actividades
                            $res_horario = buscarHorarioPorProgramacionId($conexion, $id_programacion);
                            $cantidad_dias = mysqli_num_rows($res_horario); 
                            $res_asistencia = buscarAsistenciaDocente($conexion, $id_programacion);
                            $num = 0;
                            while ($asistencia = mysqli_fetch_array($res_asistencia)) {
                                $num++;
                            ?>
                                <tr>
                                    <td><?php echo $num; ?></td>
                                    <td>Semana <?php echo round($num/$cantidad_dias); ?></td>
                                    <td><?php echo $asistencia['fecha_asistencia']; ?></td>
                                    <td><?php echo $asistencia['dia'].' de '.substr($asistencia['hora_inicio'],0,5).' a '.substr($asistencia['hora_fin'],0,5); ?></td>
                                    <td><?php echo $asistencia['fecha_registro']; ?></td>
                                    <td>
                                    <?php if($asistencia['estado'] == "PENDIENTE"){
                                      echo "PENDIENTE";
                                    }else{
                                      echo "<span style='color:green'>REALIZADO <i class='fa fa-check-circle-o'></i></span>";
                                    } ?>
                                    </td>
                                </tr>
                            <?php
                                }
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
<?php } ?>