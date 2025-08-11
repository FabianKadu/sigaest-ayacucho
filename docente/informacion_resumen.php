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

    $id_estudiante = $_GET['id'];

    $b_estudiante = buscarEstudianteById($conexion, $id_estudiante);
    $estudiante = mysqli_fetch_array($b_estudiante);

    $res_programa = buscarCarrerasById($conexion, $estudiante['id_programa_estudios']);
    $programa = mysqli_fetch_array($res_programa);

    // Informacion socioeconomica
    $res_info_socio = buscarInfoSocioByIdEstudiante($conexion, $id_estudiante);
    $existe_registro = mysqli_num_rows($res_info_socio);

    $condicion = "NO REGISTRADO";
    $familiares = "NO REGISTRADO";
    $trabajo = "NO REGISTRADO";
    $vivienda = "NO REGISTRADO";
    $ciudad = "NO REGISTRADO";
    $movilidad = "NO REGISTRADO";
    $seguro = "NO REGISTRADO";
    $sangre = "NO REGISTRADO";

    if ($existe_registro != 0) {
        $info_socio = mysqli_fetch_array($res_info_socio);

        $condicion = $info_socio['condicion'] ?? "NO REGISTRADO";
        $familiares = $info_socio['familiares'] ?? "NO REGISTRADO";
        $trabajo = $info_socio['tipo_trabajo'] ?? "NO REGISTRADO";
        $vivienda = $info_socio['vivienda'] ?? "NO REGISTRADO";
        $ciudad = $info_socio['ciudad'] ?? "NO REGISTRADO";
        $movilidad = $info_socio['vehiculos'] ?? "NO REGISTRADO";
        $seguro = $info_socio['seguro_salud'] ?? "NO REGISTRADO";
        $sangre = $info_socio['sangre'] ?? "NO REGISTRADO";
    }


    $fecha_nacimiento = $estudiante['fecha_nac'];

    // Convertir la fecha de nacimiento en un objeto DateTime
    $fecha_nacimiento_obj = new DateTime($fecha_nacimiento);

    // Fecha actual
    $fecha_actual = new DateTime();

    // Calcular la diferencia entre la fecha actual y la fecha de nacimiento
    $diferencia = $fecha_nacimiento_obj->diff($fecha_actual);

    // Obtener la edad del estudiante
    $edad = $diferencia->y;

    $semestre = "NO REGISTRADO";
    if ($estudiante['id_semestre'] != 0) {
        $res_semestre = buscarSemestreById($conexion, $estudiante['id_semestre']);
        $semestre = mysqli_fetch_array($res_semestre);
        $semestre = $semestre['descripcion'];
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

        <title>Informacion global del estudiante <?php include("../include/header_title.php"); ?></title>
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

        <style>
            .panel {
                margin: 20px;
            }

            .panel-heading {
                background-color: #f5f5f5 !important;
                border-bottom: 1px solid #ddd;
            }

            .info-row {
                margin-bottom: 8px;
            }

            .ver-completo {
                float: right;
            }
        </style>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!--menu-->
                <?php include("include/menu_secretaria.php"); ?>

                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="clearfix"></div>

                        <div class="row">
                            <center>
                                <h4><b>INFORMACION GLOBAL DE ESTUDIANTE</b></h4>
                            </center>

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><b>Información Personal</b></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="info-row">
                                            <strong>DNI:</strong> <?php echo $estudiante['dni'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Apellidos y Nombres:</strong> <?php echo $estudiante['apellidos_nombres'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Género:</strong>
                                            <?php echo $estudiante['id_genero'] == 1 ? 'Masculino' : 'Femenino'; ?>
                                        </div>

                                        <div class="info-row">
                                            <strong>Fecha de Nacimiento:</strong> <?php echo $estudiante['fecha_nac'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Dirección:</strong> <?php echo $estudiante['direccion'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Correo Electrónico:</strong> <?php echo $estudiante['correo'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Teléfono/Celular:</strong> <?php echo $estudiante['telefono'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Discapacidad:</strong> <?php echo $estudiante['discapacidad'] ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><b>Información Académica</b></h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="info-row">
                                            <strong>Programa de estudios:</strong> <?php echo $programa['nombre'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Semestre actual:</strong> <?php echo $semestre ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Sección:</strong> <?php echo $estudiante['seccion'] ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Turno:</strong> <?php echo $estudiante['turno'] ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><b>Información Socioeconómica</b></h3>
                                    </div>

                                    <div class="panel-body">
                                        <div class="info-row">
                                            <strong>Condición:</strong> <?php echo $condicion; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Familiares en casa:</strong> <?php echo $familiares; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Situación de trabajo:</strong> <?php echo $trabajo; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Tipo de vivienda:</strong> <?php echo $vivienda; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Vive en la ciudad o pueblo:</strong> <?php echo $ciudad; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Movilidad:</strong> <?php echo $movilidad; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Seguro de salud:</strong> <?php echo $seguro; ?>
                                        </div>
                                        <div class="info-row">
                                            <strong>Tipo de sangre:</strong> <?php echo $sangre; ?>
                                        </div>

                                        <a class="btn btn-primary btn-sm ver-completo" href="informacion_socioeconomica.php?id=<?php echo $id_estudiante; ?>">
                                            Ver completo
                                        </a>
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

        <!-- Custom Theme Scripts -->
        <script src="../Gentella/build/js/custom.min.js"></script>

        <?php mysqli_close($conexion); ?>
    </body>

    </html>
<?php
}
