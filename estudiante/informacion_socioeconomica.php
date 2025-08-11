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
  
  $res_programa = buscarCarrerasById($conexion, $estudiante['id_programa_estudios']);
  $programa=mysqli_fetch_array($res_programa);

  $res_info_socio = buscarInfoSocioByIdEstudiante($conexion, $id_estudiante);
  $existe_registro = mysqli_num_rows($res_info_socio);

  if($existe_registro){
    echo "<script>
        alert('Usted ya registro su información socioeconómica');
        window.history.back();
        </script>";
    exit;
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
						<center><h4><b>REGISTRO DE INFORMACIÓN SOCIOECONÓMICA</b></h4></center>
                        <form action="operaciones/registrar_informacion_socioeconomico.php" method="POST">
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <h5><b>1. DATOS GENERALES</b></h5>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Apellidos y nombres:
                                        </label>
                                        <div class="">
                                            <input type="text" class="form-control" name="ap_nombres" required="required"
                                            value="<?php echo $estudiante['apellidos_nombres'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Correo electrónico:
                                        </label>
                                        <div class="">
                                            <input type="email" class="form-control" name="correo" required="required"
                                            value="<?php echo $estudiante['correo'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">N° de celular:
                                        </label>
                                        <div class="">
                                            <input type="text" class="form-control" name="celular" required="required" oninput="validateInputNum(this, 9)"
                                            value="<?php echo $estudiante['telefono'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Domicilio:
                                        </label>
                                        <div class="">
                                            <input type="text" class="form-control" name="domicilio" required="required"
                                            value="<?php echo $estudiante['direccion'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Fecha de nacimiento:
                                        </label>
                                        <div class="">
                                            <input type="date" class="form-control" name="fecha_nac" required="required"
                                            value="<?php echo $estudiante['fecha_nac'] ?>">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-4 col-sm-6 col-xs-6">
                                        <label class="control-label">Edad:
                                        </label>
                                        <div class="">
                                            <input type="number" class="form-control" name="edad" required="required" oninput="validateInputNum(this, 2)">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-8 col-sm-6 col-xs-6">
                                        <label class="control-label">Género:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="genero" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="Masculino" <?php if($estudiante['id_genero']== 1){
                                                    echo "selected";
                                                } ?>>Masculino</option>
                                                <option value="Femenino" <?php if($estudiante['id_genero']== 2){
                                                    echo "selected";
                                                } ?>>Femenino</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <h5><b>2. ASPECTO FAMILIAR</b></h5>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Con quién o quienes vives?:
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label><input type="checkbox" name="familiares[]" value="Solo"> Solo</label><br>
                                            <label><input type="checkbox" name="familiares[]" value="Padres"> Padres</label><br>
                                            <label><input type="checkbox" name="familiares[]" value="Hermano(s)"> Hermano(s)</label><br>
                                            <label><input type="checkbox" name="familiares[]" value="Abuelos"> Abuelos</label><br>
                                            <label><input type="checkbox" name="familiares[]" value="Tíos"> Tíos</label><br>
                                            <label><input type="checkbox" name="familiares[]" value="Otros"> Otros</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Cuántas personas viven contigo?:
                                        </label>
                                        <div class="">
                                            <input type="number" class="form-control" name="cant_fami" required="required" oninput="validateInputNum(this, 2)">
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Qué problemas presentan en el hogar?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="problema" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="Ninguno">Ninguno</option>
                                                <option value="Falta de comunicación">Falta de comunicación</option>
                                                <option value="Maltrato psicológico">Maltrato psicológico</option>
                                                <option value="Maltrato físico">Maltrato físico</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Con qué familiar tienes mayor confianza?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="fami_confianza" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="Papá">Papá</option>
                                                <option value="Mamá">Mamá</option>
                                                <option value="Hermano(a)">Hermano(a)</option>
                                                <option value="Tío(a)">Tío(a)</option>
                                                <option value="Abuelos">Abuelos</option>
                                                <option value="Ninguno">Ninguno</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <h5><b>3. ASPECTO LABORAL</b></h5>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Quiénes trabajan en tu familia?:
                                        </label>
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <label><input type="checkbox" name="familiares_lab[]" value="Solo"> Yo</label><br>
                                            <label><input type="checkbox" name="familiares_lab[]" value="Papá"> Papá</label><br>
                                            <label><input type="checkbox" name="familiares_lab[]" value="Mamá"> Mamá</label><br>
                                            <label><input type="checkbox" name="familiares_lab[]" value="Hermanos"> Hermanos</label>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Cuánto de ingreso mensual persiven en casa?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="cant_ingreso" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="Menor a S/.500">Menor a S/.500</option>
                                                <option value="Entre S/.500 a S/.1000">Entre S/.500 a S/.1000</option>
                                                <option value="Entre S/.1000 a S/.3000">Entre S/.1000 a S/.3000</option>
                                                <option value="Mayor a S/.3000">Mayor a S/.3000</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿En que priorizan los ingresos?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="pri_ingreso" required="required">
                                                <option value="Ninguno" disabled selected>Seleccione</option>
                                                <option value="Alimentos">Alimentos</option>
                                                <option value="Salud">Salud</option>
                                                <option value="Estudios">Estudios</option>
                                                <option value="Otro">Otro</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Si usted trabaja, que tipo de tabajo realiza?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="trabajo" required="required">
                                                <option value="Ninguno" disabled selected>Seleccione</option>
                                                <option value="Trabajo de campo">Trabajo de campo</option>
                                                <option value="Trabajo en tienda">Trabajo en tienda</option>
                                                <option value="Trabajo de oficina">Trabajo de oficina</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿En que gastas lo que te pagan?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="gasto" required="required">
                                                <option value="Ninguno" disabled selected>Seleccione</option>
                                                <option value="Estudios">Estudios</option>
                                                <option value="Salud">Salud</option>
                                                <option value="Alimentación">Alimentación</option>
                                                <option value="Oscio o entretenimineto">Oscio o entretenimineto</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                <h5><b>4. ASPECTO VIVIENDA</b></h5>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">Vives en:
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="tipo_casa" required="required">
                                                    <option value="" disabled selected>Seleccione</option>
                                                    <option value="Casa propia">Casa propia</option>
                                                    <option value="Casa alquilada">Casa alquilada</option>
                                                    <option value="Albergue">Albergue</option>
                                                    <option value="Casa de un familiar">Casa de un familiar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">De que esta construido la vivienda:
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="const_casa" required="required">
                                                    <option value="" disabled selected>Seleccione</option>
                                                    <option value="Material nombre">Material noble</option>
                                                    <option value="Madera">Madera</option>
                                                    <option value="Adobe">Adobe</option>
                                                    <option value="Otro">Otro</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">La vivienda tiene servicios de:
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label><input type="checkbox" name="servicio_casa[]" value="Agua"> Agua</label><br>
                                                <label><input type="checkbox" name="servicio_casa[]" value="Electricidad"> Electricidad</label><br>
                                                <label><input type="checkbox" name="servicio_casa[]" value="Desague(s)"> Desague</label><br>
                                                <label><input type="checkbox" name="servicio_casa[]" value="Cable"> Cable</label><br>
                                                <label><input type="checkbox" name="servicio_casa[]" value="Internet"> Internet</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">En casa tienes:
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label><input type="checkbox" name="equipos[]" value="Computador/Laptop"> Computador/Laptop</label><br>
                                                <label><input type="checkbox" name="equipos[]" value="Televisión"> Televisión</label><br>
                                                <label><input type="checkbox" name="equipos[]" value="Celular"> Celular</label>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">En casa cuentan con vehículo:
                                            </label>
                                            <div class="col-md-12 col-sm-12 col-xs-12">
                                                <label><input type="checkbox" name="vehiculo[]" value="Bicilceta"> Bicilceta</label><br>
                                                <label><input type="checkbox" name="vehiculo[]" value="Motocicleta"> Motocicleta</label><br>
                                                <label><input type="checkbox" name="vehiculo[]" value="Auto"> Auto</label>
                                            </div>
                                        </div>
                                        
                                        <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <label class="control-label">¿A cuantos minutos vives del instituto?:
                                            </label>
                                            <div class="">
                                                <select class="form-control" name="minutos" required="required">
                                                    <option value="" disabled selected>Seleccione</option>
                                                    <option value="Entre 1 a 5 minutos">Entre 1 a 5 minutos</option>
                                                    <option value="Entre 6 a 15 minutos">Entre 6 a 15 minutos</option>
                                                    <option value="Entre 16 a 30 minutos">Entre 16 a 30 minutos</option>
                                                    <option value="Más de 30 minutos">Más de 30 minutos</option>
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-6 col-xs-12">
                                <div class="x_panel">
                                    <h5><b>5. ASPECTO SALUD</b></h5>
                                    
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Presentas alguna dificultad física?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="enfermedad" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="Si">Si</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Tienes seguro de salud?:
                                        </label>
                                        <div class="">
                                            <select class="form-control" name="seguro" required="required">
                                                <option value="" disabled selected>Seleccione</option>
                                                <option value="SIS">SIS</option>
                                                <option value="ESSALUD">ESSALUD</option>
                                                <option value="Ninguno">Ninguno</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">¿Sufres de alguna enferemedad que requiera tratemiento?:
                                        </label>
                                        <div class="">
                                            <textarea class="form-control" name="enfermedad_descripcion" id="" rows="5"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="text-right">
                                        <a href="" class="btn btn-danger">Cancelar</a>
                                        <input type="submit" class="btn btn-success" value="Guardar Información">
                                        </div>
                                </div>
                            </div>
                        </form>
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

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>

     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}

