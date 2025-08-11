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

  //DOCENTE O SECRETARIO
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $r_b_docente = mysqli_fetch_array($b_docente);

  $id_proceso_admision = $_GET['id'];

  //PROCESO DE ADMISIÓN
  $res_proceso_admision = buscarProcesoAdmisionPorId($conexion, $id_proceso_admision);
  $proceso_admision = mysqli_fetch_array($res_proceso_admision);

  $tipo_admision = $proceso_admision['Tipo'];
  
  //AJUSTES DE ADMISION - REQUISITOS
  $res_ajustes_segunda_opcion = buscarAjustesSegundaOpcionPorProceso($conexion, $id_proceso_admision);

  //AJUSTES DE ADMISION - PROGRAMAS DE SEGUNDA OPCION
  $res_ajustes_admision = buscarAjustesAdmisionPorIdProceso($conexion, $id_proceso_admision);
  $res_ajustes_admision_especificos = buscarAjustesAdmisionPorIdProceso($conexion, $id_proceso_admision);


  //MODALIDADES
  $res_modalidades = buscarTodasModalidadesOrdenadas($conexion);
  $modalidades_exonerados = mysqli_num_rows($res_modalidades);
  $modalidades_exonerados = $modalidades_exonerados - 1;

  //TITULO DE PAGINA
  $titulo_pagina = $proceso_admision['Periodo'] . " - ". $proceso_admision['Tipo'];

  $estado_proceso = determinarPeriodosActivos($conexion, $proceso_admision['Periodo']);
  $estado_proceso = mysqli_fetch_array($estado_proceso);
  $editable = False;
  if($estado_proceso['cantidad_procesos'] == 0){
    $editable = True;
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

    <title>Ajustes - Admision
        <?php include ("../include/header_title.php"); ?>
    </title>
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
    .switch {
        --circle-dim: 1em;
        font-size: 17px;
        position: relative;
        display: inline-block;
        width: 2.8em;
        height: 1.6em;
    }

    /* Hide default HTML checkbox */
    .switch input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    /* The slider */
    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #dfdfdf;
        transition: .4s;
        border-radius: 30px;
    }

    .slider-card {
        position: absolute;
        content: "";
        height: var(--circle-dim);
        width: var(--circle-dim);
        border-radius: 20px;
        left: 0.3em;
        bottom: 0.3em;
        transition: .4s;
        pointer-events: none;
    }

    .slider-card-face {
        position: absolute;
        inset: 0;
        backface-visibility: hidden;
        perspective: 1000px;
        border-radius: 50%;
        transition: .4s transform;
    }

    .slider-card-front {
        background-color: #646464;
    }

    .slider-card-back {
        background-color: #379237;
        transform: rotateY(180deg);
    }
    input:checked~.slider-card .slider-card-back {
        transform: rotateY(0);
    }

    input:checked~.slider-card .slider-card-front {
        transform: rotateY(-180deg);
    }

    input:checked~.slider-card {
        transform: translateX(1.2em);
    }

    input:checked~.slider {
        background-color: #9ed99c;
    }
</style>    

</head>

