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

  // Obtener el DNI del estudiante
  $estudiante_dni = $estudiante['dni'];


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

    <!-- PNotify -->
    <link href="../Gentella/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">

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
                  <form>
                    <input type="hidden" id="dni_estudiante" value="<?php echo $estudiante_dni; ?>">
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
                                <th width="20%">ESTADO</th>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              $res_unidad_didacticas = buscarUdByCarSem($conexion, $estudiante['id_programa_estudios'], $semestre['id']);
                              while ($unidad_didactica = mysqli_fetch_array($res_unidad_didacticas)) {
                              ?>
                              <?php
                                // Inicialización de variables
                                $periodo = "-";
                                $result = 0;
                                $resultado = 0;
                                $no_empty = $matriculado = false;

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
                                    $matriculado = true;
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

                                $is_edit = "";
                                // Calcular la calificación más alta entre la importada y la final
                                if ($matriculado) $is_edit = "readonly";

                                $resultadomax = max($result, $resultado);
                                $result = round($resultadomax);

                                // Mostrar resultado y permitir edición si es aplicable
                                echo "<tr>";
                                echo "<td>{$unidad_didactica['descripcion']}</td>";
                                echo "<td>{$unidad_didactica['tipo']}</td>";
                                echo "<td>{$unidad_didactica['creditos']}</td>";

                                // Campo de calificación
                                echo "<td><input type='number' min='0' max='20' id='calificacion_{$unidad_didactica['id']}' value='" . ($result > 0 ? $result : '-') . "' onchange='actualizarCalificacion({$unidad_didactica['id']}, this.value)' {$is_edit}></td>";

                                // Campo de periodo
                                echo "<td><input type='text' id='periodo_{$unidad_didactica['id']}' value='{$periodo}' onchange='actualizarPeriodo({$unidad_didactica['id']}, this.value)' {$is_edit}></td>";

                                // Estado
                                if ($result > 12) {
                                  echo "<td><span style='color:green'>CUMPLIDO <i class='fa fa-check-circle-o'></i></span></td>";
                                } elseif ($result < 13 && $result > 0) {
                                  echo "<td><span style='color:red'>POR RECUPERAR <i class='fa fa-times-circle-o'></i></span></td>";
                                } else {
                                  echo "<td><span><i class='fa fa-ellipsis-h'></i> PENDIENTE</span></td>";
                                }

                                echo "</tr>";
                              }
                              ?>
                            </tbody>
                          </table>
                        </div>
                      <?php } ?>
                    </div>
                  </form>
                </div>
              </div>
              <div align="center">
                <a href="<?php echo "informacion_academica.php?id=".$id_estudiante ?>" class="btn btn-danger">Regresar</a>
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

    <!-- PNotify -->
    <script src="../Gentella/vendors/pnotify/dist/pnotify.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.nonblock.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
    <?php mysqli_close($conexion); ?>

    <script>
      // Función para actualizar la calificación
      function actualizarCalificacion(id_ud, nuevaCalificacion) {
        var dni_estudiante = $('#dni_estudiante').val(); // Obtener el DNI del estudiante

        // Validación: comprobar si la calificación está entre 0 y 20
        if (nuevaCalificacion < 0 || nuevaCalificacion > 20 || isNaN(nuevaCalificacion)) {
          // Mostrar notificación de advertencia si la calificación no está en el rango correcto
          new PNotify({
            title: 'Advertencia',
            text: "La calificación debe estar entre 0 y 20.",
            type: 'warning',
            styling: 'bootstrap3'
          });
          return; // No ejecutar el AJAX si la validación falla
        }

        // Si la calificación es válida, proceder con la solicitud AJAX
        $.ajax({
          url: 'operaciones/actualizar_calificacion_importada.php', // Archivo PHP que procesará la actualización
          method: 'POST',
          data: {
            id_ud: id_ud,
            calificacion: nuevaCalificacion,
            dni: dni_estudiante // Enviar también el DNI
          },
          success: function(response) {
            // Mostrar notificación de éxito
            new PNotify({
              title: 'Exitoso',
              text: "Se ha realizado los cambios de manera correcta, recuerde actualizar la pagina para ver los cambios en el estado.",
              type: 'success',
              styling: 'bootstrap3'
            });
          },
          error: function(xhr, status, error) {
            // Mostrar notificación de error
            new PNotify({
              title: 'Error',
              text: "No se ha realizado los cambios",
              type: 'error',
              styling: 'bootstrap3'
            });
          }
        });
      }
    </script>

    <script>
      // Función para actualizar el periodo
      function actualizarPeriodo(id_ud, nuevoPeriodo) {
        var dni_estudiante = $('#dni_estudiante').val(); // Obtener el DNI del estudiante

        $.ajax({
          url: 'operaciones/actualizar_periodo_importada.php', // Archivo PHP que procesará la actualización
          method: 'POST',
          data: {
            id_ud: id_ud,
            periodo: nuevoPeriodo,
            dni: dni_estudiante // Enviar también el DNI
          },
          success: function(response) {
            if (response.error) {
              new PNotify({
                title: 'Error',
                text: "Registre la calificación, luego actualice el periodo.",
                type: 'error',
                styling: 'bootstrap3'
              });
            } else {
              new PNotify({
                title: 'Exitoso',
                text: "Se ha realizado los cambios de manera correcta",
                type: 'success',
                styling: 'bootstrap3'
              });
            }

          },
          error: function(xhr, status, error) {}
        });
      }
    </script>

    <script>
      function mostrarAlerta(tipo, mensaje) {
        var alerta = $('#alerta'); // Contenedor de la alerta
        var mensajeAlerta = $('#mensaje-alerta'); // Elemento donde se mostrará el mensaje

        // Limpiar cualquier clase de alerta anterior
        alerta.removeClass('alert-success alert-warning alert-danger');

        // Dependiendo del tipo, asignar la clase de Bootstrap adecuada
        if (tipo === 'success') {
          alerta.addClass('alert-success'); // Éxito
        } else if (tipo === 'warning') {
          alerta.addClass('alert-warning'); // Advertencia
        } else {
          alerta.addClass('alert-danger'); // Error genérico
        }

        // Establecer el mensaje y mostrar la alerta
        mensajeAlerta.html(mensaje);
        alerta.show(); // Mostrar la alerta
      }
    </script>

  </body>

  </html>
<?php
}
