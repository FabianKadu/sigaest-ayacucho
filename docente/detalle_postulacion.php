<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

$id_postulante = $_GET['id'];

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

    //recuperar postulantes
    $res_postulante = buscarPostulantePorId($conexion, $id_postulante);
    $postulante = mysqli_fetch_array($res_postulante);

    //recuperar detalle de postulacion
    $res_detalle_post = buscarDetallePostulacionPorIdPostulante($conexion, $id_postulante);
    $detalle_post = mysqli_fetch_array($res_detalle_post);
    $codigo_post = $detalle_post['Codigo_Unico'];
    $id_detalle_post = $detalle_post['Id'];
    $estado_detalle_post = $detalle_post['Estado'];
    
    //recuperar colegio
    $res_colegio = buscarDetalleColegioPorId($conexion, $detalle_post['Id_Detalle_Colegio']);
    $colegio = mysqli_fetch_array($res_colegio);

    //buscar colegio detalle
    $res_colegio_db = obtenerColegioByID($conexion, $colegio['Id_Colegio']);
    $colegio_db = mysqli_fetch_array($res_colegio_db);

    //recuperar modalidad
    $res_modalidad = buscarModalidadPorId($conexion, $detalle_post['Id_Modalidad']);
    $modalidad = mysqli_fetch_array($res_modalidad);

    //recuperar proceso de admision
    $res_admision = buscarProcesoAdmisionPorId($conexion, $detalle_post['Id_Proceso_Admision']);
    $admision = mysqli_fetch_array($res_admision);

    //recuperar programa primera opcion
    $res_programa = buscarCarrerasById($conexion, $detalle_post['Id_Programa_Estudio']);
    $programa = mysqli_fetch_array($res_programa);

    //recuperar metodo de pago
    $res_metodo_pago = buscarTodosMetodosPagoPorId($conexion, $detalle_post['Id_Metodo_Pago']);
    $metodo_pago = mysqli_fetch_array($res_metodo_pago);

    $res_segunda_opcion = $detalle_post['Id_Segunda_Opcion'];
    $segunda_opcion = "No Indicado";

    if($res_segunda_opcion > 0){
        //recuperar programa segunda opcion
        $res_programa2 = buscarCarrerasById($conexion, $detalle_post['Id_Segunda_Opcion']);
        $cont_programa2 = mysqli_num_rows($res_programa2);
        if($cont_programa2 > 0){
            $programa2 = mysqli_fetch_array($res_programa2);
            $segunda_opcion = $programa2['nombre'];
        }
    }
    

    //FUNCION PARA DETERMINAR EDAD
    function calcularEdad($fechaNacimiento) {
        // Fecha actual
        $hoy = new DateTime();
        // Fecha de nacimiento
        $cumpleanos = new DateTime($fechaNacimiento);
        // Diferencia entre la fecha actual y la fecha de nacimiento
        $edad = $hoy->diff($cumpleanos);
        // Obtener solo el año de la diferencia
        $anios = $edad->y;
        // Devolver la edad en años
        return $anios;
    }

    //DOCUMENTOS DEL POSTULANTE
    $documentos = obtenerDocumentosdeDePostulancion($conexion, $id_detalle_post);
    $documentos_modal = obtenerDocumentosdeDePostulancion($conexion, $id_detalle_post);
    
