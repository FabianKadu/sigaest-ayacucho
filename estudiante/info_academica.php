<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_estudiante.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
  $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
  $estudiante = mysqli_fetch_array($b_estudiante);
  $id_estudiante = $id_estudiante_sesion;
  /* OBTENER INFORMACIÓN NECESARIA DESDE LA DB */
  $res_estudiante = buscarEstudianteById($conexion, $id_estudiante);
  $estudiante=mysqli_fetch_array($res_estudiante);

  $res_programa = buscarCarrerasById($conexion, $estudiante['id_programa_estudios']);
  $programa=mysqli_fetch_array($res_programa);


  $semestre = "NO REGISTRADO";
  if($estudiante['id_semestre'] != 0){
    $res_semestre = buscarSemestreById($conexion, $estudiante['id_semestre']);
    $semestre=mysqli_fetch_array($res_semestre);
    $semestre = $semestre['descripcion'];
  }

  $res_modulos = buscarModulosByIdCarrera($conexion, $estudiante['id_programa_estudios']);


  function compararTexto($texto1, $texto2) {
    // Función para eliminar tildes y caracteres especiales y convertir a mayúsculas
    $textoLimpio = function($texto) {
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
  
        if(($unidad_didactica1) == ($unidad_didactica2)){
          $query = "UPDATE `notas_antiguo` SET `id_unidad_didactica`='$id_ud2' WHERE id = $id_ud1";
          mysqli_query($conexion,$query);
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
        if(compararTexto($unidad_didactica1, $unidad_didactica2) > 80){
        $query = "UPDATE `notas_antiguo` SET `id_unidad_didactica`='$id_ud2' WHERE id = $id_ud1";
        mysqli_query($conexion,$query); 
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
          include ("include/menu.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <!-- ENCABEZADO -->
                    <h4><b>INFORMACIÓN DEL ESTUDIANTE</b></h4><hr>
                    <div class="row">
                      <div class="col-md-6 col-sm-12 col-xs-12">
                       <span>ESTUDIANTE: <b> <?php echo $estudiante['apellidos_nombres'] ?> </b></span> <br>
                       <span>SEMESTRE ACTUAL: <b> <?php echo $semestre ?> </b></span> <br>
                       <span>AÑO DE INGRESO: <b> <?php echo $estudiante['anio_ingreso'] ?> </b></span>
                      </div>
                      <div class="col-md-6 col-sm-12 col-xs-12">
                        <span>PROGRAMA DE ESTUDIOS: <b> <?php echo $programa['nombre'] ?> </b></span><br>
                        <span>PLAN DE ESTUDIOS: <b> <?php echo $programa['plan_estudio'] ?> </b></span>
                      </div>
                    </div>
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
                  <div class="x_content">
                        <?php $res_semestres = buscarSemestre($conexion);
                        while($semestre=mysqli_fetch_array($res_semestres)){
                          $res_semestre = buscarSemestreById($conexion, $semestre['id']);
                          $sem=mysqli_fetch_array($res_semestre);
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
                              while($unidad_didactica=mysqli_fetch_array($res_unidad_didacticas)) {
                              ?>
                              <tr>
                                <td><?php echo $unidad_didactica['descripcion'];?></td>
                                <td><?php echo $unidad_didactica['tipo'];?></td>
                                <td><?php echo $unidad_didactica['creditos'];?></td>
                                <td>
                                  <?php 
                                  $res_calificacion = getNotasImportadaByDniAndIdUd($conexion, $estudiante['dni'], $unidad_didactica['id']);
                                  $count_calificacion = mysqli_num_rows($res_calificacion);
                                  $no_empty = false;
                                  $result = 0;
                                  $resultado = 0;
                                  if($count_calificacion == 1){
                                    $calificacion = mysqli_fetch_array($res_calificacion);
                                    $result = $calificacion['calificacion'];
                                    $no_empty = true;
                                  }
                                  $res_calificacion_matricula = getNotasMatriculaByDniAndIdUd($conexion, $unidad_didactica['id'], $estudiante['id']);
                                  if($res_calificacion_matricula){
                                    $count_calificacion_matricula = mysqli_num_rows($res_calificacion_matricula);
                                    if($count_calificacion_matricula == 1){
                                      $calificacion_matricula = mysqli_fetch_array($res_calificacion_matricula);
                                      $resultado = $calificacion_matricula['promedio'];
                                      $no_empty = true;
                                    }
                                  }
                                  $resultadomax = max($result, $resultado);
                                  if($no_empty){
                                    echo $resultadomax;
                                  }else{
                                    echo "-";
                                  }
                                  ?>
                                </td>
                                <td><?php 
                                if($no_empty){
                                  echo $calificacion['semestre_academico'];
                                }else{
                                  echo "-";
                                }
                                ?></td>
                                <td><?php 
                                if($no_empty && $result > 12){
                                  echo "<span style='color:green'>CUMPLIDO <i class='fa fa-check-circle-o'></i></span>";
                                }elseif($no_empty && $result < 13){
                                  echo "<span style='color:red'>POR RECUPERAR <i class='fa fa-times-circle-o'></i></span>";
                                }else{
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
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}
