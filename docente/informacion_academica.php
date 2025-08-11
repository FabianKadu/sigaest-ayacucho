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
  $id_estudiante = $_GET['id'];
  /* OBTENER INFORMACIÓN NECESARIA DESDE LA DB */
  $res_estudiante = buscarEstudianteById($conexion, $id_estudiante);
  $estudiante = mysqli_fetch_array($res_estudiante);

  $res_programa = buscarCarrerasById($conexion, $estudiante['id_programa_estudios']);
  $programa = mysqli_fetch_array($res_programa);


  $semestre = "NO REGISTRADO";
  if ($estudiante['id_semestre'] != 0) {
    $res_semestre = buscarSemestreById($conexion, $estudiante['id_semestre']);
    $semestre = mysqli_fetch_array($res_semestre);
    $semestre = $semestre['descripcion'];
  }

  $res_modulos = buscarModulosByIdCarrera($conexion, $estudiante['id_programa_estudios']);

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

    <title>Estudiantes <?php include("../include/header_title.php"); ?></title>
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
                    <!-- ENCABEZADO -->
                    <h4><b>INFORMACIÓN DEL ESTUDIANTE</b></h4>
                    <hr>
                    <div class="row">
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <span>ESTUDIANTE: <b> <?php echo $estudiante['apellidos_nombres'] ?> </b></span> <br>
                        <span>SEMESTRE ACTUAL: <b> <?php echo $semestre ?> </b></span> <br>
                        <span>AÑO DE INGRESO: <b> <?php echo $estudiante['anio_ingreso'] ?> </b></span>
                      </div>
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <span>PROGRAMA DE ESTUDIOS: <b> <?php echo $programa['nombre'] ?> </b></span><br>
                        <span>PLAN DE ESTUDIOS: <b> <?php echo $programa['plan_estudio'] ?> </b></span> <br>
                        <span>¿ES EGRESADO?: <b> <?php echo $estudiante['egresado'] ?> </b></span>
                      </div>
                    </div>
                    <?php
                    $text_descripcion = "¿El estudiante es apto para considerarse egresado?";
                    $text_boton = "estudiante como egresado";
                    if ($estudiante['egresado'] == "SI") {
                      $text_descripcion = "¿El estudiante no es considerado como egresado?";
                      $text_boton = "egresado como estudiante";
                    } ?>
                    <div class="clearfix"></div>
                    <div class="x_content">
                      <span><b><?php echo $text_descripcion ?></b></span> <br>
                      <button class="btn btn-success" data-toggle="modal" data-target=".fade">Establecer <?php echo $text_boton ?></button>
                    </div>
                  </div>
                </div>
                <!-- Modal de confirmación -->
                <div class="modal fade" id="confirmacionModal" tabindex="-1" role="dialog" aria-labelledby="confirmacionModalLabel" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="confirmacionModalLabel">Confirmar Acción</h5>
                      </div>
                      <div class="modal-body">
                        ¿Está seguro de establecer al <?php echo $text_boton ?>?
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <a href="operaciones/estado_estudiante.php?id=<?php echo $id_estudiante; ?>" id="confirmarBtn" type="button" class="btn btn-success">Confirmar</a>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="x_panel">
                  <div class="">
                    <!-- ENCABEZADO -->
                    <div class="row">
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <h4><b>HISTÓRICO DE CALIFICACIONES </b></h4>
                      </div>
                      <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                        <!--<a href="" class="btn btn-success"><i class="fa fa-file"> </i> Imprimir Histórico</a>-->
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <?php $res_semestres = buscarSemestre($conexion);
                    while ($semestre = mysqli_fetch_array($res_semestres)) {
                      $res_semestre = buscarSemestreById($conexion, $semestre['id']);
                      $sem = mysqli_fetch_array($res_semestre);
                    ?>
                      <div class="x_content">
                        <h5><b>SEMESTRE <?php echo $sem['descripcion'] ?></b></h5>
                        <table class="table table-striped table-bordered" style="width:100%">
                          <thead bgcolor="#e1e1e1">
                            <tr>
                              <th width="40%">UNIDAD DIDÁCTICA</th>
                              <th>TIPO</th>
                              <th>CRÉDITOS</th>
                              <th>CALIFICACIÓN</th>
                              <th>PERIODO</th>
                              <th>ESTADO</th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            $res_unidad_didacticas = buscarUdByCarSem($conexion, $estudiante['id_programa_estudios'], $semestre['id']);
                            while ($unidad_didactica = mysqli_fetch_array($res_unidad_didacticas)) {
                            ?>
                              <tr>
                                <td><?php echo $unidad_didactica['descripcion']; ?></td>
                                <td><?php echo $unidad_didactica['tipo']; ?></td>
                                <td><?php echo $unidad_didactica['creditos']; ?></td>
                                <td>
                                  <?php
                                  // Inicialización de variables
                                  $periodo = "-";
                                  $result = 0;
                                  $resultado = 0;
                                  $no_empty = false;

                                  // Buscar nota importada por DNI y Unidad Didáctica
                                  $res_calificacion = getNotasImportadaByDniAndIdUd($conexion, $estudiante['dni'], $unidad_didactica['id']);
                                  if (mysqli_num_rows($res_calificacion) == 1) {
                                    $calificacion = mysqli_fetch_array($res_calificacion);
                                    $result = $calificacion['calificacion'];
                                    $periodo = $calificacion['semestre_academico']; // Periodo de la nota importada
                                    $no_empty = true;
                                  }

                                  // Buscar matrícula más reciente de la unidad didáctica solo si no se encontró una nota importada
                                  if (!$no_empty) {
                                    $res_detalle_matricula = buscarDetalleMatriculaByIdUdAndIdEstudiante($conexion, $unidad_didactica['id'], $id_estudiante);
                                    if ($detalle_matricula = mysqli_fetch_array($res_detalle_matricula)) {
                                      // Obtener calificación final del estudiante
                                      $resultado = obtenerCalificacionFinal($conexion, $detalle_matricula['id_detalle_matricula']);
                                      if (is_null($resultado)) {
                                        $resultado = 0;
                                      }

                                      // Buscar periodo académico relacionado
                                      $res_periodo_calificacion = buscarPeriodoAcadById($conexion, $detalle_matricula['id_periodo']);
                                      if ($periodo_calificacion = mysqli_fetch_array($res_periodo_calificacion)) {
                                        $periodo = $periodo_calificacion['nombre']; // Periodo de la matrícula
                                      }
                                    }
                                  }

                                  // Calcular la calificación más alta entre la importada y la final
                                  $resultadomax = max($result, $resultado);
                                  $result = round($resultadomax);

                                  // Mostrar resultado
                                  echo $result > 0 ? $result : "-";
                                  ?>
                                </td>
                                <td><?php
                                      echo $periodo;
                                    ?></td>
                                <td><?php
                                    if ($result > 12) {
                                      echo "<span style='color:green'>CUMPLIDO <i class='fa fa-check-circle-o'></i></span>";
                                    } elseif ( $result < 13 && $result > 0) {
                                      echo "<span style='color:red'>POR RECUPERAR <i class='fa fa-times-circle-o'></i></span>";
                                    } else {
                                      echo "<span><i class='fa fa-ellipsis-h'></i>  PENDIENTE</span>";
                                    }
                                    ?></td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    <?php } ?>
                  </div>
                </div>
              </div>
            </div>
            <div align="center">
              <a href="estudiante.php" class="btn btn-danger">Regresar</a>
                <a href="actualizar_informacion_academica.php?id=<?php echo $id_estudiante; ?>" class="btn btn-primary">
                    Actualizar Todas las Calificaciones
                </a>
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
    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
