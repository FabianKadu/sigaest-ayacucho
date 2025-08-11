<?php
session_start(); 
  include "../include/conexion.php";
  include "../include/busquedas.php";

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    echo "<script>
            alert('No tiene acceso a esta página. Por favor, inicie sesión.');
            window.location.href = '../admision/login_postulante/index.php';
          </script>";
    exit; 
}

if(!isset($_SESSION['Id_Postulante'])) {
    echo "<script>
    alert('No tiene acceso a esta página. Por favor, inicie sesión.');
    window.location.href = '../admision/login_postulante/index.php';
    </script>";
    exit; 
  
  } else {
    
    $detalle_postulacion = $_GET['id'];
    //detalle_postulante
    $res_d_p = buscarDetallePostulacionPorId($conexion, $detalle_postulacion);
    $d_p = mysqli_fetch_array($res_d_p);
    //postulante
    $res_postulante = buscarPostulantePorId($conexion, $d_p['Id_Postulante']);
    $postulante = mysqli_fetch_array($res_postulante);
    //docuementos
    $res_documentos = bucarDocumentosObservadosPorDetallePostulacion($conexion, $detalle_postulacion);
    $res_documentos_duplicado = bucarDocumentosObservadosPorDetallePostulacion($conexion, $detalle_postulacion);
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
	  
    <title>Admisión <?php include ("../include/header_title.php"); ?></title>
   
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
                                <h2><b>Formulario para Regularizar Observaciones</b></h2>
                            </div>
                            
                        </div>
                    </div>
                </div>
                <!-- Div en el centro de la página -->
                <form action="operaciones/actualizar_inscripcion.php" method="POST" enctype="multipart/form-data">
                    <div  class="x_panel paso" id="paso1">
                        <h4><b>Documentos Observados</b></h4> <hr>
                        <input type="hidden" name="id_detalle_postulante" value="<?php echo $detalle_postulacion; ?>">
                        <div class="row">
                            <div class="col-md-4 col-sm-5 col-xs-12">
                                <div class="">
                                    <div class="x_content">
                                        <div class="custom-container">
                                            <div class="custom-card">
                                                <p class="custom-card-title">Recuerde</p>
                                                <div class="custom-card-content">
                                                    <p class="custom-card-text">Verificar cada documento cargado en el sistema, no incidir en subir documentos no indicados o no adecuados.</p>
                                                </div>
                                            </div>
                                        </div>
                                        <?php while ($row = mysqli_fetch_array($res_documentos_duplicado)){ 
                                            if($row['Titulo'] === "Fotografías"){
                                            ?>
                                        <center>
                                        <div class="card">
                                            <p class="custom-card-text">La foto del postulante tiene que contar con fondo blanco y con la mirada al frente. Procure que la imagen encage en el cuadro de abajo. Se recomienda subir una imagen de 200px de ancho y 280 px de alto.</p>
                                            <div class="card-content">
                                                <div class="image-container">
                                                    <img id="carnet-image" src="<?php echo $postulante['Fotografia']; ?>" alt="Carnet Image">
                                                </div>
                                                <input type="file" name="fotografia" id="upload" accept="image/*" onchange="loadImage(event)" required>
                                                <label for="upload" class="custom-file-upload">Seleccionar imagen</label>
                                            </div>
                                        </div>
                                        </center>
                                        <?php }}?>
                                        
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8 col-sm-7 col-xs-12">
                                <div class="">
                                    <div class="x_content" id="requisitos">
                                        <?php while ($row = mysqli_fetch_array($res_documentos)){ 
                                            if($row['Titulo'] !== "Fotografías"){
                                            ?>
                                        <div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
                                            <label class="control-label"> <?php echo $row["Descripcion"] ?> *:
                                            </label>
                                            <div class="">
                                                <input type="file" class="form-control" name="<?php echo $row['Id'] ?>" required="required"  accept=".pdf">
                                            </div>
                                        </div>
                                        <?php }}?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class ="row">
                            <div align="right"><button class="btn btn-primary">Siguiente</button></div>
                        </div>
                    </div>
                </form>
            </div>
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

    <script>
        function loadImage(event) {
            var image = document.getElementById('carnet-image');
            image.src = URL.createObjectURL(event.target.files[0]);
        }
    </script>
    
    <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php } ?>