?>
<!DOCTYPE html>
<html lang="es">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<!-- Meta, title, CSS, favicons, etc. -->
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Detalle Postulante <?php include("../include/header_title.php"); ?></title>
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
        .uppercase-table td, .uppercase-table th {
            text-transform: uppercase;
            text-align: center;
        }
    </style>
    <style>
        /* Estilos para los inputs con línea inferior */
        .form-group input[type="text"],
        .form-group input[type="email"],
        .form-group input[type="password"],
        .form-group  {
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
            padding: 20px 0;
            border-radius: 6px;
            margin: 0 auto;
            padding-bottom: 3px;
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

        /* Centrar el modal vertical y horizontalmente */
        .modal {
            text-align: center;
            padding: 0!important;
        }

        .modal:before {
            content: '';
            display: inline-block;
            height: 100%;
            vertical-align: middle;
            margin-right: -4px; /* Adjusts for spacing */
        }

        .modal-dialog {
            display: inline-block;
            text-align: left;
            vertical-align: middle;
        }

        /* Ajustar el tamaño del visor de PDF al ancho del modal */
        #pdfViewer {
            width: 100%;
        }

        .image-container {
        width: 200px;
        height: 280px;
        margin: 0 auto;
        overflow: hidden;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .image-container img {
        width: 100%;
        max-height: 100%;
        width: 200px;
        height: 280px;
        object-fit: cover;
        display: block;
    }
    </style>

    

</head>
<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<?php
			include("include/menu_secretaria.php"); ?>
			<!-- page content -->
			<div class="right_col">
                <div class="row">
                    <div class="x_title">
                                <h2 class="blue"><b>CÓDIGO DE POSTULACIÓN: <?php echo $codigo_post; ?> </b></h2>
                                <div class="clearfix"></div>
                    </div>
                    <div class="col-xs-12 col-lg-3"> <!-- En dispositivos extra pequeños y pequeños, ocupa todo el ancho. En dispositivos medianos y grandes, ocupa la mitad del ancho -->
                        <section class="panel">
                            <div class="panel-body">
                                <br>
                                <center>
                                <div class="project_detail">
                                    <div class="profile_center">
                                        <div class="profile_img">
                                            <div class="image-container">
                                                <img id="carnet-image" src="../admision/<?php echo $postulante['Fotografia'] ?>" alt="Carnet Image">
                                            </div>
                                        </div>
                                        <h4><?php echo $postulante['Apellido_Paterno'].' '.$postulante['Apellido_Materno']. ' ' . $postulante['Nombres']  ?></h4>
                                    </div>
                                </div>
                                </center>
                                <div class="documentos">
                                    <?php while($documento = mysqli_fetch_array($documentos)){
                                        if($documento['Titulo'] !== 'Fotografías'){ ?>
                                        <div>
                                            <a href="#" class="col-xs-12" onclick="abrirModal('<?php echo '../admision/'.$documento['Documento']; ?>')">
                                            <span align="start" class="gray"><i class="fa fa-file-o red"></i><b> <?php echo $documento['Titulo']; ?></b></span>
                                        </a><br><br>
                                        </div>
                                    <?php }} ?>
                                </div>
                                
                                <div>
                                    </table>
                                        <h5><b>Información Complementaria</b></h5>
                                        <table  class="table-bordered table uppercase-table">
                                            <tr bgcolor="#f2f2f2">   
                                                <th align="center">MÉTODO DE PAGO</th>
                                            </tr>
                                            <tr>
                                                <td align="center"><?php echo $metodo_pago['Metodo'];?></td>    
                                            </tr>
                                            <tr bgcolor="#f2f2f2">   
                                                <th align="center">MEDIO DE DIFUSIÓN</th>
                                            </tr>
                                            <tr>
                                                <td align="center"><?php echo $detalle_post['Medio_Difusion'];?></td>    
                                            </tr>
                                        </table>
                                </div>
                            </div>
                        </section>
                    </div>
                    <div class="col-xs-12 col-lg-9"> <!-- En dispositivos extra pequeños y pequeños, ocupa todo el ancho. En dispositivos medianos y grandes, ocupa la mitad del ancho -->
                        <section class="panel">
                            
                            <div class="panel-body">
                                <h4><b>Datos de Postulante</b></h4>
                                <table  class="table-bordered table uppercase-table">
                                    <tr bgcolor="#f2f2f2">   
                                        <th align="center">DNI</th>
                                        <th  align="center">APELLIDO PATERNO</th>
                                        <th  align="center">APELLIDO MATERNO</th>
                                        <th  align="center">NOMBRES</th>
                                    </tr>
                                    <tr>
                                        <td align="center"><?php echo $postulante['Dni'];?></td>    
                                        <td align="center"><?php echo $postulante['Apellido_Paterno'];?></td>
                                        <td align="center"><?php echo $postulante['Apellido_Materno'];?></td>
                                        <td align="center"><?php echo $postulante['Nombres'];?></td>
                                    </tr>

                                    <tr bgcolor="#f2f2f2">
                                        <th align="center">GÉNERO</th>    
                                        <th align="center">FECHA NACIMIENTO</th>
                                        <th  align="center">EDAD</th>
                                        <th align="center">ESTADO CIVIL</th>
                                    </tr>
                                    <tr>
                                    <td align="center"><?php if($postulante['Sexo']== 0) echo "Masculino";
                                        else echo "Femenino";?></td>
                                        <td align="center"><?php echo $postulante['Fecha_Nacimiento'];?></td>
                                        <td align="center"><?php echo calcularEdad($postulante['Fecha_Nacimiento']);?></td>
                                        <td align="center"><?php echo $postulante['Estado_Civil'];?></td>        
                                    </tr>

                                    <tr bgcolor="#f2f2f2">
                                        <th align="center">LENGUA MATERNA</th>
                                        <th align="center">NÚMERO CELULAR</th>
                                        <th colspan="2" align="center">CORREO ELECTRÓNICO</th>
                                    </tr>
                                    <tr>
                                        <td align="center"><?php echo $postulante['Lengua_Materna'];?></td>
                                        <td align="center"><?php echo $postulante['Celular'];?></td>
                                        <td colspan="2" align="center"><?php echo $postulante['Correo'];?></td>
                                    </tr>

                                    <tr bgcolor="#f2f2f2">
                                        <th  colspan="1" align="center">DISCAPACITADO?</th>
                                        <th colspan="3" align="center">TIPO DISCAPACIDAD</th>
                                    </tr>
                                    <tr>
                                        <td  colspan="1" align="center"><?php if($postulante['Presenta_Discapacidad'] == "0") echo "No";
                                        else echo "SI";?></td>
                                        <td colspan="3" align="center"><?php if($postulante['Tipo_Discapacidad'] == "") echo "No Indicado";
                                        else echo $postulante['Tipo_Discapacidad']; ?></td>
                                    </tr>
                                </table>
                                <?php if(calcularEdad($postulante['Fecha_Nacimiento']) < 18){ ?>
                                    <h4><b>Dato de Padres o Apoderado</b></h4>
                                    <table  class="table-bordered table uppercase-table">
                                        <tr bgcolor="#f2f2f2">   
                                            <th align="center">DNI</th>
                                            <th  align="center">APELLIDOS</th>
                                            <th  align="center">NOMBRES</th>
                                            <th  align="center">CELULAR</th>
                                        </tr>
                                        <tr>
                                            <td align="center"><?php echo $detalle_post['Dni_Apoderado'];?></td>    
                                            <td align="center"><?php echo $detalle_post['Apellidos_Apoderado'];?></td>
                                            <td align="center"><?php echo $detalle_post['Nombres_Apoderado'];?></td>
                                            <td align="center"><?php echo $detalle_post['Celular_Apoderado'];?></td>
                                        </tr>
                                    </table>
                                <?php } ?>
                                <h4><b>Procedencia y Colegio</b></h4>
                                <table  class="table-bordered table uppercase-table">
                                    <tr bgcolor="#f2f2f2">
                                        <th  colspan="1" align="center">INSITUCIÓN</th>
                                        <th colspan="2" align="center">CÓDIGO MODULAR</th>
                                        <th colspan="1" align="center">TIPO</th>
                                    </tr>
                                    <tr>
                                        <td  colspan="1" align="center"><?php echo $colegio_db['Nombre'];?></td>
                                        <td colspan="2" align="center"><?php echo $colegio_db['Codigo_Modular'];?></td>
                                        <td colspan="1" align="center"><?php echo $colegio['Tipo'];?></td>
                                    </tr>
                                    <tr bgcolor="#f2f2f2">
                                        <th align="center">DISTRITO</th>    
                                        <th align="center">PROVINCIA</th>
                                        <th  align="center">REGIÓN</th>
                                        <th align="center">AÑO DE EGRESO</th>
                                    </tr>
                                    <tr>
                                        <td align="center"><?php echo $colegio_db['Distrito'];?></td>
                                        <td align="center"><?php echo $colegio_db['Provincia'];?></td>
                                        <td align="center"><?php echo $colegio_db['Departamento'];?></td>        
                                        <td align="center"><?php echo $colegio['Anio_Egreso'];?></td>        
                                    </tr>

                                </table>
                                <h4><b>Modalidad y Programa</b></h4>
                                <table  class="table-bordered table uppercase-table">
                                    <tr bgcolor="#f2f2f2">
                                        <th  colspan="2" align="center">PROCESO DE ADMISION</th>
                                        <th colspan="2" align="center">MODALIDAD</th>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="center"><?php echo $admision['Tipo'];?></td>
                                        <td colspan="2" align="center"><?php echo $modalidad['Descripcion'];?></td>
                                    </tr>
                                    <tr bgcolor="#f2f2f2">
                                        <th  colspan="2" align="center">PROGRAMA DE ESTUDIOS</th>
                                        <th colspan="2" align="center">SEGUNDA OPCIÓN</th>
                                    </tr>
                                    <tr>
                                        <td  colspan="2" align="center"><?php echo $programa['nombre'];?></td>
                                        <td colspan="2" align="center"><?php echo $segunda_opcion;?></td>
                                    </tr>
                                </table>
                                 <!--MODAL APTO-->
                                 <?php if($estado_detalle_post == '1' or $estado_detalle_post == '4'){ ?>
                                 <div align="right">
                                    <button title="Editar documento" class="btn btn-warning" data-toggle="modal" data-target=".observar">OBSERVAR</button>
                                    <button title="Editar documento" class="btn btn-success" data-toggle="modal" data-target=".aceptar">MARCAR COMO APTO</button>
                                 </div>
                                 <?php }?>

                                <div class="modal fade aceptar" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-lg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel" align="center">Cargar Ficha de Postulante Emitido por el Sistema Registra</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                        <!--INICIO CONTENIDO DE MODAL-->
                                                    <div class="x_panel">
                                                    
                                                
                                                <div class="x_content">
                                                    <br />
                                                    <form role="form" action="operaciones/evaluar_postulacion.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_detalle_post ?>">
                                                    <input type="hidden" name="correo" value="<?php echo $postulante['Correo'] ?>">
                                                    <input type="hidden" name="tipo" value="aceptado">
                                                    
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
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                                        <!--FIN DE CONTENIDO DE MODAL-->
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="modal fade observar" tabindex="-1" role="dialog" aria-hidden="true">
                                                    <div class="modal-dialog modal-mg">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                                        </button>
                                                        <h4 class="modal-title" id="myModalLabel" align="center">Obervaciones</h4>
                                                        </div>
                                                        <div class="modal-body">
                                                        <!--INICIO CONTENIDO DE MODAL-->
                                                    <div class="x_panel">
                                                    
                                                
                                                <div class="x_content">
                                                    <form role="form" action="operaciones/evaluar_postulacion.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                                    <input type="hidden" name="id" value="<?php echo $id_detalle_post ?>">
                                                    <input type="hidden" name="correo" value="<?php echo $postulante['Correo'] ?>">
                                                    <input type="hidden" name="tipo" value="observado">
                                                    <h4><b>Seleccione las observaciones</b></h4>
                                                    <?php while($documento = mysqli_fetch_array($documentos_modal)){
                                                    ?>
                                                        <div class="">
                                                            <label>
                                                                <input type="checkbox" name="requisitos_observados[]" value="<?php echo $documento['Id'] ?>">  <b><?php echo $documento['Titulo']; ?></b>
                                                            </label>
                                                        </div>
                                                    <?php } ?>
                                                    <br>
                                                    <div class="form-group row">
                                                        <label class="col-form-label label-align" for="motivo"><i class="fa fa-file-pdf-o"></i> Explique las observaciones * :
                                                        </label>
                                                        <div class="">
                                                            <textarea id="motivo" name="observacion" required="required" class="form-control" rows="3"></textarea>
                                                        </div>
                                                    </div>
                                                    
                                                    <div align="center">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
                                                    <button type="submit" class="btn btn-success">Aceptar</button>
                                                    </div>
                                                </form>

                                                </div>
                                            </div>
                                                        <!--FIN DE CONTENIDO DE MODAL-->
                                        </div>
                                    </div>
                                    </div>
                                </div>
                            </div>
                        </section>
                        <!-- Modal VISOR DE PDF -->
                        <div id="modalPDF" class="modal fade" role="dialog">
                            <div class="modal-dialog modal-lg">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Visor de PDF</h4>
                                    </div>
                                    <div class="modal-body">
                                        <iframe id="pdfViewer" src="" frameborder="0" style="width: 100%; height: 500px;"></iframe>
                                    </div>
                                </div>
                            </div>
                        </div>
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

    <script>
        function abrirModal(rutaPDF) {
            var pdfViewer = document.getElementById("pdfViewer");
            pdfViewer.src = rutaPDF;
            $('#modalPDF').modal('show');
        }

        $('#modalPDF').on('hidden.bs.modal', function () {
            var pdfViewer = document.getElementById("pdfViewer");
            pdfViewer.src = ''; // Limpia la fuente del visor de PDF al cerrar el modal
        });
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