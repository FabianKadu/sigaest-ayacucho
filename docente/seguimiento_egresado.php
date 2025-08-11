<?php
include ("../include/conexion.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_secretaria.php");

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


  function compararTexto($texto1, $texto2)
  {
    // Función para eliminar tildes y caracteres especiales y convertir a mayúsculas
    $textoLimpio = function ($texto) {
      // Reemplazar vocales tildadas con vocales no tildadas
      $texto = str_replace(
        ['á', 'é', 'í', 'ó', 'ú', 'Á', 'É', 'Í', 'Ó', 'Ú'],
        ['a', 'e', 'i', 'o', 'u', 'A', 'E', 'I', 'O', 'U'],
        $texto
      );
      $texto = mb_strtoupper($texto, 'UTF-8'); // Convertir a mayúsculas
      return trim($texto); // Eliminar espacios en blanco al inicio y al final
    };

    // Convertir los textos a cadenas limpias
    $texto1Limpio = $textoLimpio($texto1);
    $texto2Limpio = $textoLimpio($texto2);

    // Dividir los textos en palabras
    $palabrasTexto1 = explode(' ', $texto1Limpio);
    $palabrasTexto2 = explode(' ', $texto2Limpio);

    // Contadores para letras coincidentes y totales
    $palabrasCoincidentes = 0;
    $palabrasTotales = 0;

    // Comparar cada palabra de los textos
    foreach ($palabrasTexto1 as $indice => $palabra) {
      // Obtener las palabras correspondientes en ambos textos
      $palabraTexto1 = $palabra;
      $palabraTexto2 = isset($palabrasTexto2[$indice]) ? $palabrasTexto2[$indice] : '';

      // Si las palabras son iguales, aumentar el contador de palabras coincidentes
      if ($palabraTexto1 === $palabraTexto2) {
        $palabrasCoincidentes++;
      }
      $palabrasTotales++;
    }

    // Calcular el porcentaje de similitud
    $porcentajeSimilitud = ($palabrasCoincidentes / $palabrasTotales) * 100;

    if (count($palabrasTexto1) != count($palabrasTexto2)) {
      return $porcentajeSimilitud - 30;
    }
    return $porcentajeSimilitud;
  }

  $res_notas_importadas2 = getNotasImportadaByDniUpdate($conexion, $estudiante['dni']);
  $unidades_didacticas2 = buscarUdByPrograma($conexion, $estudiante['id_programa_estudios']);

  // Iterar sobre cada registro de res_notas_importadas
  while ($notas_importadas = mysqli_fetch_array($res_notas_importadas2)) {
    // Iterar sobre cada registro de unidades_didacticas
    $id_ud1 = $notas_importadas['id'];

    while ($unidad_didactica = mysqli_fetch_array($unidades_didacticas2)) {
      $id_ud2 = $unidad_didactica['id'];
      $unidad_didactica1 = $notas_importadas['unidad_didactica'];
      $unidad_didactica2 = $unidad_didactica['descripcion'];

      if (($unidad_didactica1) == ($unidad_didactica2)) {
        $query = "UPDATE `notas_antiguo` SET `id_unidad_didactica`='$id_ud2' WHERE id = $id_ud1";
        mysqli_query($conexion, $query);
      }

    }
    mysqli_data_seek($unidades_didacticas2, 0);
  }

  $res_notas_importadas = getNotasImportadaByDniUpdate($conexion, $estudiante['dni']);
  $unidades_didacticas = buscarUdByPrograma($conexion, $estudiante['id_programa_estudios']);

  // Iterar sobre cada registro de res_notas_importadas
  while ($notas_importadas = mysqli_fetch_array($res_notas_importadas)) {
    // Iterar sobre cada registro de unidades_didacticas
    $id_ud1 = $notas_importadas['id'];

    while ($unidad_didactica = mysqli_fetch_array($unidades_didacticas)) {
      $id_ud2 = $unidad_didactica['id'];
      $unidad_didactica1 = $notas_importadas['unidad_didactica'];
      $unidad_didactica2 = $unidad_didactica['descripcion'];
      if (compararTexto($unidad_didactica1, $unidad_didactica2) > 80) {
        $query = "UPDATE `notas_antiguo` SET `id_unidad_didactica`='$id_ud2' WHERE id = $id_ud1";
        mysqli_query($conexion, $query);
      }
    }
    mysqli_data_seek($unidades_didacticas, 0);
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

    <title>Estudiantes <?php include ("../include/header_title.php"); ?></title>
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
        include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div align="center">
                  <h2><b>Seguimiento Egresado</b></h2>
                </div>
                <div class="x_panel">
                  <div class="">
                    <!-- ENCABEZADO -->
                    <div>
                      <h4><b>Información académica</b></h4>
                    </div>
                    <hr>
                    <div class="row">
                      <div class="col-md-3 col-sm-12 col-xs-12">
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <img src=" ../img/no-image.jpeg" class="img-responsive" alt="Imagen 2">
                        </div>
                      </div>
                      <div class="col-md-4 col-sm-12 col-xs-12">
                        <span>ESTUDIANTE: <b> <?php echo $estudiante['apellidos_nombres'] ?> </b></span> <br><br />
                        <span>SEMESTRE ACTUAL: <b> <?php echo $semestre ?> </b></span> <br><br />
                        <span>AÑO DE INGRESO: <b> <?php echo $estudiante['anio_ingreso'] ?> </b></span><br><br />
                        <span>PROGRAMA DE ESTUDIOS: <b> <?php echo $programa['nombre'] ?> </b></span>
                      </div>
                      <div class="col-md-4 col-sm-12 col-xs-12">
                        <span>PLAN DE ESTUDIOS: <b> <?php echo $programa['plan_estudio'] ?> </b></span>
                      </div>
                    </div>
                    
                  </div>
                </div>
                <div class="x_panel">
                  <div class="">
                    <!-- ENCABEZADO -->
                    <div class="row">
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <!-- <div align="center"><h4><b>HISTÓRICO DE CALIFICACIONES </b></h4></div> -->
                      </div>
                    </div>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">


                    <div class="col-md-7 col-sm-12 col-xs-12">
                      <div class="x_panel">


                        <div align="center">
                          <h4><b>Información Básica</b></h4>
                          <hr style="width:40%">
                        </div>

                        <form role="form" id="myform" action="operaciones/actualizar_datos_egresado.php"
                          class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                          <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">

                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">DNI :
                            </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" oninput="validateInputNum(this, 8)" class="form-control" name="dni" id="dni" required=""
                                value="<?php echo $estudiante['dni']; ?>" placeholder="21548763">
                              <br>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">Nivel de formación : </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="nivel_formacion" id="nivel_formacion"
                                required="" value="Superior" placeholder="">
                              <br>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">Fecha de nacimiento : </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="fecha_nacimiento" id="fecha_nacimiento"
                                required="" value="<?php echo $estudiante['fecha_nac']; ?>">
                              <br>
                            </div>
                          </div>

                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">Correo electrónico : </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="correo" id="correo" required=""
                                value="<?php echo $estudiante['correo']; ?>">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">Teléfono : </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" oninput="validateInputNum(this, 9)" class="form-control" name="telefono" id="telefono" required=""
                                value="<?php echo $estudiante['telefono']; ?>" placeholder="987654321">
                              <br>
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-md-4 col-sm-3 col-xs-12">Dirección actual : </label>
                            <div class="col-md-8 col-sm-9 col-xs-12">
                              <input type="text" class="form-control" name="direccion" id="direccion" required=""
                                value="<?php echo $estudiante['direccion']; ?>" placeholder="Jr. ejemplo #123">
                              <br>
                            </div>
                          </div>

                          <div align="center">
                            <button type="submit" class="btn btn-primary" id="btn_guardar">Guardar</button>
                            <button type="button" class="btn btn-warning" id="btn_cancelar"
                              onclick="desactivar_controles(); cancelar();">Cancelar</button>
                          </div>
                        </form>
                        <div align="center">
                          <button type="button" class="btn btn-success" id="btn_editar"
                            onclick="activar_controles();">Editar
                            Datos</button>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-5 col-sm-12 col-xs-12">
                      <div class="x_panel">


                        <div class="col-md-12 col-sm-12 col-xs-12">
                          <a href="actividades_egresado.php?id=<?php echo $id_estudiante ?>"
                            class="btn btn-primary btn-block">
                            <i class="fa fa-eye"></i> Ver Actividades
                          </a>
                          <br>
                        </div>
                        <div class="col-md-12 col-sm-12 col-xs-12" align="center">
                          <h4><b>Actividades</b></h4>
                        </div>
                        <!-- <div class="col-md-6 col-sm-12 col-xs-12 text-right">
                          <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i
                              class="fa fa-plus-square"></i> Agregar actividad</button>
                        </div> -->
                        <ul class="list-unstyled timeline">
                          <?php
                          $res_actividad = buscarActividadesById($conexion, $id_estudiante);
                          while ($actividad = mysqli_fetch_array($res_actividad)) {
                            ?>
                            <li>
                              <div class="block">
                                <div class="tags">
                                  <a href="" class="tag" style="color: blue;">
                                    <span><?php echo $actividad['fecha_inicio'] ?></span>
                                  </a>
                                </div>
                                <div class="block_content">
                                  <h2 class="title">
                                    <a><b><?php echo $actividad['nombre_cargo']; ?></b></a>
                                  </h2>
                                  <span><?php echo $actividad['nombre_organizacion']; ?> -
                                    <?php echo $actividad['lugar']; ?></span>
                                  <div class="byline">
                                    <span>Tipo: <?php echo $actividad['tipo']; ?></span>
                                  </div>
                                  <p class="excerpt"><?php echo $actividad['descripcion']; ?></a>
                                  </p>
                                </div>
                              </div>
                            </li>
                          <?php } ?>
                        </ul>
                      </div>
                    </div>

                    <!--MODAL REGISTRAR-->
                    <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel" align="center">Registrar Actividad de Egresado</h4>
                          </div>
                          <div class="modal-body">
                            <!--INICIO CONTENIDO DE MODAL-->
                            <div class="x_panel">

                              <div class="" align="center">
                                <div class="clearfix"></div>
                              </div>
                              <div class="x_content">
                                <form role="form" action="operaciones/registrar_actividad_egresado.php"
                                  class="form-horizontal form-label-left input_mask" method="POST"
                                  enctype="multipart/form-data">

                                  <input type="hidden" class="form-control" value="<?php echo $estudiante['id']; ?>"
                                    name="id_estudiante" required="required">

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" name="tipo" value="" id="tipo" required="required">
                                        <option></option>
                                        <option value="Evento">Evento</option>
                                        <option value="Voluntariado">Voluntariado</option>
                                        <option value="Profesional">Profesional</option>
                                        <option value="Academica">Academica</option>
                                      </select>
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargo laboral: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="nombre_cargo" required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre organización: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="nombre_organizacion"
                                        required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Lugar: </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="text" class="form-control" name="lugar" required="required">
                                    </div>
                                  </div>

                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Descripción : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <textarea class="form-control" rows="3" style="width: 100%; height: 165px;"
                                        name="descripcion" required="required"></textarea>
                                    </div>
                                  </div>

                                  <div class="form-group ">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12 ">Fecha de inicio : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <input type="date" class="form-control" name="fecha" required="required">
                                    </div>
                                  </div>

                                  <div align="center">
                                    <button type="button" class="btn btn-default" data-dismiss="modal" onclick="desactivar_controles()">Cancelar</button>
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                  </div>
                                </form>

                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--FIN DE CONTENIDO DE MODAL-->

                  </div>
                </div>
              </div>
            </div>
          </div>
          <div align="center"><a href="egresados.php" class="btn btn-danger">Regresar</a></div>

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

    <script type="text/javascript">
      function desactivar_controles() {
        document.getElementById('dni').disabled = true
        document.getElementById('nivel_formacion').disabled = true
        document.getElementById('fecha_nacimiento').disabled = true
        document.getElementById('correo').disabled = true
        document.getElementById('telefono').disabled = true
        document.getElementById('direccion').disabled = true

        document.getElementById('btn_cancelar').style.display = 'none'
        document.getElementById('btn_guardar').style.display = 'none'
        document.getElementById('btn_editar').style.display = ''
      }
      function activar_controles() {
        document.getElementById('dni').disabled = false
        document.getElementById('nivel_formacion').disabled = false
        document.getElementById('fecha_nacimiento').disabled = false
        document.getElementById('correo').disabled = false
        document.getElementById('telefono').disabled = false
        document.getElementById('direccion').disabled = false

        document.getElementById('btn_cancelar').removeAttribute('style')
        document.getElementById('btn_guardar').removeAttribute('style')
        document.getElementById('btn_editar').style.display = 'none'
      }
      function cancelar() {
        document.getElementById('myform').reset();
      }
      desactivar_controles();
    </script>

    <script>
      function validateInputNum(input, tamanio) {
        // Obtén el valor actual del campo de entrada
        let inputValue = input.value;

        // Remueve cualquier carácter no permitido (en este caso, letras)
        inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
        inputValue = inputValue.slice(0, tamanio);
        inputValue = inputValue.replace(/-/g, '');
        // Actualiza el valor del campo de entrada
        input.value = inputValue.toUpperCase();
      }

      function validateInput(input) {
        // Obtén el valor actual del campo de entrada
        let inputValue = input.value;

        // Remueve cualquier carácter no permitido (en este caso, letras)
        inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
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

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
  <?php
}
