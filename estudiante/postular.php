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
      .finalizar{
        background-color: #F0AD4E;
      }
      .Finalizado{
        background-color: #D9534F;
      }
      .attributes {
        display: flex;
        }

        .attributes .attribute {
            margin-right: 20px;
        }

         .attributes .attribute:last-child {
            margin-right: 0;
        }

       .attribute-icon {
            display: flex;
            align-items: center;
            margin-right: 10px;
        }

        .attribute-icon i {
            margin-right: 5px;
        }

        .container-input {
            text-align: center;
            border: 1px solid #dfdfdf;
            padding: 50px 0;
            border-radius: 6px;
            margin: 0 auto;
            margin-bottom: 20px;
        }
        .inputfile {
            width: 0.1px;
            height: 0.1px;
            opacity: 0;
            overflow: hidden;
            position: absolute;
            z-index: -1;
        }
        .inputfile + label {
            max-width: 80%;
            font-size: 1.25rem;
            font-weight: 700;
            text-overflow: ellipsis;
            white-space: nowrap;
            cursor: pointer;
            display: inline-block;
            overflow: hidden;
            padding: 0.625rem 1.25rem;
        }
        .inputfile + label svg {
            width: 1em;
            height: 1em;
            vertical-align: middle;
            fill: currentColor;
            margin-top: -0.25em;
            margin-right: 0.25em;
        }
        .iborrainputfile {
            font-size:16px; 
            font-weight:normal;
        }
        .inputfile-3 + label {
            color: #242424;
        }
        .inputfile-3:focus + label,
        .inputfile-3.has-focus + label,
        .inputfile-3 + label:hover {
            color: blue;
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
                    <form role="form" action="operaciones/registrar_postulacion.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data" >
                        <div class="col-md-4 col-sm-4  ">
                            <section class="panel">
                                <div class="x_title">
                                    <h2 class="blue">Datos del postulante</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <input type="hidden" name="id" value="<?php echo $r_b_estudiante['id']; ?>">
                                    <input type="hidden" name="tipo" value=1>
                                    <input type="hidden" name="convocatoria" value="<?php echo $convocatoria['id']; ?>">
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="dni"><i class="fa fa-lock"> </i>  D.N.I.:
                                        </label>
                                        <div class="">
                                            <input type="text" id="dni" name="dni" required="required" class="form-control" value="<?php echo $r_b_estudiante['dni']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="nombre"><i class="fa fa-lock"></i> Apellidos y nombres:
                                        </label>
                                        <div class="">
                                            <input type="text" id="nombre" name="nombre" required="required" class="form-control" value="<?php echo $r_b_estudiante['apellidos_nombres']; ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="direccion"><i class="fa fa-pencil-square-o"> </i> Dirección * :
                                        </label>
                                        <div class="">
                                            <input type="text" id="direccion" name="direccion" required="required" class="form-control" value="<?php echo $r_b_estudiante['direccion']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="celular"><i class="fa fa-pencil-square-o"> </i> Celular/Telefono * :
                                        </label>
                                        <div class="">
                                            <input type="text" id="celular" name="celular" required="required" class="form-control" value="<?php echo $r_b_estudiante['telefono']; ?>">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="correo"><i class="fa fa-pencil-square-o"> </i> Correo Electrónico * :
                                        </label>
                                        <div class="">
                                            <input type="text" id="correo" name="correo" required="required" class="form-control" value="<?php echo $r_b_estudiante['correo']; ?>">
                                        </div>
                                    </div>
                                    <br>
                                    <p><strong>* La información que modifiqué, tambien será actualizado en el sistema.</strong></p>
                                </div>
                            </section>
                        </div>
                        <div class="col-md-8 col-sm-8  ">
                            <section class="panel">
                                <div class="x_title">
                                    <h2 class="blue">Detalle de convocatoria</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <h5 class="title"><strong><?php echo $convocatoria['titulo'] ?></strong></h5>
                                    <div class="attributes">
                                        <div class="attribute attribute-icon">
                                            <i class="fa fa-map-marker"></i>
                                            <span><?php echo $convocatoria['ubicacion'] ?></span>
                                        </div>
                                        <div class="attribute attribute-icon">
                                            <i class="fa fa-circle"></i>
                                            <span><?php echo $convocatoria['modalidad'] ?></span>
                                        </div>
                                        <div class="attribute attribute-icon">
                                            <i class="fa fa-circle"></i>
                                            <span><?php echo $convocatoria['turno'] ?> </span>
                                        </div>
                                        <div class="attribute attribute-icon">
                                            <i class="fa fa-circle"></i>
                                            <span><?php echo $convocatoria['vacantes'] ?> vacante(s)</span>
                                        </div>
                                    </div>
                                </div>
                            </section>
                            <section class="panel">
                                <div class="x_title">
                                    <h2 class="blue">Subir documento</h2>
                                    <div class="clearfix"></div>
                                </div>
                                <div class="panel-body">
                                    <p> <strong>* Subir solo los requisitos propuestos en las bases del concurso, todos los requisitos dede de estar en un solo archivo PDF, de lo contrario no sera evaluado.</strong></p>
                                    <div class="form-group">
                                        <label class="col-form-label label-align" for="first-name"><i class="fa fa-file-pdf-o"></i> Subir Documento * :
                                        </label>                                                
                                        <div class="">
                                            <div class="container-input">
                                                <input type="file" name="documento" id="file-3" class="inputfile inputfile-3" data-multiple-caption="{count} archivos seleccionados" multiple required="required" accept=".pdf" />
                                                <label for="file-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                                <span class="iborrainputfile">Seleccionar archivo</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div align="center">
                                    <button type="submit"  class="btn btn-sm btn-primary">Confirmar Postulación</button>
                                </div>
                                </div>
                            </section>
                        </div>
                    </form>
                </div>
			</div>
			<?php
			include("../include/footer.php");
			?>
			<!--/footer content -->
		</div>
	</div>
    <script>
        'use strict';
        ;( function ( document, window, index )
        {
            var inputs = document.querySelectorAll( '.inputfile' );
            Array.prototype.forEach.call( inputs, function( input )
            {
                var label	 = input.nextElementSibling,
                    labelVal = label.innerHTML;
                input.addEventListener( 'change', function( e )
                {
                    var fileName = '';
                    if( this.files && this.files.length > 1 )
                        fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
                    else
                        fileName = e.target.value.split( '\\' ).pop();
                    if( fileName )
                        label.querySelector( 'span' ).innerHTML = fileName;
                    else
                        label.innerHTML = labelVal;
                });
            });
        }( document, window, 0 ));
    </script>
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