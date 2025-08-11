<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include 'include/verificar_sesion_estudiante.php';

if (!verificar_sesion($conexion)) {
  echo "<script>
          alert('Error Usted no cuenta con permiso para acceder a esta página');
          window.location.replace('index.php');
    		</script>";
} else {

  $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
  $b_estudiante = buscarEstudianteById($conexion, $id_estudiante_sesion);
  $r_b_estudiante = mysqli_fetch_array($b_estudiante);

  $matricula = buscarMatriculaActiva($conexion);
  if (!$matricula) {
    echo "<script>
          alert('No se ha encontrado una matrícula activa');
          window.location.replace('index.php');
        </script>";
    exit();
  }
  $periodo_bus = buscarPeriodoAcadById($conexion, $matricula['periodo']);
  $res_periodo = mysqli_fetch_array($periodo_bus);
  $parte = explode("-", $res_periodo['nombre']);
  $semestre = $parte[1];
  $semestre_cursos = ($semestre == 'I') ? [1, 3, 5] : [2, 4, 6];
  $programa = $r_b_estudiante['id_programa_estudios'];
  $solicitud_matricula = buscarSolicitudesMatriculaByEstudiantePeriodo($conexion, $id_estudiante_sesion, $matricula['id']);
  $solicitud_matricula_res = mysqli_fetch_array($solicitud_matricula);
  $count_sol_mat = mysqli_num_rows($solicitud_matricula);

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

    <title>Solicitar matricula<?php include("../include/header_title.php"); ?></title>
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
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>

    <style>
      .collapse-container {
        border: 1px solid #ccc;
        border-radius: 5px;
        overflow: hidden;
        margin-bottom: 15px;
        width: 100%;
        opacity: 0.9;
      }

      .collapse-btn {
        background-color: #282c34;
        color: white;
        border: none;
        padding: 10px;
        width: 100%;
        text-align: left;
        cursor: pointer;
        transition: background-color 0.3s ease;
        display: flex;
        justify-content: space-between;
        align-items: center;

      }

      .collapse-btn:hover {
        background-color: #555;
      }

      .collapse-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.3s ease, padding 0.3s ease;
        background-color: #f9f9f9;
        padding: 0 10px;
      }

      .collapse-content p {
        margin: 10px 0;
      }

      .collapse-content.open {
        max-height: 1000px;
        /* Ajusta este valor según el contenido */
        padding: 10px;
      }

      footer {
        margin: 0;
        padding: 15px 0;
      }
    </style>

    <style>
      .upload-container {
        display: flex;
        gap: 10px;
        max-width: 700px;
      }

      .file-input-container {
        flex-grow: 1;
        position: relative;
        height: 40px;
        border: 1px solid #ccc;
        border-radius: 4px;
        overflow: hidden;
        background: white;
      }

      .file-input-container input[type="text"] {
        width: 100%;
        height: 100%;
        padding: 8px;
        border: none;
        outline: none;
        cursor: default;
        background: transparent;
      }

      .hidden-file-input {
        display: none;
      }

      .select-button {
        padding: 0 20px;
        height: 40px;
        background-color: #e0e0e0;
        border: 1px solid #ccc;
        border-radius: 4px;
        cursor: pointer;
        white-space: nowrap;
      }

      .select-button:hover {
        background-color: #d0d0d0;
      }
    </style>
  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
        <?php include("include/menu.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
              <div class="x_content">

                <h2 align="center">Solicitud de Matrícula <?php echo $res_periodo['nombre'] ?></h2>

                <!-- Sección de realizar copia -->

              </div>
              <?php if ($count_sol_mat == 0 || $solicitud_matricula_res['estado']==0) { ?>
                <div class="x_content">
                  <div class="">
                    <p>Asegurese de seleccionar las unidades didácticas que esta condicionado a llevar. Posterior a su solicitud se validara si está apto para llevar los cursos seleccionados</p>
                  </div>
                  <br>
                  <h4><b>Unidades didacticas</b></h4>
                  <?php foreach ($semestre_cursos as $ciclo) { ?>
                    <div class="collapse-container">
                      <button class="collapse-btn">
                        <span>
                          CICLO <?php echo $ciclo; ?>
                        </span>

                        <span class="fa fa-chevron-down">
                      </button>

                      <div class="collapse-content open"> <!-- Agrega la clase 'open' aquí -->
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                          <thead>
                            <tr>
                              <th>N°</th>
                              <th>Unidad Didáctica</th>
                              <th>Tipo</th>
                              <th>Creditos</th>
                              <th>Acciones</th>
                            </tr>
                          </thead>

                          <tbody>
                            <?php
                            $contador = 1;
                            $cursos = buscarUdByCarSem($conexion, $programa, $ciclo);
                            while ($curso = mysqli_fetch_array($cursos)) { ?>
                              <tr>
                                <td><?php echo $contador++; ?></td>
                                <td><?php echo $curso['descripcion']; ?></td>
                                <td><?php echo $curso['tipo']; ?></td>
                                <td><?php echo $curso['creditos']; ?></td>
                                <td>
                                  <button class="btn btn-success agregar-curso" data-id="<?php echo $curso['id']; ?>" data-ciclo="<?php echo $ciclo; ?>">
                                    Agregar <i class="fa fa-plus"></i>
                                  </button>
                                </td>
                              </tr>
                            <?php } ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <?php } ?>

                </div>

                <div class="x_content">
                  <h4><b>Detalle de matricula</b></h4>

                  <table id="detalle-matricula" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                      <tr>
                        <th>N°</th>
                        <th>Unidad Didáctica</th>
                        <th>Tipo</th>
                        <th>Ciclo</th>
                        <th>Creditos</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>

                    <tbody>
                    </tbody>
                  </table>

                  <!-- TOTAL DE CREDITOS: -->
                  <p style="display: flex; justify-content: flex-end;">
                    <b>TOTAL DE CREDITOS: <span id="cantidad_creditos">0</span></b>
                  </p>
                  <p style="display: flex; justify-content: flex-end;">
                    <b>MAXIMA CANTIDAD DE CREDITOS: <span id="max_cantidad_creditos"><?php echo $matricula['creditos'] ?></span></b>
                  </p>


                </div>
                <form class="x_content" id="formulario-matricula" action="operaciones/solicitar_matricula.php" method="POST" enctype="multipart/form-data">
                  <input type="hidden" name="cursos-seleccionados" id="cursos-seleccionados">
                  <input type="hidden" name="id_estudiante" id="id_estudiante" value="<?php echo $id_estudiante_sesion; ?>">
                  <input type="hidden" name="id_ajuste_matricula" id="id_matricula" value="<?php echo $matricula['id']; ?>">
                  <div class="x_content">
                    <p>
                      Subir el Boucher de pago escaneado o fotografiado en PDF
                    </p>

                    <div class="upload-container">
                      <div class="file-input-container">
                        <input type="file" name="file_pago" class="form-control" accept=".pdf" required>
                      </div>
                    </div>
                  </div>

                  <div class="x_content">
                    <button class="btn btn-primary" type="submit">
                      Confirmar matrícula
                      <i class="fa fa-send"></i>
                    </button>
                  </div>
                </form>
              <?php }

              if ($count_sol_mat != 0 && $solicitud_matricula_res['estado'] == 1) { ?>

                <!-- Cuando la solicitud se a enviado -->
                <div class="x_content">
                  <p>
                    Su solicitud esta siendo evaluado, este proceso puede demorar de 1 a 5 días.
                  </p>
                  <p>
                    A continuación puede descargar su documento de solicitud de matricula.
                  </p>
                  <a href="../docente/solicitud_matricula.php?id=<?php echo $solicitud_matricula_res['id']  ?>" target="_blank" class="btn btn-primary">
                    Descargar solicitud de matricula
                    <i class="fa fa-download"></i>
                  </a>
                </div>
              <?php }
              if ($count_sol_mat != 0 && $solicitud_matricula_res['estado'] == 2) { ?>
                <!-- Cuando la solicitud fue observada. -->
                <div class="x_content">
                  <p>
                    <b style="color: red;">Su solicitud a sido observado!</b>
                  </p>
                  <p>
                    <b>Detalle de observación:</b> <?php echo $solicitud_matricula_res['observacion']; ?>
                  </p>
                  <form class="form-horizontal form-label-left" action="operaciones/actualizar_solicitud_matricula.php" method="POST">
                    <input type="hidden" name="id" value="<?php echo $solicitud_matricula_res['id']; ?>">
                    <button class="btn btn-primary" type="submit">
                      Proceder a regularizar
                      <i class="fa fa-check"></i>
                    </button>
                  </form>
                </div>
              <?php }
              if ($count_sol_mat != 0 && $solicitud_matricula_res['estado'] == 3) { ?>
                <!-- Cuando la solicitud es aprobada. -->
                <div class="x_content">
                  <p><b>Su solicitud a sido aceptado!</b></p>
                  <p>Ahora puede descargar su ficha de matricula!</p>

                  <a href="../docente/solicitud_matricula.php?id=<?php echo $solicitud_matricula_res['id']  ?>" target="_blank" class="btn btn-primary">
                    Descargar solicitud de matricula
                    <i class="fa fa-download"></i>
                  </a>

                  <button class="btn btn-success">
                    Descargar ficha de matricula
                    <i class="fa fa-download"></i>
                  </button>
                </div>
              <?php } ?>
            </div>
          </div>
          <!-- /page content -->

          <!-- footer content -->
          <?php include("../include/footer.php"); ?>
          <!-- /footer content -->
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
      const collapseButtons = document.querySelectorAll('.collapse-btn');

      collapseButtons.forEach(button => {
        button.addEventListener('click', () => {
          const content = button.nextElementSibling;
          content.classList.toggle('open');
        });
      });
    </script>

    <script>
      document.getElementById('file-input').addEventListener('change', function(e) {
        const fileName = e.target.files[0]?.name || '';
        document.getElementById('file-path').value = fileName;
      });
    </script>
    <script>
      $(document).ready(function() {
        let cursosSeleccionados = [];
        let totalCreditos = 0;
        let maxCreditos = parseInt($('#max_cantidad_creditos').text(), 10); // Límite de créditos

        function actualizarTotalCreditos() {
          $('#cantidad_creditos').text(totalCreditos);
        }

        // Agregar curso a la tabla de detalle de matrícula
        $(document).on('click', '.agregar-curso', function() {
          let idCurso = $(this).data('id');
          let ciclo = $(this).data('ciclo');
          let nombreCurso = $(this).closest('tr').find('td:nth-child(2)').text();
          let tipoCurso = $(this).closest('tr').find('td:nth-child(3)').text();
          let creditosCurso = parseInt($(this).closest('tr').find('td:nth-child(4)').text(), 10);

          if (totalCreditos + creditosCurso > maxCreditos) {
            alert("No puedes inscribirte en más cursos. Se ha alcanzado el máximo de créditos permitidos.");
            return; // Detiene la ejecución y no agrega el curso
          }

          if (!cursosSeleccionados.includes(idCurso)) {
            cursosSeleccionados.push(idCurso);
            totalCreditos += creditosCurso;
            $('#detalle-matricula tbody').append(
              `<tr data-id="${idCurso}">
                <td>${idCurso}</td>
                <td>${nombreCurso}</td>
                <td>${tipoCurso}</td>
                <td>${ciclo}</td>
                <td>${creditosCurso}</td>
                <td><button class="btn btn-danger eliminar-curso" data-id="${idCurso}" data-creditos="${creditosCurso}">Eliminar</button></td>
            </tr>`
            );
            $(this).prop('disabled', true); // Ocultar el botón
            actualizarTotalCreditos();
          }
        });

        // Eliminar curso de la tabla de detalle de matrícula
        $(document).on('click', '.eliminar-curso', function() {
          let idCurso = $(this).data('id');
          let creditosCurso = parseInt($(this).data('creditos'), 10);
          cursosSeleccionados = cursosSeleccionados.filter(id => id !== idCurso);
          totalCreditos -= creditosCurso;
          $(this).closest('tr').remove();
          $(`.agregar-curso[data-id='${idCurso}']`).prop('disabled', false); // Mostrar botón otra vez
          actualizarTotalCreditos();
        });

        // Enviar el formulario con los cursos seleccionados
        $('#formulario-matricula').submit(function(event) {
          event.preventDefault(); // Prevenir envío normal
          $('#cursos-seleccionados').val(cursosSeleccionados.join(','));
          this.submit();
        });
      });
    </script>

    <?php mysqli_close($conexion); ?>
  </body>

  </html>
<?php
}
