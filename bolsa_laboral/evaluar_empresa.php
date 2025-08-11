<?php
include("../include/conexion.php");
include("../empresa/include/consultas.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_administrador.php");

$id = isset($_GET['id']) ? $_GET['id'] : null;

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $res_emp = buscarEmpresaById($conexion, $id);
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
	<title>Empresas <?php include("../include/header_title.php"); ?></title>
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
          $res_docente = buscarDocenteById($conexion,$id_docente_sesion);
          $docente = mysqli_fetch_array($res_docente);
          if($docente['id_cargo'] == 7){
            include ("include/menu_administrador.php"); 
          }
          include("../docente/include/menu_secretaria.php"); ?>
			<!-- page content -->
			<div class="right_col">
                <div class="row">
                    <div class="col-md-3 col-sm-3">
                        <section class="panel">
                            <div class="panel-body">
                                <div class="project_detail">
                                    <h3 class="title"><?php echo $empresa['razon_social'] ?></h3>
                                    <p>R.U.C. <?php echo $empresa['ruc'] ?></p>
                                </div>
                                <br>
                                <ul class="list-unstyled project_files">
                                    <li><strong>Ubicación: </strong> <br> <?php echo $empresa['ubicacion'] ?>
                                    </li>
                                </ul>
                                <br />
                                <h5><strong>Información de contacto</strong></h5>
                                <ul class="list-unstyled project_files">
                                    <li><a href><i class="fa fa-user"></i> <?php echo $empresa['contacto']?></a>
                                    </li>
                                    <li><a href><i class="fa fa-user"></i> <?php echo $empresa['cargo']?></a>
                                    </li>
                                    <li><a href><i class="fa fa-user"></i><?php echo $empresa['correo_institucional'] ?></a>
                                    </li>
                                    <li><a href><i class="fa fa-user"></i><?php echo $empresa['celular_telefono'] ?></a>
                                    </li>
                                </ul>
                                
                            </div>
                        </section>
                    </div>
                    <div class="col-md-9 col-sm-9  ">
                        <section class="panel">
                            <div class="x_title">
                                <h2 class="blue">Formulario de Aceptación o Rechazo</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <form action="operaciones/evaluar_empresa.php" class="form-horizontal form-label-center"  method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="id" name="id" value=<?php echo '"'.$id.'"'; ?> class="form-control  ">
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="motivo"><i class="fa fa-file-pdf-o"></i> Sustento * :
                                        </label>
                                        <div class="">
                                            <textarea id="motivo" name="motivo" required="required" class="form-control" rows="3"></textarea>
                                        </div>
                                    </div>
                                    <br>
                                    <p>* Al aceptar, se enviara un correo al correo de la institución, adjuntando el usuario y contraseña para el acceso al sistema.</p>
                                    <br>
                                    <div align="center">
                                        <button type="submit" class="btn btn-danger" name="rechazar">Rechazar</button>
                                        <button type="submit" class="btn btn-primary" name="aceptar">Aceptar</button>
                                    </div>
                                </form>
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