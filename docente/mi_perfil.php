<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include 'include/verificar_sesion_docente_coordinador.php';

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {
  
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  $res_docente = buscarDocenteById($conexion, $id_docente_sesion);
  $docente = mysqli_fetch_array($res_docente);

  $res_programa = buscarCarrerasById($conexion, $docente['id_programa_estudio']);
  $programa=mysqli_fetch_array($res_programa);

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
	  
    <title>docentes <?php include ("../include/header_title.php"); ?></title>
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

        .card-content input[type="file"], .btn-guardar-foto {
            display: none;
        }

        .botones{
            display:flex;
            flex-wrap: wrap;
        }

        .delete-doc{
            cursor: pointer;
        }
        .delete-doc:hover{
            color: #132412;
        }
        #deleteModal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #deleteModal > div {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
  </head>
  <body class="nav-md" onload="desactivar_controles();">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
          if($docente['id_cargo'] == 5 ){
            include ("include/menu_docente.php");
           }elseif($docente['id_cargo'] == 3 ){
            include ("include/menu_jua.php");
           }
           else{
            include ("include/menu_coordinador.php");
           } ?>
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
                        <center><h4><b>MI PERFIL</b></h4></center>
                        <div class="col-md-8 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div class="">
                                    <!-- ENCABEZADO -->
                                    <div class="row">
                                        <div class="col-md-3 col-sm-4 col-xs-6">
                                            <img id="foto-perfil" class="image img-responsive" src="<?php echo $docente['foto'] ?>" alt="Foto del docente">
                                        </div>
                                        <div class="col-md-9 col-sm-8 col-xs-6">
                                            <h5>NOMBRES Y APELLIDOS: <b>
                                                    <?php echo $docente['apellidos_nombres'] ?>
                                                </b></h5>
                                            <h5>D.N.I.: <b>
                                                    <?php echo $docente['dni'] ?>
                                                </b></h5>
                                            <h5>CONDICIÓN LABORAL: <b>
                                                    <?php
                                                    echo $docente['cond_laboral'];
                                                     ?>
                                                </b></h5>
                                            <h5>PROGRAMA DE ESTUDIOS DESIGNADO EN EL SISTEMA: <b>
                                                    <?php echo isset($programa['nombre']) ? $programa['nombre']:"NO DESIGNADO" ?>
                                                </b></h5>
                                            <form action="operaciones/guardar_foto_docente.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                                <div class="card-content">
                                                    <input type="file" name="fotografia" id="upload" accept="image/*" onchange="loadImage(event)" required>
                                                    <div class="botones">
                                                        <button class="btn btn-primary btn-guardar-foto" id="btn-guardar" type="submit"><i class="fa fa-check"></i> Guardar Foto</button>
                                                        <label for="upload" class="btn btn-success" id="btn-select">Seleccionar imagen</label>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                </div>
                            </div>
                            <div class="x_panel">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <h4><b>Mi información</b></h4>
                                    <hr>
                                    <form role="form" id="myform" action="operaciones/actualizar_datos_docente.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Profesión: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" class="form-control" name="profesion" id="profesion" value="<?php echo $docente['profesion']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nivel de Educación: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="text" class="form-control" name="nivel_edu" id="nivel_edu" value="<?php echo $docente['nivel_educacion']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                        <input type="date" class="form-control" name="fecha_nac" id="fecha_nac" value="<?php echo $docente['fecha_nac']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="email" class="form-control" name="correo" id="correo"  value="<?php echo $docente['correo']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono o Celular: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="telefono" id="telefono"  value="<?php echo $docente['telefono']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección Actual: </label>
                                        <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $docente['direccion']; ?>">
                                        <br>
                                        </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12" align="center">
                                        <button type="submit" class="btn btn-primary" id="btn_guardar">Guardar</button> 
                                        <button type="button" class="btn btn-warning" id="btn_cancelar" onclick="desactivar_controles(); cancelar();">Cancelar</button>
                                    </div>
                                    </div>
                                    </form>
                                    <div class="col-md-12 col-sm-12 col-xs-12" align="center">
                                        <button type="button" class="btn btn-success" id="btn_editar" onclick="activar_controles();">Editar Datos</button>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6 col-xs-12">
                            <div class="x_panel">
                                <div>
                                    <div class="col-md-8 col-sm-6 col-xs-6">
                                        <h4><b>Mis documentos</b></h4>
                                    </div>
                                    <div class="col-md-4 col-sm-6 col-xs-6 text-right">
                                        <button class="btn btn-primary" data-toggle="modal" data-target=".registrar">Agregar</button>
                                    </div>
                                </div>
                                <br>
                                <div class="x_content">
                                    <p>Tenga en cuenta que los documentos cargados tienen que ser por periodo académico.</p>
                                    <div class="x_content">
                                        <ul class="list-unstyled project_files">
                                                <li><a href = "./imprimir_carga_academica.php?id_doc=<?php echo $id_docente_sesion; ?>&id_per=<?php echo $_SESSION['periodo']; ?>" target="_blank"><h5><i class="fa fa-file-pdf-o blue"></i> Generar Carga Académica del Presente Periodo</h5></a>
                                                </li>
                                                <hr>
                                            <?php 
                                            if(!is_null($docente['hoja_vida'])){
                                            ?>
                                                <li><a href = "<?php echo $docente['hoja_vida']; ?>" target="_blank"><h5><i class="fa fa-file-pdf-o blue"></i> Curriculo Vitae</h5></a>
                                                </li>
                                                <hr>
                                            <?php }else{
                                                echo "<li>No hay documentos para mostrar...</li>";
                                            } ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal (ejemplo básico) -->
                            <div id="deleteModal" style="display:none;">
                                <div>
                                    <h2>Eliminar Documento</h2>
                                    <form role="form" action="operaciones/eliminar_documento_docente.php" method="POST">
                                    <input type="hidden" id="docId" name="docId">
                                    <p>¿Está seguro que desea eliminar este documento?</p>
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                    <button type="button" class="btn" onclick="closeModal()">Cancelar</button>
                                    </form>
                                </div>
                            </div>

                            <!--MODAL REGISTRAR-->
                            <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                            <div class="modal-dialog modal-mg">
                                <div class="modal-content">

                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                    </button>
                                    <h4 class="modal-title" id="myModalLabel" align="center">Registrar Documento</h4>
                                </div>
                                <div class="modal-body">
                                    <!--INICIO CONTENIDO DE MODAL-->
                                    <div class="x_panel">

                                    <div class="" align="center">
                                        <h2></h2>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="x_content">
                                        <br />
                                        <form role="form" action="operaciones/registrar_documento_docente.php"
                                        class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Qué documento va ha subir?: </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                            <select type="text" class="form-control" name="titulo" required="required">
                                                <option value="Curriculo Vitae">Curriculo Vitae</option>
                                            </select>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Cargue su archivo: </label>
                                            <div class="col-md-9 col-sm-9 col-xs-12">
                                            <input type="file" class="form-control" name="documento" accept=".pdf"
                                                required="required">
                                            <br>
                                            </div>
                                        </div>
                                        <div align="center">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                            <button type="submit" class="btn btn-primary">Guardar</button>
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

    <script type="text/javascript">
        function desactivar_controles(){
            document.getElementById('fecha_nac').disabled = true
            document.getElementById('correo').disabled = true
            document.getElementById('telefono').disabled = true
            document.getElementById('direccion').disabled = true
            document.getElementById('nivel_edu').disabled = true
            document.getElementById('profesion').disabled = true
            document.getElementById('btn_cancelar').style.display = 'none'
            document.getElementById('btn_guardar').style.display = 'none'
            document.getElementById('btn_editar').style.display = ''
        };
        function activar_controles(){
            document.getElementById('fecha_nac').disabled = false
            document.getElementById('correo').disabled = false
            document.getElementById('telefono').disabled = false
            document.getElementById('nivel_edu').disabled = false
            document.getElementById('direccion').disabled = false
            document.getElementById('profesion').disabled = false
            document.getElementById('btn_cancelar').removeAttribute('style')
            document.getElementById('btn_guardar').removeAttribute('style')
            document.getElementById('btn_editar').style.display = 'none'
        };
        function cancelar(){
            document.getElementById('myform').reset();
        }
    </script>

    <script>
        function loadImage(event) {
            var file = event.target.files[0];
            var maxSize = 1024 * 1024;
        
            if (file.size > maxSize) {
                alert('El tamaño máximo permitido es de 1MB.');
                event.target.value = '';
                return false;
            }
        
            var img = new Image();
            img.onload = function () {
                document.getElementById('foto-perfil').src = this.src;
                document.getElementById('btn-guardar').style.display = "block";
        };
        img.src = URL.createObjectURL(file);
        }
        
    </script>

    <script>
        function showModal(id) {
        // Mostrar el modal
        document.getElementById('deleteModal').style.display = 'flex';
        
        // Colocar el id en el input oculto
        document.getElementById('docId').value = id;
        }

        function closeModal() {
        // Ocultar el modal
        document.getElementById('deleteModal').style.display = 'none';
        }
    </script>

     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}
