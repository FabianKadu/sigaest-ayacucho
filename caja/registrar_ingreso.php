<?php
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

include("include/verificar_sesion_caja.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

    $id_tipo = $_POST['comprobante'];
    $dni = $_POST['dni'];
    $nombre = $_POST['nombres'];

    $res_com = buscarComprobantesById($conexion, $id_tipo);
    $tipo = mysqli_fetch_array($res_com);
    $nombre_compr = $tipo['comprobante'];
    $cod_compr = $tipo['codigo'];
    $cod_lon = $tipo['longitud'];

    $res_ing = buscarIngresosByComprobante($conexion, $nombre_compr);
    $cont_ing = mysqli_num_rows($res_ing);

    function generarCodigo($prefijo, $longitud, $numero)
    {
        // Crear el formato del número con ceros a la izquierda
        $numeroFormateado = sprintf('%0' . $longitud . 'd', $numero);

        // Combinar el prefijo con el número formateado
        $codigoCompleto = $prefijo . "-" . $numeroFormateado;

        return $codigoCompleto;
    }

    $codigo = generarCodigo($cod_compr, $cod_lon, $cont_ing + 1);

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

        <title>Caja <?php include("../include/header_title.php"); ?></title>
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
        <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>

        <!-- Custom Theme Style -->
        <link href="../Gentella/build/css/custom.min.css" rel="stylesheet">
        <style>
            .list {
                margin: 10px auto;
                display: flex;
                justify-content: center;
                flex-wrap: wrap;
                gap: 20px;
            }

            .form-element {
                position: relative;
                width: 150px;
                height: 50px;
            }

            .form-element input {
                display: none;
            }

            .form-element label {
                padding-left: 20px;
                width: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                height: 100%;
                cursor: pointer;
                border: 2px solid #ddd;
                background: #fff;
                box-shadow: 0px 5px 20px 2px rgba(0, 0, 0, 0.1);
                text-align: center;
                transition: all 200ms ease-in-out;
                border-radius: 5px;
            }

            .form-element .title {
                font-size: 9px;
                color: #555;
                padding: 1px 0px;
                transition: all 200ms ease-in-out;
            }

            .form-element label:before {
                content: "✓";
                position: absolute;
                width: 18px;
                height: 18px;
                top: 8px;
                left: 8px;
                background: #0d0df1;
                color: #fff;
                text-align: center;
                line-height: 18px;
                font-size: 14px;
                font-weight: 600;
                border-radius: 50%;
                opacity: 0;
                transform: scale(0.5);
                transition: all 200ms ease-in-out;
            }

            .form-element input:checked+label:before {
                opacity: 1;
                transform: scale(1);
            }

            .form-element input:checked+label .icon {
                color: #0d0df1;
            }

            .form-element input:checked+label .title {
                color: #0d0df1;
            }

            .form-element input:checked+label {
                border: 2px solid #0d0df1;
            }
        </style>
    </head>

    <body class="nav-md">
        <div class="container body">
            <div class="main_container">
                <!--menu-->
                <?php
                $res_docente = buscarDocenteById($conexion, $id_docente_sesion);
                $docente = mysqli_fetch_array($res_docente);
                if ($docente['id_cargo'] == 6) {
                    include("include/menu_caja.php");
                }
                include("../docente/include/menu_secretaria.php"); ?>
                <!-- page content -->
                <div class="right_col" role="main">
                    <div class="">
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <div class="x_panel">
                                    <div class="x_content">
                                        <div class="">
                                            <h2 align="center">Modulo de Pago</h2>
                                        </div>
                                    </div>
                                    <div class="" role="main">
                                        <form role="form" action="operaciones/registrar_ingreso.php"
                                            class="form-horizontal form-label-left input_mask" method="POST">
                                            <div class="x_panel">
                                                <div class="x_content">

                                                    <div class="row">
                                                        <!-- Columna 1: Datos del Usuario (por ejemplo, DNI y Nombre) -->
                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="dni">D.N.I.:</label>
                                                                <input type="text" class="form-control" id="dni" name="dni"
                                                                    value="<?php echo $dni; ?>" readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="nombre">Apellidos y Nombres:</label>
                                                                <input type="text" class="form-control" id="nombre"
                                                                    name="nombre" value="<?php echo $nombre; ?>" readonly>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <div class="form-group">
                                                                <label for="doc">Tipo Comprobante:</label>
                                                                <input type="text" class="form-control" id="doc"
                                                                    value="<?php echo $nombre_compr; ?>" name="tipo"
                                                                    readonly>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cod">Serie y Número:</label>
                                                                <input type="text" class="form-control" id="cod"
                                                                    value="<?php echo $codigo; ?>" name="codigo" readonly>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="x_panel">
                                                <div class="x_content">
                                                    <h4>Conceptos de Ingreso</h4>
                                                    <div class="list">
                                                        <?php
                                                        $res_ser = buscarConceptoIngresos($conexion);
                                                        while ($ingresos = mysqli_fetch_array($res_ser)) {
                                                            ?>
                                                            <div class="form-element">
                                                                <input onclick="actualizarTabla()" type="checkbox"
                                                                    class="concepto-checkbox"
                                                                    data-monto="<?php echo floatval(decryptText($ingresos['monto'])) ?>"
                                                                    name="conceptos[]" value="<?php echo $ingresos['id'] ?>"
                                                                    id="<?php echo $ingresos['concepto'] ?>">
                                                                <label for="<?php echo $ingresos['concepto'] ?>">
                                                                    <div class="title">
                                                                        <?php echo $ingresos['concepto'] ?>
                                                                    </div>
                                                                </label>
                                                            </div>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- Tabla de Precios y Total a Pagar -->
                                            <div class="x_panel">
                                                <div class="x_title">
                                                    <h2>Resumen</h2>
                                                    <div class="clearfix"></div>
                                                </div>
                                                <div class="x_content">
                                                    <table class="table table-bordered">
                                                        <thead>
                                                            <tr>
                                                                <th>Cantidad</th>
                                                                <th>Nombre</th>
                                                                <th>Costo Unitario</th>
                                                                <th>Sub Total</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody id="tablaSeleccionados"></tbody>
                                                    </table>
                                                    <div class="form-group">
                                                        <label for="totalGeneral">Total a Pagar:</label>
                                                        <input type="text" class="form-control" id="totalGeneral"
                                                            name="monto" readonly>
                                                    </div>
                                                    <input type="hidden" class="form-control" id="cantidad" name="cantidad"
                                                        readonly>
                                                </div>
                                            </div>
                                            <div align="center">
                                                <input type="button" class="btn btn-success" data-toggle="modal"
                                                    data-target="#modalPago" value="PAGAR">
                                            </div>
                                            <div class="modal fade" id="modalPago" tabindex="-1" role="dialog"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Una vez
                                                                registrado el pago, no hay posibilidad de editar.
                                                                El monto a pagar es de <h4 style="color: black;"> S./ <span
                                                                        id="total_modal"></span> soles. </h4>
                                                            </h5>
                                                            <button type="button" class="close" data-dismiss="modal"
                                                                aria-label="Close">
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- Formulario dentro del modal -->
                                                            <div class="form-group">
                                                                <label for="metodoPago">Método de Pago:</label>
                                                                <!-- Campo de selección para el método de pago -->
                                                                <select class="form-control" id="metodoPago"
                                                                    name="metodoPago" required>
                                                                    <option value="EFECTIVO">Efectivo</option>
                                                                    <option value="TRANSFERENCIA">Transferencia</option>
                                                                    <option value="YAPE">Yape</option>
                                                                </select>
                                                            </div>
                                                            <div class="form-group">
                                                                <label for="cantidadPago">Con cuanto pagó:</label>
                                                                <!-- Campo de entrada para la cantidad pagada -->
                                                                <input type="text" class="form-control" id="cantidadPago"
                                                                    oninput="validateInputNum(this, 4)" name="cantidadPago"
                                                                    required>
                                                            </div>
                                                            <div id="vueltoContainer" style="display: none; color: black;">
                                                                <h4>Vuelto: <span id="vueltoSpan"></span></h4>
                                                            </div>
                                                            <br>
                                                            <button type="submit" class="btn btn-success">Registrar
                                                                Pago</button>
                                                        </div>
                                                    </div>
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
        <script>
            // Agrega un manejador de eventos para el campo cantidadPago
            document.getElementById('cantidadPago').addEventListener('input', function () {
                calcularVuelto(); // Llama a la función calcularVuelto al cambiar el valor
            });

            function calcularVuelto() {
                // Obtén el valor del campo cantidadPago y conviértelo a número
                var cantidadPago = parseFloat(document.getElementById('cantidadPago').value);

                // Obtén el valor total_modal y conviértelo a número
                var totalModal = parseFloat(document.getElementById('total_modal').innerText);

                // Calcula el vuelto
                var vuelto = cantidadPago - totalModal;

                if (vuelto >= 0) {
                    // Muestra el vuelto en el contenedor
                    document.getElementById('vueltoSpan').innerText = vuelto.toFixed(2);
                    document.getElementById('vueltoContainer').style.display = 'block';
                } else {
                    document.getElementById('vueltoContainer').style.display = 'none';
                }

            }
        </script>
        <script>
            // Guardar información de los elementos seleccionados y sus cantidades
            var elementosData = {};

            function actualizarTabla() {
                // Limpiar la tabla antes de actualizar
                $("#tablaSeleccionados").empty();

                var totalGeneral = 0;

                // Iterar sobre los elementos almacenados y actualizar la tabla
                Object.keys(elementosData).forEach(function (nombre) {
                    var monto = elementosData[nombre].monto;
                    var cantidad = elementosData[nombre].cantidad;

                    // Agregar fila a la tabla con una celda para la cantidad y otra para el valor total
                    $("#tablaSeleccionados").append(
                        "<tr>" +
                        "<td><input type='number' oninput='validateInputNum(this, 2)' class='cantidad' value='" + cantidad + "' min='1'></td>" +
                        "<td>" + nombre + "</td>" +
                        "<td>" + monto + "</td>" +
                        "<td class='valorTotal'>" + (monto * cantidad) + "</td>" +
                        "</tr>"
                    );

                    totalGeneral += monto * cantidad;
                });

                // Actualizar el total general
                $("#totalGeneral").val(totalGeneral.toFixed(2));
                $("#total_modal").text(totalGeneral.toFixed(2));

                // Actualizar el valor de las cantidades
                var cantidades = "";
                $(".cantidad").each(function (index, element) {
                    cantidades += ($(element).val()) + "/";
                });
                $("#cantidad").val(cantidades);
            }

            // Evento de cambio en la selección de los elementos
            $(".concepto-checkbox").on("change", function () {
                var nombre = $(this).attr("id");
                var monto = parseFloat($(this).data("monto"));

                if ($(this).is(":checked")) {
                    // Elemento seleccionado, inicializar o actualizar información
                    if (!elementosData[nombre]) {
                        elementosData[nombre] = {
                            monto: monto,
                            cantidad: 1
                        };
                    }
                } else {
                    // Elemento deseleccionado, eliminar información
                    delete elementosData[nombre];
                }

                // Actualizar la tabla
                actualizarTabla();
            });

            $(document).on("input", ".cantidad", function () {
                var cantidad = $(this).val();
                var nombre = $(this).closest("tr").find("td:nth-child(2)").text();
                var monto = elementosData[nombre].monto;
                elementosData[nombre].cantidad = cantidad;

                // Calcular el subtotal y actualizar la celda correspondiente en la tabla
                var subtotal = cantidad * monto;
                $(this).closest("tr").find(".valorTotal").text(subtotal.toFixed(2));

                // Actualizar el total general
                var totalGeneral = 0;
                $(".valorTotal").each(function (index, element) {
                    totalGeneral += parseFloat($(element).text());
                });
                $("#totalGeneral").val(totalGeneral.toFixed(2));
                $("#total_modal").text(totalGeneral.toFixed(2));

                // Actualizar el valor de las cantidades
                var cantidades = "";
                $(".cantidad").each(function (index, element) {
                    cantidades += ($(element).val()) + "/";
                });
                $("#cantidad").val(cantidades);
            });
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
    </body>

    </html>

<?php } ?>