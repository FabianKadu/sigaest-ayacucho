<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_estudiante.php';
if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

    $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
    $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
    $r_b_estudiante = mysqli_fetch_array($b_estudiante);

    $per_select = $_SESSION['periodo'];
    $id_pro_ud = $_GET['id'];

    //buscar matricula de estudiante
    $b_mat = buscarMatriculaByEstudiantePeriodo($conexion, $id_estudiante_sesion, $per_select);
    $r_b_mat = mysqli_fetch_array($b_mat);
    $id_mat_est = $r_b_mat['id'];

    $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
    $r_b_estudiante = mysqli_fetch_array($b_estudiante);

?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Calificaciones<?php include("../include/header_title.php"); ?></title>
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

        <style>
            p.verticalll {
                /* idéntico a rotateZ(45deg); */

                writing-mode: vertical-lr;
                transform: rotate(180deg);

            }

            .nota_input {
                width: 3em;
            }
        </style>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <?php
                include("include/menu.php"); ?>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form role="form" action="" class="form-horizontal form-label-left input_mask" method="POST">
                                <div class="table-responsive">
                                    
                                    <table class="table table-striped table-bordered jambo_table bulk_action" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th bgcolor="black" colspan="36">
                                                    <center>ASISTENCIA</center>
                                                </th>
                                            </tr>
                                            <tr>
                                                <?php
                                                $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_pro_ud);
                                                $r_b_silabo = mysqli_fetch_array($b_silabo);
                                                $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);

                                                while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                                // buscamos la sesion que corresponde
                                                $id_act = $res_b_prog_act['id'];
                                                $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                                while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                                    $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $r_b_mat['id_estudiante']);
                                                    $r_b_asistencia = mysqli_fetch_array($b_asistencia);
                                                ?>
                                                    <th>
                                                    <p class="verticalll" id=""><?php echo $r_b_sesion['fecha_desarrollo']; ?></p>
                                                    </th>
                                                <?php
                                                }
                                                }
                                                ?>
                                                <th><p class="verticalll">Inasistencia</p></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                $b_prog_ud = buscarProgramacionById($conexion, $id_pro_ud);
                                                $r_b_prog = mysqli_fetch_array($b_prog_ud);

                                                $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
                                                $r_b_ud = mysqli_fetch_array($b_ud);


                                                $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_pro_ud);
                                                $r_b_silabo = mysqli_fetch_array($b_silabo);
                                                $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
                                                echo "<tr>";
                                                $cont_inasistencia = 0;
                                                $cont_asis = 0;
                                                while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                                    // buscamos la sesion que corresponde
                                                    $id_act = $res_b_prog_act['id'];
                                                    $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                                    while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                                        $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $id_estudiante_sesion);
                                                        $r_b_asistencia = mysqli_fetch_array($b_asistencia);
                                                        $cont_asis += mysqli_num_rows($b_asistencia);

                                                        if ($r_b_asistencia['asistencia'] == "P") {
                                                            echo "<td><center><font color='blue'>" . $r_b_asistencia['asistencia'] . "</font></center></td>";
                                                        } elseif ($r_b_asistencia['asistencia'] == "F") {
                                                            echo "<td><center><font color='red'>" . $r_b_asistencia['asistencia'] . "</font></center></td>";
                                                            $cont_inasistencia += 1;
                                                        } else {
                                                            echo "<td></td>";
                                                        }
                                                    }
                                                }
                                                if ($cont_inasistencia > 0) {
                                                    $porcent_ina = $cont_inasistencia * 100 / $cont_asis;
                                                } else {
                                                    $porcent_ina = 0;
                                                }
                                                if (round($porcent_ina) > 29) {
                                                    echo "<td><font color='red'><center>" . round($porcent_ina) . "%</font></center></td>";
                                                } else {
                                                    echo "<td><font color='blue'><center>" . round($porcent_ina) . "%</font></center></td>";
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                    <div>
                                        <center><b>
                                            <h5><?php echo $r_b_ud["descripcion"]; ?></h5></b>
                                        </center>
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
        <!-- Chart.js -->
        <script src="../Gentella/vendors/Chart.js/dist/Chart.min.js"></script>
        <!-- gauge.js -->
        <script src="../Gentella/vendors/gauge.js/dist/gauge.min.js"></script>
        <!-- bootstrap-progressbar -->
        <script src="../Gentella/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js"></script>
        <!-- iCheck -->
        <script src="../Gentella/vendors/iCheck/icheck.min.js"></script>
        <!-- Skycons -->
        <script src="../Gentella/vendors/skycons/skycons.js"></script>
        <!-- Flot -->
        <script src="../Gentella/vendors/Flot/jquery.flot.js"></script>
        <script src="../Gentella/vendors/Flot/jquery.flot.pie.js"></script>
        <script src="../Gentella/vendors/Flot/jquery.flot.time.js"></script>
        <script src="../Gentella/vendors/Flot/jquery.flot.stack.js"></script>
        <script src="../Gentella/vendors/Flot/jquery.flot.resize.js"></script>
        <!-- Flot plugins -->
        <script src="../Gentella/vendors/flot.orderbars/js/jquery.flot.orderBars.js"></script>
        <script src="../Gentella/vendors/flot-spline/js/jquery.flot.spline.min.js"></script>
        <script src="../Gentella/vendors/flot.curvedlines/curvedLines.js"></script>
        <!-- DateJS -->
        <script src="../Gentella/vendors/DateJS/build/date.js"></script>
        <!-- JQVMap -->
        <script src="../Gentella/vendors/jqvmap/dist/jquery.vmap.js"></script>
        <script src="../Gentella/vendors/jqvmap/dist/maps/jquery.vmap.world.js"></script>
        <script src="../Gentella/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js"></script>
        <!-- bootstrap-daterangepicker -->
        <script src="../Gentella/vendors/moment/min/moment.min.js"></script>
        <script src="../Gentella/vendors/bootstrap-daterangepicker/daterangepicker.js"></script>

        <!-- Custom Theme Scripts -->
        <script src="../Gentella/build/js/custom.min.js"></script>

    </body>

    </html>
<?php }
