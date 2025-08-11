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
                                    <table id="" class="table table-striped table-bordered jambo_table bulk_action" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="15" bgcolor="black">
                                                    <center>CALIFICACIONES - UNIDADES DIDÁCTICAS</center>
                                                </th>
                                            </tr>

                                            <tr>
                                                <td>UNIDAD DIDÁCTICA</td>
                                                <?php
                                                for ($i = 1; $i <= 12; $i++) {
                                                    echo "<td><center>I.L. " . $i . "</center></td>";
                                                }
                                                ?>
                                                <td>
                                                    <center>RECUPERACIÓN</center>
                                                </td>
                                                <td>
                                                    <center>PROMEDIO</center>
                                                </td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            //buscar estudiante para su id
                                            $b_est = buscarEstudianteById($conexion, $id_estudiante_sesion);
                                            $r_b_est = mysqli_fetch_array($b_est);

                                            $b_ud_pe_sem = buscarUdByCarSem($conexion, $r_b_est['id_programa_estudios'], $r_b_est['id_semestre']);
                                            $min_ud_desaprobar = round(mysqli_num_rows($b_ud_pe_sem) / 2, 0, PHP_ROUND_HALF_DOWN);

                                            ?>

                                            <?php
                                            //buscar si estudiante esta matriculado en una unidad didactica
                                            $suma_califss = 0;
                                            $suma_ptj_creditos = 0;
                                            $cont_ud_desaprobadas = 0;
                                            $b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
                                            while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
                                            ?>
                                                <tr>
                                                    <?php
                                                    $b_prog = buscarProgramacionById($conexion, $r_b_det_mat['id_programacion_ud']);
                                                    $r_b_prog = mysqli_fetch_array($b_prog);
                                                    $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
                                                    $r_bb_ud = mysqli_fetch_array($b_ud);

                                                    $id_det_mat = $r_b_det_mat['id'];


                                                    echo "<td>" . $r_bb_ud['descripcion'] . "</td>";

                                                    //buscar las calificaciones
                                                    $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);

                                                    $suma_calificacion = 0;
                                                    $cont_calif = 0;
                                                    $total_il = mysqli_num_rows($b_calificaciones);
                                                    while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {

                                                        $id_calificacion = $r_b_calificacion['id'];
                                                        //buscamos las evaluaciones
                                                        $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);

                                                        if ($suma_evaluacion != 0 && $r_b_calificacion['mostrar_calificacion'] == 1) {
                                                            $cont_calif += 1;
                                                            $suma_calificacion += $suma_evaluacion;
                                                            $suma_evaluacion = round($suma_evaluacion);
                                                            if ($suma_evaluacion > 12) {
                                                                echo '<td><center><font color="blue">' . $suma_evaluacion . '</font></center></td>';
                                                            } else {
                                                                echo '<td><center><font color="red">' . $suma_evaluacion . '</font></center></td>';
                                                            }
                                                        } else {
                                                            $suma_evaluacion = "";
                                                            echo '<td></td>';
                                                        }
                                                    }
                                                    if ($total_il < 12) {
                                                        for ($i = $total_il; $i < 12; $i++) {
                                                            echo "<td></td>";
                                                        }
                                                    }
                                                    if ($r_b_det_mat['mostrar_calificacion'] == 0) {
                                                        echo '<td></td><td  bgcolor="#BEBBBB"></td>';
                                                    } else {
                                                        # code...

                                                        if ($cont_calif > 0) {
                                                            $calificacion = round($suma_calificacion / $cont_calif);
                                                        } else {
                                                            $calificacion = round($suma_calificacion);
                                                        }
                                                        if ($calificacion != 0) {
                                                            $calificacion = round($calificacion);
                                                        } else {
                                                            $calificacion = "";
                                                        }
                                                        //buscamos si tiene recuperacion
                                                        if ($r_b_det_mat['recuperacion'] != '') {
                                                            $calificacion = $r_b_det_mat['recuperacion'];
                                                            echo '<td align="center">' . $r_b_det_mat['recuperacion'] . '</td>';
                                                        } else {
                                                            echo '<td align="center">' . $r_b_det_mat['recuperacion'] . '</td>';
                                                        }
                                                        if ($calificacion > 12) {
                                                            echo '<td align="center" bgcolor="#BEBBBB"><font color="blue">' . $calificacion . '</font></td>';
                                                        } else {
                                                            echo '<td align="center" bgcolor="#BEBBBB"><font color="red">' . $calificacion . '</font></td>';
                                                            $cont_ud_desaprobadas += 1;
                                                        }
                                                    }
                                                    ?>
                                                </tr>
                                            <?php
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                            
                                </div>

                            </form>
                            <?php
                            ?>
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