<body class="nav-md">
    <div class="container body">
        <div class="main_container">
            <!--menu-->
            <?php 
            include ("include/menu_secretaria.php"); ?>

            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">

                    <div class="clearfix"></div>
                    <div class="row">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="">
                                    <h2 align="center">Ajustes Admision
                                        <?php echo $titulo_pagina; ?>
                                    </h2>
                                    <div class="clearfix"></div>
                                    <a href="procesos_admision.php" class="btn btn-danger">Regresar</a>
                                </div>
                                <br>
                                <div class="x_content">
                                    <h4><b>REQUISITOS</b></h4>
                                    <hr />
                                    <?php if($tipo_admision == "ORDINARIO"){ ?>
                                    <h4>Requisitos Generales</h4>
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Requisito</th>
                                                <th>Descripcion</th>
                                                <th>Modalidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            while ($ajustes_admision = mysqli_fetch_array($res_ajustes_admision)){
                                                if($ajustes_admision['Id_Modalidad'] < 1){  
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $ajustes_admision['Titulo']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $ajustes_admision['Descripcion']; ?>
                                                </td>
                                                <td>
                                                    Ordinario
                                                </td>
                                                <td>
                                                    <center>
                                                    <label class="switch">
                                                        <input type="checkbox" class="checkbox_estado" id="<?php echo $ajustes_admision['Id']; ?>" <?php  if($ajustes_admision['Estado'] === "1") echo "checked"; ?>>
                                                        <div class="slider"></div>
                                                        <div class="slider-card">
                                                            <div class="slider-card-face slider-card-front"></div>
                                                            <div class="slider-card-face slider-card-back"></div>
                                                        </div>
                                                    </label>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php
                                            }};
                                            ?>

                                        </tbody>
                                    </table>
                                    <?php }else{ ?>

                                    <h4>Requisitos Generales</h4>
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Requisito</th>
                                                <th>Descripcion</th>
                                                <th>Modalidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            while ($ajustes_admision = mysqli_fetch_array($res_ajustes_admision)){
                                                if($ajustes_admision['Id_Modalidad'] < 1){  
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $ajustes_admision['Titulo']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $ajustes_admision['Descripcion']; ?>
                                                </td>
                                                <td>
                                                    Ordinario
                                                </td>
                                                <td>
                                                    <center>
                                                    <label class="switch">
                                                        <input type="checkbox" class="checkbox_estado" id="<?php echo $ajustes_admision['Id']; ?>" <?php  if($ajustes_admision['Estado'] === "1") echo "checked"; ?>>
                                                        <div class="slider"></div>
                                                        <div class="slider-card">
                                                            <div class="slider-card-face slider-card-front"></div>
                                                            <div class="slider-card-face slider-card-back"></div>
                                                        </div>
                                                    </label>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php
                                            }};
                                            ?>

                                        </tbody>
                                    </table>
                                    <br><br>
                                    <h4>Requisítos Específicos</h4>
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Requisito</th>
                                                <th>Descripcion</th>
                                                <th>Modalidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            
                                            while ($ajustes_admision = mysqli_fetch_array($res_ajustes_admision_especificos)){
                                                if($ajustes_admision['Id_Modalidad'] > 0){  
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $ajustes_admision['Titulo']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $ajustes_admision['Descripcion']; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                    
                                                    $res_modalidad  = buscarModalidadPorId($conexion, $ajustes_admision['Id_Modalidad']);
                                                    $modalidad = mysqli_fetch_array($res_modalidad);
                                                    echo $modalidad['Descripcion'];
                                                    ?>
                                                </td>
                                                <td>
                                                    <center>
                                                    <label class="switch">
                                                        <input type="checkbox" class="checkbox_estado" id="<?php echo $ajustes_admision['Id']; ?>" <?php  if($ajustes_admision['Estado'] === "1") echo "checked"; ?>>
                                                        <div class="slider"></div>
                                                        <div class="slider-card">
                                                            <div class="slider-card-face slider-card-front"></div>
                                                            <div class="slider-card-face slider-card-back"></div>
                                                        </div>
                                                    </label>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php
                                            }};
                                            ?>

                                        </tbody>
                                    </table>
                                    <?php } ?>
                                </div>
                                <br>
                                <br>
                                <div class="x_content">
                                    <h4><b>PROGRAMAS DE SEGUNDA OPCIÓN</b></h4>
                                    <hr />
                                    <h4>Programas Elegibles</h4>
                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nombre del Programa</th>
                                                <th>Plan de Estudios</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            while ($ajustes_segunda_opcion = mysqli_fetch_array($res_ajustes_segunda_opcion)){
                                            ?>
                                            <tr>
                                                <td>
                                                    <?php echo $ajustes_segunda_opcion['nombre']; ?>
                                                </td>
                                                <td>
                                                    <?php echo $ajustes_segunda_opcion['plan_estudio']; ?>
                                                </td>
                                                <td>
                                                    <center>
                                                    <label class="switch">
                                                        <input type="checkbox" class="checkbox_estado_programa" id="<?php echo $ajustes_segunda_opcion['Id']; ?>" <?php  if($ajustes_segunda_opcion['Estado'] === "1") echo "checked"; ?>>
                                                        <div class="slider"></div>
                                                        <div class="slider-card">
                                                            <div class="slider-card-face slider-card-front"></div>
                                                            <div class="slider-card-face slider-card-back"></div>
                                                        </div>
                                                    </label>
                                                    </center>
                                                </td>
                                            </tr>
                                            <?php
                                            };
                                            ?>

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

    <script>
        $(document).ready(function(){
            // Detectar cambios en los checkboxes
            $('.checkbox_estado').change(function(){
                // Obtener el ID único del checkbox modificado
                var id = $(this).attr('id');
                // Obtener el estado actual del checkbox (true o false)
                var estado = $(this).prop('checked');
                if (estado) var estado_num = 1;
                else var estado_num = 0;
                // Enviar el ID mediante AJAX al archivo PHP
                console.log(estado_num);
                $.ajax({
                    type: "POST",
                    url: "operaciones/cambiar_estado_ajustes_admision.php",
                    data: { id: id, estado: estado },
                    success: function(response){
                        // Manejar la respuesta si es necesario
                        
                    }
                });
            });

            // Detectar cambios en los checkboxes de programas segunda opcion
            $('.checkbox_estado_programa').change(function(){
                // Obtener el ID único del checkbox modificado
                var id = $(this).attr('id');
                // Obtener el estado actual del checkbox (true o false)
                var estado = $(this).prop('checked');
                if (estado) var estado_num = 1;
                else var estado_num = 0;
                // Enviar el ID mediante AJAX al archivo PHP
                console.log(estado_num);
                $.ajax({
                    type: "POST",
                    url: "operaciones/cambiar_estado_ajustes_programa.php",
                    data: { id: id, estado: estado },
                    success: function(response){
                        // Manejar la respuesta si es necesario
                        
                    }
                });
            });

        });
    </script>

    <?php mysqli_close($conexion); ?>
</body>

</html>
<?php }