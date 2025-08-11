<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../caja/consultas.php");
include("../include/funciones.php");

include("include/verificar_sesion_secretaria.php");

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
	  
    <title>Caja <?php include ("../include/header_title.php"); ?></title>
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

  <body class="nav-md">
    <div class="container body">
      <div class="main_container">
        <!--menu-->
          <?php 
          include ("include/menu_secretaria.php"); ?>

        <!-- page content -->
        <div class="right_col" role="main">
          <div class="">
           
            <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="x_panel">
                  <div class="x_content">
                  <div class="">
                    <h2 align="center">Movimientos</h2>
                  </div>
                    <!-- Tabs -->
                  <ul class="nav nav-tabs">
                    <li class="active"><a data-toggle="tab" href="#tab1">INGRESOS</a></li>
                    <li><a data-toggle="tab" href="#tab2">EGRESOS</a></li>
                  </ul>
                  <!-- Contenido de los Tabs -->
                  <div class="tab-content">
                    <div id="tab1" class="tab-pane fade in active">
                      <br>
                      <button class="btn btn-success" data-toggle="modal" data-target=".registrar"><i class="fa fa-plus-square"></i> Nuevo</button>
                      <br><br>
                      <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>N°</th>
                            <th>Usuario Caja</th>
                            <th>D.N.I. Usuario</th>
                            <th>Usuario</th>
                            <th>Comprobante</th>
                            <th>Serie-Número</th>
                            <th>Metodo de Pago</th>
                            <th>Fecha de Pago</th>
                            <th>Monto Pagado</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $busc_ingr = buscarIngresos($conexion); 
                            while ($ingresos=mysqli_fetch_array($busc_ingr)){
                          ?>
                          <tr>
                            <td><?php echo $ingresos['id']; ?></td>
                            <td><?php echo $ingresos['responsable']; ?></td>
                            <td><?php echo $ingresos['dni']; ?></td>
                            <td><?php echo $ingresos['usuario']; ?></td>
                            <td><?php echo $ingresos['tipo_comprobante']; ?></td>
                            <td><?php echo $ingresos['codigo']; ?></td>
                            <td><?php echo $ingresos['metodo_pago']; ?></td>
                            <td><?php echo $ingresos['fecha_pago']; ?></td>
                            <td><?php echo $ingresos['monto_total']; ?></td>
                            <td>
                            <button class="btn btn-danger" data-toggle="modal" data-target=".anular_ingreso_<?php echo $ingresos['id']; ?>"><i class="fa fa-ban"></i> Anular</button>  
                            <a title="Ver PDF" class="btn btn-success" href="<?php echo substr($ingresos['ruta_archivo'],3); ?>" target="_blank"><i class="fa fa-file"></i></a></td>
                              
                          </tr>  
                          <?php
                          include('include/acciones_anular_ingreso.php');
                            };
                          ?>

                        </tbody>
                      </table>
                    </div>
                    <div id="tab2" class="tab-pane fade">
                    <br>
                      <button class="btn btn-success" data-toggle="modal" data-target=".registrar_egreso"><i class="fa fa-plus-square"></i> Nuevo</button>
                      <br><br>
                      <table id="exa" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                          <tr>
                            <th>N°</th>
                            <th>Usuario Caja</th>
                            <th>Nombre Empresa</th>
                            <th>R.U.C. Empresa</th>
                            <th>Concepto</th>
                            <th>Tipo Comprobante</th>
                            <th>Serie-número</th>
                            <th>Costo Total</th>
                            <th>Fecha Pago</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php 
                            $busc_egr = buscarEgresos($conexion); 
                            while ($egresos=mysqli_fetch_array($busc_egr)){
                          ?>
                          <tr>
                            <td><?php echo $egresos['id']; ?></td>
                            <td><?php echo $egresos['responsable']; ?></td>
                            <td><?php echo $egresos['empresa']; ?></td>
                            <td><?php echo $egresos['ruc']; ?></td>
                            <td><?php echo $egresos['concepto']; ?></td>
                            <td><?php echo $egresos['tipo_comprobante']; ?></td>
                            <td><?php echo $egresos['comprobante']; ?></td>
                            <td><?php echo $egresos['monto_total']; ?></td>
                            <td><?php echo $egresos['fecha_pago']; ?></td>
                            <td><?php echo $egresos['fecha_registro']; ?></td>
                            <td>
                            <button class="btn btn-danger" data-toggle="modal" data-target=".anular_<?php echo $egresos['id']; ?>"><i class="fa fa-ban"></i> Anular</button>  
                            
                          </tr>   
                          <?php
                         include('include/acciones_anular_egreso.php');
                          };
                        ?>

                        </tbody>
                      </table>
                    </div>
                  </div>

                    <!--MODAL REGISTRAR INGRESOS-->
                <div class="modal fade registrar" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Registrar Nuevo Ingreso</h4>
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
                    <form role="form" action="registrar_ingreso.php" class="form-horizontal form-label-left input_mask" method="POST" >
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo Comprobante : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="concepto" name="comprobante" value="" required="required" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <option value="natural">PERSONA NATURAL</option>
                            <option value="juridica">PERSONA JURÍDICA</option>
                          </select>  
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">D.N.I. / R.U.C. : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" id="dni" name="dni" required="required" style="text-transform:uppercase;"  oninput="validateInput(this)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <!--<button type="button" onclick="buscarPorDNI()" oninput="validateInputNum(this, 8)" class="btn btn-success">Buscar</button>
                          <br><br>-->
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Apellidos y Nombres : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="nombres" id="nombres_apellidos" required="required" style="text-transform:uppercase;" oninput="validateInputText(this, 40)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo Comprobante : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="concepto" name="comprobante" value="" required="required" onkeyup="javascript:this.value=this.value.toUpperCase();">
                            <option></option>
                          <?php 
                            $res_ce = buscarComprobante($conexion);
                            while ($compro = mysqli_fetch_array($res_ce)) {
                              $id_ci = $compro['id'];
                              $compro = $compro['comprobante'];
                              ?>
                              <option value="<?php echo $id_ci;
                              ?>"><?php echo $compro; ?></option>
                            <?php
                            }
                            ?>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div align="center">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                          
                          <button type="submit" class="btn btn-primary">Ir a Pagos</button>
                      </div>
                    </form>
                  </div>
                </div>
                </div>
                          <!--FIN DE CONTENIDO DE MODAL-->
                
      </div>
    </div>
  </div>
