<?php
include ("../include/conexion.php");
include ("../empresa/include/consultas.php");
include ("../include/busquedas.php");
include ("../include/funciones.php");

include ("include/verificar_sesion_administrador.php");

if (!verificar_sesion($conexion)) {
  echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
}else {

  $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
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
    <link href="../Gentella/vendors/pnotify/dist/pnotify.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.buttons.css" rel="stylesheet">
    <link href="../Gentella/vendors/pnotify/dist/pnotify.nonblock.css" rel="stylesheet">
    <!-- Datatables -->
    <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">

    <style>
      .ui-pnotify.dark {
          opacity: 0;
          display: none;
      }
    </style>

  </head>

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
           include ("include/menu_administrador.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">

            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="">
                    <a href="estudiantes.php" class="btn btn-danger"><i class="fa fa-reply"></i> Regresar</a>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <!-- Contenido de los Tabs -->
                    <div class="tab-content">
                        <div id="tab1" class="tab-pane fade in active">
                          <h4><b>Formato en Excel</b></h4>
                          <a class="blue" href="https://drive.google.com/uc?id=1q-vb3Q7Qr63u6lR8T9pAcPYeBNRCHage&export=download"><i class="fa fa-file-excel-o"></i> Descargar Archivo</a>
                          <br>
                          <h4><b>Importante!!</b></h4>
                          <p>El formato de excel proporcionado cuenta con una header (cabecera) en las cuales está comentada la manera en cómo se debe de colocar la información. En cuanto a la información, las columnas que tienen (**) se consideran indispensable para el funcionamiento del sistema, con un (*) se puede dejar en blanco pero se considera importante y los que no cuentan con (*) no es indispensable.</p>
                          <section class="">
                            <div class="col-md-5 col-sm-5">
                              <h4><b>Programas de estudio</b></h4>
                              <p>Colocar solo el número en la columna de programa de estudio en el excel.</p>
                              <?php 
                              $carreras = buscarCarreras($conexion);
                              while($carrera = mysqli_fetch_array($carreras)){
                              ?>
                              <b><?php echo $carrera['id'];?> - <?php echo $carrera['nombre'];?> </b> <br>
                              <?php } ?>
                            </div>
                            <div class="col-md-7 col-sm-7">
                              <h4><b>Subir Documento</b></h4>
                              <form  class="form-horizontal form-label-left input_mask" action="../composer/importar_estudiantes.php" method="post" enctype="multipart/form-data">
                                <label for="estudaintes">Seleccionar el archivo excel:</label>
                                <input type="file" name="estudiantes" id="estudiantes" required="required" accept=".xlsx">
                                <br>
                                <input class="btn btn-success" type="submit" value="Importar">
                              </form>
                            </div>
                          </section>
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

    <script src="../Gentella/vendors/pnotify/dist/pnotify.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.buttons.js"></script>
    <script src="../Gentella/vendors/pnotify/dist/pnotify.nonblock.js"></script>

    <!-- Custom Theme Scripts -->
    <script src="../Gentella/build/js/custom.min.js"></script>
    
    <!-- FUNCION AJAX PARA RECUPERAR NOTAS DEL ESTUDIANTE -->
    <script type="text/javascript">
        function getNotas(){

          var calificacion = document.getElementById("dni_es").value;

        // Realizar la petición AJAX
        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'operaciones/obtener_notas.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                // Manejar la respuesta de la petición

                var response = JSON.parse(xhr.responseText);

                var notas = response.notas;
                var estudiante = response.estudiante;
                if (response.error || notas.length == 0) {
                    // Si hay un error, mostrarlo en la consola
                    var tableHTML = '<b class ="red">No cuenta con calificaciones.</b>';
                } else {
                    // Si no hay error, construir la tabla
                    var tableHTML = `
                            <b class ="">${estudiante}</b> <br><br>
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                              <thead>
                                <tr>
                                  <th>Programa de estudio</th>
                                  <th>Asignatura</th>
                                  <th>Créditos</th>
                                  <th>Calificación</th>
                                  <th>Semestre</th>
                                  <th>Fecha Importada</th>
                                  <th>Observacion</th>
                                  <th>Acciones</th>
                                </tr>
                              </thead>
                              <tbody>`;
                    for (var i = 0; i < notas.length; i++) {
                        var row = notas[i];

                        tableHTML += `<tr>
                                  <td>${row.nombre}</td>
                                  <td>${row.unidad_didactica}</td>
                                  <td>${row.cantidad_creditos}</td>
                                  <td>${row.calificacion}</td>
                                  <td>${row.semestre_academico}</td>
                                  <td>${row.date_create}</td>
                                  <td>${row.observacion}</td>
                                  <td>                      
                                    <button class="btn btn-warning" data-toggle="modal" title="Editar" data-placement="bottom" data-target=".editar_${row.id}">Editar</button>
                                    <button type="button" class="btn btn-danger" onclick="eliminarNota(${row.id})">Eliminar</button>
                                  </td>
                                </tr>  
                                <div class="modal fade editar_${row.id}" tabindex="-1" role="dialog" aria-hidden="true">
                                  <div class="modal-dialog modal-sm">
                                      <div class="modal-content">
                                          <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                                              </button>
                                              <div align="center">
                                                <h4 class="modal-title" id="myModalLabel" align="center">Editar Calificación</h4>
                                                <b>${row.dni}</b>
                                              </div>
                                          </div>
                                          <div class="modal-body">
                                              <!--INICIO CONTENIDO DE MODAL-->
                                              <div class="x_panel">
                                                  <div class="x_content">
                                                    <form role="form" action="operaciones/actualizar_nota_importada.php" id="${row.id}" class="form-horizontal form-label-left input_mask" method="POST">
                                                      <input type="hidden" name="id" id="id_${row.id}" value="${row.id}">
                                                      <div class="form-group">
                                                          <label class="control-label col-md-12 col-sm-12">Unidad Académica : </label>
                                                          <div class="col-md-12 col-sm-12">
                                                              <input type="text" id="unidad_${row.id}" class="form-control" name="unidad_didactica" required="required" value="${row.unidad_didactica}">
                                                              <br>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-12 col-sm-12">Calificación Final : </label>
                                                          <div class="col-md-12 col-sm-12">
                                                              <input type="number" id="calificacion_${row.id}" class="form-control" name="calificacion_final" required="required" maxlength="2" max="20" min="0" value="${row.calificacion}">
                                                              <br>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label class="control-label col-md-12 col-sm-12">Semestre Académico : </label>
                                                          <div class="col-md-12 col-sm-12">
                                                              <input type="text" id="semestre_${row.id}" class="form-control" name="semestre_academico" required="required" value="${row.semestre_academico}">
                                                              <br>
                                                          </div>
                                                      </div>
                                                      <div align="center">
                                                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                                          <!-- Cambiado type="button" a type="submit" -->
                                                          <button type="button" class="btn btn-primary" onclick="enviarFormulario(${row.id})">Guardar</button
                                                      </div>
                                                  </form>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                                `;
                    }
                    tableHTML += '</table>';
                }
                // Insertar la tabla en algún elemento del DOM
                document.getElementById("tablaNotas").innerHTML = tableHTML;
            }
        };
        xhr.send('dni=' + encodeURIComponent(calificacion));
    }
  </script>
  <script>
    function enviarFormulario(id) {
        // Obtener el formulario
        var id_up = document.getElementById("id_"+id).value;
        var calificacion = document.getElementById("calificacion_"+id).value;
        var semestre = document.getElementById("semestre_"+id).value;
        var unidad = document.getElementById("unidad_"+id).value;

        // Crear el objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Especificar el tipo de solicitud y la URL de destino
        xhr.open("POST", "operaciones/actualizar_nota_importada.php", true);

        // Configurar el encabezado de la solicitud
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Definir la función de callback que se ejecutará cuando la solicitud haya finalizado
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                  getNotas();
                  $('.modal').modal('hide');
                  new PNotify({
                                  title: 'Exitoso!',
                                  text: 'Se ha actualizado de manera correcta!',
                                  type: 'success',
                                  styling: 'bootstrap3'
                  });
                } else {
                  getNotas();
                  $('.modal').modal('hide');
                  new PNotify({
                                  title: 'Error!',
                                  text: 'Pueda que la información proporcionada sea incorrecta.',
                                  type: 'error',
                                  styling: 'bootstrap3'
                  });
                }
            }
        };

        // Construir los datos a enviar
        var datos = "id=" + encodeURIComponent(id_up) + "&calificacion_final=" + encodeURIComponent(calificacion) 
        + "&semestre_academico=" + encodeURIComponent(semestre)  + "&unidad_didactica=" + encodeURIComponent(unidad);

        // Enviar la solicitud con los datos
        xhr.send(datos);
    }
  </script>

