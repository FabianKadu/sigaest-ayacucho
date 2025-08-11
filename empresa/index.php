<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("include/consultas.php");
include("include/verificar_sesion_empresa.php");
include("operaciones/sesiones.php");
include("../include/funciones.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                window.location.replace('login/');
    		</script>";
} else {

    $id_empresa = $_SESSION['id_emp'];
    $res_emp = buscarEmpresaById($conexion, $id_empresa);
    $empresa = mysqli_fetch_array($res_emp);
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
                            <div class="panel-body">
                                <div class="project_detail" align="center" >
                                    <img src="<?php echo $empresa['ruta_logo'] ?>" height="80" width="80" target = "Logo de la empresa" class="img-circle ">
                                    <h3 class="title"><?php echo $empresa['razon_social'] ?></h3>
                                    <p>R.U.C. <?php echo $empresa['ruc'] ?></p>
                                </div>
                                <ul class="list-unstyled project_files">
                                    <li><strong>Ubicación: </strong> <br> <?php echo $empresa['ubicacion'] ?>
                                    </li>
                                </ul>
                                <h5><strong>Información de contacto</strong></h5>
                                <ul class="list-unstyled project_files">
                                    <li><a><i class="fa fa-user"></i> <?php echo $empresa['contacto']?></a>
                                    </li>
                                    <li><a ><i class="fa fa-user"></i> <?php echo $empresa['cargo']?></a>
                                    </li>
                                    <li><a ><i class="fa fa-user"></i><?php echo $empresa['correo_institucional'] ?></a>
                                    </li>
                                    <li><a ><i class="fa fa-user"></i><?php echo $empresa['celular_telefono'] ?></a>
                                    </li>
                                </ul>
                                <br>
                                <div align="center">
                                    <a href="editar_empresa.php" class="btn btn-success" data-toggle="tooltip" data-original-title="Actualizar información" data-placement="bottom"><i class="fa fa-edit"></i> Editar</a>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-9 col-sm-9  ">
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