</div>

<!-- FIN MODAL REGISTRAR-->


        <!--MODAL REGISTRAR EGRESO-->
                    <div class="modal fade registrar_egreso" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                      <div class="modal-content">

                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                          </button>
                          <h4 class="modal-title" id="myModalLabel" align="center">Registrar Nuevo Egreso</h4>
                        </div>
                        <div class="modal-body">
                          <!--INICIO CONTENIDO DE MODAL-->
                  <div class="x_panel">
                    
                  <div class="" align="center">
                    <h2 ></h2>
                    <div class="clearfix"></div>
                  </div>
                  <div class="x_content">
                    <b>A travez de esta opción puede registrar los costos/gastos de la IESTP, recuerde que la responsabilidad de autentificar el comprobante no es por medio del sistema.</b>
                    <br /><br>
                    
                    <form role="form" action="operaciones/registrar_egreso.php" class="form-horizontal form-label-left input_mask" method="POST" >
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Nombre del emisor : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="usuario" value="" required="required" style="text-transform:uppercase;" oninput="validateInputText(this, 40)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>  
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Número R.U.C. del emisor : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="ruc" value="" required="required" style="text-transform:uppercase;" oninput="validateInputNum(this,11)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Concepto de egreso : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="concepto" value="" required="required" style="text-transform:uppercase;" oninput="limitarTamanio(this, 80)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Tipo de Comprobante : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <select class="form-control" id="tipo" name="tipo" required>
                            <option value="BOLETA">BOLETA</option>
                            <option value="FACTURA">FACTURA</option>
                          </select>
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Serie Comprobante: </label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" class="form-control" name="serie" value="" required="required" style="text-transform:uppercase;" oninput="limitarTamanio(this, 3)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                <br>
                            </div>

                            <label class="control-label col-md-3 col-sm-3 col-xs-12">Número Comprobante: </label>
                            <div class="col-md-3 col-sm-3 col-xs-12">
                                <input type="text" class="form-control" name="numero" value="" required="required" style="text-transform:uppercase;" oninput="validateInputNum(this, 8)" onkeyup="javascript:this.value=this.value.toUpperCase();">
                                <br>
                            </div>
                        </div>
                        
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Fecha del Pago : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="date" class="form-control" name="fecha" value="" required="required" >
                          <br>
                        </div>
                      </div>
                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12">Monto Pagado : </label>
                        <div class="col-md-9 col-sm-9 col-xs-12">
                          <input type="text" class="form-control" name="monto" value="" required="required" oninput="validateInputNum(this, 8)">
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
                </div>
                          <!--FIN DE CONTENIDO DE MODAL-->
                
      </div>
    </div>
  </div>
