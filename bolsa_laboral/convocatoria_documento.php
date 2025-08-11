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
  
    $oferta_laboral = buscarOfertaLaboralByIdIestp($conexion, $id);
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
                    <div class="col-md-3 col-sm-3  ">
                        <section class="panel">
                            <div align="center">
                                <br>
                                <a href="convocatorias.php" class="btn btn-danger"><i class="fa fa-mail-reply"></i>  Regresar</a>
                                <br><br>
                                
                            </div>
                            <div class="alert-info <?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?>" role="alert" align="center">
                                <strong><?php echo determinarEstado($convocatoria['fecha_inicio'], $convocatoria['fecha_fin'])?></strong>
                            </div>
                            <div class="panel-body">
                                <p><b>Usted puede agregar los documentos en formato PDF de la siguiente convocatoria: </b></p>
                                <div class="project_detail">
                                    <p class="title"><?php echo $convocatoria['titulo'] ?></p>
                                </div>
                                <ul class="list-unstyled project_files">
                                    <li><strong>Empresa: </strong> <br> <?php echo $convocatoria['empresa']?>
                                    </li>
                                </ul>
                                <br>
                                <h5><strong>Agregar documentos</strong></h5>
                                
                                <form action="operaciones/agregar_documentos.php" class="form-horizontal form-label-center"  method="POST" enctype="multipart/form-data">
                                    <input type="hidden" id="id" name="id" value=<?php echo '"'.$id.'"'; ?> class="form-control  ">
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="nombre"><i class="fa fa-file-pdf-o"></i> Nombre * :
                                        </label>
                                        <div class="">
                                            <input type="text" id="nombre" name="nombre" required="required" class="form-control  ">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-form-label label-align" for="tipo"><i class="fa fa-file-pdf-o"></i> Tipo de Documento * :
                                        </label>
                                        <div class="">
                                            <select class="form-control" id="tipo" name="tipo_documento" required>
                                                <option value="BASES">Bases</option>
                                                <option value="CRONOGRAMA">Cronograma</option>
                                                <option value="RESULTADO">Resultados</option>
                                                <option value="OTRO">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="col-form-label label-align" for="first-name"><i class="fa fa-file-pdf-o"></i> Subir Documento * :
                                        </label>                                                
                                        <div class="">
                                            <div class="container-input">
                                                <input type="file" name="documento" id="file-3" class="inputfile inputfile-3" data-multiple-caption="{count} archivos seleccionados" multiple required="required" accept=".pdf" onchange="validarArchivo(event)" />
                                                <label for="file-3">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                                <span class="iborrainputfile">Seleccionar archivo</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div align="center">
                                            <input class="btn btn-success" type="submit" value="Registrar">
                                    </div>
                                </form>
                            </div>
                        </section>
                    </div>
                    <div class="col-md-9 col-sm-9  ">
                        <section class="panel">
                            <div class="x_title">
                                <h2 class="blue">Detalle de convocatoria</h2>
                                <div class="clearfix"></div>
                            </div>
                            <div class="panel-body">
                                <table id="example" class="table table-striped table-bordered" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>Tipo de documento</th>
                                        <th>Nombre de documento</th>
                                        <th>Acciones</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <?php 
                                        $res = buscarDocumentosByIdOfertaIestp($conexion, $id);
                                        while ($documento=mysqli_fetch_array($res)){
                                    ?>
                                    <tr>
                                        <td><?php echo $documento['tipo_documento']; ?></td>
                                        <td><?php echo $documento['nombre_documento']; ?></td>
                                        <td>
                                        <a href="<?php echo $documento['url_documento']; ?>" target="_blank" class="btn btn-primary" data-toggle="tooltip" data-original-title="Ver documento" data-placement="bottom"><i class="fa fa-eye"></i></a>
                                        <a href="operaciones/eliminar_documentos.php?id=<?php echo $documento['id'] ?>" class="btn btn-danger" data-toggle="tooltip" data-original-title="Eliminar" data-placement="bottom"><i class="fa fa-trash"></i></a>
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
    <script>
        function validarArchivo(event) {
            const archivo = event.target.files[0];
            const maxSize = 5 * 1024 * 1024; // 5 MB en bytes

            if (archivo) {
                if (archivo.size > maxSize) {
                    alert('El archivo es demasiado grande. El tamaño máximo permitido es de 5 MB.');
                    event.target.value = ''; // Limpiar el valor del input file
                }
            }
        }
    </script>
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