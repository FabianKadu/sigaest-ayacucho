<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_coordinador.php';
if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
    $r_b_docente = mysqli_fetch_array($b_docente);

    $id_est = $_POST['id'];
    $per_select = $_SESSION['periodo'];

    //buscar matricula de estudiante
    $b_mat = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $per_select);
    $r_b_mat = mysqli_fetch_array($b_mat);
    $id_mat_est = $r_b_mat['id'];

    $b_estudiante = buscarEstudianteById($conexion, $id_est);
    $r_b_estudiante = mysqli_fetch_array($b_estudiante);



    $b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
    $cant_ud_mat = mysqli_num_rows($b_det_mat);
    $cont_ind_capp = 0;
    while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
        $id_prog = $r_b_det_mat['id_programacion_ud'];
        $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
        $r_b_prog = mysqli_fetch_array($b_prog_ud);
        $id_udd = $r_b_prog['id_unidad_didactica'];
        //BUSCAR UD
        $b_uddd = buscarUdById($conexion, $id_udd);
        $r_b_udd = mysqli_fetch_array($b_uddd);
        //buscar capacidad
        $cont_ind_logro_cap_ud = 0;
        $b_cap_ud = buscarCapacidadesByIdUd($conexion, $id_udd);
        while ($r_b_cap_ud = mysqli_fetch_array($b_cap_ud)) {
            $id_cap_ud = $r_b_cap_ud['id'];
            // buscar indicadores de capacidad
            $b_ind_l_cap_ud = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_cap_ud);
            $cant_id_cap_ud = mysqli_num_rows($b_ind_l_cap_ud);
            $cont_ind_logro_cap_ud += $cant_id_cap_ud;
        }
        $cont_ind_capp += $cont_ind_logro_cap_ud;
    }
    $total_columnas = $cont_ind_capp + $cant_ud_mat + 3;
    ?>
    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <!-- Meta, title, CSS, favicons, etc. -->
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Reportes<?php include("../include/header_title.php"); ?></title>
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
        <script src="https://code.jquery.com/jquery-3.6.0.js"
            integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
        <style>
            p.verticalll {
                /* idéntico a rotateZ(45deg); */

                writing-mode: vertical-lr;
                transform: rotate(180deg);
            }

            table {
                width: 100%;
                border-collapse: collapse;
            }


            /* Fijar las primeras dos columnas */
            tbody td:nth-child(1),
            tbody td:nth-child(2) {
                position: sticky;
                left: 0;
                background-color: #fff;
                /* Fondo para que no se vea afectado por el scroll */
                z-index: 100;
                /* Asegurarse que las columnas fijas queden encima del resto */
            }

            /* Ajustar las columnas que se desplazan para no superponer las fijas */
            tbody td:nth-child(2) {
                left: 70px;
            }
        </style>

        <style>
            @media print {

                /* Ocultar el botón de imprimir y otros elementos no deseados */
                #printButton,
                .btn-danger,
                .no-print,
                .menu_coordinador,
                .footer,
                .header_title {
                    display: none;
                }

                /* Eliminar la URL de la página en la parte inferior y superior */
                @page {
                    size: A4 landscape;
                    margin: 0;
                }

                body {
                    margin: 1cm;
                    transform: scale(0.8);
                    /* Reducir el tamaño del contenido */
                    transform-origin: top left;
                    /* Asegurar que el contenido se mantenga en la parte superior izquierda */
                }

                /* Asegurarse de que todo el contenido se imprima */
                html,
                body {
                    height: auto;
                    width: 100%;
                }

                /* Ajustar el tamaño de la tabla para que se ajuste a la página */
                .table-responsive {
                    width: 100%;
                    overflow: visible;
                }

                .table {
                    width: 100%;
                    /* table-layout: fixed; */
                }

                .table th,
                .table td {
                    word-wrap: break-word;
                }
            }
        </style>

    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <div class="menu_coordinador no-print">
                    <?php
                    include("include/menu_coordinador.php"); ?>
                </div>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <form role="form" action="imprimir_reporte_individual.php"
                                class="form-horizontal form-label-left input_mask" method="POST" target="_blank">
                                <input type="hidden" name="id_est" value="<?php echo $id_est; ?>">
                                <button type="submit" class="btn btn-info"><i class="fa fa-print"></i> Imprimir
                                    Reporte</button>
                            </form>
                            <!-- <button id="printButton" class="btn btn-info"><i class="fa fa-print"></i> Imprimir
                                Reporte</button> -->
                            <a href="reportes_coordinador.php" class="btn btn-danger">Regresar</a>
                            <h2 align="center"><b>REPORTE INDIVIDUAL -
                                    <?php echo $r_b_estudiante['dni'] . " - " . $r_b_estudiante['apellidos_nombres']; ?></b>
                            </h2>
                            <form role="form" action="" class="form-horizontal form-label-left input_mask" method="POST">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered jambo_table bulk_action report-table"
                                        style="width:100%">
                                        <thead>
                                            <tr>
                                                <th colspan="<?php echo $total_columnas; ?>" bgcolor="black">
                                                    <center>CALIFICACIONES - UNIDADES DIDÁCTICAS</center>
                                                </th>
                                            </tr>

                                            <tr>
                                                <?php
                                                $b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
                                                while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
                                                    $id_prog = $r_b_det_mat['id_programacion_ud'];
                                                    $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
                                                    $r_b_prog = mysqli_fetch_array($b_prog_ud);
                                                    $id_udd = $r_b_prog['id_unidad_didactica'];
                                                    //BUSCAR UD
                                                    $b_uddd = buscarUdById($conexion, $id_udd);
                                                    $r_b_udd = mysqli_fetch_array($b_uddd);

                                                    $b_Semestre = buscarSemestreById($conexion, $r_b_udd['id_semestre']);
                                                    $r_b_semestre = mysqli_fetch_array($b_Semestre);
                                                    //buscar capacidad
                                                    $cont_ind_logro_cap_ud = 0;
                                                    $b_cap_ud = buscarCapacidadesByIdUd($conexion, $id_udd);
                                                    while ($r_b_cap_ud = mysqli_fetch_array($b_cap_ud)) {
                                                        $id_cap_ud = $r_b_cap_ud['id'];
                                                        // buscar indicadores de capacidad
                                                        $b_ind_l_cap_ud = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_cap_ud);
                                                        $cant_id_cap_ud = mysqli_num_rows($b_ind_l_cap_ud);
                                                        $cont_ind_logro_cap_ud += $cant_id_cap_ud;
                                                    }

                                                    ?>
                                                    <th colspan="<?php echo $cont_ind_logro_cap_ud; ?>">
                                                        <p class="verticalll">
                                                            <center>
                                                                <?php echo $r_b_udd['descripcion'] . "<br>S-" . $r_b_semestre['descripcion']; ?>
                                                            </center>
                                                        </p>
                                                    </th>
                                                    <th>
                                                        <p class="verticalll">PROMEDIO</p>
                                                    </th>

                                                    <?php
                                                }
                                                ?>
                                                <th>
                                                    <p class="verticalll">Ptj. Total</p>
                                                </th>
                                                <th>
                                                    <p class="verticalll">Ptj. Créditos</p>
                                                </th>
                                                <th>
                                                    <p>
                                                        <center>CONDICIÓN</center>
                                                    </p>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php

                                            //buscar estudiante para su id
                                            $b_est = buscarEstudianteById($conexion, $id_est);
                                            $r_b_est = mysqli_fetch_array($b_est);

                                            $b_ud_pe_sem = buscarUdByCarSem($conexion, $r_b_est['id_programa_estudios'], $r_b_est['id_semestre']);
                                            $min_ud_desaprobar = round(mysqli_num_rows($b_ud_pe_sem) / 2, 0, PHP_ROUND_HALF_DOWN);

                                            ?>
                                            <tr>
                                                <?php
                                                //buscar si estudiante esta matriculado en una unidad didactica
                                                $suma_califss = 0;
                                                $suma_ptj_creditos = 0;
                                                $cont_ud_desaprobadas = 0;
                                                $b_det_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
                                                while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
                                                    $b_prog = buscarProgramacionById($conexion, $r_b_det_mat['id_programacion_ud']);
                                                    $r_b_prog = mysqli_fetch_array($b_prog);
                                                    $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
                                                    $r_bb_ud = mysqli_fetch_array($b_ud);

                                                    $id_det_mat = $r_b_det_mat['id'];


                                                    //echo "<td>SI</td>";
                                            
                                                    //buscar las calificaciones
                                                    $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);

                                                    $suma_calificacion = 0;
                                                    $cont_calif = 0;
                                                    while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {

                                                        $id_calificacion = $r_b_calificacion['id'];
                                                        //buscamos las evaluaciones
                                                        $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);

                                                        if ($suma_evaluacion != 0) {
                                                            $cont_calif += 1;
                                                            $suma_calificacion += $suma_evaluacion;
                                                            $suma_evaluacion = round($suma_evaluacion);
                                                            if ($suma_evaluacion > 12) {
                                                                echo '<td><center><font color="blue">' . $suma_evaluacion . '</font></center></td>';
                                                                //echo '<th><center><input type="number" class="nota_input" style="color:blue;" value="' . $calificacion_final . '" min="0" max="20" disabled></center></th>';
                                                            } else {
                                                                echo '<td><center><font color="red">' . $suma_evaluacion . '</font></center></td>';
                                                                //echo 
                                                            }
                                                        } else {
                                                            $suma_evaluacion = "";
                                                            echo '<th></th>';
                                                        }
                                                    }
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
                                                    }

                                                    if ($calificacion > 12) {
                                                        echo '<th align="center" bgcolor="#BEBBBB"><font color="blue">' . $calificacion . '</font></th>';
                                                    } else {
                                                        echo '<th align="center" bgcolor="#BEBBBB"><font color="red">' . $calificacion . '</font></th>';
                                                        $cont_ud_desaprobadas += 1;
                                                    }
                                                    if (is_numeric($calificacion)) {
                                                        $suma_califss += $calificacion;
                                                        $suma_ptj_creditos += $calificacion * $r_bb_ud['creditos'];
                                                    } else {
                                                        $suma_ptj_creditos += 0 * $r_bb_ud['creditos'];
                                                    }
                                                }
                                                echo '<td align="center" ><font color="black">' . $suma_califss . '</font></td>';
                                                echo '<td align="center" ><font color="black">' . $suma_ptj_creditos . '</font></td>';
                                                if ($cont_ud_desaprobadas == 0) {
                                                    echo '<td align="center" ><font color="black">Promovido</font></td>';
                                                } elseif ($cont_ud_desaprobadas <= $min_ud_desaprobar) {
                                                    echo '<td align="center" ><font color="black">Repite U.D. del Módulo Profesional</font></td>';
                                                } else {
                                                    echo '<td align="center" ><font color="black">Repite el Módulo Profesional</font></td>';
                                                }

                                                ?>
                                            </tr>


                                        </tbody>
                                    </table>

                                    <?php
                                    $b_detalle_mat = buscarDetalleMatriculaByIdMatricula($conexion, $id_mat_est);
                                    while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
                                        $id_prog = $r_b_det_mat['id_programacion_ud'];
                                        $b_prog_ud = buscarProgramacionById($conexion, $id_prog);
                                        $r_b_prog = mysqli_fetch_array($b_prog_ud);
                                        $b_ud = buscarUdById($conexion, $r_b_prog['id_unidad_didactica']);
                                        $r_b_ud = mysqli_fetch_array($b_ud);
                                        ?>
                                        <table class="table table-striped table-bordered jambo_table bulk_action report-table"
                                            style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th bgcolor="black" colspan="34">
                                                        <center>ASISTENCIA - <?php echo $r_b_ud['descripcion']; ?></center>
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <th>UNIDAD DIDÁCTICA</th>
                                                    <?php
                                                    $b_silabo = buscarSilaboByIdProgramacion($conexion, $id_prog);
                                                    $r_b_silabo = mysqli_fetch_array($b_silabo);
                                                    $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
                                                    while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                                        $id_act = $res_b_prog_act['id'];
                                                        $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                                        while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                                            ?>
                                                            <th>
                                                                <p class="verticalll"><?php echo $r_b_sesion['fecha_desarrollo']; ?></p>
                                                            </th>
                                                            <?php
                                                        }
                                                    }
                                                    ?>
                                                    <th>Inasistencia</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td><?php echo $r_b_ud['descripcion']; ?></td>
                                                    <?php
                                                    $cont_inasistencia = 0;
                                                    $cont_asis = 0;
                                                    $b_prog_act = buscarProgActividadesSilaboByIdSilabo($conexion, $r_b_silabo['id']);
                                                    while ($res_b_prog_act = mysqli_fetch_array($b_prog_act)) {
                                                        $id_act = $res_b_prog_act['id'];
                                                        $b_sesion = buscarSesionByIdProgramacionActividades($conexion, $id_act);
                                                        while ($r_b_sesion = mysqli_fetch_array($b_sesion)) {
                                                            $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $r_b_sesion['id'], $id_est);
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
                                                </tr>
                                            </tbody>
                                        </table>
                                        <?php
                                    }
                                    ?>
                                    <center><a href="reportes_coordinador.php" class="btn btn-danger">Regresar</a></center>
                                </div>



                            </form>
                            <?php



                            ?>
                        </div>
                    </div>
                </div>
                <!-- /page content -->


                <!-- footer content -->
                <div class="footer no-print">
                    <?php
                    include("../include/footer.php");
                    ?>
                </div>
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

        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.7.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>


        <script>
            $(document).ready(function () {

                $('#printButton').on('click', function () {
                    console.log('Print button clicked');
                    window.print();
                });
            });
        </script>

    </body>

    </html>
<?php }
