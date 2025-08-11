<?php

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_estudiante.php';
include("../empresa/include/consultas.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('index.php');
          </script>";
} else {

    $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
    $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
    $r_b_estudiante = mysqli_fetch_array($b_estudiante);
    $res_efsrt = buscarEfsrtByEstudiante($conexion, $id_estudiante_sesion);
    $res_efsrt_concluidas = buscarEfsrtConcluidasByEstudiante($conexion, $id_estudiante_sesion);
    $count_efsrt = mysqli_num_rows($res_efsrt);

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

        <title>EFSRT <?php include("../include/header_title.php"); ?></title>
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
        <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

        <style>
            .comenzar {
                background-color: #337AB7;
            }

            .proceso {
                background-color: #26B99A;
            }

            .finalizar {
                background-color: #F0AD4E;
            }

            .Finalizado {
                background-color: #D9534F;
            }

            thead tr th {
                text-align: center;
                vertical-align: middle !important;

            }
        </style>

    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!--menu-->
                <?php

                $per_select = $_SESSION['periodo'];
                include("include/menu.php");
                $b_perido = buscarPeriodoAcadById($conexion, $_SESSION['periodo']);
                $r_b_per = mysqli_fetch_array($b_perido);
                ?>

                <!-- page content -->
                <div class="right_col" role="main">


                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <?php if ($count_efsrt != 0) { ?>
                                <div class="x_panel">
                                    <div class="">
                                        <h2 align="center">EFSRT Aperturado</h2>
                                        <br>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <div class="">
                                            <table id="" class="table table-striped table-bordered" style="width:100%">
                                                <thead>
                                                    <tr>
                                                        <th>Estado</th>
                                                        <th>Programa de estudios</th>
                                                        <th>Módulo formativo</th>
                                                        <th>Tutor</th>
                                                        <th>Celular del tutor</th>
                                                        <th>Acciones</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($efsrt = mysqli_fetch_array($res_efsrt)) { ?>
                                                        <tr>
                                                            <td>
                                                                <?php
                                                                if ($efsrt['estado'] == 1) {
                                                                    echo '<span class="label label-primary">Aperturado</span>';
                                                                }
                                                                if ($efsrt['estado'] == 2) {
                                                                    echo '<span class="label label-success">Para revisión</span>';
                                                                }
                                                                if ($efsrt['estado'] == 3) {
                                                                    echo '<span class="label label-warning">Observado</span>';
                                                                }
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $id_programa = $efsrt['id_programa'];
                                                                $ejec_busc_prog = buscarCarrerasById($conexion, $id_programa);
                                                                $res_busc_prog = mysqli_fetch_array($ejec_busc_prog);
                                                                echo $res_busc_prog['nombre'];
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $id_modulo = $efsrt['id_modulo'];
                                                                $ejec_busc_mod = buscarModuloFormativoById($conexion, $id_modulo);
                                                                $res_busc_mod = mysqli_fetch_array($ejec_busc_mod);
                                                                echo $res_busc_mod['descripcion'];
                                                                ?>
                                                            </td>
                                                            <td>
                                                                <?php
                                                                $id_docente = $efsrt['id_docente'];
                                                                $ejec_busc_doc = buscarDocenteById($conexion, $id_docente);
                                                                $res_busc_doc = mysqli_fetch_array($ejec_busc_doc);
                                                                echo $res_busc_doc['apellidos_nombres'];
                                                                ?>
                                                            </td>
                                                            <td><?php echo $res_busc_doc['telefono']; ?></td>
                                                            <td>
                                                                <?php
                                                                if ($efsrt['estado'] == 1) { ?>
                                                                    <a
                                                                        data-toggle="tooltip"
                                                                        data-original-title="Cargar documentos"
                                                                        data-placement="bottom"><button class="btn btn-primary" data-toggle="modal"
                                                                            data-target=".subir_documentos<?php echo $efsrt['id']; ?>"><i class="fa fa-link"></i></button>
                                                                    </a>
                                                                <?php }
                                                                if ($efsrt['estado'] == 3) { ?>
                                                                    <a
                                                                        data-toggle="tooltip"
                                                                        data-original-title="Subsanar observaciones"
                                                                        data-placement="bottom"><button class="btn btn-warning" data-toggle="modal"
                                                                            data-target=".subir_documentos<?php echo $efsrt['id']; ?>"><i class="fa fa-link"></i></button>
                                                                    </a>
                                                                <?php }
                                                                include('include/subir_documento_efsrt.php');
                                                                ?>
                                                            </td>
                                                        </tr>
                                                    <?php }
                                                    ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                            <div class="x_panel">
                                <div class="">
                                    <h2 align="center">Mis EFSRT</h2>
                                    <br>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <div class="">
                                        <table id="efsrt" class="table table-striped table-bordered" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th>Estado</th>
                                                    <th>Programa de estudios</th>
                                                    <th>Módulo formativo</th>
                                                    <th>Tutor</th>
                                                    <th>Empresa</th>
                                                    <th>Periodo Lectivo</th>
                                                    <th>Calificación</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php while ($efsrt = mysqli_fetch_array($res_efsrt_concluidas)) { ?>
                                                    <tr>
                                                        <td><?php echo $efsrt['estado']; ?></td>
                                                        <td><?php
                                                            $programa_res = buscarCarrerasById($conexion, $efsrt['id_programa']);
                                                            $programa = mysqli_fetch_array($programa_res);
                                                            echo $programa['nombre'];
                                                            ?></td>
                                                        <td><?php
                                                            $modulo_res = buscarModuloFormativoById($conexion, $efsrt['id_modulo']);
                                                            $modulo = mysqli_fetch_array($modulo_res);
                                                            echo $modulo['descripcion'];
                                                            ?></td>
                                                        <td><?php
                                                            $docente_res = buscarDocenteById($conexion, $efsrt['id_docente']);
                                                            $docente = mysqli_fetch_array($docente_res);
                                                            echo $docente['apellidos_nombres'];
                                                            ?></td>
                                                        <td><?php echo $efsrt['lugar']; ?></td>
                                                        <td><?php echo $efsrt['periodo_lectivo']; ?></td>
                                                        <td><?php echo decryptText($efsrt['calificacion']); ?></td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
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
        <script>
            $(document).ready(function() {
                $('#efsrt').DataTable({
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
                    },
                    // order:[8,'desc']
                });

            });
        </script>


        <?php mysqli_close($conexion); ?>
    </body>

    </html>
<?php }
