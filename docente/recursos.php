<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                 alert('Error Usted no cuenta con permiso para acceder a esta página');
                 window.location.replace('index.php');
         </script>";
} else {

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
  // Comprobar si se ha enviado el formulario
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ruta_base = '../img/';
    $campos = ['img_sistema', 'img_logo_documento', 'img_cabeza', 'img_footer', 'img_fondo'];
    $archivos_actualizados = [];

    foreach ($campos as $campo) {
      if (!empty($_FILES[$campo]['name'])) {
        $nombre_archivo = basename($_FILES[$campo]['name']);
        $ruta_archivo = $ruta_base . $nombre_archivo;
        if (move_uploaded_file($_FILES[$campo]['tmp_name'], $ruta_archivo)) {
          $archivos_actualizados[$campo] = $ruta_archivo;
        }
      }
    }

    if (!empty($archivos_actualizados)) {
      $update_query = "UPDATE recursos SET ";
      foreach ($archivos_actualizados as $campo => $ruta) {
        $update_query .= "$campo = '$ruta', ";
      }
      $update_query = rtrim($update_query, ', ') . " WHERE id = 1";
      mysqli_query($conexion, $update_query);
    }
  }

  // Obtener las rutas actuales de la base de datos
  $query = "SELECT * FROM recursos WHERE id = 1";
  $result = mysqli_query($conexion, $query);
  $recursos = mysqli_fetch_assoc($result);
?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="Content-Language" content="es-ES">
    <!-- Meta, title, CSS, favicons, etc. -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Recursos <?php include("../include/header_title.php"); ?></title>
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
  </head>

  <body class="nav-md" onload="desactivar_controles();">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php
        include("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_title">
                    <h2>Administrar Recursos</h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="">
                    <form action="" method="POST" enctype="multipart/form-data">

                      <!-- Imagen del Sistema -->
                      <div class="form-group">
                        <label class="control-label" for="img_sistema">Logo de la institución para el sistema. Uso común en el menú, login, etc.</label>
                        <div>
                          <input type="file" id="img_sistema" name="img_sistema" class="form-control" onchange="previewImage(this, 'preview_img_sistema')">
                          <p>Se recomieda una imagen horizontal.</p>
                          <img id="preview_img_sistema" src="<?= $recursos['img_sistema'] ?>" alt="Imagen del Sistema" style="height:50px; cursor:pointer;" onclick="openModal('<?= $recursos['img_sistema'] ?>')">
                        </div>
                      </div>

                      <!-- Imagen del Logo Documento -->
                      <div class="form-group">
                        <label class="control-label " for="img_logo_documento">Logo de la institución para el uso en documentos.</label>
                        <div>
                          <input type="file" id="img_logo_documento" name="img_logo_documento" class="form-control" onchange="previewImage(this, 'preview_img_logo_documento')">
                          <p>Se recomieda cargar el logo con fondo transparente.</p>
                          <img id="preview_img_logo_documento" src="<?= $recursos['img_logo_documento'] ?>" alt="Imagen Logo Documento" style="height:50px; cursor:pointer;" onclick="openModal('<?= $recursos['img_logo_documento'] ?>')">
                        </div>
                      </div>

                      <!-- Imagen de la Cabeza -->
                      <div class="form-group">
                        <label class="control-label " for="img_cabeza">Imagen de encabezado de documentos</label>
                        <div>
                          <input type="file" id="img_cabeza" name="img_cabeza" class="form-control" onchange="previewImage(this, 'preview_img_cabeza')">
                          <p>Se recomieda una imagen horizontal.</p>
                          <img id="preview_img_cabeza" src="<?= $recursos['img_cabeza'] ?>" alt="Imagen Cabeza" style="height:50px; cursor:pointer;" onclick="openModal('<?= $recursos['img_cabeza'] ?>')">
                        </div>
                      </div>

                      <!-- Imagen del Pie -->
                      <div class="form-group">
                        <label class="control-label " for="img_footer">Imagen de pie de página para los documentos</label>
                        <div>
                          <input type="file" id="img_footer" name="img_footer" class="form-control" onchange="previewImage(this, 'preview_img_footer')">
                          <p>Se recomieda una imagen horizontal.</p>
                          <img id="preview_img_footer" src="<?= $recursos['img_footer'] ?>" alt="Imagen Pie" style="height:50px; cursor:pointer;" onclick="openModal('<?= $recursos['img_footer'] ?>')">
                        </div>
                      </div>

                      <!-- Imagen de Fondo -->
                      <div class="form-group">
                        <label class="control-label " for="img_fondo">Imagen de fondo para el login y la pagina principal de cada usuario</label>
                        <div>
                          <input type="file" id="img_fondo" name="img_fondo" class="form-control" onchange="previewImage(this, 'preview_img_fondo')">
                          <p>Se recomieda una imagen horizontal.</p>
                          <img id="preview_img_fondo" src="<?= $recursos['img_fondo'] ?>" alt="Imagen Fondo" style="height:50px; cursor:pointer;" onclick="openModal('<?= $recursos['img_fondo'] ?>')">
                        </div>
                      </div>

                      <!-- Botón para guardar -->
                      <center>
                        <div class="form-group">
                          <div class="">
                            <button type="submit" class="btn btn-success">Guardar Cambios</button>
                          </div>
                        </div>
                      </center>

                    </form>
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
      include("../include/footer.php");
      ?>
      <!-- /footer content -->
    </div>

    <!-- Modal para ampliar imagen -->
    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-body">
            <img id="modalImage" src="" alt="Imagen ampliada" class="img-responsive">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
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
    <script src="../Gentella/vendors/jszip/dist/jszip.min.js"></script>
    <script src="../Gentella/vendors/pdfmake/build/pdfmake.min.js"></script>
    <script src="../Gentella/vendors/pdfmake/build/vfs_fonts.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>

    <script>
      function previewImage(input, previewID) {
        var file = input.files[0];
        var reader = new FileReader();

        reader.onload = function(e) {
          document.getElementById(previewID).src = e.target.result;
        };

        if (file) {
          reader.readAsDataURL(file);
        }
      }

      function openModal(imageSrc) {
        document.getElementById('modalImage').src = imageSrc;
        $('#imageModal').modal('show');
      }
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php }
