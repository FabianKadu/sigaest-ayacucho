<?php

	include("../include/conexion.php");
	include("../include/busquedas.php");
	include("../include/funciones.php");
	include 'include/verificar_sesion_estudiante.php';
    include("../empresa/include/consultas.php");

    $id = isset($_GET['id']) ? $_GET['id'] : null;

	if (!verificar_sesion($conexion)) {
		echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('index.php');
          </script>";
	} else {

        $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
		$b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
		$r_b_estudiante = mysqli_fetch_array($b_estudiante);
        $oferta_laboral = buscarOfertaLaboralById($conexion, $id);
        $convocatoria = mysqli_fetch_array($oferta_laboral);    

        $estado = determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Bolsa Laboral<?php include("../include/header_title.php"); ?></title>
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
      .Finalizado{
          background-color: #242424;
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
			<div class="right_col">
                <div class="row">
                    <div class="col-md-3 col-sm-3  ">
                        <section class="panel">
                            <div align="center">
                                <br>
                                <a href="convocatorias.php" class="btn btn-danger"><i class="fa fa-mail-reply"></i>  Regresar</a>
                                <br><br>
                            </div>
                            <div class="alert-error <?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?>" role="alert" align="center">
                                <strong><?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?></strong>
                            </div>
                            <div class="panel-body">
                                <div class="project_detail">
                                    <p class="title"><?php echo $convocatoria['titulo'] ?></p>
                                    
                                </div>
                                <br>
                                <ul class="list-unstyled project_files">
                                    <li><strong>Empresa: </strong> <br> <?php echo  buscarEmpresaByIdOferta($conexion, $id)?>
                                    </li>
                                    <li><strong>Lugar de Trabajo: </strong> <br> <?php echo $convocatoria['ubicacion'] ?>
                                    </li>
                                    <li><strong>Modalidad: </strong> <?php echo $convocatoria['modalidad'] ?>
                                    </li>
                                    <li><strong>Turno: </strong> <?php echo $convocatoria['turno'] ?>
                                    </li>
                                    <li><strong>Vacantes: </strong> <?php echo $convocatoria['vacantes'] ?>
                                    </li>
                                    <li><strong>Salario: </strong> <?php echo $convocatoria['salario'] ?>
                                    </li>
                                    <li><strong>Inicio de Convocatoria: </strong> <br> <?php echo $convocatoria['fecha_inicio'] ?>
                                    </li>
                                    <li><strong>Fin de Convocatoria: </strong> <br> <?php echo $convocatoria['fecha_fin'] ?>
                                    </li>
                                    <li><strong>Carreras de Interés: </strong> <br> 
                                    <p>
                                    <?php $programas = buscarProgramasByIdOferta($conexion,$convocatoria['id']);
                                        while ($programa = mysqli_fetch_array($programas)) {
                                            echo $programa['nombre']. "<br>";
                                        }
                                    ?>
                                    </p>
                                    </li>
                                </ul>
                                <h5><strong>Documentos de la convocatoria</strong></h5>
                                <ul class="list-unstyled project_files">
                                    <?php 
                                        $res = buscarDocumentosByIdOferta($conexion, $id);
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
                                <p><?php echo nl2br(htmlspecialchars($convocatoria['requisitos'])) ?></p>
                                <h4 class="title">Funciones</h4>
                                <p><?php echo nl2br(htmlspecialchars($convocatoria['funciones'])) ?></p>
                                <h4 class="title">Beneficios</h4>
                                <p><?php echo nl2br(htmlspecialchars($convocatoria['beneficios'])) ?></p>
                                <h4 class="title">Condiciones</h4>
                                <p><?php echo nl2br(htmlspecialchars($convocatoria['condiciones'])) ?></p>

                                <br />
                                <?php
                                if($estado == "En proceso") {
                                ?>
                                <div class="text-right mtop20">
                                    <a href="postular.php?id=<?php echo $convocatoria['id']; ?>"  class="btn btn-sm btn-primary">Quiero Postular</a>
                                </div>
                                <?php }?>
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