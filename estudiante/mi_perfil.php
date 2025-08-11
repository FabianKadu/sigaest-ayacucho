<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_estudiante.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
  $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
  $estudiante = mysqli_fetch_array($b_estudiante);
  $id_estudiante = $id_estudiante_sesion;
  /* OBTENER INFORMACIÓN NECESARIA DESDE LA DB */
  $res_estudiante = buscarEstudianteById($conexion, $id_estudiante);
  $estudiante=mysqli_fetch_array($res_estudiante);

  $res_programa = buscarCarrerasById($conexion, $estudiante['id_programa_estudios']);
  $programa=mysqli_fetch_array($res_programa);


  $semestre = "NO REGISTRADO";
  if($estudiante['id_semestre'] != 0){
    $res_semestre = buscarSemestreById($conexion, $estudiante['id_semestre']);
    $semestre=mysqli_fetch_array($res_semestre);
    $semestre = $semestre['descripcion'];
  }

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
	  
    <title>Estudiantes <?php include ("../include/header_title.php"); ?></title>
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

  </head>

  <body class="nav-md" onload="desactivar_controles();">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
          include ("include/menu.php"); ?>
            <!-- page content -->
            <div class="right_col" role="main">
                <div class="">
                    <div class="clearfix"></div>
                    <div class="row">
							<center><h4><b>MI PERFIL</b></h4></center>
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="x_panel">
                                <div class="">
                                    <!-- ENCABEZADO -->
                                    <div class="row">
                                        <div class="col-md-2 col-sm-4 col-xs-6">
                                            <img class="image img-responsive" src="../img/no-image.jpeg" alt="">
                                            <center>
                                                <!--<button class="btn btn-success">Actualizar Imagen</button>-->
                                            </center>
                                        </div>
                                        <div class="col-md-6 col-sm-4 col-xs-6">
                                            <h5>ESTUDIANTE: <b>
                                                    <?php echo $estudiante['apellidos_nombres'] ?>
                                                </b></h5>
                                            <h5>D.N.I.: <b>
                                                    <?php echo $estudiante['dni'] ?>
                                                </b></h5>
                                            <h5>SEMESTRE ACTUAL: <b>
                                                    <?php echo $semestre ?>
                                                </b></h5>
                                            <h5>CONDICIÓN: <b>
                                                    <?php
                                                    if($estudiante['egresado'] == "SI"){
                                                        echo "EGRESADO";
                                                    }else{
                                                        echo "ESTUDIANTE";
                                                    }
                                                     ?>
                                                </b></h5>
                                            <h5>PROGRAMA DE ESTUDIOS: <b>
                                                    <?php echo $programa['nombre'] ?>
                                                </b></h5>
                                            <h5>PLAN DE ESTUDIOS: <b>
                                                    <?php echo $programa['plan_estudio'] ?>
                                                </b></h5>
                                            <h5>AÑO DE INGRESO: <b>
                                                    <?php echo $estudiante['anio_ingreso'] ?>
                                                </b></h5>
                                        </div>
                                        <?php 
                                        $res_info_socio = buscarInfoSocioByIdEstudiante($conexion, $id_estudiante);
                                        $existe_registro = mysqli_num_rows($res_info_socio);
                                      
                                        if(!$existe_registro){
                                        ?>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <br>
                                            <div class="x_panel">
                                                <h5><b>Información Socioeconómica</b></h5>
                                                <p>Aun no a registrado su información socioeconómica, puedo ingresar al formulario mediante el siguiente botón.</p>
                                                <a href="informacion_socioeconomica.php" class="btn btn-warning">Registrar</a>
                                            </div>
                                        </div>
                                        <?php }else{?>
                                        <div class="col-md-4 col-sm-4 col-xs-12">
                                            <br>
                                            <div class="x_panel">
                                                <h5 class="green"><b>Información Socioeconómica</b></h5>
                                                <p>Gracias por completar el formulario de información socioeconómica.</p>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <div class="clearfix"></div>
                                    
                                </div>
                            </div>
                            <div class="x_panel">
                                <h4><b>Mi información</b></h4>
                                <hr>
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <form role="form" id="myform" action="operaciones/actualizar_datos_institucion.php" class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data">
                                    <input type="hidden" name="id" value="<?php echo $estudiante['id']; ?>">
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento: </label>
                                        <div class="col-md-7 col-sm-9 col-xs-12">
                                        <input type="date" class="form-control" name="fecha_nac" id="fecha_nac" value="<?php echo $estudiante['fecha_nac']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico: </label>
                                        <div class="col-md-7 col-sm-9 col-xs-12">
                                            <input type="email" class="form-control" name="correo" id="correo"  value="<?php echo $estudiante['correo']; ?>">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono o Celular: </label>
                                        <div class="col-md-7 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="telefono" id="telefono"  value="<?php echo $estudiante['telefono']; ?>"  oninput="validateInputNum(this, 9)">
                                        <br>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección Actual: </label>
                                        <div class="col-md-7 col-sm-9 col-xs-12">
                                            <input type="text" class="form-control" name="direccion" id="direccion" value="<?php echo $estudiante['direccion']; ?>">
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
      function validateInputNum(input, tamanio) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
          inputValue = inputValue.slice(0, tamanio);
          // Actualiza el valor del campo de entrada
          input.value = inputValue.toUpperCase();
      }
      
      function validateInputText(input, tamanio) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^a-zA-Z\s!"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]+/g, '');
          inputValue = inputValue.slice(0, tamanio);
          // Actualiza el valor del campo de entrada
          input.value = inputValue.toUpperCase();
      }
      function limitarTamanio(input, tamanio) {
        let inputValue = input.value;

        // Remueve cualquier carácter no permitido (en este caso, letras)
        //inputValue = inputValue.replace(/[^a-zA-Z\s!"#$%&'()*+,\-./:;<=>?@[\\\]^_`{|}~]+/g, '');
        inputValue = inputValue.slice(0, tamanio);
        // Actualiza el valor del campo de entrada
        input.value = inputValue.toUpperCase();
        }
    </script>

    <script type="text/javascript">
        function desactivar_controles(){
            document.getElementById('fecha_nac').disabled = true
            document.getElementById('correo').disabled = true
            document.getElementById('telefono').disabled = true
            document.getElementById('direccion').disabled = true
            document.getElementById('btn_cancelar').style.display = 'none'
            document.getElementById('btn_guardar').style.display = 'none'
            document.getElementById('btn_editar').style.display = ''
        };
        function activar_controles(){
            document.getElementById('fecha_nac').disabled = false
            document.getElementById('correo').disabled = false
            document.getElementById('telefono').disabled = false
            document.getElementById('direccion').disabled = false
            document.getElementById('btn_cancelar').removeAttribute('style')
            document.getElementById('btn_guardar').removeAttribute('style')
            document.getElementById('btn_editar').style.display = 'none'
        };
        function cancelar(){
            document.getElementById('myform').reset();
        }
    </script>

     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}
