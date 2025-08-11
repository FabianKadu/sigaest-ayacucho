<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
require_once('../../tcpdf/tcpdf.php');
include("../include/verificar_sesion_caja.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
} else {

	$id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);

	$usuario = buscarDocenteById($conexion, $id_docente_sesion);
	$usuario = mysqli_fetch_array($usuario);
	$usuario = $usuario['apellidos_nombres'];

	$tipo = $_POST['tipo'];
	$codigo = $_POST['codigo'];
	$dni = $_POST['dni'];
	$nombre = $_POST['nombre'];
	$monto = floatval(($_POST['monto']));
	$pagado_con = floatval($_POST['cantidadPago']);
	$diferencia = ($pagado_con - $monto);
	$metodo = $_POST['metodoPago'];
	$cantidad = $_POST['cantidad'];
	$conceptos = isset($_POST['conceptos']) ? $_POST['conceptos'] : [];

	$res_ingreso = buscarIngresosByCodigo($conexion, $codigo);
	$count = mysqli_num_rows($res_ingreso);

	if ($count == -1) {
		echo "<script>
				  alert('Este codigo de comprobante ya fue utilizado con anterioridad!.');
				  window.history.back();
			  </script>";
	} else {

		$datos_iestp = buscarDatosSistema($conexion);
		$datos_iestp = mysqli_fetch_array($datos_iestp);
		$nombre_insti = str_replace("IESTP ", "", $datos_iestp['titulo']);

		$datos_lugar = buscarDatosGenerales($conexion);
		$datos_iestp = mysqli_fetch_array($datos_lugar);
		$ruc_insti = $datos_iestp['ruc'];
		$dir_insti = $datos_iestp['direccion'] . " - " . $datos_iestp['distrito'] . " - " . $datos_iestp['departamento'];

		$rutaArchivo = '../../documentos/boletas_de_notas/' . $codigo . '.pdf';

		//CONFIGURACIÓN PDF
		$pdf = new TCPDF('P', PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetTitle($tipo . "-" . $codigo);
		$pdf->SetHeaderData('', '', PDF_HEADER_TITLE, PDF_HEADER_STRING);
		$pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
		$pdf->SetDefaultMonospacedFont('helvetica');
		$pdf->SetMargins(PDF_MARGIN_LEFT, '10', PDF_MARGIN_RIGHT);
		$pdf->setPrintHeader(false);
		$pdf->setPrintFooter(false);
		$pdf->SetAutoPageBreak(TRUE, 25);
		$pdf->SetFont('helvetica', '', 11);
		$pdf->AddPage('P', 'A5');
		$text_size = 8;
		$contador = 0;

		//CONTENIDO DEL PDF
		$documento = '
			<table border="0" width="100%" cellspacing="0" cellpadding="0">
				<tr>
					<td align="center"><font size="10"><b>INSTITUTO DE EDUCACIÓN SUPERIOR TECNOLÓGICO PÚBLICO</b></font></td>
				</tr>
				<tr>
					<td  align="center"><font size="10"><b>"' . $nombre_insti . '"</b></font></td>
				</tr>
				<br>
				<tr>
					<td align="center"><font size="9"><b>' . $tipo . '</b></font></td>
				</tr>
				<tr>
					<td  align="center"><font size="9"><b>' . $codigo . '</b></font></td>
				</tr><br>
				<tr>
					<td><font size="' . $text_size . '"> R.U.C. : ' . $ruc_insti . '</font></td>
				</tr> 
				<tr>
					<td><font size="' . $text_size . '"> DIRECCIÓN : ' . $dir_insti . '</font></td>
				</tr> 
				<tr>
					<td><font size="' . $text_size . '"> CLIENTE : ' . $nombre . '</font></td>
				</tr> 
				<tr>
					<td><font size="' . $text_size . '"> RESPONSABLE : ' . $usuario . '</font></td>
				</tr> 
				<tr><br>
					<td><font size="' . $text_size . '">DETALLE DE PAGO :</font></td>
				</tr> 
			</table>
			<br />

		';

		$documento .= '
			<table border="1" cellspacing="0" cellpadding="2">
				<tr>
					<th width="15%" align="center"><font size="7">CANTIDAD</font></th>
					<th width="55%" align="center"><font size="7">CONCEPTO</font></th>
					<th width="15%" align="center"><font size="7">PRECIO UNIT.</font></th>
					<th width="15%" align="center"><font size="7">SUB TOTAL</font></th>
				</tr>
		';

		$cambio_cantidad = false;
		if ($cantidad != "0") {

			preg_match_all('/\d+/', $cantidad, $matches);

			// Obtener los resultados de la coincidencia
			$cantidades = $matches[0];

			$cambio_cantidad = true;
		}
		$montoEncrypted = encryptText($monto);
		$stmt = $conexion->prepare("INSERT INTO `ingresos`(`dni`, `usuario`,`tipo_comprobante`, `codigo`, `fecha_pago`, `monto_total`,`estado`, `ruta_archivo`,`responsable`,`metodo_pago`) VALUES (?, ?, ?, ?, CURRENT_TIMESTAMP(), ?, 'PAGADO', ?, ?, ?)");
		$stmt->bind_param("ssssssss", $dni, $nombre, $tipo, $codigo, $montoEncrypted, $rutaArchivo, $usuario, $metodo);
		$ejecutar_insetar = $stmt->execute();

		if ($ejecutar_insetar && $cambio_cantidad == false) {
			$id_ing = $stmt->insert_id;

			// Insertar los servicios seleccionados en la tabla 'categoria_servicio'
			foreach ($conceptos as $idCon) {
				$res_con = buscarConceptoIngresosById($conexion, $idCon);
				$con_in = mysqli_fetch_array($res_con);
				$costo = $con_in['monto'];
				$nombre_con = $con_in['concepto'];

				$stmt_detalle = $conexion->prepare("INSERT INTO detalle_ingresos (id_ingreso, id_concepto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
				$stmt_detalle->bind_param("iiis", $id_ing, $idCon, $cant, $costo);
				$stmt_detalle->execute();

				$documento .= '
				<tr>
					<td align="center"><font size="7">1</font></td>
					<td><font size="7">' . $nombre_con . '</font></td>
					<td align="center"><font size="7"> S/. ' . floatval(decryptText($costo)) . '</font></td>
					<td align="center"><font size="7"> S/. ' . $costo . '</font></td>
				</tr>';
			}
			;

			$documento .= '
				</table>
				<tr> <br>
					<td colspan="4" align="rigth"><font size="' . $text_size . '"> TOTAL A PAGAR : S/. ' . $monto . '</font></td>
				</tr> 
				<br>
					<tr>
					<td colspan="3"><font size="' . $text_size . '"> FORMA DE PAGO : ' . $metodo . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> MONTO RECIBIDO : S/.' . $pagado_con . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> DIFERENCIA : S/.' . $diferencia . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> FECHA Y HORA : ' . date("d/m/Y H:i:s") . '</font></td>
					</tr>
					<tr> <br>
						<td colspan="4" align="center"><font size="' . $text_size . '"><b>¡GRACIAS POR SU PAGO!</b></font></td>
				</tr> ';

			// Escribir el contenido HTML en el PDF
			$pdf->writeHTML($documento, true, false, true, false, '');
			// Guardar el contenido en el archivo
			$pdfContent = $pdf->Output('', 'S');
			// Enviar el PDF al navegador
			file_put_contents($rutaArchivo, $pdfContent);
		} elseif ($ejecutar_insetar && $cambio_cantidad == true) {
			$id_ing = mysqli_insert_id($conexion);

			// Insertar los servicios seleccionados en la tabla 'categoria_servicio'
			for ($i = 0; $i < count($conceptos); $i++) {
				$idCon = $conceptos[$i];

				$res_con = buscarConceptoIngresosById($conexion, $idCon);
				$con_in = mysqli_fetch_array($res_con);
				$costo = $con_in['monto'];

				$cant = (int) $cantidades[$i];

				$nombre_con = $con_in['concepto'];

				$documento .= '
				<tr>
					<td align="center"><font size="7">' . $cant . '</font></td>
					<td><font size="7">' . $nombre_con . '</font></td>
					<td align="center"><font size="7"> S/. ' . floatval(decryptText($costo)) . '</font></td>
					<td align="center"><font size="7"> S/. ' . floatval(decryptText($costo)) * $cant . '</font></td>
				</tr>';

				$costo = encryptText(floatval(decryptText($costo)) * $cant);
				$stmt_detalle = $conexion->prepare("INSERT INTO detalle_ingresos (id_ingreso, id_concepto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
				$stmt_detalle->bind_param("iiis", $id_ing, $idCon, $cant, $costo);
				$stmt_detalle->execute();
			}

			$documento .= '
			</table>
				<tr> <br>
					<td colspan="4" align="rigth"><font size="' . $text_size . '"> TOTAL A PAGAR : S/. ' . $monto . '</font></td>
				</tr> 
				<br>
					<tr>
					<td colspan="3"><font size="' . $text_size . '"> FORMA DE PAGO : ' . $metodo . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> MONTO RECIBIDO : S/.' . $pagado_con . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> DIFERENCIA : S/.' . $diferencia . '</font></td>
					</tr> 
					<tr>
						<td colspan="3"><font size="' . $text_size . '"> FECHA Y HORA : ' . date("d/m/Y H:i:s") . '</font></td>
					</tr>
					<tr> <br>
						<td colspan="4" align="center"><font size="' . $text_size . '"><b>¡GRACIAS POR SU PAGO!</b></font></td>
				</tr> ';

			// Escribir el contenido HTML en el PDF
			$pdf->writeHTML($documento, true, false, true, false, '');
			// Guardar el contenido en el archivo
			$pdfContent = $pdf->Output('', 'S');
			// Enviar el PDF al navegador
			file_put_contents($rutaArchivo, $pdfContent);

			// JavaScript para abrir el PDF en una nueva ventana y redirigir la ventana actual
		} else {
			echo "<script>
				alert('Error al registrar, por favor verifique sus datos');
				window.history.back();
					</script>
				";
		}
		;
	}

}

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

	<title>Comprobante <?php include("../../include/header_title.php"); ?></title>
	<!--icono en el titulo-->
	<link rel="shortcut icon" href="../img/favicon.ico">
	<!-- Bootstrap -->
	<link href="../../Gentella/vendors/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
	<!-- Font Awesome -->
	<link href="../../Gentella/vendors/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<!-- NProgress -->
	<link href="../../Gentella/vendors/nprogress/nprogress.css" rel="stylesheet">
	<!-- iCheck -->
	<link href="../../Gentella/vendors/iCheck/skins/flat/green.css" rel="stylesheet">
	<!-- Datatables -->
	<link href="../../Gentella/vendors/datatables.net-bs/css/dataTables.bootstrap.min.css" rel="stylesheet">
	<link href="../../Gentella/vendors/datatables.net-buttons-bs/css/buttons.bootstrap.min.css" rel="stylesheet">
	<link href="../../Gentella/vendors/datatables.net-fixedheader-bs/css/fixedHeader.bootstrap.min.css"
		rel="stylesheet">
	<link href="../../Gentella/vendors/datatables.net-responsive-bs/css/responsive.bootstrap.min.css" rel="stylesheet">
	<link href="../../Gentella/vendors/datatables.net-scroller-bs/css/scroller.bootstrap.min.css" rel="stylesheet">

	<!-- Custom Theme Style -->
	<link href="../../Gentella/build/css/custom.min.css" rel="stylesheet">

</head>

<body class="nav-md">
	<div class="container body">
		<div class="main_container">
			<!-- page content -->
			<div role="main">
				<div class="">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="x_panel">
							<div class="">
								<a href="../movimientos.php" class="btn btn-danger">Regresar</a>
								<div class="clearfix"></div>
							</div>
							<iframe src="<?php echo $rutaArchivo ?>" width="100%" height="600px"></iframe>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<script>
		document.getElementById('enviarCorreoBtn').addEventListener('click', function () {
			// Obtiene el valor del campo de entrada
			var correoValue = document.getElementById('correoInput').value;

			// Construye la URL con el valor del correo
			var url = "./login/enviar_certificado_correo.php?documento=<?= $rutaArchivo ?>&dni=<?= $dni ?>&correo=" + encodeURIComponent(correoValue);

			// Redirecciona a la nueva URL
			window.location.href = url;
		});
	</script>

	<!-- jQuery -->
	<script src="../../Gentella/vendors/jquery/dist/jquery.min.js"></script>
	<!-- Bootstrap -->
	<script src="../../Gentella/vendors/bootstrap/dist/js/bootstrap.min.js"></script>
	<!-- FastClick -->
	<script src="../../Gentella/vendors/fastclick/lib/fastclick.js"></script>
	<!-- NProgress -->
	<script src="../:./Gentella/vendors/nprogress/nprogress.js"></script>
	<!-- iCheck -->
	<script src="../../Gentella/vendors/iCheck/icheck.min.js"></script>
	<!-- Datatables -->
	<script src="../../Gentella/vendors/datatables.net/js/jquery.dataTables.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-buttons/js/buttons.flash.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-buttons/js/buttons.html5.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-buttons/js/buttons.print.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-responsive/js/dataTables.responsive.min.js"></script>
	<script src="../../Gentella/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js"></script>
	<script src="../../Gentella/vendors/datatables.net-scroller/js/dataTables.scroller.min.js"></script>
	<script src="../../Gentella/vendors/jszip/dist/jszip.min.js"></script>
	<script src="../../Gentella/vendors/pdfmake/build/pdfmake.min.js"></script>
	<script src="../../Gentella/vendors/pdfmake/build/vfs_fonts.js"></script>

	<!-- Custom Theme Scripts -->
	<script src="../../Gentella/build/js/custom.min.js"></script>
	<script>
		$(document).ready(function () {
			$('#example').DataTable({
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
				}
			});

		});
	</script>
	<?php mysqli_close($conexion); ?>
</body>

</html>