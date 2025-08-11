<?php
// -------------------  FUNCIONES BUSCAR ------------------------------
function buscarSolicitudEmpresa($conexion){
	$sql = "SELECT * FROM empresa WHERE estado = 'Por confirmar'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarEmpresas($conexion){
	$sql = "SELECT * FROM empresa WHERE estado = 'Activo' OR estado = 'Inactivo'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarEmpresaById($conexion, $id){
	$sql = "SELECT * FROM empresa WHERE id = '$id'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarEmpresaByRucAndCorreo($conexion, $ruc, $correo){
	$sql = "SELECT * FROM empresa WHERE ruc = '$ruc' OR correo_institucional = '$correo'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboral($conexion){
	$sql = "SELECT * FROM oferta_laboral WHERE estado != 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralAdministrador($conexion){
	$sql = "SELECT *
	FROM oferta_laboral_propia WHERE estado != 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralEmpresa($conexion){
	$sql = "SELECT o.id, e.razon_social as empresa, e.ruc, e.contacto, e.correo_institucional as correo, e.celular_telefono as celular, o.titulo , o.ubicacion, o.funciones, o.requisitos, o.condiciones, o.beneficios, o.salario, o.vacantes, o.fecha_inicio, o.fecha_fin, o.link_postulacion, o.estado, o.fecha_estado
	FROM
		oferta_laboral o
	INNER JOIN
		empresa e ON e.id = o.id_empresa WHERE o.estado != 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralArchivada($conexion){
	$sql = "SELECT * FROM oferta_laboral WHERE estado = 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralById($conexion, $id){
	$sql = "SELECT ol.*, e.razon_social as empresa FROM oferta_laboral ol INNER JOIN empresa e ON ol.id_empresa = e.id WHERE ol.id = $id";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralEmpresaById($conexion, $id){
	$sql = "SELECT ol.*, e.razon_social as empresa
	FROM  oferta_laboral ol INNER JOIN empresa e ON 
		e.id = ol.id_empresa WHERE ol.id = $id";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarEmpresaByIdOferta($conexion, $id){
	$sql = "SELECT e.razon_social FROM oferta_laboral o INNER JOIN empresa e ON e.id = o.id_empresa  WHERE o.id = $id";
	$res = mysqli_query($conexion, $sql);
	$empresa = mysqli_fetch_array($res);
	$empresa = $empresa['razon_social'];
    return $empresa;
}

function buscarOfertaLaboralByIdIestp($conexion, $id){
	$sql = "SELECT * FROM oferta_laboral_propia WHERE id = $id";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralByIdEmpresa($conexion, $id_empresa){
	$sql = "SELECT * FROM oferta_laboral WHERE id_empresa = $id_empresa AND estado != 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarOfertaLaboralByEmpresaArchivado($conexion, $id_empresa){
	$sql = "SELECT * FROM oferta_laboral WHERE id_empresa = $id_empresa AND estado = 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarEmpresaUsuario($conexion, $usuario){
	$sql = "SELECT * FROM empresa WHERE usuario = '$usuario'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarProgramasByIdOferta($conexion, $id_ol){
	$sql = "SELECT pe.nombre FROM oferta_programas op INNER JOIN programa_estudios pe ON op.id_pr = pe.id WHERE op.id_ol = $id_ol AND op.propietario != 'iestp'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarProgramasByIdOfertaIestp($conexion, $id_ol){
	$sql = "SELECT pe.nombre FROM oferta_programas op INNER JOIN programa_estudios pe ON op.id_pr = pe.id WHERE op.id_ol = $id_ol AND op.propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function cantidadDocumentos($conexion){
	$sql = "SELECT COUNT(*) as total FROM `oferta_documentos`";
	$res = mysqli_query($conexion, $sql);
	$res = mysqli_fetch_array($res);
	return $res['total'];
}

function buscarProgramasByOferta($conexion, $id_ol){
	$sql = "SELECT id_pr FROM `oferta_programas` WHERE `id_ol` = $id_ol AND propietario != 'iestp'";
	$res = mysqli_query($conexion, $sql);
	$programasDirigidosSeleccionados = array(); // Inicializamos la lista de números
	while ($row = mysqli_fetch_array($res)) {
    	$programasDirigidosSeleccionados[] = $row['id_pr']; // Agregamos el id_pr a la lista
	}
	return $programasDirigidosSeleccionados;
}

function buscarProgramasByOfertaIestp($conexion, $id_ol){
	$sql = "SELECT id_pr FROM `oferta_programas` WHERE `id_ol` = $id_ol AND propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
	$programasDirigidosSeleccionados = array(); // Inicializamos la lista de números
	while ($row = mysqli_fetch_array($res)) {
    	$programasDirigidosSeleccionados[] = $row['id_pr']; // Agregamos el id_pr a la lista
	}
	return $programasDirigidosSeleccionados;
}

function buscarDocumentosByIdOferta($conexion, $id_oferta){
	$sql = "SELECT * FROM oferta_documentos WHERE id_ol = $id_oferta AND propietario != 'iestp'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarDocumentosByIdOfertaIestp($conexion, $id_oferta){
	$sql = "SELECT * FROM oferta_documentos WHERE id_ol = $id_oferta AND propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
    return $res;
}

function buscarPostulantesByIdOferta($conexion, $id_ol){
	$sql = "SELECT op.url_documento, es.dni, es.apellidos_nombres, es.telefono, es.correo FROM oferta_postulantes op INNER JOIN estudiante es ON op.id_es = es.id WHERE op.id_ol = $id_ol";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarPostulantesByIdOfertaIestp($conexion, $id_ol){
	$sql = "SELECT op.url_documento, es.dni, es.apellidos_nombres, es.telefono, es.correo FROM oferta_postulantes op INNER JOIN estudiante es ON op.id_es = es.id WHERE op.id_ol = $id_ol";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarOfertasDisponiblesByPrograma($conexion, $id_programa){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.salario,ol.modalidad,ol.estado, e.razon_social as empresa, 'empresa' as propietario 
	FROM  oferta_laboral ol INNER JOIN `oferta_programas` op ON op.id_ol = ol.id INNER JOIN empresa e ON 
		e.id = ol.id_empresa WHERE op.id_pr = $id_programa AND ol.estado != 'ARCHIVADO' AND op.propietario = 'empresa'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarOfertasDisponiblesByProgramaIestp($conexion, $id_programa){
	$sql = "SELECT ol.*	FROM  oferta_laboral_propia ol INNER JOIN `oferta_programas` op ON op.id_ol = ol.id  WHERE op.id_pr = $id_programa AND ol.estado != 'ARCHIVADO' AND op.propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarOfertasEstudiante($conexion, $id_estudiante){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.salario,ol.modalidad,ol.estado, em.razon_social as empresa, 'empresa' as propietario  FROM  oferta_laboral ol INNER JOIN `oferta_postulantes` op ON op.id_ol = ol.id INNER JOIN `empresa` em ON ol.id_empresa = em.id INNER JOIN estudiante e ON 
	e.id = op.id_es  WHERE op.id_es = $id_estudiante AND op.propietario = 'empresa' GROUP BY ol.id";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarOfertasEstudianteInstituto($conexion, $id_estudiante){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.salario,ol.modalidad,ol.estado, ol.empresa as empresa, 'iestp' as propietario  FROM  oferta_laboral_propia ol INNER JOIN `oferta_postulantes` op ON op.id_ol = ol.id  INNER JOIN estudiante e ON 
	e.id = op.id_es  WHERE op.id_es = $id_estudiante AND op.propietario = 'iestp' GROUP BY ol.id";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarOfertaPostuladaInstituto($conexion, $id_oferta){
	$sql = "SELECT id FROM oferta_postulantes WHERE id_ol = $id_oferta AND propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}


function buscarTotalOfertasArchivadas($conexion){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.modalidad,ol.estado, e.razon_social as empresa, ol.fecha_estado FROM oferta_laboral ol 
	INNER JOIN empresa e ON e.id = ol.id_empresa WHERE ol.estado = 'ARCHIVADO' 
	UNION SELECT o.id,o.fecha_inicio, o.fecha_fin, o.titulo, o.ubicacion, o.vacantes, o.turno, o.modalidad, o.estado, o.empresa, o.fecha_estado FROM oferta_laboral_propia o where o.estado = 'ARCHIVADO';";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarTotalOfertas($conexion){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.modalidad,ol.estado, e.razon_social as empresa, ol.fecha_estado, 0 as propietario FROM oferta_laboral ol 
	INNER JOIN empresa e ON e.id = ol.id_empresa WHERE ol.estado != 'ARCHIVADO' 
	UNION SELECT o.id,o.fecha_inicio, o.fecha_fin, o.titulo, o.ubicacion, o.vacantes, o.turno, o.modalidad, o.estado, o.empresa, o.fecha_estado, 1 as propietario FROM oferta_laboral_propia o where o.estado != 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}


function buscarTotalOfertasEmpresaArchivadas($conexion){
	$sql = "SELECT ol.*, e.razon_social as empresa, 'empresa' as propietario FROM oferta_laboral ol INNER JOIN empresa e ON e.id = ol.id_empresa WHERE ol.estado = 'ARCHIVADO'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

//FUNCIÓN PARA LISTAR CONVOCATORIAS NO ARCHIVADAS, NO POSTULADAS, Y DEL PROGRAMA DEL ESTUDIANTE
function buscarTotalOfertasPorPrograma($conexion, $id_pro){
	$sql = "SELECT ol.id,ol.fecha_inicio,ol.fecha_fin, ol.titulo,ol.ubicacion,ol.vacantes,ol.turno,ol.salario,ol.modalidad,ol.estado, e.razon_social as empresa, 'empresa' as propietario 
	FROM  oferta_laboral ol INNER JOIN `oferta_programas` op ON op.id_ol = ol.id INNER JOIN empresa e ON 
		e.id = ol.id_empresa WHERE op.id_pr = $id_pro AND ol.estado != 'ARCHIVADO' AND op.propietario = 'empresa'
        UNION 
	SELECT olp.id, olp.fecha_inicio, olp.fecha_fin, olp.titulo, olp.ubicacion, olp.vacantes, olp.turno, olp.salario, olp.modalidad, olp.estado, olp.empresa, 'iestp' as propietario	
	FROM  oferta_laboral_propia olp INNER JOIN `oferta_programas` op ON op.id_ol = olp.id  
	WHERE op.id_pr = $id_pro AND olp.estado != 'ARCHIVADO' AND op.propietario = 'iestp'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function convocatoriaPostulada($conexion, $id_es, $id_ol, $propietario) {
    // Construir la consulta SQL para verificar si existe el registro
    $sql = "SELECT COUNT(*) AS total FROM `oferta_postulantes` 
            WHERE `id_es` = ? AND `id_ol` = ? AND `propietario` = ?";

    // Preparar la declaración
    if ($stmt = mysqli_prepare($conexion, $sql)) {
        // Enlazar los parámetros
        mysqli_stmt_bind_param($stmt, "iis", $id_es, $id_ol, $propietario);

        // Ejecutar la declaración
        mysqli_stmt_execute($stmt);

        // Vincular el resultado a una variable
        mysqli_stmt_bind_result($stmt, $total);

        // Obtener el resultado
        mysqli_stmt_fetch($stmt);

        // Cerrar la declaración
        mysqli_stmt_close($stmt);

        // Verificar si el total es mayor a 0
        if ($total > 0) {
            return false;
        } else {
            return true;
        }
    } else {
        // Manejo de error en la preparación de la declaración
        return true;
    }
}


//-----------BUSQUEDA DE REPORTES----------------
/*
function buscarTotalOfertasReporte($conexion, $year, $month){
	$sql = "SELECT ol.*, e.razon_social as empresa, 'empresa' as propietario FROM oferta_laboral ol 
	INNER JOIN empresa e ON e.id = ol.id_empresa WHERE MONTH(ol.fecha_inicio) = '$month' AND YEAR(ol.fecha_inicio) = '$year'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}*/

function buscarTotalOfertasReporte($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT 
		ol.id,
		ol.fecha_inicio,
		ol.fecha_fin,
		ol.titulo,
		ol.ubicacion,
		ol.vacantes,
		ol.turno,
		ol.modalidad,
		e.razon_social AS empresa
		FROM 
		oferta_laboral ol 
		INNER JOIN 
		empresa e ON e.id = ol.id_empresa 
		WHERE 
		ol.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin' 
		UNION 

		SELECT 
		o.id,
		o.fecha_inicio,
		o.fecha_fin,
		o.titulo,
		o.ubicacion,
		o.vacantes,
		o.turno,
		o.modalidad,
		o.empresa
		FROM 
		oferta_laboral_propia o  
		WHERE 
		o.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin'";
		$res = mysqli_query($conexion, $sql);
		return $res;
}

function buscarEmpresasReporte($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT 
                e.id, e.estado, e.ruc, e.razon_social as empresa, e.ubicacion,
                SUM(CASE WHEN op.id_es > 0 THEN 1 ELSE 0 END) AS postulantes
            FROM 
                empresa e
                INNER JOIN oferta_laboral ol ON e.id = ol.id_empresa
                LEFT JOIN oferta_postulantes op ON op.id_ol = ol.id
            	WHERE ol.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                OR ol.fecha_fin BETWEEN '$fecha_inicio' AND '$fecha_fin' 
            	AND e.estado != 'Por confirmar'
            GROUP BY 	
            e.id" ;
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarEmpresasConvocatoriasInstitutoReporte($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT ol.empresa, ol.ubicacion, SUM(CASE WHEN op.id_es > 0 THEN 1 ELSE 0 END) AS postulantes FROM oferta_laboral_propia ol  INNER JOIN oferta_postulantes op ON op.id_ol = ol.id 
	WHERE op.propietario = 'iestp' AND ol.fecha_inicio BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                OR ol.fecha_fin BETWEEN '$fecha_inicio' AND '$fecha_fin' GROUP BY ol.empresa, ol.ubicacion" ;
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarProgramasReporte($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT 
            pe.*,
            COUNT(pe.id) as postulantes
            
            FROM 
                estudiante e
                INNER JOIN oferta_postulantes oe ON oe.id_es = e.id
                INNER JOIN programa_estudios pe ON pe.id = e.id_programa_estudios
            	WHERE oe.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin' 
                OR oe.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'
                GROUP BY pe.id";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarPostulantesDetallado($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT 
			e.*, pe.*, ol.*, em.*, DATE_SUB(oe.fecha_registro, INTERVAL 5 HOUR) as fecha_registro
            FROM 
                estudiante e
                INNER JOIN oferta_postulantes oe ON oe.id_es = e.id
                INNER JOIN programa_estudios pe ON pe.id = e.id_programa_estudios
                INNER JOIN oferta_laboral ol ON ol.id = oe.id_ol
                INNER JOIN empresa em ON em.id = ol.id_empresa
            	WHERE oe.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

function buscarPostulantesDetalladoInstituto($conexion, $fecha_inicio, $fecha_fin){
	$sql = "SELECT 
			e.*, pe.*, ol.*, DATE_SUB(oe.fecha_registro, INTERVAL 5 HOUR) as fecha_registro
            FROM 
                estudiante e
                INNER JOIN oferta_postulantes oe ON oe.id_es = e.id
                INNER JOIN programa_estudios pe ON pe.id = e.id_programa_estudios
                INNER JOIN oferta_laboral_propia ol ON ol.id = oe.id_ol
            	WHERE oe.propietario = 'iestp' AND oe.fecha_registro BETWEEN '$fecha_inicio' AND '$fecha_fin'";
	$res = mysqli_query($conexion, $sql);
	return $res;
}

/*       SESIONES EMPRESA       */

function buscarSesionEmpresaLoginById($conexion, $id){
	$sql = "SELECT * FROM sesion_empresa WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}

//FUNCIONES DE TIEMPO Y ESTADO

function determinarEstado($fechaInicio, $fechaFin) {
    
    echo "<script>console.log('".$fechaInicio."');</script>";

    $zonaHorariaCliente = 'America/Lima';

    $fechaActualObj = new DateTime("now", new DateTimeZone($zonaHorariaCliente));

    $fechaInicioObj = DateTime::createFromFormat('Y-m-d', $fechaInicio, new DateTimeZone($zonaHorariaCliente));
    
    $fechaInicioObj->setTime(0, 0, 0);
    
    $fechaFinObj = DateTime::createFromFormat('Y-m-d', $fechaFin, new DateTimeZone($zonaHorariaCliente));
    $fechaFinObj->setTime(23, 59, 59);
    
    if ($fechaActualObj < $fechaInicioObj) {
        return "Por comenzar";
    } elseif ($fechaActualObj >= $fechaInicioObj && $fechaActualObj <= $fechaFinObj) {
        return "En proceso";
    } else {
        return "Finalizado";
    }
}

?>