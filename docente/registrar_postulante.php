<?php
include '../include/conexion.php';
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

        <title>Presente Periodo Académico<?php include("../include/header_title.php"); ?></title>
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
                                        <h2 align="center">Registro de postulante</h2>
                                        <!--<button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>-->

                                        <div class="clearfix"></div>
                                    </div>
                                    <form action="operaciones/registrar_postulante.php" method="POST" enctype="multipart/form-data">
                                        <div class="x_content">
                                            <input type="hidden" name="proceso" value="<?php echo $_GET['id']; ?>">
                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label ">D.N.I. *: </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="dni" id="dni" required="required" oninput="validateInputNum(this,8)">
                                                </div>
                                            </div>
                                            <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label">Apellido Paterno *:
                                                </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="paterno" id="apellidoPaterno" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label ">Apellido Materno *: </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="materno" id="apellidoMaterno" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label">Nombres *:
                                                </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="nombres" id="nombres" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label ">Género *: </label>
                                                <div class="">
                                                    <div class="row">
                                                        <label class="col-md-6">
                                                            <input type="radio" name="genero" value="0" required>
                                                            M
                                                        </label>

                                                        <label class="col-md-6">
                                                            <input type="radio" name="genero" value="1" required>
                                                            F
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label ">Fecha de Nacimiento *: </label>
                                                <div class="">
                                                    <input type="date" id="fecha_nacimiento" class="form-control" name="fecha_nacimiento" required="required">
                                                </div>
                                            </div>



                                            <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label ">Correo Electrónico *: </label>
                                                <div class="">
                                                    <input type="email" class="form-control" name="correo" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                                <label class="control-label">Número de Celular *:
                                                </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="celular" required="required" oninput="validateInputNum(this,9)">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                                <label class="control-label ">Dirección Actual *: </label>
                                                <div class="">
                                                    <input type="text" class="form-control" name="direccion" required="required">
                                                </div>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                                <label class="control-label ">Programa de estudio *: </label>
                                                <select class="form-control" name="carrera" value="" required="required">
                                                    <option disabled selected>Seleccione</option>
                                                    <?php
                                                    $ejec_busc_carr = buscarCarreras($conexion);
                                                    while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                                                        $id_carr = $res__busc_carr['id'];
                                                        $carr = $res__busc_carr['nombre'];
                                                    ?>
                                                        <option value="<?php echo $id_carr;
                                                                        ?>"><?php echo $carr . ' - ' . $res__busc_carr['plan_estudio']; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 col-sm-12 col-xs-12">
                                                <label class="control-label ">Ficha de postulante *: </label>
                                                <div class="">
                                                    <input type="file" class="form-control" name="ficha" required="required">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="x_content">
                                            <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                                <button type="submit" class="btn btn-success">Guardar</button>
                                                <button type="reset" class="btn btn-primary">Limpiar</button>
                                                <a href="postulantes_admision.php?id=<?php echo $_GET['id']; ?>" class="btn btn-default">Regresar</a>
                                            </div>
                                        </div>
                                    </form>
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
                $('#example').DataTable({
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dniInput = document.getElementById('dni');
            const nameInput = document.getElementById('nombres');
            const apInput = document.getElementById('apellidoPaterno');
            const amInput = document.getElementById('apellidoMaterno');
            let timeoutId = null;

            dniInput.addEventListener('input', function() {
                const dni = dniInput.value.trim();

                if (dni.length !== 8) {
                    if (timeoutId) clearTimeout(timeoutId);
                    nameInput.value = "";
                    apInput.value = "";
                    amInput.value = "";
                    return;
                }

                if (timeoutId) clearTimeout(timeoutId);

                timeoutId = setTimeout(() => {
                    fetch(`operaciones/buscar_dni.php?dni=${dni}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.error) {
                                nameInput.value = "";
                                apInput.value = "";
                                amInput.value = "";
                            } else {
                                apInput.value = data.apellidoPaterno || "";
                                amInput.value = data.apellidoMaterno || "";
                                nameInput.value = data.nombres || "";
                            }
                        })
                        .catch(error => {
                            console.error("Error al obtener datos:", error);
                        });
                }, 500);
            });
        });
    </script>

    </html>
<?php
}
