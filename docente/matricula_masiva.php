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
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
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

    <title>Matrícula<?php include("../include/header_title.php"); ?></title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>


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
                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Cargar Plantilla</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />
                                        <form action="matricular_masiva.php" enctype="multipart/form-data" method="POST">
                                        <div class="form-group">
                                            <input type="file" name="my_file_input" id="my_file_input" class="form-control" />
                                        </div>
                                        <div align="center">
                                            <a href="matriculas.php"  class="btn btn-warning">Cancelar</a>
                                            <button type="submit" id="btn_lectura" class="btn btn-success">Guardar</button>
                                        </div>
                                        </form>
                                        <div class="ln_solid"></div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-xs-12">
                            <div class="x_panel">
                                <div class="x_title">
                                    <h2>Descargar Plantilla</h2>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="x_content">
                                    <br />
                                    <form role="form" class="form-horizontal form-label-left" action="generar_plantilla_matricula.php" method="POST" target="_blank">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Programa de Estudios : </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select class="form-control" id="carrera_m" name="carrera_m" value="" required="required">
                                                    <option></option>
                                                    <?php
                                                    $b_pe = buscarCarreras($conexion);
                                                    while ($r_b_pe = mysqli_fetch_array($b_pe)) { ?>
                                                        <option value="<?php echo $r_b_pe['id']; ?>"><?php echo $r_b_pe['nombre']; ?></option>
                                                    <?php } ?>

                                                </select>
                                                <br>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Semestre : </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                                <select class="form-control" id="semestre" name="semestre" value="" required="required">
                                                    <option></option>
                                                    <?php
                                                    $ejec_busc_sem = buscarSemestre($conexion);
                                                    while ($res__busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                                                        $id_sem = $res__busc_sem['id'];
                                                        $sem = $res__busc_sem['descripcion'];
                                                    ?>
                                                        <option value="<?php echo $id_sem; ?>"><?php echo $sem; ?></option>
                                                    <?php
                                                    }
                                                    ?>
                                                </select>
                                                <br>
                                            </div>
                                        </div>
                                        <div align="center">
                                            <button type="submit" class="btn btn-primary"><i class="fa fa-download"></i> Descargar</button>
                                        </div>
                                        <div class="ln_solid"></div>

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
    <script>
        $('#btn_lectura').click(function() {
            valores = new Array();
            var contador = 0;
            $('#my_file_output tr').each(function() {

                var d1 = $(this).find('td').eq(0).html();
                var d2 = $(this).find('td').eq(1).html();
                var d3 = $(this).find('td').eq(2).html();
                var d4 = $(this).find('td').eq(3).html();
                valor = new Array(d1, d2, d3, d4);
                valores.push(valor);
                console.log(valor);
                // alert(valor);
                $.post('operaciones/registrar_matricula_masiva.php', {
                    d1: d1,
                    d2: d2,
                    d3: d3,
                    d4: d4
                }, function(datos) {
                    $('#respuesta').html(datos);
                });

                contador = contador + 1;
                $('#contador').html("Se registro " + contador + " registros correctamente.");

            });



        });
    </script>


    <?php mysqli_close($conexion); ?>
</body>

</html>
<?php
}