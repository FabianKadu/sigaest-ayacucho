<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_secretaria.php");

// include("operaciones/generar_backup_docs.php");
// include("operaciones/generar_backup_db.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
            alert('Error Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('index.php');
          </script>";
} else {
  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
?>
  <!DOCTYPE html>
  <html lang="es">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Backup<?php include("../include/header_title.php"); ?></title>
    <link rel="shortcut icon" href="../img/favicon.ico">
    <!-- Bootstrap -->
    <link href="../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <?php include("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row">
            <div class="col-md-12">
              <div class="x_panel">
                <!-- Título principal -->
                <div class="bg-light p-3 rounded mb-4">
                  <h2 align="center">Backup</h2>
                </div>

                <!-- Sección de realizar copia -->
                <div class="bg-light p-4 rounded mb-4">
                  <div><b>Realizar copia de seguridad</b></div>
                  <ol class="ps-4">
                    <li class="mb-3">Respaldo de Documentos: Haz clic en el botón "Realizar Copia de Seguridad". El sistema generará un archivo comprimido (.zip) con todos los documentos registrados hasta la fecha. Descárgalo y guárdalo en un lugar seguro.</li>
                    <li class="mb-3">Respaldo de la Base de Datos: El sistema también creará un archivo .sql con la estructura y los datos de la base de datos actual. Descárgalo para completar el respaldo.</li>
                  </ol>

                  <div class="text-center my-4">
                    <button class="btn btn-success px-4" onclick="realizarBackup()">
                      Realizar Copia de Seguridad
                    </button>
                  </div>
                </div>

                <!-- Modal de Progreso -->
                <div class="modal fade" id="backupModal" tabindex="-1" role="dialog">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" align="center">Generando Copia de Seguridad</h4>
                      </div>

                      <div class=" modal-body">
                        <p>
                          Por favor, espera mientras se genera la copia de seguridad. Este proceso puede tardar unos minutos. No cierres ni recargues la página para garantizar que el respaldo se complete correctamente.
                        </p>
                        <p style="color: #FF0000;">
                          <b>Importante:</b> No realices otras acciones en el sistema mientras se completa el proceso.
                        </p>

                        <div class="progress-container">
                          <div id="progressBar" class="progress-bar"></div>
                        </div>

                        <p id="statusText" class="text-center mt-2">Iniciando proceso...</p>
                      </div>

                      <!-- <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div> -->
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->

                <!-- Sección restaurar documentos -->
                <div class="bg-light p-4 rounded mb-4">
                  <div><b>Restaurar documentos</b></div>
                  <ul class="list-unstyled">
                    <li class="mb-2">• Sube el archivo comprimido (.zip) del respaldo a la raíz del sistema.</li>
                    <li class="mb-2">• Elimina la carpeta de documentos existente para evitar conflictos.</li>
                    <li class="mb-2">• Descomprime el archivo en la misma ubicación.</li>
                  </ul>
                </div>

                <!-- Sección restaurar base de datos -->
                <div class="bg-light p-4 rounded">
                  <div><b>Restaurar base de datos</b></div>
                  <ul class="list-unstyled">
                    <li class="mb-2">• Accede a tu sistema de gestión de bases de datos (por ejemplo, phpMyAdmin).</li>
                    <li class="mb-2">• Limpia la base de datos existente ejecutando un DROP de las tablas actuales.</li>
                    <li class="mb-2">• Importa el archivo .sql desde la opción de Importar.</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!-- /page content -->

        <!-- footer content -->
        <?php include("../include/footer.php"); ?>
        <!-- /footer content -->
      </div>
    </div>

    <!-- jQuery -->
    <script src="../Gentella/vendors/jquery/dist/jquery.min.js"></script>
    <!-- Bootstrap -->
    <script src="../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>

    <script>
      function realizarBackup() {
        showBackupModal();

        // Iniciar backup de documentos
        startDocumentBackup()
          .then(() => {
            // Iniciar backup de base de datos
            return startDatabaseBackup();
          })
          .then(() => {
            updateProgress(100, "¡Proceso completado!");
            setTimeout(() => {
              $('#backupModal').modal('hide');
              downloadBackupFiles();
            }, 1500);
          })
          .catch(error => {
            showError(error.message);
          });
      }

      function showBackupModal() {
        $('#backupModal').modal({
          backdrop: 'static',
          keyboard: false
        }).modal('show');
        updateProgress(0, "Iniciando backup de documentos...");
      }

      function startDocumentBackup() {
        return new Promise((resolve, reject) => {
          $.ajax({
            url: './operaciones/generar_backup_docs.php',
            method: 'POST',
            xhr: function() {
              var xhr = new XMLHttpRequest();
              xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                  var percent = Math.round((e.loaded / e.total) * 50);
                  updateProgress(percent, "Procesando documentos...");
                }
              });
              return xhr;
            },
            success: function(response) {
              if (!response.success) {
                reject(new Error("Error en backup de documentos: " + response.message));
              } else {
                updateProgress(50, "Iniciando backup de base de datos...");
                resolve();
              }
            },
            error: function() {
              reject(new Error("Error al generar el backup de documentos"));
            }
          });
        });
      }

      function startDatabaseBackup() {
        return new Promise((resolve, reject) => {
          $.ajax({
            url: './operaciones/generar_backup_db.php',
            method: 'POST',
            success: function(dbResponse) {
              if (!dbResponse.success) {
                reject(new Error("Error en backup de base de datos: " + dbResponse.message));
              } else {
                resolve();
              }
            },
            error: function() {
              reject(new Error("Error al generar el backup de la base de datos"));
            }
          });
        });
      }

      function downloadBackupFiles() {
        const dateSuffix = getFormattedDate();

        // Descargar backup de documentos
        downloadFile('../temp/backup_docs.zip', 'backup_documentos_' + dateSuffix + '.zip');

        // Descargar backup de base de datos
        setTimeout(() => {
          downloadFile('../temp/backup_db.sql', 'backup_bd_' + dateSuffix + '.sql');
        }, 1000);
      }

      function downloadFile(url, filename) {
        const link = document.createElement('a');
        link.href = url;
        link.download = filename;
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
      }

      function updateProgress(percent, status) {
        const progressBar = document.getElementById('progressBar');
        const statusText = document.getElementById('statusText');

        progressBar.style.width = percent + '%';
        progressBar.setAttribute('data-progress', percent);
        statusText.textContent = status;
      }

      function showError(message) {
        $('#backupModal').modal('hide');
        alert(message);
      }

      function getFormattedDate() {
        const date = new Date();
        return date.getFullYear() +
          ('0' + (date.getMonth() + 1)).slice(-2) +
          ('0' + date.getDate()).slice(-2) + '_' +
          ('0' + date.getHours()).slice(-2) +
          ('0' + date.getMinutes()).slice(-2) +
          ('0' + date.getSeconds()).slice(-2);
      }
    </script>

    <style>
      .progress-container {
        width: 100%;
        height: 20px;
        background-color: #f0f0f0;
        border-radius: 10px;
        overflow: hidden;
        margin: 10px 0;
      }

      .progress-bar {
        width: 0;
        height: 100%;
        background-color: #4CAF50;
        transition: width 0.3s ease-in-out;
        position: relative;
      }

      .progress-bar::after {
        content: attr(data-progress) '%';
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -50%);
        color: white;
        font-size: 12px;
      }
    </style>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php } ?>