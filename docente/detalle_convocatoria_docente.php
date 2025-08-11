<?php
include("../include/conexion.php");
include("../empresa/include/consultas.php");
include("../include/busquedas.php");
include("../include/funciones.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;

include 'include/verificar_sesion_docente.php';
if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
        </script>";
}else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $celular_contacto = "";
    if($_GET['type'] == "1"){
        $oferta_laboral = buscarOfertaLaboralByIdIestp($conexion, $id);
        $convocatoria = mysqli_fetch_array($oferta_laboral);
        $celular_contacto = "<li><strong>Celular de contacto: </strong> <br> ".$convocatoria['celular_contacto']."
        </li>";
    }
    if($_GET['type'] == "0"){
        $oferta_laboral = buscarOfertaLaboralById($conexion, $id);
        $convocatoria = mysqli_fetch_array($oferta_laboral);
        
    }

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
	<!-- Custom Theme Style -->
	<link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
    <style>
        /* Estilos para los inputs con línea inferior */
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group textarea {
            border-top: none;
            border-left: none;
            border-right: none;
            border-radius: 0;
            box-shadow: none;
            background-color: transparent; 
        }
    </style>
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
			include("include/menu_docente.php"); ?>
			<!-- page content -->
			<div class="right_col">
                <div class="row">
                    <div class="col-md-3 col-sm-3  ">
                        <section class="panel">
                            <div align="center">
                                <br>
                                <a href="convocatorias_docente.php" class="btn btn-danger"><i class="fa fa-mail-reply"></i>  Regresar</a>
                                <br><br>
                            </div>
                            <div class="alert-error <?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?>" role="alert" align="center">
                                <strong><?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?></strong>
                            </div>
                            <div class="panel-body">
                                <div class="project_detail">
                                    <p class="title"><?php echo $convocatoria['titulo'] ?></p>
                                    <br>
                                    <p class="title">Enlace de la convocatoria</p>
                                    <?php
                                    if(!empty(trim($convocatoria['link_postulacion']))){
                                        echo '
                                        <a href="'.$convocatoria['link_postulacion'] .'" class="blue">Ir al enlace</a>
                                        ';
                                    }else{
                                        echo "No registrado";
                                    }?>
                                </div>
                                <br>
                                <ul class="list-unstyled project_files">
                                    <li><strong>Nombre de la empresa, persona natural o jurídica: </strong> <br> <?php echo $convocatoria['empresa']?>
                                    </li>
                                    <?php echo $celular_contacto; ?>
                                    <li><strong>Lugar de Trabajo: </strong> <br> <?php echo $convocatoria['ubicacion'] ?>
                                    </li>
                                    <li><strong>Vacantes: </strong> <br> <?php echo $convocatoria['vacantes'] ?>
                                    </li>
                                    <li><strong>Salario: </strong> <br> <?php echo $convocatoria['salario'] ?>
                                    </li>
                                    <li><strong>Inicio de Convocatoria: </strong> <br> <?php echo convertirFormatoFecha($convocatoria['fecha_inicio']) ?>
                                    </li>
                                    <li><strong>Fin de Convocatoria: </strong> <br> <?php echo convertirFormatoFecha($convocatoria['fecha_fin']) ?>
                                    </li>
                                    <li><strong>Programas de Interés: </strong> <br> 
                                    <p>
                                    <?php $programas = buscarProgramasByIdOfertaIestp($conexion,$convocatoria['id']);
                                        while ($programa = mysqli_fetch_array($programas)) {
                                            echo $programa['nombre']. "<br>";
                                        }
                                    ?>
                                    </p>
                                    </li>
                                </ul>
                                <br />
                                <h5><strong>Documentos de la convocatoria</strong></h5>
                                <ul class="list-unstyled project_files">
                                    <?php 
                                        $res = buscarDocumentosByIdOfertaIestp($conexion, $id);
                                        $cantidad_doc = mysqli_num_rows($res);
                                        if($cantidad_doc == 0){
                                        echo "No hay documentos";}
                                        while ($documento=mysqli_fetch_array($res)){
                                    ?>
                                    <li><a href="../empresa/<?php echo $documento['url_documento'] ?>" target="_blank"><i class="fa fa-file-pdf-o"></i><?php echo $documento['nombre_documento'] ?></a>
                                    </li>
                                    <?php }?>
                                </ul>
                                
                            </div>
                        </section>
                    </div>
                    <div class="col-md-9 col-sm-9  ">
                        <section class="panel">
                            <div class="x_title">
                                <h2 class="blue">Detalle de la convocatoria</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <h4 class="title">Requisitos</h4>
                                <p><?php echo $convocatoria['requisitos'] ?></p>
                                <h4 class="title">Funciones</h4>
                                <p><?php echo $convocatoria['funciones'] ?></p>
                                <h4 class="title">Beneficios</h4>
                                <p><?php echo $convocatoria['beneficios'] ?></p>
                                <h4 class="title">Condiciones</h4>
                                <p><?php echo $convocatoria['condiciones'] ?></p>

                                <br />
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
</body>
</html>
<?php } ?>