</div>

<!-- FIN MODAL-->
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
                          
    <script>
      // Función para cambiar la longitud máxima del input y limpiar su valor según la opción seleccionada
      function actualizarLongitudMaxima() {
        var tipoComprobante = document.getElementById("concepto").value;
        var inputDNI = document.getElementById("dni");

        if (tipoComprobante === "natural") {
          inputDNI.maxLength = 8;
        } else if (tipoComprobante === "juridica") {
          inputDNI.maxLength = 11;
        }

        // Limpiar el valor del input cuando cambia la opción en el select
        inputDNI.value = "";
      }

      // Agrega un evento onchange al select para llamar a la función cuando cambia la opción seleccionada
      document.getElementById("concepto").addEventListener("change", actualizarLongitudMaxima);

      // Llama a la función al cargar la página para establecer la longitud máxima inicial y limpiar el valor
      actualizarLongitudMaxima();
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
      
      function validateInput(input) {
          // Obtén el valor actual del campo de entrada
          let inputValue = input.value;

          // Remueve cualquier carácter no permitido (en este caso, letras)
          inputValue = inputValue.replace(/[^0-9!@#$%^&*()_+\-=\[\]{};':"\\|,.<>\/?]+/g, '');
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

    <script>
        async function buscarPorDNI() {
            // Obtener el valor del DNI
            var dni = document.getElementById("dni").value;

            // Token proporcionado por la API (reemplaza 'TU_TOKEN' con tu token real)
            var token = '38921056413a314097179eec84229162502967e29eb0076c26c8c303845da65d';

            // Configurar los parámetros de la solicitud
            var params = {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify({ dni: dni })
            };

            try {
                // Realizar la solicitud a la API con fetch
                var response = await fetch("https://apiperu.dev/api/dni", params);
                var data = await response.json();

                // Manejar la respuesta de la API
                mostrarResultado(data.data.nombre_completo);
            } catch (error) {
                // Manejar el error
                mostrarResultado("Registre manualmente");
            }
        }

        function mostrarResultado(resultado) {
            // Mostrar el resultado en la página
            document.getElementById("nombres_apellidos").value = resultado;
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
    $(document).ready(function() {
    $('#exa').DataTable({
      "language":{
    "processing": "Procesando...",
    "lengthMenu": "Mostrar _MENU_ registros",
    "zeroRecords": "No se encontraron resultados",
    "emptyTable": "Ningún dato disponible en esta tabla",
    "sInfo": "Mostrando del _END_ al _START_ de un total de _TOTAL_ registros",
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

     <?php mysqli_close($conexion); ?>
  </body>
</html>
<?php } ?>