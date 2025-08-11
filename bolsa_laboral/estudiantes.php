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
} else {

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
    <!-- Datatables -->
    <link href="../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
    <link href="../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

    <!-- Custom Theme Style -->
    <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">


    <!-- <style>
      .ui-pnotify.dark {
        opacity: 0;
        display: none;
      }

      .dataTables_filter {
        display: none;
      }
    </style> -->

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

                  <div class="container">
                    <h2 align="center">Estudiantes</h2>
                    <div class="">
                    <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>
                    <a href="importar_estudiantes.php" class="btn btn-primary"><i class="fa fa-cloud-upload"></i> Importar Estudiantes</a>

                    <div class="clearfix"></div>
                    <br>

                  </div>
                    <div class="row">
                      <div class="col-lg-4">
                        <div><b>Filtrar Por Programa de Estudios: </b></div>
                        <div class="form-group ">
                          <select id="filtro_programa" class="form-control">
                            <option value="">TODOS</option>
                            <?php
                            $ejec_busc_carr = buscarCarreras($conexion);
                            while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                              $id_carr = $res__busc_carr['id'];
                              $carr = $res__busc_carr['nombre'];
                              ?>
                              <option value="<?php echo $carr;
                              ?>"><?php echo $carr; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div><b>Filtrar Por Semestre: </b></div>
                        <div class="form-group ">
                          <select id="filtro_semestre" class="form-control">
                            <option value="">TODOS</option>
                            <?php
                            $ejec_busc_sem = buscarSemestre($conexion);
                            while ($res_busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                              $id_sem = $res_busc_sem['id'];
                              $sem = $res_busc_sem['descripcion'];
                              ?>
                              <option value="<?php echo $sem;
                              ?>"><?php echo $sem; ?></option>
                              <?php
                            }
                            ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-lg-3">
                        <div><b>Filtrar Por Situación: </b></div>
                        <div class="form-group ">
                          <select id="filtro_situacion" class="form-control">
                            <option value="">TODOS</option>
                            <option value="EGRESADO">EGRESADO</option>
                            <option value="NO EGRESADO">NO EGRESADO</option>
                          </select>
                        </div>
                      </div>

                    </div>
                  </div>

                  <div class="x_content">
                    <br />

                    <table id="tabla-estudiantes" class="table table-striped table-bordered" style="width:100%">
                      <thead>
                        <tr>
                          <th>N°</th>
                          <th>DNI</th>
                          <th>Apellidos y Nombres</th>
                          <th>Teléfono / Celular</th>
                          <th>Programa de Estudios</th>
                          <th>Situación</th>
                          <th>Semestre / Ciclo</th>
                          <th>Postulaciones</th>
                          <th>Acciones</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $ejec_busc_est = buscarTodosEstudiantes($conexion);
                        while ($res_busc_est = mysqli_fetch_array($ejec_busc_est)) {
                          $id_estudiante = $res_busc_est['id'];
                          $res_ofertas = buscarOfertasEstudiante($conexion, $id_estudiante);
                          $res_ofertas_iestp = buscarOfertasEstudianteInstituto($conexion, $id_estudiante);
                          $cantidad_ofertas = mysqli_num_rows($res_ofertas);
                          $cantidad_ofertas_iestp = mysqli_num_rows($res_ofertas_iestp);
                          $cantidad_ofertas += $cantidad_ofertas_iestp;
                          ?>
                          <tr>
                            <td><?php echo $res_busc_est['id']; ?></td>
                            <td><?php echo $res_busc_est['dni']; ?></td>
                            <td><?php echo $res_busc_est['apellidos_nombres']; ?></td>
                            <td><?php echo $res_busc_est['telefono']; ?></td>
                            <?php
                            $id_p_e = $res_busc_est['id_programa_estudios'];
                            $ejec_busc_p_e = buscarCarrerasById($conexion, $id_p_e);
                            $res_busc_p_e = mysqli_fetch_array($ejec_busc_p_e);
                            ?>
                            <td><?php echo $res_busc_p_e['nombre']; ?></td>
                            <td>
                              <?php
                              if ($res_busc_est['egresado'] == 'SI') {
                                echo 'EGRESADO';
                              } else {
                                echo 'NO EGRESADO';
                              }
                              ?>
                            </td>
                            <?php
                            $id_sem = $res_busc_est['id_semestre'];
                            $semestre = "No registrado";
                            if ($id_sem != 0) {
                              $ejec_busc_sem = buscarSemestreById($conexion, $id_sem);
                              $res_busc_sem = mysqli_fetch_array($ejec_busc_sem);
                              $semestre = $res_busc_sem['descripcion'];
                            }

                            ?>
                            <td><?php echo $semestre; ?></td>

                            <td><?php echo $cantidad_ofertas; ?></td>
                            <td>
                            <a class="btn btn-success" href="editar_estudiante.php?id=<?php echo $res_busc_est['id']; ?>"><i class="fa fa-pencil-square-o"></i> Editar</a>
                            <a class="btn btn-info"
                                href="convocatorias_estudiante.php?id=<?php echo $res_busc_est['id']; ?>"
                                data-toggle="tooltip" data-original-title="Detalles Postulaciones"
                                data-placement="bottom"><i class="fa fa-eye"></i></a>

                            </td>
                          </tr>
                          <?php

                        }
                        ;
                        ?>

                      </tbody>
                    </table>
 <!--MODAL REGISTRAR-->
  <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Registrar Estudiante</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  <div class="" align="center">
                    <h2 ></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <br />
                    <form role="form" action="operaciones/registrar_estudiante.php" class="form-horizontal form-label-left input_mask" method="POST" >
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">DNI : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="number" class="form-control" name="dni" id="dni" required="required" maxlength="8">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Apellidos y Nombres : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="nom_ap" id="nom_ap" required="required">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Género : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="genero" name="genero" value="" required="required">
                            <option></option>
                          <?php 
                            $ejec_busc_gen = buscarGenero($conexion);
                            while ($res_busc_gen = mysqli_fetch_array($ejec_busc_gen)) {
                              $id_gen = $res_busc_gen['id'];
                              $gen = $res_busc_gen['genero'];
                              ?>
                              <option value="<?php echo $id_gen;
                              ?>"><?php echo $gen; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha de Nacimiento : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="date" class="form-control" name="fecha_nac" required="required">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Dirección : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="direccion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Correo Electrónico : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="email" class="form-control" name="email" required="required">
                          <br>
                          
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Teléfono : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="Number" class="form-control" name="telefono" required="required" maxlength="15">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Año de Ingreso : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select name="anio_ingreso" id="" class="form-control" required="required">
                            <?php
                            $anio = date("Y");
                            $anio_inicio = $anio -2;
                            $anio_fin = $anio + 2;
                            for ($i=$anio_inicio; $i < $anio_fin; $i++) { 
                              ?>
                              <option value="<?php echo $i;?>"><?php echo $i; ?></option>
                              <?php
                            }
                            ?>
                            
                          </select>
                          
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Carrera Profesional : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control"  id="carrera" name="carrera" value="" required="required">
                            <option></option>
                          <?php 
                            $ejec_busc_carr = buscarCarreras($conexion);
                            while ($res__busc_carr = mysqli_fetch_array($ejec_busc_carr)) {
                              $id_carr = $res__busc_carr['id'];
                              $carr = $res__busc_carr['nombre'];
                              ?>
                              <option value="<?php echo $id_carr;
                              ?>"><?php echo $carr; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Semestre / Ciclo : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="semestre" name="semestre" value="" required="required">
                            <option></option>
                          <?php 
                            $ejec_busc_sem = buscarSemestre($conexion);
                            while ($res_busc_sem = mysqli_fetch_array($ejec_busc_sem)) {
                              $id_sem = $res_busc_sem['id'];
                              $sem = $res_busc_sem['descripcion'];
                              ?>
                              <option value="<?php echo $id_sem;
                              ?>"><?php echo $sem; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Sección : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="seccion" required="required" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();" maxlength="1">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Turno : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" name="turno" value="" required="required">
                            <option value="MAÑANA">MAÑANA</option>
                            <option value="TARDE">TARDE</option>
                          </select>
                          <br>
                        </div>
                      </div>
                      
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Discapacidad : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" name="discapacidad" value="" required="required">
                            <option></option>
                            <option value="SI">SI</option>
                            <option value="NO">NO</option>
                          </select>
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

<!-- FIN MODAL REGISTRAR-->
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
    <!-- <script>
      $(document).ready(function () {
        var tabla = $('#tabla-estudiantes').DataTable({
          "language": {
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
          },
          "order": [1, "desc"],
          "searching": true,
        });

        // Capturar el cambio en el select y realizar la búsqueda
        $('#filtro').on('change', function () {
          var valorSeleccionado = $(this).val(); // Obtener el valor seleccionado del select
          tabla.search(valorSeleccionado).draw(); // Realizar la búsqueda en DataTables y dibujar la tabla
        });

      });
    </script> -->

    <script>
      $(document).ready(function () {
        $('#tabla-estudiantes').DataTable({
          "language": {
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
          },

        });

      });
    </script>

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const dniInput = document.getElementById('dni');
            const nameInput = document.getElementById('nom_ap');

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
                          nameInput.value = data.apellidoPaterno + ' ' + data.apellidoMaterno + ' ' + data.nombres
                        })
                        .catch(error => {
                            
                        });
                }, 500);
            });
        });
    </script>

    <script>
      $(document).ready(function () {
        var table = $('#tabla-estudiantes').DataTable();

        $.fn.dataTable.ext.search.push(
          function (settings, data, dataIndex) {
            var programa = $('#filtro_programa').val().trim();
            var semestre = $('#filtro_semestre').val().trim();
            var situacion = $('#filtro_situacion').val().trim();
            var programaCell = data[4] || ''; // Índice de columna para Programa de Estudios
            var situacionCell = data[5] || ''; // Índice de columna para Situacion
            var semestreCell = data[6] || ''; // Índice de columna para Semestre

            if ((programa === '' || programaCell === programa) &&
              (semestre === '' || semestreCell === semestre) &&
              (situacion === '' || situacionCell === situacion)) {
              return true;
            }
            return false;
          }
        );
        $('#filtro_programa, #filtro_semestre, #filtro_situacion').on('change', function () {
          table.draw();
        });
      });
    </script>



    <?php mysqli_close($conexion); ?>
  </body>

  </html>
  <?php
}
