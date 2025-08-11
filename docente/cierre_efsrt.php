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
  $id_efsrt = $_GET['id'];
  $res_efsrt = buscarEfsrtById($conexion, $id_efsrt);
  $efsrt = mysqli_fetch_array($res_efsrt);

  $res_estudiante = buscarEstudianteById($conexion, $efsrt['id_estudiante']);
  $estudiante = mysqli_fetch_array($res_estudiante);

  $res_pr = buscarCarrerasById($conexion, $efsrt['id_programa']);
  $programa = mysqli_fetch_array($res_pr);

  $res_modulo = buscarModuloFormativoById($conexion, $efsrt['id_modulo']);
  $modulo = mysqli_fetch_array($res_modulo);

  $res_tutor = buscarDocenteById($conexion, $efsrt['id_docente']);
  $tutor = mysqli_fetch_array($res_tutor);

  ?>
  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>EFSRT<?php include("../include/header_title.php"); ?></title>
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
    <!-- Script obtenido desde CDN jquery -->
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
      crossorigin="anonymous"></script>


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



            <div class="row">
              <div class="">
                <br><br>
                <h2 align="center">Cierre de EFSRT</h2>
                <div class="clearfix"></div>
                <br>
              </div>
              <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <br />

                    <div class="form-group">
                      <label class="control-label">DNI: </label>
                      <br>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input class="form-control" type="number" name="dni_est" id="dni_est"
                          value="<?php echo $estudiante['dni'] ?>" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Alumno: </label>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="hidden" id="id_est" name="id_est">
                        <input type="hidden" id="id_pe" name="id_pe">
                        <input type="hidden" id="id_sem" name="id_sem">
                        <input class="form-control" type="text" name="estudiante" id="estudiante"
                          value="<?php echo $estudiante['apellidos_nombres'] ?>" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Programa: </label>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control" id="carrera_m" name="carrera_m" required="required"
                          value="<?php echo $programa['nombre'] ?>" readonly />
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Módulo: </label>
                      <div class="col-md-12 col-sm-12 col-xs-12">
                        <input type="text" class="form-control" id="modulo" name="modulo" required="required"
                          value="<?php echo $modulo['descripcion'] ?>" readonly>
                      </div>
                    </div>
                    <div>
                      <label class="control-label">Documentos: </label>
                      <br>
                      <a href="<?php echo $efsrt['carta_presentacion'] ?>" target="_blank"><strong>CARTA DE
                          PRESENTACIÓN</strong></a>
                      <br>
                      <a href="<?php echo $efsrt['informe'] ?>" target="_blank"><strong>INFORME DE EFSRT</strong></a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="col-md-6 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                    <div class="form-group">
                      <label class="control-label">Lugar de experiencia : </label>
                      <div class="col-md-12">
                        <input type="text" class="form-control" name="lugar" value="<?php echo $efsrt['lugar'] ?>"
                          required="required" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Cargo de la persona que aceptará la carta: </label>
                      <div class="col-md-12">
                        <input type="text" class="form-control" name="cargo"
                          value="<?php echo $efsrt['cargo_responsable'] ?>" required="required" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Nombres y apellidos de responsable: </label>
                      <div class="col-md-12">
                        <input type="text" class="form-control" name="responsable"
                          value="<?php echo $efsrt['responsable'] ?>" required="required" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Fecha de inicio: </label>
                      <div class="col-md-12">
                        <input type="date" class="form-control" name="inicio" value="<?php echo $efsrt['fecha_inicio'] ?>"
                          required="required" readonly>
                      </div>
                    </div>
                    <div class="form-group">
                      <label class="control-label">Docente tutor: </label>
                      <div class="col-md-12">
                        <input type="text" class="form-control" name="tutor"
                          value="<?php echo $tutor['apellidos_nombres'] ?>" required="required" readonly>
                      </div>
                    </div>
                    <form role="form" id="myform" action="operaciones/aceptar_efsrt.php"
                      class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                      <!-- datos extra para los pdfs -->
                      <?php
                      $datos_sistema = buscarDatosSistema($conexion);
                      $sistema = mysqli_fetch_array($datos_sistema);
                      ?>
                      <input type="hidden" name="nombre_iestp" value="<?php echo $sistema['titulo']; ?>">
                      <input type="hidden" name="ruc" value="<?php echo $sistema['ruc']; ?>">
                      <input type="hidden" name="nombre_estudiante"
                        value="<?php echo $estudiante['apellidos_nombres']; ?>">
                      <input type="hidden" name="dni_estudiante" value="<?php echo $estudiante['dni']; ?>">
                      <input type="hidden" name="programa_estudios" value="<?php echo $programa['nombre']; ?>">
                      <input type="hidden" name="modulo_formativo" value="<?php echo $modulo['descripcion']; ?>">
                      <input type="hidden" name="fecha_inicio" value="<?php echo $efsrt['fecha_inicio']; ?>">
                      <input type="hidden" name="region" value="<?php echo $sistema['region']; ?>">
                      <input type="hidden" name="id_modulo" value="<?php echo $efsrt['id_modulo']; ?>">
                      <input type="hidden" name="id_programa" value="<?php echo $efsrt['id_programa']; ?>">

                      <!-- fin datos extra para pdf -->

                      <input type="hidden" name="id" value="<?php echo $efsrt['id']; ?>">
                      <div class="form-group">
                        <label class="control-label">Periodo Académico : </label>
                        <div class="row">
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php $anio = date("Y") - 2; ?>
                            <select class="form-control" name="anio" id="anio" required>
                              <option value=""></option>
                              <?php for ($i = 0; $i <= 4; $i++) { ?>
                                <option value="<?php echo $anio + $i; ?>"><?php echo $anio + $i; ?></option>
                              <?php } ?>
                            </select>
                          </div>
                          <div class="col-md-6 col-sm-6 col-xs-6">
                            <?php $anio = date("Y") - 2; ?>
                            <select class="form-control" name="per" id="per" required>
                              <option value=""></option>
                              <option value="I">I</option>
                              <option value="II">II</option>
                            </select>
                          </div>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Acto resolutivo: </label>
                        <div class="col-md-12">
                          <input type="file" class="form-control" name="file_resolucion" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Resolución: </label>
                        <div class="col-md-12">
                          <input type="text" class="form-control" name="resolucion" required="required">
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label">Calificación: </label>
                        <div class="col-md-12">
                          <input type="number" class="form-control" name="calificacion" required="required" max="20"
                            min="0">
                        </div>
                      </div>
                      <div class="form-group text-right">
                        <button data-toggle="modal" data-target=".observar_<?php echo $efsrt['id'] ?>"
                          class="btn btn-danger">Observar</button>
                        <input type="submit" class="btn btn-success" value="Finalizar e imprimir constancia" />
                      </div>

                    </form>
                  </div>
                </div>
                <div class="modal fade observar_<?php echo $efsrt['id'] ?>" tabindex="-1" role="dialog"
                  aria-hidden="true">
                  <div class="modal-dialog modal-md">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel" align="center">Observar EFSRT</h4>
                      </div>
                      <div class="modal-body">
                        <!--INICIO CONTENIDO DE MODAL-->
                        <div class="x_panel">

                          <div class="" align="center">
                            <h2></h2>
                            <div class="clearfix"></div>
                          </div>
                          <div class="x_content">
                            <form role="form" action="operaciones/observar_efrst.php"
                              class="form-horizontal form-label-left input_mask" method="POST"
                              enctype="multipart/form-data">
                              <input type="hidden" name="id" value="<?php echo $efsrt['id']; ?>">
                              <div class="form-group">
                                <label class="control-label">Detalle de observación: </label>
                                <div class="col-md-12">
                                  <textarea class="form-control" name="observacion" required="required"
                                    placeholder="Escriba la observación" rows="7"></textarea>
                                </div>
                              </div>
                              <div align="center">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                <input type="submit" class="btn btn-primary" value="Aceptar">
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              </form>
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
    <!--script para obtener los datos dependiendo del dni-->
    <?php mysqli_close($conexion); ?>
  </body>

  </html>
  <?php
}
