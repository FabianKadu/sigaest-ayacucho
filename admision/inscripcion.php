<?php
  include "../include/conexion.php";
  include "../include/busquedas.php";

  $res_procesos = buscarProcesosActivosParaInscripción($conexion);
  $res_extemporaneo = buscarProcesosActivosParaInscripciónExtemporaneo($conexion);
  $contador = mysqli_num_rows($res_procesos);
  $contador_extemporaneo = mysqli_num_rows($res_extemporaneo);

  if ($contador  === 0 and $contador_extemporaneo === 0) {
    echo "<script>
                  alert('No hay procesos de admisión activos en este momento!');
                  window.history.back();
              </script>";
  }elseif($contador  === 0 and $contador_extemporaneo > 0) {
    $res_procesos = $res_extemporaneo;
}else{
  $res_medios_pago = buscarTodosMetodosPago($conexion);
  $res_programas = buscarCarreras($conexion);
  $res_regiones = obtenerRegiones($conexion);

?>
<!DOCTYPE html>
<html lang="es">
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta http-equiv="Content-Type" content="text/html" charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	  
    <title>Admisión - Inscripciones</title>
   
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
    <!-- Script obtenido desde CDN jquery -->
    <script
  src="https://code.jquery.com/jquery-3.6.0.js"
  integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
  crossorigin="anonymous"></script>

    <style>
        .custom-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            border-left: 1px solid #ddd;
            border-right: 1px solid #ddd;

        }

        .custom-card {
            background-color: #fff;
            border-top: 8px solid #ff9900;
            padding: 10px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-around;
        }

        .custom-card-title {
            font-size: 16px;
            font-weight: bold;
            font-family: sans-serif;
            margin-bottom: 10px;
        }

        .custom-card-text {
            color: #666;
            font-size: 12px;
            line-height: 1.5;
        }

        .card {
        background-color: #fff;
        padding: 20px;
        max-width: 600px;
        width: 100%;
        border: 1px solid #ddd;
        border-top: none;

        }

        .card-content {
            text-align: center;
        }

        .card-title {
            margin-bottom: 20px;
            font-size: 24px;
        }

        .image-container {
            width: 200px;
            height: 280px;
            margin: 0 auto;
            overflow: hidden;
            border: 1px solid red;
            border-radius: 5px;
            background-color: #e6898980;
        }

        .image-container img {
            width: 100%;
            max-height: 100%;
            width: 200px;
            height: 280px;
            object-fit: cover;
            display: block;
        }

        .custom-file-upload {
            display: inline-block;
            padding: 8px 12px;
            background-color: #4CAF50;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .card-content input[type="file"] {
            display: none;
        }
        body{
            height: 100vh;
        }
        
        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        .spinner {
            border: 4px solid rgba(0, 0, 0, 0.1);
            border-left-color: #008000;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }

    </style>

</head>

<body>
    <div class="container body">
        <div class="main_container">
            <div class="right_col main" role="main">
                <div class="">
                    <div class="">
                        <div class="x_panel" align="center">
                            <div class="">
                                <h2><b>Formulario de Inscripción</b></h2>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- Div en el centro de la página -->
                <form action="operaciones/guardar_inscripcion.php" method="POST" enctype="multipart/form-data">
                    <div  class="x_panel paso" id="paso1">
                        <h4><b>Información del Postulante</b></h4> <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="custom-container">
                                            <div class="custom-card">
                                                <p class="custom-card-title">Fotografia</p>
                                                <div class="custom-card-content">
                                                    <p class="custom-card-text">La foto del postulante tiene que contar con fondo blanco y con la mirada al frente. Procure que la imagen encage en el cuadro de abajo. La dimensión aceptada es de 200px : 280px</p>
                                                </div>
                                            </div>
                                        </div>
                                        <center>
                                        <div class="card">
                                            <div class="card-content">
                                                <div class="image-container">
                                                    <img id="carnet-image" src="utils/carnet_default.png" alt="Carnet Image">
                                                </div>
                                                <input type="file" name="fotografia" id="upload" accept="image/*" onchange="loadImage(event)" required>
                                                <label for="upload" class="custom-file-upload">Seleccionar imagen</label>
                                            </div>
                                        </div>
                                        </center>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label ">D.N.I. *: </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="dni" id="dni" required="required" oninput="validateInputNum(this,8)">
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Apellido Paterno *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="paterno" id="apellidoPaterno" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label ">Apellido Materno *: </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="materno" id="apellidoMaterno" required="required" >
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Nombres *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="nombres" id="nombres" required="required">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label ">Género *: </label>
                                            <div class="">
                                                <div class="row">
                                                    <label class="col-md-6">
                                                            <input type="radio" name="genero" value="0" required>
                                                            M
                                                        </label>
                                                        
                                                        <label class="col-md-6" >
                                                            <input type="radio" name="genero" value="1" required>
                                                            F
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Estado Civil *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="" name="est_civil" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <option value="Soltero/a"  >Soltero/a</option>
                                                    <option value="Casado/a"  >Casado/a</option>
                                                    <option value="Divorciado/a"  >Divorciado/a</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label ">Fecha de Nacimiento *: </label>
                                            <div class="">
                                                <input type="date" id="fecha_nacimiento" class="form-control" name="fecha_nacimiento" required="required" >
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Lengua Materna *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="" name="len_materna" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <option value="Castellano"  >Castellano</option>
                                                    <option value="Quechua"  >Quechua</option>
                                                    <option value="Aymara"  >Aymara</option>
                                                    <option value="Ingles"  >Ingles</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label ">Es Discapacidado? *: </label>
                                            <div class="">
                                                <div class="row">
                                                    <label class="col-md-6">
                                                            <input type="radio" name="discapacidad" value="1" onclick="mostrarTipoDiscapacidad()">
                                                            Si
                                                        </label>
                                                        
                                                        <label class="col-md-6" >
                                                            <input type="radio" name="discapacidad" value="0" onclick="ocultarTipoDiscapacidad()">
                                                            No
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12" id="tipo_discapacidad" style="display: none;">
                                            <label class="control-label">Tipo de Discapacidad *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="tipoDiscapacidad" name="tipo_discapacidad" value="">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <option value="Física"> Física</option>
                                                    <option value="Visual"> Visual</option>
                                                    <option value="Auditiva"> Auditiva</option>
                                                    <option value="Intelectual"> Intelectual</option>
                                                    <option value="Mental"> Mental</option>
                                                    <option value="Sensorial"> Sensorial</option>
                                                    <option value="Cognitiva"> Cognitiva</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label ">Correo Electrónico *: </label>
                                            <div class="">
                                                <input type="email" class="form-control" name="correo" required="required" >
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-3 col-sm-6 col-xs-12">
                                            <label class="control-label">Número de Celular *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="celular" required="required" oninput="validateInputNum(this,9)">
                                            </div>
                                        </div>
                                        <div class="form-group col-md-9 col-sm-12 col-xs-12">
                                            <label class="control-label ">Dirección Actual *: </label>
                                            <div class="">
                                                <input type="text" class="form-control" name="direccion" required="required" >
                                            </div>
                                        </div>
                                    </div>
                                    
                                </div>
                                <div class="" id="padres" style="display: none;">
                                    <div class="x_content">
                                        <h4><b>  Información de Apoderado</b></h4>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label ">D.N.I. *: </label>
                                            <div class="">
                                                <input type="text" class="form-control" id="ap_dni" name="ap_dni" oninput="validateInputNum(this,8)">
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Apellidos *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" id="ap_apellidos" name="ap_apellidos" >
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Nombres *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" id="ap_nombres" name="ap_nombres">
                                            </div>
                                        </div>
                                        <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Número de Celular *:
                                            </label>
                                            <div class="">
                                                <input type="text" class="form-control" id="ap_celular" name="ap_celular" oninput="validateInputNum(this,9)">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div align="right"><button class="btn btn-primary" onclick="siguientePaso3()">Siguiente</button></div>
                        </div>
                    </div>
                    <!-- Div en el centro de la página -->
                    <div  class="x_panel paso" id="paso3" style="display: none;">
                        <h4><b>Información de Colegio y Procedencia</b></h4> <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="custom-container">
                                            <div class="custom-card">
                                                <p class="custom-card-title">Educación</p>
                                                <div class="custom-card-content">
                                                    <p class="custom-card-text">Recuerde que debe de contar con su certificado de estudios original y que la información proporcionada sea válida.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label ">Region *: </label>
                                            <div class="">
                                                <select class="form-control" id="region" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <?php while($region = mysqli_fetch_array($res_regiones)) {?>
                                                    <option value="<?php echo $region['Departamento'] ?>"><?php echo $region['Departamento'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label">Provincia *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="provincia" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label ">Distrito *: </label>
                                            <div class="">
                                                <select class="form-control" id="distrito" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label ">Colegio *: </label>
                                            <div class="">
                                                <select class="form-control" id="colegio" name="colegio" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label">Tipo Institución *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="" name="tipo_colegio" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <option value="Pública - Basica Regular">Pública - Basica Regular</option>
                                                    <option value="Pública - Basica Alternativa">Pública - Basica Alternativa</option>
                                                    <option value="Privada - Basica Regular">Privada - Basica Regular</option>
                                                    <option value="Privada - Basica Alternativa">Privada - Basica Alternativa</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 col-sm-4 col-xs-12">
                                            <label class="control-label "> Año Egreso </label>
                                            <div class="">
                                                <input type="number" class="form-control" name="anio_egreso" required="required" min="2000" max="<?php echo date('Y'); ?>" value="<?php echo date('Y'); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="left"><button class="btn" onclick="retrocederPaso()">Retroceder</button></div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="right"><button class="btn btn-primary" onclick="siguientePaso4()">Siguiente</button></div>
                        </div>
                    </div>
                    <!-- Div en el centro de la página -->
                    <div class="x_panel paso" id="paso4" style="display: none;">
                        <h4><b>Modalidad y Programa</b></h4> <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="custom-container">
                                            <div class="custom-card">
                                                <p class="custom-card-title">Documentos</p>
                                                <div class="custom-card-content">
                                                    <p class="custom-card-text">Recuerde tener los documentos que son requisitos de la modalidad seleccionada. Esa información fue publicada en el portal de admisión.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label ">Proceso de Admisión *: </label>
                                            <div class="">
                                                <select class="form-control" id="proceso" name="proceso" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <?php while($procesos = mysqli_fetch_array($res_procesos)) {?>
                                                    <option value="<?php echo $procesos['Id'] ?>"><?php echo  $procesos['Periodo']."  ". $procesos['Tipo'] ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label">Modalidad *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="modalidad" name="modalidad" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label ">Programa Elegible *: </label>
                                            <div class="">
                                                <select class="form-control" id="programa" name="programa" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label ">Programa - Segunda Opción *: </label>
                                            <div class="">
                                                <select class="form-control" id="programa_opcional" name="segun_opcion" value="">
                                                    <option value="0" disabled selected>Seleccionar</option>
                                                
                                                </select>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="left"><button class="btn" onclick="retrocederPaso()">Retroceder</button></div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="right"><button class="btn btn-primary" onclick="siguientePaso5()">Siguiente</button></div>
                        </div>
                    </div>
                    <!-- Div en el centro de la página -->
                    <div class="x_panel paso" id="paso5" style="display: none;">
                        <h4><b>Carga de Requisitos</b></h4> <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="form-group form-group col-md-12 col-sm-12 col-xs-12">
                                            <h4><b>Total a pagar S/. <span id="monto_pagar"></span></b></h4>
                                            <label class="control-label">Método de Pago *:
                                            </label>
                                            <div class="">
                                                <select class="form-control" id="metodo_pago" name="metodo_pago" value="" required="required">
                                                    <option value="" disabled selected>Seleccionar</option>
                                                    <?php while($medios_pago = mysqli_fetch_array($res_medios_pago)){ ?>
                                                    <option value="<?php echo $medios_pago['Id'] ?>"  ><?php echo $medios_pago['Metodo'];
                                                    if($medios_pago['Metodo'] == 'Depósito') echo ' - '.$medios_pago['Banco'];
                                                    if($medios_pago['Metodo'] == 'Transferencia Interbancaria') echo ' - '.$medios_pago['Banco'];
                                                    ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>
                                        </div>
                                        <br> <br>
                                        <div id="card_pago"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content" id="requisitos">
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="left"><button class="btn" onclick="retrocederPaso()">Retroceder</button></div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="right"><button class="btn btn-primary" onclick="siguientePaso6()">Siguiente</button></div>
                        </div>
                    </div>
                    <!-- Div en el centro de la página -->
                    <div class="x_panel paso" id="paso6" style="display: none">
                        <h4><b>Finalizar</b></h4> <hr>
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="custom-container">
                                            <div class="custom-card">
                                                <p class="custom-card-title">Recuerde</p>
                                                <div class="custom-card-content">
                                                    <p class="custom-card-text">Revise la información proporcionada, recuerde que la portulación debe de contar con información verídica que sera revisada cuidadosamente.
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                    <div class="form-group col-md-12 col-sm-12 col-xs-12">
                                        <label class="control-label">Medio de Difusión *:</label>
                                        <div class="">
                                            <select class="form-control" id="difusion" name="difusion" required="required">
                                                <option value="" disabled selected>Seleccionar</option>
                                                <option value="Radio o Televisión">Radio o Televisión</option>
                                                <option value="Pagina Web">Pagina Web</option>
                                                <option value="Redes Sociales">Redes Sociales</option>
                                                <option value="Familiar o Amigos">Familiar o Amigos</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-group col-md-12 col-sm-12 col-xs-12">
                                            <input type="checkbox" name="terminos" id="terminos" required>
                                            Aceptar términos y condiciones
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div class="col-md-6 col-sm-6 col-xs-6" align="left"><button class="btn" onclick="retrocederPaso()">Retroceder</button></div>
                            <div class="col-md-6 col-sm-6 col-xs-6" align="right"><button id="btnFinalizar" class="btn btn-success">Finalizar</button></div>
                        </div>
                    </div>
                </form>
            </div>
            
            <!-- Modal -->
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-md">
                    <div class="modal-content">
                        <div class="modal-body">
                            <center>
                                <div class="spinner"></div>
                            </center>
                            Procesando tu solicitud de inscripción.
                        </div>
                    </div>
                </div>
            </div>
            <!-- Fin modal -->
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
        function mostrarTipoDiscapacidad() {
            document.getElementById("tipo_discapacidad").style.display = "block";
            document.getElementById("tipoDiscapacidad").setAttribute("required", "required");
        }

        function ocultarTipoDiscapacidad() {
            document.getElementById("tipo_discapacidad").style.display = "none";
            document.getElementById("tipoDiscapacidad").removeAttribute("required");
        }
    </script>

    <!--script para obtener los modulos dependiendo de la carrera que seleccione-->
    <script type="text/javascript">
      $(document).ready(function(){
        recargarlista();
        recargarListaProgramas();
        recargarListaRequisitos();
        recargarListaProgramasOpcionales();
        $('#proceso').change(function(){
          recargarlista();
          recargarListaProgramas();
          recargarListaRequisitos();
          recargarListaProgramasOpcionales();

        });
        $('#modalidad').change(function(){
            recargarListaProgramas();
            recargarListaRequisitos();
            obtenerPrecioModalidad();
            recargarListaProgramasOpcionales();

        });
        $('#programa').change(function(){
            recargarListaProgramasOpcionales();

        });
        $('#metodo_pago').change(function(){
            recuperarMetodoPago();

        });
        $('#region').change(function(){
            recuperarProvincia();
        });
        $('#provincia').change(function(){
            recuperarDistrito();
        });
        $('#distrito').change(function(){
            recuperarColegio();
        });
      })
    </script>
    <script type="text/javascript">
     function recargarlista(){
      $.ajax({
        type:"POST",
        url:"operaciones/obtener_modalidades.php",
        data:"id_proceso="+ $('#proceso').val(),
          success:function(r){
            $('#modalidad').html(r);
          }
      });
     }
    </script>
    <script type="text/javascript">
     function recargarListaProgramas(){

        var idProceso = $('#proceso').val();
        var idModalidad = $('#modalidad').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_programas.php",
        data: {
            id_proceso: idProceso,
            id_modalidad: idModalidad
        },
          success:function(r){
            $('#programa').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recargarListaProgramasOpcionales(){

        var idProceso = $('#proceso').val();
        var idModalidad = $('#modalidad').val();
        var idPrograma = $('#programa').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_programas_segunda_opcion.php",
        data: {
            id_proceso: idProceso,
            id_modalidad: idModalidad,
            id_programa: idPrograma
        },
          success:function(r){
            $('#programa_opcional').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function obtenerPrecioModalidad(){

        var idProceso = $('#proceso').val();
        var idModalidad = $('#modalidad').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_precio_modalidad.php",
        data: {
            id_modalidad: idModalidad,
            id_proceso: idProceso
        },
          success:function(r){
            $('#monto_pagar').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recargarListaRequisitos(){

        var idProceso = $('#proceso').val();
        var idModalidad = $('#modalidad').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_requisitos.php",
        data: {
            id_proceso: idProceso,
            id_modalidad: idModalidad
        },
          success:function(r){
            $('#requisitos').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recuperarMetodoPago(){

        var idMetodoPago = $('#metodo_pago').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_metodo_pago.php",
        data: {
            id_metodo: idMetodoPago
        },
          success:function(r){
            $('#card_pago').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recuperarProvincia(){

        var region = $('#region').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_provincia.php",
        data: {
            region: region
        },
          success:function(r){
            $('#provincia').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recuperarDistrito(){

        var region = $('#region').val();
        var provincia = $('#provincia').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_distrito.php",
        data: {
            region: region,
            provincia: provincia
        },
          success:function(r){
            $('#distrito').html(r);
          }
      });
     }
    </script>

    <script type="text/javascript">
     function recuperarColegio(){

        var provincia = $('#provincia').val();
        var distrito = $('#distrito').val();

      $.ajax({
        type:"POST",
        url:"operaciones/obtener_colegio.php",
        data: {
            distrito: distrito,
            provincia: provincia
        },
          success:function(r){
            $('#colegio').html(r);
          }
      });
     }
    </script>

    <script>
        // JavaScript para mostrar los nombres de los archivos seleccionados
        const inputsArchivo = document.querySelectorAll('.archivo');    
        inputsArchivo.forEach(inputArchivo => {
            inputArchivo.addEventListener('change', function() {
                const nombreArchivo = this.nextElementSibling.querySelector('.nombre-archivo');
                if (this.files.length > 0) {
                    let nombres = Array.from(this.files).map(file => file.name).join(', ');
                    nombreArchivo.textContent = nombres;
                } else {
                    nombreArchivo.textContent = 'Seleccionar archivo';
                }
            });
        });
    </script>

    <script>
        function loadImage(event) {
            var file = event.target.files[0];
            var maxSize = 1024 * 1024;
            var maxWidth = 200;
            var maxHeight = 280;
        
            if (file.size > maxSize) {
                alert('El tamaño máximo permitido es de 1MB.');
                event.target.value = '';
                return false;
            }
        
            var img = new Image();
            img.onload = function () {
                /*if (this.width > maxWidth || this.height > maxHeight) {
                    alert('Las dimensiones máximas permitidas son ' + maxWidth + 'x' + maxHeight + '.');
                    event.target.value = '';
                    return false;
                }*/
                document.getElementById('carnet-image').src = this.src;
        };

        img.src = URL.createObjectURL(file);
        }
        
    </script>

<script>
        function loadRequisito(event) {
            var file = event.target.files[0];
            var maxSize = 1024 * 1024 * 5;
        
            if (file.size > maxSize) {
                alert('El tamaño máximo permitido es de 5MB.');
                event.target.value = '';
                return false;
        
            };
        }
        
    </script>

    <script>
            document.getElementById('fecha_nacimiento').addEventListener('change', function() {
                var fechaNacimiento = new Date(this.value);
                var hoy = new Date();
                var edad = hoy.getFullYear() - fechaNacimiento.getFullYear();
                var mes = hoy.getMonth() - fechaNacimiento.getMonth();
                
                if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNacimiento.getDate())) {
                    edad--;
                }

                if (edad < 18) {
                    document.getElementById('padres').style.display = 'block';
                    document.getElementById("ap_dni").setAttribute("required", "required");
                    document.getElementById("ap_apellidos").setAttribute("required", "required");
                    document.getElementById("ap_nombres").setAttribute("required", "required");
                    document.getElementById("ap_celular").setAttribute("required", "required");
                } else {
                    document.getElementById('padres').style.display = 'none';
                    document.getElementById("ap_dni").removeAttribute("required");
                    document.getElementById("ap_apellidos").removeAttribute("required");
                    document.getElementById("ap_nombres").removeAttribute("required");
                    document.getElementById("ap_celular").removeAttribute("required");
                }
            });
    </script>

    <script>
        function siguientePaso3() {
            // Llamar a la función de validación del formulario
            if (validarFormulario1()) {
                var pasoActual = document.querySelector('#paso1:not([style*="display: none;"])');
                var siguientePaso = pasoActual.nextElementSibling;
                if (siguientePaso) {
                    pasoActual.style.display = 'none';
                    siguientePaso.style.display = 'block';
                    window.scrollTo(0, 0);
                }
            } else {
                alert("Por favor, complete todos los campos obligatorios antes de continuar. Tambien no se olvide de cargar su fotografía.");
            }
        }

        function validarFormulario1() {
            var campos = document.querySelectorAll('#paso1 input[required], #paso1 select[required]');
            var formularioValido = true;
            
            campos.forEach(function(campo) {
                if (campo.value.trim() === "") {
                    formularioValido = false;
                    // Resaltar el campo vacío o sin cambios
                    campo.style.border = "2px solid red";
                } else {
                    // Restaurar el estilo por defecto
                    campo.style.border = "";
                }
            });

            return formularioValido;
        }
    </script>

    <script>
        function siguientePaso4() {
            // Llamar a la función de validación del formulario
            if (validarFormulario3()) {
                var pasoActual = document.querySelector('#paso3:not([style*="display: none;"])');
                var siguientePaso = pasoActual.nextElementSibling;
                if (siguientePaso) {
                    pasoActual.style.display = 'none';
                    siguientePaso.style.display = 'block';
                    window.scrollTo(0, 0);
                }
            } else {
                alert("Por favor, complete todos los campos obligatorios antes de continuar.");
            }
        }

        function validarFormulario3() {
            var campos = document.querySelectorAll('#paso3 input[required], #paso3 select[required]');
            var formularioValido = true;
            
            campos.forEach(function(campo) {
                if (campo.value.trim() === "") {
                    formularioValido = false;
                    // Resaltar el campo vacío o sin cambios
                    campo.style.border = "2px solid red";
                } else {
                    // Restaurar el estilo por defecto
                    campo.style.border = "";
                }
            });

            return formularioValido;
        }
    </script>

    <script>
        function siguientePaso5() {
            // Llamar a la función de validación del formulario
            if (validarFormulario4()) {
                var pasoActual = document.querySelector('#paso4:not([style*="display: none;"])');
                var siguientePaso = pasoActual.nextElementSibling;
                if (siguientePaso) {
                    pasoActual.style.display = 'none';
                    siguientePaso.style.display = 'block';
                    window.scrollTo(0, 0);
                }
            } else {
                alert("Por favor, complete todos los campos obligatorios antes de continuar.");
            }
        }

        function validarFormulario4() {
            var campos = document.querySelectorAll('#paso4 input[required], #paso4 select[required]');
            var formularioValido = true;
            
            campos.forEach(function(campo) {
                if (campo.value.trim() === "") {
                    formularioValido = false;
                    // Resaltar el campo vacío o sin cambios
                    campo.style.border = "2px solid red";
                } else {
                    // Restaurar el estilo por defecto
                    campo.style.border = "";
                }
            });

            return formularioValido;
        }
    </script>

    <script>
        function siguientePaso6() {
            // Llamar a la función de validación del formulario
            if (validarFormulario5()) {
                var pasoActual = document.querySelector('#paso5:not([style*="display: none;"])');
                var siguientePaso = pasoActual.nextElementSibling;
                if (siguientePaso) {
                    pasoActual.style.display = 'none';
                    siguientePaso.style.display = 'block';
                    window.scrollTo(0, 0);
                }
            } else {
                alert("Por favor, complete todos los campos obligatorios antes de continuar.");
            }
        }

        function validarFormulario5() {
            var campos = document.querySelectorAll('#paso5 input[required], #paso5 select[required]');
            var formularioValido = true;
            
            campos.forEach(function(campo) {
                if (campo.value.trim() === "") {
                    formularioValido = false;
                    // Resaltar el campo vacío o sin cambios
                    campo.style.border = "2px solid red";
                } else {
                    // Restaurar el estilo por defecto
                    campo.style.border = "";
                }
            });

            return formularioValido;
        }
    </script>

    <script>
        function retrocederPaso() {
            var pasoActual = document.querySelector('.paso:not([style*="display: none;"])');
            var pasoAnterior = pasoActual.previousElementSibling;
            if (pasoAnterior) {
                pasoActual.style.display = 'none';
                pasoAnterior.style.display = 'block';
                window.scrollTo(0, 0);
            }
        }
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const dniInput = document.getElementById('dni');
            const nameInput = document.getElementById('nombres');
            const apInput = document.getElementById('apellidoPaterno');
            const amInput = document.getElementById('apellidoMaterno');
            let timeoutId = null;


              dniInput.addEventListener('input', function() {
                  const dni = dniInput.value;

                  // Si el valor no tiene 8 dígitos, limpiamos el timeout y retornamos
                  if (dni.length !== 8) {
                      if (timeoutId) {
                          clearTimeout(timeoutId);
                          nameInput.value = "";
                      }
                      return;
                  }

                  // Limpiamos cualquier timeout anterior
                  if (timeoutId) {
                      clearTimeout(timeoutId);
                  }

                  // Establecemos un nuevo timeout de 1 segundo
                  timeoutId = setTimeout(() => {
                      fetch(`https://dni.biblio-ideas.com/api/dni/${dni}`)
                          .then(response => response.json())
                          .then(data => {
                            apInput.value = data.apellidoPaterno
                            amInput.value = data.apellidoMaterno
                            nameInput.value = data.nombres
                          })
                          .catch(error => {
                              
                          });
                  }, 500);
              });
        });
    </script>
    
    <script>
        document.getElementById("btnFinalizar").addEventListener("click", function() {
            // Verificar si ambos elementos están marcados antes de mostrar el modal
            var difusionSeleccionada = document.getElementById("difusion").value;
            var terminosAceptados = document.getElementById("terminos").checked;
            
            // Si ambos elementos están marcados, mostrar el modal
            if (difusionSeleccionada && terminosAceptados) {
                $('#myModal').modal('show');
            } else {
                // Si no están marcados, mostrar un mensaje de error
                alert("Por favor, seleccione un medio de difusión y acepte los términos y condiciones.");
            }
        });
    </script>
    
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
     </script>
    
    <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php } ?>