<script>
    function eliminarNota(id) {      
    // Mostrar una alerta de confirmación
    if (confirm("¿Estás seguro de que quieres eliminar esta nota?")) {
        // Crear el objeto XMLHttpRequest
        var xhr = new XMLHttpRequest();

        // Especificar el tipo de solicitud y la URL de destino
        xhr.open("POST", "operaciones/eliminar_nota_importada.php", true);

        // Configurar el encabezado de la solicitud
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        // Definir la función de callback que se ejecutará cuando la solicitud haya finalizado
        xhr.onreadystatechange = function() {
            if (xhr.readyState === XMLHttpRequest.DONE) {
                if (xhr.status === 200) {
                    getNotas();
                    $('.modal').modal('hide');
                    new PNotify({
                        title: 'Exitoso!',
                        text: 'Se ha eliminado de manera correcta!',
                        type: 'success',
                        styling: 'bootstrap3'
                    });
                } else {
                    getNotas();
                    $('.modal').modal('hide');
                    new PNotify({
                        title: 'Error!',
                        text: 'Ah ocurrido un error inesperado.',
                        type: 'error',
                        styling: 'bootstrap3'
                    });
                }
            }
        };

        // Enviar la solicitud con los datos
        xhr.send("id=" + id);
    }
}
  </script>
     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php
}