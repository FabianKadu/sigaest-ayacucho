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
  $res = buscarEmpresaById($conexion, $id);
  $empresa = mysqli_fetch_array($res);

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
	  
    <title>Bolsa Laboral <?php include ("../include/header_title.php"); ?></title>
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

  <style>
        body {
            background-color: #f7f7f7;
        }
        .container_form {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .form-container {
            width: 600px; /* Ancho del contenedor */
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
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
            padding: 10px 0;
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
          $res_docente = buscarDocenteById($conexion,$id_docente_sesion);
          $docente = mysqli_fetch_array($res_docente);
          if($docente['id_cargo'] == 7){
            include ("include/menu_administrador.php"); 
          }
          include("../docente/include/menu_secretaria.php"); ?>
        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <h2 align="center">Actualizar Empresa</h2>
                    <div class="clearfix"></div>
                  </div>
                    <div class="x_content">
                        <div class="container_form">
                        <div class="form-container">
                            <!-- Formulario de registro de empresa con jQuery Steps -->
                            <form id="registro_empresa_form" action="operaciones/actualizar_empresa.php" method="post" enctype="multipart/form-data">
                                <div id="wizard">
                                    <h3>Datos de la Empresa</h3>
                                    <section>
                                        <!-- Campos de datos de la empresa -->
                                        <input type="hidden" name="id" value="<?php echo $id ?>">
                                        <div class="form-group">
                                            <label for="nombre_empresa">Nombre de la Empresa*:</label>
                                            <input type="text" class="form-control" id="nombre_empresa" name="nombre_empresa" value="<?php echo $empresa['razon_social'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="ruc">RUC:</label>
                                            <input type="text" class="form-control" id="ruc" name="ruc"  oninput="validateInputNum(this,11)"  value="<?php echo $empresa['ruc'] ?>">
                                        </div>
                                        <div class="form-group">
                                            <label for="ubicacion">Ciudad*:</label>
                                            <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="<?php echo $empresa['ubicacion'] ?>" onkeyup="javascript:this.value=this.value.toUpperCase();" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label label-align" for="logo"><i class="fa fa-file-pdf-o"></i> Logo con el nombre de la empresa:
                                            </label>                                                
                                            <div class="">
                                                <div class="container-input">
                                                    <input type="file" name="logo" id="file-3" class="inputfile inputfile-3" data-multiple-caption="{count} archivos seleccionados" multiple  accept=".png, .jpeg, .jpg" />
                                                    <label for="file-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="iborrainputfile" width="20" height="17" viewBox="0 0 20 17"><path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"></path></svg>
                                                    <span class="iborrainputfile"><?php 
                                                        if($empresa['ruta_logo'] == ""){
                                                            echo 'Seleccione archivo';
                                                        }else{
                                                    echo 'Actualizar'; }?></span>
                                                    </label>
                                                </div>
                                            </div>
                                            <p>    * El logo debe de tener una relación de aspecto 1:1, ejemplo 240px * 240px</p>
                                        </div>
                                        <br>
                                        <!-- Campos de datos de contacto -->
                                        <div class="form-group">
                                            <label for="correo">Correo Electrónico:</label>
                                            <input type="email" class="form-control" id="correo" name="correo" value="<?php echo $empresa['correo_institucional'] ?>" onkeyup="javascript:this.value=this.value.toLowerCase();" >
                                        </div>
                                        <div class="form-group">
                                            <label for="celular">Teléfono - Celular:</label>
                                            <input type="text" class="form-control" id="celular"  oninput="validateInputNum(this,9)"  value="<?php echo $empresa['celular_telefono'] ?>" name="celular" >
                                        </div>
                                    </section>
                                </div>
                                <!-- Botón de enviar formulario -->
                                <div style="text-align: center;"> <br>
                                    <button type="submit" class="btn btn-success">Actualizar Empresa</button>
                                </div>
                            </form>
                        </div>
                   </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <?php
        include ("../include/footer.php"); 
        ?>
      </div>
    </div>

    <!-- Scripts de Gentella y otros scripts necesarios (si es necesario) -->
    <!-- Scripts de jQuery y jQuery Steps -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#wizard").steps({
                headerTag: "h3",
                bodyTag: "section",
                transitionEffect: "slideLeft"
            });
        });
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
    <!-- Scripts de Gentella y otros scripts necesarios (si es necesario) -->
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