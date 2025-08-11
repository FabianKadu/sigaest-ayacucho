<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("include/consultas.php");
include("include/verificar_sesion_empresa.php");
include("operaciones/sesiones.php");
include("../include/funciones.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!verificar_sesion($conexion) && $id == null) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('login/');
    		</script>";
} else {

    $id_empresa = $_SESSION['id_emp'];
    $res_emp = buscarEmpresaById($conexion, $id_empresa);
    $empresa = mysqli_fetch_array($res_emp);
    $oferta_laboral = buscarOfertaLaboralById($conexion, $id);
    $convocatoria = mysqli_fetch_array($oferta_laboral);    

?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Inicio<?php include("../include/header_title.php"); ?></title>
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
    <!-- Datatables -->
    <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">
	<!-- Custom Theme Style -->
	<link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
   
    <style>
      .comenzar{
        background-color: #337AB7;
      }
      .proceso{
        background-color: #26B99A;
      }
      .finalizar{
        background-color: #F0AD4E;
      }
      .Finalizado{
        background-color: #D9534F;
      }
    </style>
    
</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php
			include("include/menu_empresa.php"); ?>
			<!-- page content -->
			<div class="right_col">
                <div class="row">
                    <div class="col-md-3 col-sm-3  ">
                        <section class="panel">
                            <div align="center">
                                <br>
                                <a href="convocatoria.php" class="btn btn-danger"><i class="fa fa-mail-reply"></i>  Regresar</a>
                                <br><br>
                            </div>
                            <div class="alert-info <?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?>" role="alert" align="center">
                                <strong><?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?></strong>
                            </div>
                            <div class="panel-body">
                                <div class="project_detail">
                                    <p class="title"><?php echo $convocatoria['titulo'] ?></p>
                                </div>
                                <br>
                                <ul class="list-unstyled project_files">
                                    
                                    <li><strong>Vacantes: </strong> <br> <?php echo $convocatoria['vacantes'] ?>
                                    </li>
                                    <li><strong>Inicio de Convocatoria: </strong> <br> <?php echo $convocatoria['fecha_inicio'] ?>
                                    </li>
                                    <li><strong>Fin de Convocatoria: </strong> <br> <?php echo $convocatoria['fecha_fin'] ?>
                                    </li>
                                </ul>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-9 col-sm-9  ">
                        <section class="panel">
                            <div class="x_title">
                                <h2 class="blue">Postulantes</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>DNI</th>
                                        <th>Estudiante</th>
                                        <th>Celular</th>
                                        <th>Correo</th>
                                        <th>Documento</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $res =  buscarPostulantesByIdOferta($conexion, $id);
                                        while ($postulante=mysqli_fetch_array($res)){
                                    ?>
                                    <tr>
                                        <td><?php echo $postulante['dni']; ?></td>
                                        <td><?php echo $postulante['apellidos_nombres']; ?></td>
                                        <td><?php echo $postulante['telefono']; ?></td>
                                        <td><?php echo $postulante['correo']; ?></td>
                                        <td>
                                        <a href="<?php echo $postulante['url_documento']; ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" data-original-title="Ver documento" data-placement="bottom"><i class="fa fa-eye"></i></a>
                                        </td>
                                    </tr>  
                                    <?php
                                        };
                                    ?>
                                    </tbody>
                                </table>
                            </div>
                        </section>
                    </div>
                </div>
			</div>
			<?php
			include("../include/footer.php");
			?>
			<!--/footer content -->
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
	<!-- Custom Theme Scripts -->
	<script src="../Gentella/build/js/custom.min.js"></script>
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
    <script src="../Gentella/vendors/pnotify/dist/pnotify.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.nonblock.js"></script>
    <script>
        $(document).ready(function() {
        $('#example').DataTable({
        "language":{
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
        } );
    </script>
</body>
</html>
<?php } ?>