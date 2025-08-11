<?php
// -------------------  FUNCIONES BUSCAR ------------------------------
function buscarConceptoIngresos($conexion){
	$sql = "SELECT * FROM concepto_ingreso";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoIngresosById($conexion, $id_ingreso){
	$sql = "SELECT * FROM concepto_ingreso WHERE id = '$id_ingreso'";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoEgresos($conexion){
	$sql = "SELECT * FROM concepto_egreso";
	return mysqli_query($conexion, $sql);
}

function buscarComprobante($conexion){
	$sql = "SELECT * FROM comprobantes_pago";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoEgresosById($conexion, $id_egreso){
	$sql = "SELECT * FROM concepto_egreso WHERE id = '$id_egreso'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresos($conexion){
	$sql = "SELECT * FROM ingresos WHERE estado = 'PAGADO' ORDER BY fecha_pago DESC";
	return mysqli_query($conexion, $sql);
}
function buscarIngresosByComprobante($conexion,$nombre_compr){
	$sql = "SELECT * FROM ingresos WHERE tipo_comprobante = '$nombre_compr'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosByCodigo($conexion, $codigo){
	$sql = "SELECT * FROM ingresos WHERE codigo = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function buscarEgresos($conexion){
	$sql = "SELECT * FROM egresos WHERE estado = 'PAGADO'  ORDER BY fecha_pago DESC";
	return mysqli_query($conexion, $sql);
}

function buscarEgresosByCodigo($conexion, $codigo){
	$sql = "SELECT * FROM egresos WHERE comprobante = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosAnulados($conexion){
	$sql = "SELECT usuario, tipo_comprobante, codigo, monto_total, i.responsable as usuario_caja ,ia.responsable as responsable, motivo, fecha_anulacion FROM ingresos i INNER JOIN ingresos_anulados ia ON i.id = ia.id_ingreso";
	return mysqli_query($conexion, $sql);
}

function buscarEgresosAnulados($conexion){
	$sql = "SELECT empresa, ruc, concepto ,tipo_comprobante, comprobante ,monto_total, e.responsable as usuario_caja, ea.responsable as responsable, motivo, fecha_anulacion FROM egresos e INNER JOIN egresos_anulados ea ON e.id = ea.id_egreso";
	return mysqli_query($conexion, $sql);
}

function buscarComprobantesById($conexion, $id){
	$sql = "SELECT * FROM comprobantes_pago WHERE id = $id";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosByFechas($conexion, $inicio, $fin){
	$sql = "SELECT * FROM ingresos	WHERE DATE(fecha_pago) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO';";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleIngresosByFechas($conexion, $inicio, $fin){
	$sql = "SELECT i.tipo_comprobante, i.codigo, i.dni, i.usuario, di.cantidad, ci.concepto, ci.monto, di.subtotal, i.monto_total, i.metodo_pago, i.fecha_pago,i.responsable
	FROM ingresos i INNER JOIN detalle_ingresos di ON i.id  = di.id_ingreso INNER JOIN concepto_ingreso ci ON ci.id = di.id_concepto
	WHERE DATE(i.fecha_pago) BETWEEN '$inicio' AND '$fin' AND i.estado = 'PAGADO'
	ORDER BY i.fecha_pago";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleFlujoCaja($conexion, $inicio, $fin){
	$sql = "SELECT 'EGRESO' AS tipo, concepto as concepto, SUM(monto_total) AS suma_subtotales
	FROM egresos
	WHERE DATE(fecha_registro) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO'
	GROUP BY concepto
	UNION ALL
	SELECT
		'INGRESO' AS tipo,
		ci.concepto,
		SUM(di.subtotal) AS suma_subtotales
	FROM
		concepto_ingreso ci
	JOIN
		detalle_ingresos di ON ci.id = di.id_concepto
	JOIN
		ingresos i ON di.id_ingreso = i.id
	WHERE
		DATE(i.fecha_pago) BETWEEN '$inicio' AND '$fin'
		AND i.estado = 'PAGADO'
	GROUP BY
		ci.concepto";
	return mysqli_query($conexion, $sql);
}

function buscarEgresosByFechas($conexion, $inicio, $fin){
	$sql = "SELECT * FROM egresos	WHERE DATE(fecha_registro) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO';";
	return mysqli_query($conexion, $sql);
}

function buscarSaldoIncialByFechaInicio($conexion, $inicio){
	$sql = "SELECT
    (SELECT SUM(monto_total) FROM ingresos WHERE DATE(fecha_pago) < '$inicio' AND estado = 'PAGADO') -
    (SELECT SUM(monto_total) FROM egresos WHERE DATE(fecha_registro) < '$inicio' AND estado = 'PAGADO') AS saldo_inicial";
	return mysqli_query($conexion, $sql);
}

function buscarTotalIngresoByFechas($conexion, $inicio, $fin){
	$sql = "SELECT  SUM(monto_total) AS total
	FROM  ingresos WHERE DATE(fecha_pago) BETWEEN '$inicio' AND '$fin'  AND estado = 'PAGADO'";
	return mysqli_query($conexion, $sql);
}
function buscarTotalEgresoByFechas($conexion, $inicio, $fin){
	$sql = "SELECT SUM(monto_total) AS total FROM egresos WHERE DATE(fecha_registro) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO'";
	return mysqli_query($conexion, $sql);
}
?>