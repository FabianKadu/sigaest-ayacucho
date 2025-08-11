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
} else {

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);


  $id_sesion = $_GET['id'];
  $b_sesion = buscarSesionById($conexion, $id_sesion);
  $r_b_sesion = mysqli_fetch_array($b_sesion);
  $id_prog_act = $r_b_sesion['id_programacion_actividad_silabo'];
  // buscamos datos de la programacion de actividades
  $b_prog_act = buscarProgActividadesSilaboById($conexion, $id_prog_act);
  $r_b_prog_act = mysqli_fetch_array($b_prog_act);
  $id_silabo = $r_b_prog_act['id_silabo'];
  // buscamos datos de silabo
  $b_silabo = buscarSilaboById($conexion, $id_silabo);
  $r_b_silabo = mysqli_fetch_array($b_silabo);
  $id_prog = $r_b_silabo['id_programacion_unidad_didactica'];
  //buscamos datos de la programacion de unidad didactica
  $b_prog = buscarProgramacionById($conexion, $id_prog);
  $res_b_prog = mysqli_fetch_array($b_prog);

  $condicion = " ";
  $mostrar_acciones = true;

  if (!($res_b_prog['id_docente'] == $id_docente_sesion)) {
    $condicion = "readonly";
    $mostrar_acciones = false;
  }
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

    <title>Sesión de Aprendizaje<?php include("../include/header_title.php"); ?></title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>
    <!-- script para tags -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.css"
      rel="stylesheet" />


  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        $director = false;
        $unidad = false;
        if ($r_b_docente['id_cargo'] == 1 and $r_b_docente['carga_academica'] == 1) {
          $director = true;
        }

        if ($r_b_docente['id_cargo'] == 3 and $r_b_docente['carga_academica'] == 1) {
          $unidad = true;
        }

        if ($r_b_docente['id_cargo'] == 5 || $director || $unidad) {//si es docente
          include("include/menu_docente.php");
        } elseif ($r_b_docente['id_cargo'] == 4) {
          include("include/menu_coordinador.php");
        }elseif ($r_b_docente['id_cargo'] == 9) {
          include("include/menu_secretaria.php");
        }
        //buscar datos del periodo
        $b_periodo = buscarPeriodoAcadById($conexion, $res_b_prog['id_periodo_acad']);
        $r_b_periodo = mysqli_fetch_array($b_periodo);
        //buscar datos de docente
        $b_docente = buscarDocenteById($conexion, $res_b_prog['id_docente']);
        $r_b_docente = mysqli_fetch_array($b_docente);
        //buscar datos de unidad didactica
        $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
        $r_b_ud = mysqli_fetch_array($b_ud);
        //buscar programa de estudio
        $b_pe = buscarCarrerasById($conexion, $r_b_ud['id_programa_estudio']);
        $r_b_pe = mysqli_fetch_array($b_pe);
        //buscar modulo profesional
        $b_mod = buscarModuloFormativoById($conexion, $r_b_ud['id_modulo']);
        $r_b_mod = mysqli_fetch_array($b_mod);
        //buscar semestre
        $b_sem = buscarSemestreById($conexion, $r_b_ud['id_semestre']);
        $r_b_sem = mysqli_fetch_array($b_sem);
        //buscar capacidad de unidad didactica
        $b_capacidad = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
        $r_b_capacidad = mysqli_fetch_array($b_capacidad);
        //buscar competencias
        $b_competencia = buscarCompetenciasById($conexion, $r_b_capacidad['id_competencia']);
        $r_b_competencia = mysqli_fetch_array($b_competencia);


        ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center"><b>Sesión de Aprendizaje - Semana <?php echo $r_b_prog_act['semana']; ?> -
                        <?php echo $r_b_ud['descripcion']; ?></b></h2>
                    <form action="imprimir_sesion.php" method="GET" target="_blank"><input type="hidden" name="data"
                        value="<?php echo $id_sesion; ?>">
                      <button type="submit" class="btn btn-info">Imprimir</button>
                    </form>
                    <?php if ($mostrar_acciones) { ?>
                      <button class="btn btn-success" data-toggle="modal" data-target=".copiar">Copiar Información</button>
                    <?php } ?>
                    <a href="sesiones.php?id=<?php echo $id_prog; ?>" class="btn btn-danger">Regresar</a>

                    <!--MODAL COPIAR INFFORMACION-->
                    <div class="modal fade copiar" tabindex="-1" role="dialog" aria-hidden="true">
                      <div class="modal-dialog modal-lg">
                        <div class="modal-content">

                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                            </button>
                            <h4 class="modal-title" id="myModalLabel" align="center">Copiar Información de Sesión</h4>
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
                                <form role="form" action="operaciones/copiar_informacion_sesion.php" method="POST"
                                  class="form-horizontal form-label-left input_mask">
                                  <input type="hidden" name="myidactual" value="<?php echo $id_sesion; ?>">
                                  <div class="form-group">
                                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Copiar Sesión de : </label>
                                    <div class="col-md-9 col-sm-9 col-xs-12">
                                      <select class="form-control" id="sesion_copi" name="sesion_copi" value=""
                                        required="required">
                                        <option></option>
                                        <?php
                                        $b_prog_act_b = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
                                        while ($res_b_prog_act = mysqli_fetch_array($b_prog_act_b)) {
                                          // buscamos la sesion que corresponde
                                          $id_act = $res_b_prog_act['id'];
                                          $b_sesion_b = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                          $contar = 0;
                                          while ($res_b_sesion = mysqli_fetch_array($b_sesion_b)) {
                                            $contar += 1;
                                            ?>
                                            <option value="<?php echo $res_b_sesion['id']; ?>">
                                              <?php echo "Semana " . $res_b_prog_act['semana'] . " - Sesión " . $contar; ?>
                                            </option>
                                            <?php
                                          }
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

                    <!-- FIN MODAL COPIAR INFORMACION-->

                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br>

                    <form role="form" action="operaciones/actualizar_sesion.php"
                      class="form-horizontal form-label-left input_mask" method="POST">
                      <input type="hidden" name="id_sesion" value="<?php echo $id_sesion; ?>">
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered jambo_table bulk_action">
                          <thead>
                            <tr>

                              <th colspan="2">
                                <center>INFORMACIÓN GENERAL</center>
                              </th>

                            </tr>
                            <tr>

                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td width="30%">Docente a Cargo</td>
                              <td>: <?php echo $r_b_docente['apellidos_nombres']; ?></td>
                            </tr>
                            <tr>
                              <td>Periodo Académico</td>
                              <td>: <?php echo $r_b_periodo['nombre']; ?></td>
                            </tr>
                            <tr>
                              <td>Programa de Estudios</td>
                              <td>: <?php echo $r_b_pe['nombre']; ?></td>
                            </tr>
                            <tr>
                              <td>Competencia técnica o de especialidad</td>
                              <td>: <?php
                              if ($r_b_competencia['tipo_competencia'] == "ESPECÍFICA") {
                                echo $r_b_competencia['codigo'] . " - " . $r_b_competencia['descripcion'] . "<br>";
                              }
                              ?>
                              </td>
                            </tr>
                            <tr>
                              <td>Competencia para la empleabilidad</td>
                              <td>: <?php
                              if ($r_b_competencia['tipo_competencia'] == "EMPLEABILIDAD") {
                                echo $r_b_competencia['codigo'] . " - " . $r_b_competencia['descripcion'] . "<br>";
                              }
                              ?>
                              </td>
                            </tr>
                            <tr>
                              <td>Módulo Profesional</td>
                              <td>: <?php echo $r_b_mod['descripcion']; ?></td>
                            </tr>
                            <tr>
                              <td>Unidad Didáctica</td>
                              <td>: <?php echo $r_b_ud['descripcion']; ?></td>
                            </tr>
                            <tr>
                              <td>Capacidad</td>
                              <td>: <?php
                              $b_capacidad_a = buscarCapacidadesByIdUd($conexion, $res_b_prog['id_unidad_didactica']);
                              while ($r_b_capacidad_a = mysqli_fetch_array($b_capacidad_a)) {
                                echo $r_b_capacidad_a['codigo'] . " - " . $r_b_capacidad_a['descripcion'] . "<br>";
                              }

                              ?></td>
                            </tr>
                            <!--<tr>
                          <td>Indicador(es) de logro de competencia a la que se vincula</td>
                          <td>
                            <?php

                            ?>
                            <div clas="checkbox">
                                <label for="">
                                  <input type="checkbox">Para Revisar
                                </label>
                            </div>
                          </td>
                        </tr>-->
                            <tr>
                              <td>Tema o Actividad</td>
                              <td>
                                <textarea <?php echo $condicion; ?> style="min-width: 100%" name="ta_sesion" id=""
                                  cols="100" rows="2"
                                  class="form-control"><?php echo $r_b_sesion['ta_sesion']; ?></textarea>
                              </td>
                            </tr>
                            <tr>
                              <td>Actividades de tipo</td>
                              <td>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                  <select name="tipo_actividad" id="tipo_actividad" class="form-control" <?php echo $condicion; ?>>
                                    <option value=""></option>
                                    <option value="TEORICO" <?php if ($r_b_sesion['tipo_actividad'] == "TEORICO") {
                                      echo "selected";
                                    } ?>>TEORICO</option>
                                    <option value="PRÁCTICO" <?php if ($r_b_sesion['tipo_actividad'] == "PRÁCTICO") {
                                      echo "selected";
                                    } ?>>PRÁCTICO</option>
                                    <option value="TEORICO-PRÁCTICO" <?php if ($r_b_sesion['tipo_actividad'] == "TEORICO-PRÁCTICO") {
                                      echo "selected";
                                    } ?>>TEORICO-PRÁCTICO</option>
                                  </select>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>Tipo de sesión</td>
                              <td>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                  <select name="tipo_sesion" id="tipo_sesion" class="form-control" <?php echo $condicion; ?>>
                                    <option value=""></option>
                                    <option value="Presencial" <?php if ($r_b_sesion['tipo_sesion'] == "Presencial") {
                                      echo "selected";
                                    } ?>>Presencial</option>
                                    <option value="Virtual sincrónica" <?php if ($r_b_sesion['tipo_sesion'] == "Virtual sincrónica") {
                                      echo "selected";
                                    } ?>>Virtual sincrónica</option>
                                    <option value="Virtual Asincrónica" <?php if ($r_b_sesion['tipo_sesion'] == "Virtual Asincrónica") {
                                      echo "selected";
                                    } ?>>Virtual Asincrónica</option>
                                  </select>
                                </div>
                              </td>

                            </tr>
                            <tr>
                              <td>Fecha de Desarrollo</td>
                              <td>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                  <input <?php echo $condicion; ?> type="date" class="form-control"
                                    name="fecha_desarrollo" value="<?php echo $r_b_sesion['fecha_desarrollo']; ?>">
                                </div>
                              </td>

                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th colspan="2">
                                <center>PLANIFICACION DE APRENDIZAJE</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td width="20%">Indicador de Logro de Competencia a la que se vincula</td>
                              <td>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                  <select name="ind_logro_competencia" id="ind_logro_competencia" class="form-control"
                                    <?php echo $condicion; ?>>
                                    <option value=""></option>


                                    <?php
                                    $b_ind_log_comp = buscarIndicadorLogroCompetenciaByUd($conexion, $res_b_prog['id_unidad_didactica']);
                                    while ($r_b_ind_log_comp = mysqli_fetch_array($b_ind_log_comp)) {
                                      ?>
                                      <option value="<?php echo $r_b_ind_log_comp['id']; ?>" <?php if ($r_b_ind_log_comp['id'] == $r_b_sesion['id_ind_logro_competencia_vinculado']) {
                                           echo "selected";
                                         } ?>><?php echo $r_b_ind_log_comp['descripcion']; ?></option>

                                      <?php
                                    }

                                    ?>
                                  </select>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>Indicador de Logro de Capacidad a la que se vincula</td>
                              <td>
                                <div class="col-md-9 col-sm-9 col-xs-9">
                                  <select name="ind_logro_capacidad" id="ind_logro_capacidad" class="form-control" <?php echo $condicion; ?>>
                                    <option value=""></option>
                                    <?php
                                    $b_cap_ud = buscarCapacidadesByIdUd($conexion, $r_b_ud['id']);
                                    while ($r_b_cap_ud = mysqli_fetch_array($b_cap_ud)) {
                                      $b_ind_cap_ud = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $r_b_cap_ud['id']);
                                      while ($r_b_ind_cap_ud = mysqli_fetch_array($b_ind_cap_ud)) {
                                        ?>
                                        <option value="<?php echo $r_b_ind_cap_ud['id']; ?>" <?php if ($r_b_ind_cap_ud['id'] == $r_b_sesion['id_ind_logro_capacidad_vinculado']) {
                                             echo "selected";
                                           } ?>><?php echo $r_b_ind_cap_ud['descripcion']; ?></option>
                                        <?php
                                      }
                                    }
                                    ?>
                                  </select>
                                </div>
                              </td>
                            </tr>
                            <tr>
                              <td>Logro de Sesión</td>
                              <td>
                                <textarea <?php echo $condicion; ?> style="min-width: 100%" name="logro_sesion" id=""
                                  cols="100" rows="2"
                                  class="form-control"><?php echo $r_b_sesion['logro_sesion']; ?></textarea>
                                <!--<input type="text" name="logro_sesion" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['logro_sesion']; ?>">-->
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th colspan="4">
                                <center>Secuencia Didáctica</center>
                              </th>
                            </tr>
                            <tr>
                              <th>
                                <center>Momentos</center>
                              </th>
                              <th>
                                <center>Estrategias y actividades</center>
                              </th>
                              <th>
                                <center>Recursos didácticos</center>
                              </th>
                              <th>
                                <center>Tiempo (minutos)</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            //buscar momentos de la sesion
                            $b_momentos_sesion = buscarMomentosSesionByIdSesion($conexion, $id_sesion);
                            while ($r_b_momentos_sesion = mysqli_fetch_array($b_momentos_sesion)) {
                              ?>
                              <tr>
                                <td rowspan="2" width="15%">
                                  <div
                                    style=" box-sizing: border-box;width: 100%;height: 16em;display: flex;align-items: center;justify-content: center;">
                                    <div style="display: flex;"><?php echo $r_b_momentos_sesion['momento']; ?></div>
                                  </div>
                                </td>
                                <td width="45%">
                                  <label for="">Estrategias:</label><br>
                                  <textarea <?php echo $condicion; ?>
                                    name="estrategia_<?php echo $r_b_momentos_sesion['id']; ?>"
                                    style="width:100%; resize: none; height:auto;" class="form-control" id="" cols="50"
                                    rows="5"><?php echo $r_b_momentos_sesion['estrategia']; ?></textarea>
                                  <!--<input type="text" name="estrategia_<?php echo $r_b_momentos_sesion['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_momentos_sesion['estrategia']; ?>">-->
                                </td>
                                <td rowspan="2" width="20%">
                                  <textarea <?php echo $condicion; ?>
                                    name="recursos_<?php echo $r_b_momentos_sesion['id']; ?>"
                                    style="width:100%; resize: none; height:auto;" class="form-control" id="" cols="50"
                                    rows="5"><?php echo $r_b_momentos_sesion['recursos']; ?></textarea>
                                  <!--<input type="text" name="recursos_<?php echo $r_b_momentos_sesion['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_momentos_sesion['recursos']; ?>">-->
                                </td>
                                <td rowspan="2">
                                  <input <?php echo $condicion; ?> type="number"
                                    name="tiempo_<?php echo $r_b_momentos_sesion['id']; ?>" max="500" min="0"
                                    class="form-control" value="<?php echo $r_b_momentos_sesion['tiempo']; ?>"
                                    placeholder="Tiempo en Minutos">
                                </td>
                              </tr>
                              <tr>
                                <td>
                                  <label for="">Actividades:</label><br>
                                  <textarea <?php echo $condicion; ?>
                                    name="actividades_<?php echo $r_b_momentos_sesion['id']; ?>"
                                    style="width:100%; resize: none; height:auto;" class="form-control" id="" cols="50"
                                    rows="5"><?php echo $r_b_momentos_sesion['actividad']; ?></textarea>
                                  <!--<input type="text" name="actividades_<?php echo $r_b_momentos_sesion['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_momentos_sesion['actividad']; ?>">-->
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th colspan="5">
                                <center>ACTIVIDADES DE EVALUACIÓN</center>
                              </th>
                            </tr>
                            <tr>
                              <th width="30%">
                                <center>INDICADORES DE LOGRO DE SESIÓN</center>
                              </th>
                              <th width="15%">
                                <center>TÉCNICAS</center>
                              </th>
                              <th width="25%">
                                <center>INSTRUMENTOS</center>
                              </th>
                              <th width="15%">
                                <center>PESO O PORCENTAJE</center>
                              </th>
                              <th width="15%">
                                <center>MOMENTO</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <?php
                            //buscar actividades de evaluacion de la sesion 
                            $b_actividades_eval = buscarActividadesEvaluacionByIdSesion($conexion, $id_sesion);
                            while ($r_b_actividades_eval = mysqli_fetch_array($b_actividades_eval)) {
                              ?>
                              <tr>
                                <td>
                                  <center>
                                    <textarea <?php echo $condicion; ?>
                                      name="indicador_eva_<?php echo $r_b_actividades_eval['id']; ?>" id="" cols="30"
                                      rows="3" class="form-control"
                                      style="width:100%; resize: none; height:auto;"><?php echo $r_b_actividades_eval['indicador_logro_sesion']; ?></textarea>
                                    <!--<input type="text" name="indicador_eva_<?php echo $r_b_actividades_eval['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_actividades_eval['indicador_logro_sesion']; ?>">-->
                                  </center>
                                </td>
                                <td>
                                  <center>
                                    <textarea <?php echo $condicion; ?>
                                      name="tecnicas_eva_<?php echo $r_b_actividades_eval['id']; ?>" id="" cols="30"
                                      rows="3" class="form-control"
                                      style="width:100%; resize: none; height:auto;"><?php echo $r_b_actividades_eval['tecnica']; ?></textarea>
                                    <!--<input type="text" name="tecnicas_eva_<?php echo $r_b_actividades_eval['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_actividades_eval['tecnica']; ?>">-->
                                  </center>
                                </td>
                                <td>
                                  <center>
                                    <textarea <?php echo $condicion; ?>
                                      name="instrumentos_eva_<?php echo $r_b_actividades_eval['id']; ?>" id="" cols="30"
                                      rows="3" class="form-control"
                                      style="width:100%; resize: none; height:auto;"><?php echo $r_b_actividades_eval['instrumentos']; ?></textarea>
                                    <!--<input type="text" name="instrumentos_eva_<?php echo $r_b_actividades_eval['id']; ?>" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_actividades_eval['instrumentos']; ?>">-->
                                  </center>
                                </td>
                                <td>
                                  <center>
                                    <input <?php echo $condicion; ?> type="number"
                                      name="peso_eva_<?php echo $r_b_actividades_eval['id']; ?>" max="100" min="0"
                                      class="form-control" value="<?php echo $r_b_actividades_eval['peso']; ?>">
                                  </center>
                                </td>
                                <td>
                                  <center>
                                    <div
                                      style=" box-sizing: border-box;width: 100%;height: 6em;display: flex;align-items: center;justify-content: center;">
                                      <div style="display: flex;"><?php echo $r_b_actividades_eval['momento']; ?></div>
                                    </div>
                                  </center>
                                </td>
                              </tr>
                              <?php
                            }
                            ?>
                          </tbody>
                        </table>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-bordered jambo_table bulk_action">
                          <thead>
                            <tr>
                              <th colspan="2">
                                <center>BIBLIOGRAFÍA</center>
                              </th>
                            </tr>
                            <tr>
                              <th width="50%">
                                <center>PARA EL DOCENTE</center>
                              </th>
                              <th width="50%">
                                <center>PARA EL ESTUDIANTE</center>
                              </th>
                            </tr>
                          </thead>
                          <tbody>
                            <tr>
                              <td>
                                <label for="">Obligatoria :</label><br>
                                <textarea <?php echo $condicion; ?> name="bib_doc_oblig" id="" cols="30" rows="4"
                                  class="form-control"
                                  style="width:100%; resize: none; height:auto;"><?php echo $r_b_sesion['bibliografia_obligatoria_docente']; ?></textarea>
                                <!--<input type="text" name="bib_doc_oblig" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['bibliografia_obligatoria_docente']; ?>">-->
                              </td>
                              <td>
                                <label for="">Obligatoria :</label><br>
                                <textarea <?php echo $condicion; ?> name="bib_est_oblig" id="" cols="30" rows="4"
                                  class="form-control"
                                  style="width:100%; resize: none; height:auto;"><?php echo $r_b_sesion['bibliografia_obligatoria_estudiantes']; ?></textarea>
                                <!--<input type="text" name="bib_est_oblig" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['bibliografia_obligatoria_estudiantes']; ?>">-->
                              </td>
                            </tr>
                            <tr>
                              <td>
                                <label for="">Opcional :</label><br>
                                <textarea <?php echo $condicion; ?> name="bib_doc_opci" id="" cols="30" rows="4"
                                  class="form-control"
                                  style="width:100%; resize: none; height:auto;"><?php echo $r_b_sesion['bibliografia_opcional_docente']; ?></textarea>
                                <!--<input type="text" name="bib_doc_opci" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['bibliografia_opcional_estudiante']; ?>">-->
                              </td>
                              <td>
                                <label for="">Opcional :</label><br>
                                <textarea <?php echo $condicion; ?> name="bib_est_opci" id="" cols="30" rows="4"
                                  class="form-control"
                                  style="width:100%; resize: none; height:auto;"><?php echo $r_b_sesion['bibliografia_opcional_docente']; ?></textarea>
                                <!--<input type="text" name="bib_est_opci" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['bibliografia_opcional_estudiante']; ?>">-->
                              </td>
                            </tr>
                            <thead>
                              <tr>
                                <th colspan="2">
                                  <center>ANEXOS</center>
                              </tr>
                              </th>
                            </thead>
                            <tr>
                              <td colspan="2">
                                <textarea <?php echo $condicion; ?> name="anexos" cols="30" rows="4" class="form-control"
                                  style="width:100%; resize: none; height:auto;"><?php echo $r_b_sesion['anexos']; ?></textarea>

                                <!--<input type="text" name="anexos" class="bootstrap-tagsinput form-control" data-role="tagsinput" placeholder="Agregar+" value="<?php echo $r_b_sesion['anexos']; ?>">-->
                              </td>
                            </tr>
                          </tbody>
                        </table>
                      </div>



                      <div align="center">
                        <br>
                        <br>
                        <?php if ($mostrar_acciones) { ?>
                          <a href="sesiones.php?id=<?php echo $id_prog; ?>" class="btn btn-danger">Regresar</a>
                          <button type="submit" class="btn btn-success">Guardar</button>
                        <?php } ?>
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

    <!-- para tags -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-tagsinput/0.8.0/bootstrap-tagsinput.js"></script>

    <script>
      $(document).ready(function () {
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