<?php
// -------------------  FUNCIONES BUSCAR ------------------------------
function buscarDatosGenerales($conexion)
{
	$sql = "SELECT * FROM datos_institucionales WHERE id=1";
	return mysqli_query($conexion, $sql);
}
function buscarDatosSistema($conexion)
{
	$sql = "SELECT * FROM sistema WHERE id=1";
	return mysqli_query($conexion, $sql);
}

function buscarRecursos($conexion)
{
	$sql = "SELECT * FROM recursos WHERE id=1";
	return mysqli_query($conexion, $sql);
}

function buscarPresentePeriodoAcad($conexion)
{
	$sql = "SELECT * FROM presente_periodo_acad ORDER BY id LIMIT 1";
	return mysqli_query($conexion, $sql);
}
function buscarPeriodoAcademico($conexion)
{
	$sql = "SELECT * FROM periodo_academico ORDER BY id DESC LIMIT 10";
	return mysqli_query($conexion, $sql);
}
function buscarPeriodoAcademicoInvert($conexion)
{
	$sql = "SELECT * FROM periodo_academico ORDER BY id DESC LIMIT 8";
	return mysqli_query($conexion, $sql);
}
function buscarPeriodoAcadById($conexion, $id)
{
	$sql = "SELECT * FROM periodo_academico WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarEstudiante($conexion)
{
	$sql = "SELECT * FROM estudiante WHERE egresado = 'NO'";
	return mysqli_query($conexion, $sql);
}

function buscarEstudiantesCorreosByPrograma($conexion, $id_programa)
{
	$sql = "SELECT correo FROM estudiante WHERE id_programa_estudios = $id_programa";
	return mysqli_query($conexion, $sql);
}

function buscarTodosEstudiantes($conexion)
{
	$sql = "SELECT * FROM estudiante";
	return mysqli_query($conexion, $sql);
}

function buscarEgresado($conexion)
{
	$sql = "SELECT * FROM estudiante WHERE egresado = 'SI'";
	return mysqli_query($conexion, $sql);
}
function buscarActividadesById($conexion, $id)
{
	$sql = "SELECT * FROM actividades_egresado WHERE id_estudiante = '$id' ORDER BY fecha_inicio DESC";
	return mysqli_query($conexion, $sql);
}

function buscarEstudianteById($conexion, $id)
{
	$sql = "SELECT * FROM estudiante WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarEstudianteByDni($conexion, $dni)
{
	$sql = "SELECT * FROM estudiante WHERE dni='$dni'";
	return mysqli_query($conexion, $sql);
}

function buscarEstudianteDni($conexion, $dni)
{
	$sql = "SELECT * FROM estudiante WHERE dni='$dni' AND egresado = 'NO'";
	return mysqli_query($conexion, $sql);
}


function buscarEstudianteByDniPe($conexion, $pe, $dni)
{
	$sql = "SELECT * FROM estudiante WHERE dni='$dni' AND id_programa_estudios='$pe'";
	return mysqli_query($conexion, $sql);
}
function buscarEstudianteByApellidosNombres($conexion, $dato)
{
	$sql = "SELECT * FROM estudiante WHERE apellidos_nombres='$dato'";
	return mysqli_query($conexion, $sql);
}
function buscarEstudianteByApellidosNombres_like($conexion, $pe, $dato)
{
	$sql = "SELECT * FROM estudiante WHERE apellidos_nombres LIKE '%$dato%' AND id_programa_estudios='$pe'";
	return mysqli_query($conexion, $sql);
}
function buscarDocente($conexion)
{
	$sql = "SELECT * FROM docente";
	return mysqli_query($conexion, $sql);
}

function buscarDocenteActivos($conexion)
{
	$sql = "SELECT * FROM docente WHERE activo = 1";
	return mysqli_query($conexion, $sql);
}

function buscarDocenteOrdesByApellidosNombres($conexion)
{
	$sql = "SELECT * FROM docente ORDER BY apellidos_nombres";
	return mysqli_query($conexion, $sql);
}
function buscarDocenteById($conexion, $id)
{
	$sql = "SELECT * FROM docente WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarDocenteByDni($conexion, $dni)
{
	$sql = "SELECT * FROM docente WHERE dni='$dni'";
	return mysqli_query($conexion, $sql);
}
function buscarDocentesByIdPe($conexion, $id_pe)
{
	$sql = "SELECT * FROM docente WHERE id_programa_estudio='$id_pe' AND activo='1'";
	return mysqli_query($conexion, $sql);
}
function buscarCoordinadorAreaByIdPe($conexion, $id_pe)
{
	$sql = "SELECT * FROM docente WHERE id_programa_estudio='$id_pe' AND id_cargo='4' AND activo='1'";
	return mysqli_query($conexion, $sql);
}
function buscarCoordinadorArea_All($conexion)
{
	$sql = "SELECT * FROM docente WHERE id_cargo='4' AND activo='1'";
	return mysqli_query($conexion, $sql);
}
function buscarDirector_All($conexion)
{
	$sql = "SELECT * FROM docente WHERE id_cargo='1' AND activo='1'";
	return mysqli_query($conexion, $sql);
}




function buscarCarreras($conexion)
{
	$sql = "SELECT * FROM programa_estudios";
	return mysqli_query($conexion, $sql);
}
function buscarCarrerasById($conexion, $id)
{
	$sql = "SELECT * FROM programa_estudios WHERE id=$id";
	return mysqli_query($conexion, $sql);
}

function buscarSemestre($conexion)
{
	$sql = "SELECT * FROM semestre";
	return mysqli_query($conexion, $sql);
}

function buscarSemestreById($conexion, $id)
{
	$sql = "SELECT * FROM semestre WHERE id=$id";
	return mysqli_query($conexion, $sql);
}

function buscarSemestreByIdModulo($conexion, $id)
{
	$sql = "SELECT ud.id_semestre FROM `modulo_profesional` mp INNER JOIN unidad_didactica ud ON ud.id_modulo = mp.id 
	WHERE mp.id = $id GROUP BY ud.id_semestre ORDER BY ud.id_semestre";
	return mysqli_query($conexion, $sql);
}

function buscarUdByPrograma($conexion, $idCarrera)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_programa_estudio = '$idCarrera'";
	return mysqli_query($conexion, $sql);
}

function buscarUdByName($conexion, $nombre)
{
	$sql = "SELECT * FROM unidad_didactica WHERE descripcion='$nombre'";
	return mysqli_query($conexion, $sql);
}
function buscarUdByCarSem($conexion, $idCarrera, $idSemestre)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_programa_estudio = '$idCarrera' AND id_semestre= '$idSemestre' ORDER BY id";
	return mysqli_query($conexion, $sql);
}
function buscarUdByModSem($conexion, $idModulo, $idSemestre)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_modulo = '$idModulo' AND id_semestre= '$idSemestre' ORDER BY id";
	return mysqli_query($conexion, $sql);
}
function buscarUdByModCarr($conexion, $idModulo, $idProg)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_modulo = '$idModulo' AND id_programa_estudio= '$idProg' ORDER BY id";
	return mysqli_query($conexion, $sql);
}

function buscarUnidadDidactica($conexion)
{
	$sql = "SELECT * FROM unidad_didactica";
	return mysqli_query($conexion, $sql);
}

function buscarUnidadDidacticaOByProgramaOrdenada($conexion, $id_programa)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_programa_estudio = $id_programa ORDER BY id_semestre ASC";
	return mysqli_query($conexion, $sql);
}
function buscarUdById($conexion, $id)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarUdByCodCor($conexion, $id_cod_cor)
{
	$sql = "SELECT * FROM unidad_didactica WHERE codigo_correlativo='$id_cod_cor'";
	return mysqli_query($conexion, $sql);
}
function buscarUdByIdModulo($conexion, $id)
{
	$sql = "SELECT * FROM unidad_didactica WHERE id_modulo='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarModuloFormativo($conexion)
{
	$sql = "SELECT * FROM modulo_profesional";
	return mysqli_query($conexion, $sql);
}
function buscarModuloFormativoById($conexion, $id)
{
	$sql = "SELECT * FROM modulo_profesional WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarModuloFormativoByIdCarrera($conexion, $id)
{
	$sql = "SELECT * FROM modulo_profesional WHERE id_programa_estudio = '$id' ORDER BY id";
	return mysqli_query($conexion, $sql);
}

function buscarModulosByIdCarrera($conexion, $id)
{
	$sql = "SELECT mp.* FROM `modulo_profesional` mp INNER JOIN unidad_didactica ud ON ud.id_modulo = mp.id 
	WHERE ud.tipo != 'TRANSVERSAL' AND mp.id_programa_estudio = $id  GROUP BY mp.id";
	return mysqli_query($conexion, $sql);
}

function buscarUdTransversalesByPrograma($conexion, $id)
{
	$sql = "SELECT ud.* FROM `modulo_profesional` mp INNER JOIN unidad_didactica ud ON ud.id_modulo = mp.id 
	WHERE ud.tipo = 'TRANSVERSAL' AND mp.id_programa_estudio = $id  GROUP BY ud.id ORDER BY ud.id_semestre";
	return mysqli_query($conexion, $sql);
}

function buscarUdAlternativasByPrograma($conexion, $dni)
{
	$sql = "SELECT * FROM notas_antiguo na INNER JOIN unidad_didactica ud ON ud.id = na.id_unidad_didactica 
	WHERE ud.tipo = 'ALTERNATIVA' AND na.dni = '$dni'";
	return mysqli_query($conexion, $sql);
}


function buscarMatricula($conexion)
{
	$sql = "SELECT * FROM matricula";
	return mysqli_query($conexion, $sql);
}
function buscarMatriculaById($conexion, $id)
{
	$sql = "SELECT * FROM matricula WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarMatriculaByIdPeriodo($conexion, $id)
{
	$sql = "SELECT * FROM matricula WHERE id_periodo_acad='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarMatriculaByIdPeriodoCarrera($conexion, $id_per, $id_pe)
{
	$sql = "SELECT * FROM matricula WHERE id_periodo_acad='$id_per' AND id_programa_estudio = '$id_pe'";
	return mysqli_query($conexion, $sql);
}
function buscarMatriculaByIdPeriodoCarreraSem($conexion, $id_per, $id_pe, $id_sem)
{
	$sql = "SELECT * FROM matricula WHERE id_periodo_acad='$id_per' AND id_programa_estudio = '$id_pe' AND id_semestre = '$id_sem'";
	return mysqli_query($conexion, $sql);
}
function buscarMatriculaByEstudiantePeriodo($conexion, $id_estudiante, $id_periodo_acad)
{
	$sql = "SELECT * FROM matricula WHERE id_estudiante='$id_estudiante' AND id_periodo_acad = '$id_periodo_acad'";
	return mysqli_query($conexion, $sql);
}
function buscarDetalleMatriculaById($conexion, $id)
{
	$sql = "SELECT * FROM detalle_matricula_unidad_didactica WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleMatriculaByIdUdAndIdEstudiante($conexion, $id_unidad_didactica, $id_estudiante)
{
	$sql = "SELECT m.id_periodo_acad as id_periodo, dmud.id as id_detalle_matricula, m.fecha_reg 
	FROM programacion_unidad_didactica pud INNER JOIN detalle_matricula_unidad_didactica dmud ON dmud.id_programacion_ud = pud.id 
	INNER JOIN matricula m ON m.id = dmud.id_matricula 
	WHERE pud.id_unidad_didactica = $id_unidad_didactica  and m.id_estudiante = $id_estudiante ORDER BY m.fecha_reg DESC LIMIT 1";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleMatriculaByIdUdAndEstudiante($conexion, $id_unidad_didactica, $id_estudiante)
{
	$sql = "SELECT dmud.id as id_detalle_matricula, m.id_periodo_acad  FROM programacion_unidad_didactica pud INNER JOIN detalle_matricula_unidad_didactica dmud 
	ON dmud.id_programacion_ud = pud.id INNER JOIN matricula m ON m.id = dmud.id_matricula 
	WHERE pud.id_unidad_didactica = $id_unidad_didactica AND m.id_estudiante = $id_estudiante ORDER BY m.fecha_reg DESC LIMIT 1";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleMatriculaByIdMatricula($conexion, $id)
{
	$sql = "SELECT * FROM detalle_matricula_unidad_didactica WHERE id_matricula = '$id'";
	return mysqli_query($conexion, $sql);
}
function buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog)
{
	$sql = "SELECT * FROM detalle_matricula_unidad_didactica WHERE id_programacion_ud = '$id_prog' ORDER BY orden";
	return mysqli_query($conexion, $sql);
}
function buscarDetalleMatriculaByIdMatriculaProgramacion($conexion, $id_mat, $id_prog)
{
	$sql = "SELECT * FROM detalle_matricula_unidad_didactica WHERE id_matricula = '$id_mat' AND id_programacion_ud='$id_prog'";
	return mysqli_query($conexion, $sql);
}


function buscarMatriculaByIdPeriodoSinLicencia($conexion, $id)
{
	$sql = "SELECT * FROM matricula WHERE id_periodo_acad='$id' AND licencia =''";
	return mysqli_query($conexion, $sql);
}
function buscarLicenciaPeriodo($conexion, $id_periodo_acad)
{
	$sql = "SELECT * FROM matricula WHERE id_periodo_acad = '$id_periodo_acad' AND licencia !=''";
	return mysqli_query($conexion, $sql);
}



function buscarCalificacionById($conexion, $id)
{
	$sql = "SELECT * FROM calificaciones WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarCalificacionByIdDetalleMatricula($conexion, $id_detalle)
{
	$sql = "SELECT * FROM calificaciones WHERE id_detalle_matricula = '$id_detalle' ORDER BY nro_calificacion";
	return mysqli_query($conexion, $sql);
}
function buscarCalificacionByIdDetalleMatricula_nro($conexion, $id_detalle, $nro_calificacion)
{
	$sql = "SELECT * FROM calificaciones WHERE id_detalle_matricula = '$id_detalle' AND nro_calificacion='$nro_calificacion'";
	return mysqli_query($conexion, $sql);
}
function buscarEvaluacionById($conexion, $id)
{
	$sql = "SELECT * FROM evaluacion WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}
function buscarEvaluacionByIdCalificacion($conexion, $id)
{
	$sql = "SELECT * FROM evaluacion WHERE id_calificacion = '$id' ORDER BY id";
	return mysqli_query($conexion, $sql);
}
function buscarEvaluacionByIdCalificacion_detalle($conexion, $id, $detalle)
{
	$sql = "SELECT * FROM evaluacion WHERE id_calificacion = '$id' AND detalle='$detalle' ORDER BY id";
	return mysqli_query($conexion, $sql);
}

$config = include __DIR__ . '../../config.php'; 
$key = $config['ENCRYPTION_KEY'] ? $config['ENCRYPTION_KEY'] : 'joewbndlclkwijkbcliyqubojschjvsxfr';
function buscarCriterioEvaluacionByEvaluacion($conexion, $id)
{
	global $key;
	$sql = "SELECT id, id_evaluacion, ponderado, orden, detalle, CAST(AES_DECRYPT(calificacion, '$key') AS UNSIGNED) AS calificacion FROM criterio_evaluacion WHERE id_evaluacion = '$id' ORDER BY id";
	return mysqli_query($conexion, $sql);
}
function buscarCriterioEvaluacionByEvaluacionOrden($conexion, $id, $orden)
{
	global $key;

	$sql = "SELECT id, id_evaluacion, ponderado, orden, detalle, CAST(AES_DECRYPT(calificacion, '$key') AS UNSIGNED) AS calificacion FROM criterio_evaluacion WHERE id_evaluacion = '$id' AND orden='$orden' ORDER BY id";
	return mysqli_query($conexion, $sql);
}


function buscarProgramacion($conexion)
{
	$sql = "SELECT * FROM programacion_unidad_didactica";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionEspecial($conexion, $extra)
{
	$sql = "SELECT * FROM programacion_unidad_didactica " . $extra;
	return mysqli_query($conexion, $sql);
}

function buscarSolicitudProgramacionEspecial($conexion, $extra)
{
	$sql = "SELECT * FROM solicitud_programacion_ud " . $extra;
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionById($conexion, $id)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarProgramacionByIdPeriodoAndUnidad($conexion, $id_periodo, $id_unidad)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_periodo_acad='$id_periodo' AND id_unidad_didactica='$id_unidad' LIMIT 1";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionByIdDocente($conexion, $id)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_docente='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionByIdPeriodo($conexion, $id)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_periodo_acad='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarProgramacionByIdPeriodoPorProgramasActivos($conexion, $id_periodo)
{
	$sql = "SELECT pud.* FROM `programacion_unidad_didactica` pud INNER JOIN unidad_didactica ud ON ud.id = pud.id_unidad_didactica 
	WHERE pud.id_periodo_acad = '$id_periodo'";
	return mysqli_query($conexion, $sql);
}

function buscarProgramacionByIdDocentePeriodo($conexion, $id_docente, $id_periodo)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_docente='$id_docente' AND id_periodo_acad='$id_periodo'";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionByUd_Peridodo($conexion, $id_ud, $periodo)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_unidad_didactica='$id_ud' AND id_periodo_acad='$periodo'";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionByUdDocentePeriodo($conexion, $ud, $docente, $periodo)
{
	$sql = "SELECT * FROM programacion_unidad_didactica WHERE id_unidad_didactica='$ud' AND id_docente='$docente' AND id_periodo_acad='$periodo'";
	return mysqli_query($conexion, $sql);
}
function buscarCargo($conexion)
{
	$sql = "SELECT * FROM cargo";
	return mysqli_query($conexion, $sql);
}
function buscarCargoById($conexion, $id)
{
	$sql = "SELECT * FROM cargo WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarGenero($conexion)
{
	$sql = "SELECT * FROM genero";
	return mysqli_query($conexion, $sql);
}

function buscarCompetencias($conexion)
{
	$sql = "SELECT * FROM competencias";
	return mysqli_query($conexion, $sql);
}
function buscarCompetenciasById($conexion, $id)
{
	$sql = "SELECT * FROM competencias WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarCompetenciasByIdModulo($conexion, $id)
{
	$sql = "SELECT * FROM competencias WHERE id_modulo_formativo='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarCompetenciasEspecialidadByIdModulo($conexion, $id)
{
	$sql = "SELECT * FROM competencias WHERE id_modulo_formativo='$id' AND tipo_competencia='ESPECÍFICA'";
	return mysqli_query($conexion, $sql);
}
function buscarIndicadorLogroCompetenciasById($conexion, $id)
{
	$sql = "SELECT * FROM indicador_logro_competencia WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarIndicadorLogroCompetenciasByIdCompetencia($conexion, $id)
{
	$sql = "SELECT * FROM indicador_logro_competencia WHERE id_competencia='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarCapacidades($conexion)
{
	$sql = "SELECT * FROM capacidades";
	return mysqli_query($conexion, $sql);
}
function buscarCapacidadesById($conexion, $id)
{
	$sql = "SELECT * FROM capacidades WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarCapacidadesByIdUd($conexion, $id)
{
	$sql = "SELECT * FROM capacidades WHERE id_unidad_didactica='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarIndicadorLogroCapacidadById($conexion, $id)
{
	$sql = "SELECT * FROM indicador_logro_capacidad WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id)
{
	$sql = "SELECT * FROM indicador_logro_capacidad WHERE id_capacidad='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarIndicadorLogroCompetenciaByUd($conexion, $id_ud)
{
	$sql = "SELECT ilc.* FROM indicador_logro_competencia ilc INNER JOIN competencias c ON c.id = ilc.id_competencia 
	INNER JOIN capacidades cap ON cap.id_competencia = c.id  
	INNER JOIN unidad_didactica ud ON ud.id = cap.id_unidad_didactica WHERE ud.id = $id_ud";
	return mysqli_query($conexion, $sql);
}

function buscarSilaboById($conexion, $id)
{
	$sql = "SELECT * FROM silabo WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarSilaboByIdProgramacion($conexion, $id)
{
	$sql = "SELECT * FROM silabo WHERE id_programacion_unidad_didactica='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarProgActividadesSilaboById($conexion, $id)
{
	$sql = "SELECT * FROM programacion_actividades_silabo WHERE id='$id' ORDER BY semana";
	return mysqli_query($conexion, $sql);
}
function buscarProgActividadesSilaboByIdSilabo($conexion, $id)
{
	$sql = "SELECT * FROM programacion_actividades_silabo WHERE id_silabo='$id' ORDER BY semana";
	return mysqli_query($conexion, $sql);
}
function buscarProgActividadesSilaboByIdSilabo_16($conexion, $id)
{
	$sql = "SELECT * FROM programacion_actividades_silabo WHERE id_silabo='$id' ORDER BY semana LIMIT 16";
	return mysqli_query($conexion, $sql);
}
function buscarProgActividadesSilaboByIdSilaboAndSemana($conexion, $idSilabo, $semana)
{
	$sql = "SELECT * FROM programacion_actividades_silabo WHERE id_silabo='$idSilabo' AND semana='$semana'";
	return mysqli_query($conexion, $sql);
}

function buscarSesionById($conexion, $id)
{
	$sql = "SELECT * FROM sesion_aprendizaje WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarSesionByIdProgramacionActividades($conexion, $id)
{
	$sql = "SELECT * FROM sesion_aprendizaje WHERE id_programacion_actividad_silabo='$id'";
	return mysqli_query($conexion, $sql);
}

function buscarMomentosSesionByIdSesion($conexion, $id)
{
	$sql = "SELECT * FROM momentos_sesion_aprendizaje WHERE id_sesion_aprendizaje='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarActividadesEvaluacionByIdSesion($conexion, $id)
{
	$sql = "SELECT * FROM actividad_evaluacion_sesion_aprendizaje WHERE id_sesion_aprendizaje='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarAsistenciaBySesionAndEstudiante($conexion, $id, $estudiante)
{
	$sql = "SELECT * FROM asistencia WHERE id_sesion_aprendizaje='$id' AND id_estudiante='$estudiante'";
	return mysqli_query($conexion, $sql);
}
function buscarAsistenciaByIdSesion($conexion, $id)
{
	$sql = "SELECT * FROM asistencia WHERE id_sesion_aprendizaje='$id'";
	return mysqli_query($conexion, $sql);
}



/// buscar sesion

function buscarSesionLoginById($conexion, $id)
{
	$sql = "SELECT * FROM sesion WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarSesionEstudianteLoginById($conexion, $id)
{
	$sql = "SELECT * FROM sesion_estudiante WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}


////TUTORIA
function buscarTutoriaByIdDocenteAndIdPeriodo($conexion, $id_docente, $id_periodo_acad)
{
	$sql = "SELECT * FROM tutoria WHERE id_docente='$id_docente' AND id_periodo_acad='$id_periodo_acad'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaByIdAndIdPeriodo($conexion, $id, $id_periodo_acad)
{
	$sql = "SELECT * FROM tutoria WHERE id='$id' AND id_periodo_acad='$id_periodo_acad'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaById($conexion, $id)
{
	$sql = "SELECT * FROM tutoria WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaEstudiantesById($conexion, $id)
{
	$sql = "SELECT * FROM tutoria_estudiantes WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaEstudiantesByIdTutoria($conexion, $id_tutoria)
{
	$sql = "SELECT * FROM tutoria_estudiantes WHERE id_tutoria='$id_tutoria'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoria_EstudianteByIdEstudiante($conexion, $id_estudiante)
{
	$sql = "SELECT * FROM tutoria_estudiantes WHERE id_estudiante='$id_estudiante'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaEstudiantesByIdTutoriaAndIdEst($conexion, $id_tutoria, $id_estudiante)
{
	$sql = "SELECT * FROM tutoria_estudiantes WHERE id_tutoria='$id_tutoria' AND id_estudiante='$id_estudiante'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaRecojoInfoByIdTutEst($conexion, $id_tut_est)
{
	$sql = "SELECT * FROM tutoria_recojo_informacion WHERE id_tutoria_estudiante='$id_tut_est'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaSesIndivById($conexion, $id)
{
	$sql = "SELECT * FROM tutoria_sesion_individual WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaSesIndivByIdTutEst($conexion, $id_tut_est)
{
	$sql = "SELECT * FROM tutoria_sesion_individual WHERE id_tutoria_estudiante='$id_tut_est'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaSesGrupalById($conexion, $id)
{
	$sql = "SELECT * FROM tutoria_sesion_grupal WHERE id='$id'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaSesGrupalByIdTutoria($conexion, $id_tutoria)
{
	$sql = "SELECT * FROM tutoria_sesion_grupal WHERE id_tutoria='$id_tutoria'";
	return mysqli_query($conexion, $sql);
}
function buscarTutoriaSesGrupalByIdAndIdTutoria($conexion, $id, $id_tutoria)
{
	$sql = "SELECT * FROM tutoria_sesion_grupal WHERE id='$id' AND id_tutoria='$id_tutoria'";
	return mysqli_query($conexion, $sql);
}

//-------------------------Nuevas Consultas----------------------------

function buscarAllBoletasNotas($conexion)
{
	$sql = "SELECT * FROM boleta_notas";
	return mysqli_query($conexion, $sql);
}

function buscarNotaReptida($conexion, $dni, $id_progama, $unidad, $calificacion, $semestre, $periodo)
{
	$sql = "SELECT * FROM notas_antiguo WHERE dni = '$dni' AND id_programa = '$id_progama' AND unidad_didactica = '$unidad' AND
	calificacion = '$calificacion' AND semestre_academico = '$semestre' AND periodo = '$periodo'";
	return mysqli_query($conexion, $sql);
}

function buscarAllCertificados($conexion)
{
	$sql = "SELECT * FROM certificado_estudios";
	return mysqli_query($conexion, $sql);
}

function buscarMatriculado($conexion, $id_estudiante, $id_periodo)
{
	$sql = "SELECT * FROM matricula WHERE  id_periodo_acad = '$id_periodo' AND id_estudiante = '$id_estudiante'";
	return mysqli_query($conexion, $sql);
}

function getCalificacionFinalByDni($conexion, $dni)
{
	$sql = "SELECT u.descripcion, u.creditos , CAST(ROUND(pf.promedio) AS SIGNED)  as promedio_final, pa.nombre as periodo FROM estudiante e INNER JOIN matricula m ON e.id = m.id_estudiante
    INNER JOIN programa_estudios p ON m.id_programa_estudio = p.id INNER JOIN periodo_academico pa ON pa.id = m.id_periodo_acad
    INNER JOIN detalle_matricula_unidad_didactica dm ON dm.id_matricula = m.id INNER JOIN programacion_unidad_didactica pu ON
    pu.id_unidad_didactica = dm.id_programacion_ud INNER JOIN unidad_didactica u ON u.id = pu.id_unidad_didactica
    INNER JOIN promedio_final pf ON pf.id_detalle_matricula = dm.id WHERE e.dni = '$dni' ORDER BY periodo ASC";
	return mysqli_query($conexion, $sql);
}

function getCertificadoByCodigo($conexion, $codigo)
{
	$sql = "SELECT * FROM certificado_estudios WHERE codigo = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function getEstudianteNotasCertificado($conexion, $id_estudiante, $id_periodo)
{
	$sql = "SELECT ud.id as id_ud, ud.descripcion as unidad_didactica, ud.creditos as cantidad_creditos, pa.nombre 
	AS semestre_academico, dmud.id as id_detalle_matricula FROM matricula m 
	INNER JOIN detalle_matricula_unidad_didactica dmud ON dmud.id_matricula = m.id  
	INNER JOIN programacion_unidad_didactica pud ON pud.id = dmud.id_programacion_ud 
    INNER JOIN periodo_academico pa ON pa.id = m.id_periodo_acad
	INNER JOIN unidad_didactica ud ON ud.id = pud.id_unidad_didactica 
    WHERE m.id_estudiante = $id_estudiante AND m.id_programa_estudio = $id_periodo";
	return mysqli_query($conexion, $sql);
}

function getBoletaByCodigo($conexion, $codigo)
{
	$sql = "SELECT * FROM boleta_notas WHERE codigo = '$codigo'";
	return mysqli_query($conexion, $sql);
}

//---------------------PARA ESTUDIANTES ANTIGUOS AL USO DEL SISTEMA --------------------

function getNotasEgresado($conexion, $dni, $programa)
{
	$sql = "SELECT * FROM notas_antiguo WHERE dni = '$dni' AND id_programa = '$programa' AND calificacion >= 13";
	return mysqli_query($conexion, $sql);
}

function getNotasImportada($conexion)
{
	$sql = "SELECT * FROM notas_antiguo";
	return mysqli_query($conexion, $sql);
}

function getNotasImportadaByDni($conexion, $dni)
{
	$sql = "SELECT na.*, pe.nombre  FROM notas_antiguo na INNER JOIN programa_estudios pe ON na.id_programa = pe.id WHERE dni = '$dni'";
	return mysqli_query($conexion, $sql);
}

function getNotasImportadaByDniUpdate($conexion, $dni)
{
	$sql = "SELECT na.*, pe.nombre  FROM notas_antiguo na INNER JOIN programa_estudios pe ON na.id_programa = pe.id WHERE dni = '$dni' AND id_unidad_didactica = 0";
	return mysqli_query($conexion, $sql);
}

function getNotasImportadaByDniAndIdUd($conexion, $dni, $id)
{
	$sql = "SELECT * FROM notas_antiguo WHERE dni = '$dni' AND id_unidad_didactica = '$id' ORDER BY semestre_academico DESC LIMIT 1";
	return mysqli_query($conexion, $sql);
}

function getNotasMatriculaByDniAndIdUd($conexion, $id, $id_estudent)
{
	$sql = "SELECT pf.* FROM `promedio_final` pf INNER JOIN programacion_unidad_didactica pu ON pu.id = pf.id_programacion_ud 
	INNER JOIN matricula m ON m.id = pf.id_matricula WHERE pu.id_unidad_didactica = $id and  m.id_estudiante = $id_estudent ";
	return mysqli_query($conexion, $sql);
}

function getCalificacionFinalByIdAndPeriodo($conexion, $id_estudiante, $periodo)
{
	$sql = "SELECT ud.descripcion, ud.creditos, ud.tipo, dmud.id as id_detalle_matricula  FROM matricula m 
	INNER JOIN detalle_matricula_unidad_didactica dmud ON dmud.id_matricula = m.id  
	INNER JOIN programacion_unidad_didactica pud ON pud.id = dmud.id_programacion_ud 
	INNER JOIN unidad_didactica ud ON ud.id = pud.id_unidad_didactica WHERE m.id_periodo_acad = $periodo AND m.id_estudiante = $id_estudiante";
	return mysqli_query($conexion, $sql);
}



// -------------------------- MODULO ADMISIÓN --------------------------

// Para la tabla Proceso_Admision
function buscarTodosProcesosAdmision($conexion)
{
	$sql = "SELECT * FROM proceso_admision";
	return mysqli_query($conexion, $sql);
}

// Para la tabla Proceso_Admision
function cantidadPostulaciones($conexion)
{
	$sql = "SELECT COUNT(*) as total FROM detalle_postulacion";
	$res_total = mysqli_query($conexion, $sql);
	$total = mysqli_fetch_array($res_total);
	return $total['total'];
}

// Para la tabla Proceso_Admision por periodo
function buscarTodosProcesosAdmisionPeriodo($conexion, $periodo)
{
	$sql = "SELECT * FROM proceso_admision WHERE Periodo = '$periodo'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla documento_admision
function buscarTodosDocumentosAdmision($conexion)
{
	$sql = "SELECT * FROM documento_admision";
	return mysqli_query($conexion, $sql);
}

// Para la tabla resultados_examen
function buscarTodosResultadosExamen($conexion)
{
	$sql = "SELECT * FROM resultados_examen";
	return mysqli_query($conexion, $sql);
}

// Para la tabla metodo_pago
function buscarTodosMetodosPago($conexion)
{
	$sql = "SELECT * FROM metodo_pago";
	return mysqli_query($conexion, $sql);
}

// Para la tabla metodo_pago
function buscarTodosMetodosPagoPorId($conexion, $id)
{
	$sql = "SELECT * FROM metodo_pago WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla cuadro_vacantes
function buscarTodosCuadrosVacantes($conexion)
{
	$sql = "SELECT * FROM cuadro_vacantes";
	return mysqli_query($conexion, $sql);
}

// Para la tabla Proceso_Admision por ID
function buscarProcesoAdmisionPorId($conexion, $id)
{
	$sql = "SELECT * FROM proceso_admision WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla documento_admision por ID de proceso
function buscarDocumentosEgresadoPorIdEgresado($conexion, $idEgresado)
{
	$sql = "SELECT * FROM documento_egresado WHERE Id_estudiante = '$idEgresado'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla documento_admision por ID de proceso
function buscarDocumentosAdmisionPorIdProceso($conexion, $idProceso)
{
	$sql = "SELECT * FROM documento_admision WHERE Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla imagen de documento_admision por ID de proceso
function buscarImagenPublicitariaPorIdProceso($conexion, $idProceso)
{
	$sql = "SELECT * FROM documento_admision WHERE Id_Proceso_Admision = '$idProceso' AND Tipo = 'Imagen Publicitaria'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla resultados_examen por ID de postulante
function buscarResultadosExamenPorIdPostulante($conexion, $idPostulante)
{
	$sql = "SELECT * FROM resultados_examen WHERE Id_Postulante = '$idPostulante'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla resultados_examen por ID de postulante y Id del Proceso
function buscarResultadoPostulanteRepetido($conexion, $idPostulante, $idProceso)
{
	$sql = "SELECT * FROM resultados_examen WHERE Id_Postulante = '$idPostulante' AND Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

// Para la tabla cuadro_vacantes por ID de proceso
function buscarCuadrosVacantesPorIdProceso($conexion, $idProceso)
{
	$sql = "SELECT * FROM cuadro_vacantes WHERE Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

function buscarDocumentoAdmisionPorIdProceso($conexion, $idProceso)
{
	$sql = "SELECT * FROM documento_admision WHERE Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

function buscarCuadroVacantesPorPeriodoPrograma($conexion, $periodo, $id_programa)
{
	$sql = "SELECT cv.*, m.Descripcion FROM cuadro_vacantes cv INNER JOIN proceso_admision pa ON pa.Id = cv.Id_Proceso_Admision INNER JOIN modalidad m ON m.Id = cv.Id_Modalidad 
	WHERE pa.Periodo = '$periodo' AND cv.Id_Programa = '$id_programa' 
	ORDER BY  cv.Id_Programa ASC, CASE WHEN m.Descripcion = 'Ordinario' THEN 1 ELSE 0 END, cv.Id_Modalidad ASC";
	return mysqli_query($conexion, $sql);
}

function buscarTotalVacantesPorPeriodoPrograma($conexion, $periodo, $id_programa)
{
	$sql = "SELECT 
	SUM(cv.Vacantes) AS total_vacante_programa
	FROM 
		cuadro_vacantes cv 
	INNER JOIN 
		proceso_admision pa ON pa.Id = cv.Id_Proceso_Admision 
	INNER JOIN 
		modalidad m ON m.Id = cv.Id_Modalidad 
	WHERE 
		pa.Periodo = '$periodo' 
		AND cv.Id_Programa = '$id_programa' ";
	return mysqli_query($conexion, $sql);
}

function buscarModalidadPorPeriodo($conexion, $periodo)
{
	$sql = "SELECT m.* FROM cuadro_vacantes cv INNER JOIN proceso_admision pa ON pa.Id = cv.Id_Proceso_Admision INNER JOIN modalidad m ON m.Id = cv.Id_Modalidad 
	WHERE pa.Periodo = '$periodo' GROUP BY Id_Modalidad
	ORDER BY  cv.Id_Programa ASC, CASE WHEN m.Descripcion = 'Ordinario' THEN 1 ELSE 0 END, cv.Id_Modalidad ASC";
	return mysqli_query($conexion, $sql);
}

// Para traer todos los registros de la tabla ajustes_admision
function buscarTodosAjustesAdmision($conexion)
{
	$sql = "SELECT * FROM ajustes_admision";
	return mysqli_query($conexion, $sql);
}

// Para buscar ajustes de admisión por ID de proceso de admisión
function buscarAjustesAdmisionPorIdProceso($conexion, $idProceso)
{
	$sql = "SELECT aa.Id, aa.Estado, r.Titulo, r.Tipo, r.Descripcion, r.Id_Modalidad FROM ajustes_admision aa INNER JOIN requisito r ON r.Id = aa.Id_Requisito
	WHERE Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

// Para buscar ajustes de admisión por su ID
function buscarAjustesAdmisionPorId($conexion, $id)
{
	$sql = "SELECT * FROM ajustes_admision WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarProcesoAdmisionPorPeriodoTipo($conexion, $tipo, $periodo)
{
	$sql = "SELECT COUNT(*) AS num_rows FROM proceso_admision WHERE Tipo = '$tipo' AND Periodo = '$periodo'";
	return mysqli_query($conexion, $sql);
}

//TABLA Postulante
function buscarTodosPostulantes($conexion)
{
	$sql = "SELECT * FROM postulante";
	return mysqli_query($conexion, $sql);
}

//Obtener postulantes por proceso
function buscarTodosPostulantesPorProceso($conexion, $proceso)
{
	$sql = "SELECT p.*, dp.Fecha FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante WHERE dp.Id_Proceso_Admision = '$proceso' AND dp.Estado = 1";
	return mysqli_query($conexion, $sql);
}
//obtener postulantes aptos por proceso
function buscarTodosPostulantesPorProcesoAptos($conexion, $proceso)
{
	$sql = "SELECT p.*, dp.Fecha FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante WHERE dp.Id_Proceso_Admision = '$proceso' AND dp.Estado = 2";
	return mysqli_query($conexion, $sql);
}
//obtener postulantes no aptos por proceso
function buscarTodosPostulantesPorProcesoNoAptos($conexion, $proceso)
{
	$sql = "SELECT p.*, dp.Fecha FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante WHERE dp.Id_Proceso_Admision = '$proceso' AND dp.Estado = 3";
	return mysqli_query($conexion, $sql);
}

//obtener postulantes subsanados por proceso
function buscarTodosPostulantesPorProcesoSubsanados($conexion, $proceso)
{
	$sql = "SELECT p.*, dp.Fecha FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante WHERE dp.Id_Proceso_Admision = '$proceso' AND dp.Estado = 4";
	return mysqli_query($conexion, $sql);
}

//TABLA Detalle_proceso_admision
function buscarTodosDetalleColegio($conexion)
{
	$sql = "SELECT * FROM detalle_colegio";
	return mysqli_query($conexion, $sql);
}

//TABLA Modalidad
function buscarTodasModalidades($conexion)
{
	$sql = "SELECT * FROM modalidad";
	return mysqli_query($conexion, $sql);
}

//TABLA Modalidad
function buscarModalidadOrdinario($conexion)
{
	$sql = "SELECT * FROM modalidad WHERE Descripcion = 'Ordinario'";
	return mysqli_query($conexion, $sql);
}

//Procesos Activos
function buscarProcesosActivos($conexion)
{
	$sql = "SELECT *
	FROM proceso_admision
	WHERE CURDATE() BETWEEN Fecha_Inicio AND Fecha_Fin;";
	return mysqli_query($conexion, $sql);
}

//Procesos Activos por fechas
function buscarProcesosActivosPorFechas($conexion, $fechaInicio, $fechaFin)
{
	$sql = "SELECT *
	FROM proceso_admision
	WHERE '$fechaInicio' BETWEEN Fecha_Inicio AND Fecha_Fin OR '$fechaFin' BETWEEN Fecha_Inicio AND Fecha_Fin";
	return mysqli_query($conexion, $sql);
}

//Procesos Activos por fechas
function buscarProcesosActivosPorFechasActualizar($conexion, $fechaInicio, $id)
{
	$sql = "SELECT *
	FROM proceso_admision
	WHERE '$fechaInicio' BETWEEN Fecha_Inicio AND Fecha_Fin AND Id != $id";
	return mysqli_query($conexion, $sql);
}


//Procesos en etapa de inscripcion Activos
function buscarProcesosActivosParaInscripción($conexion)
{
	$sql = "SELECT *
	FROM proceso_admision
	WHERE CURDATE() BETWEEN Inicio_Inscripcion AND Fin_Inscripcion;";
	return mysqli_query($conexion, $sql);
}
//Procesos en etapa de inscripcion extemporaneo Activos
function buscarProcesosActivosParaInscripciónExtemporaneo($conexion)
{
	$sql = "SELECT *
	FROM proceso_admision
	WHERE CURDATE() BETWEEN Inicio_Extemporaneo AND Fin_Extemporaneo";
	return mysqli_query($conexion, $sql);
}

//Modalidad por cuadro vacante
function buscarModalidadesCuadroVacantes($conexion, $proceso)
{
	$sql = "SELECT m.*
	FROM proceso_admision pa INNER JOIN cuadro_vacantes cv ON pa.Id = cv.Id_Proceso_Admision INNER JOIN modalidad m ON m.Id = cv.Id_Modalidad
	WHERE CURDATE() BETWEEN pa.Fecha_Inicio AND pa.Fecha_Fin AND cv.Id_Proceso_Admision = '$proceso' AND m.Descripcion != 'Ordinario' GROUP BY m.Id";
	return mysqli_query($conexion, $sql);
}

//Modalidad por cuadro vacante
function buscarModalidadesPeriodo($conexion, $periodo)
{
	$sql = "SELECT m.*
	FROM proceso_admision pa INNER JOIN cuadro_vacantes cv ON pa.Id = cv.Id_Proceso_Admision INNER JOIN modalidad m ON m.Id = cv.Id_Modalidad
	WHERE pa.Periodo = '$periodo' AND m.Descripcion != 'Ordinario' AND cv.Vacantes > 0 GROUP BY m.Id";
	return mysqli_query($conexion, $sql);
}

//Modalidad programas con vacantes por modalidad y periodo
function buscarProgramaPorProcesoModalidad($conexion, $periodo, $modalidad)
{
	$sql = "SELECT pe.nombre, pe.id
	FROM proceso_admision pa INNER JOIN cuadro_vacantes cv ON pa.Id = cv.Id_Proceso_Admision INNER JOIN modalidad m ON m.Id = cv.Id_Modalidad INNER JOIN programa_estudios pe ON pe.id = cv.Id_Programa
	WHERE  pa.Periodo = '$periodo' AND m.Id = '$modalidad' AND cv.Vacantes != 0";
	return mysqli_query($conexion, $sql);
}

//Requisitos generales por proceso de admision
function buscarRequisitosGeneralesPorProceso($conexion, $proceso)
{
	$sql = "SELECT r.* FROM ajustes_admision aa INNER JOIN requisito r ON aa.Id_Requisito = r.Id WHERE aa.Id_Proceso_Admision = '$proceso' AND aa.Estado = 1 AND r.Tipo = 'General'";
	return mysqli_query($conexion, $sql);
}

//Requisitos generales por proceso de admision
function buscarRequisitosEspecificosPorProcesoModalidad($conexion, $proceso, $modalidad)
{
	$sql = "SELECT r.* FROM ajustes_admision aa INNER JOIN requisito r ON aa.Id_Requisito = r.Id INNER JOIN modalidad m ON m.Id = r.Id_Modalidad 
	WHERE aa.Id_Proceso_Admision = '$proceso' AND aa.Estado = 1 AND r.Tipo = 'Especifico' AND m.Id = '$modalidad'";
	return mysqli_query($conexion, $sql);
}

function buscarTodasModalidadesOrdenadas($conexion)
{
	$sql = "SELECT * FROM modalidad ORDER BY CASE WHEN Descripcion = 'Ordinario' THEN 1 ELSE 0 END, Id ASC";
	return mysqli_query($conexion, $sql);
}


//TABLADetalle_Postulacion
function buscarTodosDetallePostulacion($conexion)
{
	$sql = "SELECT * FROM detalle_postulacion";
	return mysqli_query($conexion, $sql);
}

//TABLA Requisito
function buscarTodosRequisitos($conexion)
{
	$sql = "SELECT * FROM requisito";
	return mysqli_query($conexion, $sql);
}

//TABLA Postulacion_Documento
function buscarTodosPostulacionDocumento($conexion)
{
	$sql = "SELECT * FROM postulacion_documento";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Postulante
function buscarPostulantePorId($conexion, $id)
{
	$sql = "SELECT * FROM postulante WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA detalle_colegio
function buscarDetalleColegioPorId($conexion, $id)
{
	$sql = "SELECT * FROM detalle_colegio WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Modalidad
function buscarModalidadPorId($conexion, $id)
{
	$sql = "SELECT * FROM modalidad WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Detalle_Postulacion
function buscarDetallePostulacionPorId($conexion, $id)
{
	$sql = "SELECT * FROM detalle_postulacion WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID de Postulante
function buscarDetallePostulacionPorIdPostulante($conexion, $id_postulante)
{
	$sql = "SELECT * FROM detalle_postulacion WHERE Id_Postulante = '$id_postulante'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Requisito
function buscarRequisitoPorId($conexion, $id)
{
	$sql = "SELECT * FROM requisito WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Requisito
function buscarRequisitoPorIdModalidad($conexion, $id)
{
	$sql = "SELECT * FROM requisito WHERE Id_Modalidad = '$id'";
	return mysqli_query($conexion, $sql);
}

// Utilizando su ID TABLA Postulacion_Documento
function buscarPostulacionDocumentoPorId($conexion, $id)
{
	$sql = "SELECT * FROM postulacion_documento WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

// 	Buscar Postulantes por DNI:
function buscarPostulantePorDni($conexion, $dni)
{
	$sql = "SELECT * FROM postulante WHERE Dni = '$dni'";
	return mysqli_query($conexion, $sql);
}

//Buscar Postulantes por Lugar de Procedencia:
function buscarPostulantesPorLugarProcedencia($conexion, $lugar)
{
	$sql = "SELECT * FROM postulante WHERE Lugar_Procedencia = '$lugar'";
	return mysqli_query($conexion, $sql);
}

// Buscar Postulaciones por Fecha:
function buscarPostulacionesPorFecha($conexion, $fecha)
{
	$sql = "SELECT * FROM detalle_postulacion WHERE Fecha = '$fecha'";
	return mysqli_query($conexion, $sql);
}

//Buscar Modalidades por Monto:
function buscarModalidadesPorMonto($conexion, $monto)
{
	$sql = "SELECT * FROM modalidad WHERE Monto = '$monto'";
	return mysqli_query($conexion, $sql);
}

//Buscar Requisitos por Modalidad:
function buscarRequisitosPorModalidad($conexion, $id_modalidad)
{
	$sql = "SELECT * FROM requisito WHERE Id_Modalidad = '$id_modalidad'";
	return mysqli_query($conexion, $sql);
}

//Buscar Documentos de Postulación por ID de Detalle de Postulación:
function buscarDocumentosDePostulacionPorDetalle($conexion, $id_detalle)
{
	$sql = "SELECT * FROM postulacion_documento WHERE Id_Detalle_Postulacion = '$id_detalle'";
	return mysqli_query($conexion, $sql);
}

// Buscar Detalle de Colegio por Nombre:
function buscarDetalleColegioPorNombre($conexion, $nombre)
{
	$sql = "SELECT * FROM detalle_colegio WHERE Nombre LIKE '%$nombre%'";
	return mysqli_query($conexion, $sql);
}

// Buscar Detalles de Postulación por Proceso de Admisión:
function buscarDetallesPostulacionPorProcesoAdmision($conexion, $idProceso)
{
	$sql = "SELECT * FROM detalle_postulacion WHERE Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

// Buscar Postulantes por Lengua Materna:
function buscarPostulantesPorLenguaMaterna($conexion, $lengua)
{
	$sql = "SELECT * FROM postulante WHERE Lengua_Materna = '$lengua'";
	return mysqli_query($conexion, $sql);
}

//Buscar Postulantes por Programa de Estudio:
function buscarPostulantesPorProgramaEstudio($conexion, $idPrograma)
{
	$sql = "SELECT * FROM detalle_postulacion WHERE Id_Programa_Estudio = '$idPrograma'";
	return mysqli_query($conexion, $sql);
}

//Buscar Requisitos generales:
function buscarRequisitosGenerales($conexion)
{
	$sql = "SELECT * FROM requisito WHERE Tipo = 'General'";
	return mysqli_query($conexion, $sql);
}

//determinar_periodos_activos
function determinarPeriodosActivos($conexion, $periodo)
{
	$sql = "SELECT COUNT(*) AS cantidad_procesos
	FROM proceso_admision
	WHERE Periodo = '$periodo' 
	AND Fecha_Fin <= CURDATE()";
	return mysqli_query($conexion, $sql);
}

// --------------------------- FUNCIONES COLEGIO----------------------------

function obtenerColegioByID($conexion, $id)
{
	$sql = "SELECT * FROM `colegio` WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerRegiones($conexion)
{
	$sql = "SELECT Departamento FROM `colegio` GROUP BY Departamento";
	return mysqli_query($conexion, $sql);
}

function obtenerProvinciaPorRegion($conexion, $region)
{
	$sql = "SELECT Provincia FROM `colegio` WHERE Departamento = '$region' GROUP BY Provincia";
	return mysqli_query($conexion, $sql);
}

function obtenerDistritoPorRegionProvincia($conexion, $region, $provincia)
{
	$sql = "SELECT Distrito FROM `colegio` WHERE Departamento = '$region' AND Provincia = '$provincia' GROUP BY Distrito";
	return mysqli_query($conexion, $sql);
}

function obtenerColegioPorProvinciaDistrito($conexion, $distrito, $provincia)
{
	$sql = "SELECT Id, Nombre FROM `colegio`  WHERE Distrito = '$distrito' AND Provincia = '$provincia'";
	return mysqli_query($conexion, $sql);
}

function obtenerDatosPostulantePorProcesoAdmision($conexion, $idProcesoAdmision)
{
	$sql = "SELECT dp.*, p.*, re.*, pe.* 
			FROM detalle_postulacion dp
			INNER JOIN postulante p ON dp.Id_Postulante = p.Id
			INNER JOIN resultados_examen re ON dp.Id_Postulante = re.Id_Postulante
			INNER JOIN programa_estudios pe ON re.Id_Programa = pe.id
			WHERE dp.Id_Proceso_Admision = '$idProcesoAdmision'";
	return mysqli_query($conexion, $sql);
}

function obtenerDatosPostulantePorDni($conexion, $dni)
{
	$sql = "SELECT p.*, dp.*
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			WHERE p.Dni = '$dni'";
	return mysqli_query($conexion, $sql);
}

function obtenerDatosPostulantePorDniCodigoUnico($conexion, $dni, $codigo)
{
	$sql = "SELECT p.*, dp.*
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			WHERE p.Dni = '$dni' AND dp.Codigo_Unico = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function obtenerDatosPostulantePorDniProcesoAdmision($conexion, $dni, $proceso)
{
	$sql = "SELECT p.*
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			WHERE p.Dni = '$dni' AND dp.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}

function obtenerDatosPostulancionPorId($conexion, $id)
{
	$sql = "SELECT p.*, dp.*, m. Descripcion, pa.*, pa.Fecha_Examen, pe. nombre
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			INNER JOIN modalidad m ON dp.Id_Modalidad = m.Id
			INNER JOIN proceso_admision pa ON dp.Id_Proceso_Admision = pa.Id
			INNER JOIN programa_estudios pe ON dp.Id_Programa_Estudio = pe.Id
			WHERE p.Id = '$id'";
	return mysqli_query($conexion, $sql);
}


function obtenerIdDetallePostulacion($conexion, $id)
{
	$sql = "SELECT dp.Id
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			WHERE p.Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerDocumentosdePostulancionPorId($conexion, $id)
{
	$sql = "SELECT docp.*, dp.*
			FROM documento_postulacion docp
			INNER JOIN detalle_postulacion dp ON docp.Id_Detalle_Postulacion = dp.Id
			WHERE dp.Id = '$id'";
	return mysqli_query($conexion, $sql);
}


function obtenerDatosPostulancionCompletaPorId($conexion, $id)
{
	$sql = "SELECT p.*, dp.*, re.*, m. Descripcion, pa. Periodo, pe. nombre
			FROM postulante p
			INNER JOIN detalle_postulacion dp ON p.Id = dp.Id_Postulante
			INNER JOIN resultados_examen re ON p.Id = re.Id_Postulante
			INNER JOIN modalidad m ON dp.Id_Modalidad = m.Id
			INNER JOIN proceso_admision pa ON dp.Id_Proceso_Admision = pa.Id
			INNER JOIN programa_estudios pe ON dp.Id_Programa_Estudio = pe.Id
			WHERE p.Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerDocumentosdePostulancionPorPeriodo($conexion, $proceso)
{
	$sql = "SELECT da.*, pa.*
			FROM documento_admision da
			INNER JOIN proceso_admision pa ON da.Id_Proceso_Admision = pa.Id
			WHERE pa.Id = '$proceso' AND da.Tipo = 'Carpeta del Postulante'";
	return mysqli_query($conexion, $sql);
}

function obtenerDocumentosdeDePostulancion($conexion, $id)
{
	$sql = "SELECT dp.*, r.Titulo FROM `documento_postulacion` dp INNER JOIN requisito r ON dp.Id_Requisito = r.Id WHERE dp.Id_Detalle_Postulacion = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerPostulantesAptosProcesoAdmision($conexion, $id)
{
	$sql = "SELECT p.Dni, p.Apellido_Paterno, p.Apellido_Materno, p.Nombres, pe.nombre, dp.Id_Segunda_Opcion FROM `detalle_postulacion` dp 
	INNER JOIN postulante p ON p.Id = dp.Id_Postulante 
	INNER JOIN programa_estudios pe ON pe.id = dp.Id_Programa_Estudio WHERE Id_Proceso_Admision = '$id' AND Estado = 2";
	return mysqli_query($conexion, $sql);
}

//FUNCIONES PARA SEGUNDA OPCION - ADMISION
function buscarAjustesSegundaOpcionPorProceso($conexion, $id)
{
	$sql = "SELECT pe.nombre, pe.plan_estudio, aso.Estado, aso.Id FROM admision_segunda_opcion aso INNER JOIN programa_estudios pe ON pe.id = aso.Id_Programa WHERE aso.Id_Proceso_Admision = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarAjustesSegundaOpcionPorProcesoPrograma($conexion, $id_proceso, $id_programa)
{
	$sql = "SELECT * FROM admision_segunda_opcion WHERE Id_Proceso_Admision = '$id_proceso' AND Id_Programa = '$id_programa'";
	return mysqli_query($conexion, $sql);
}

//FUNCIONES PARA OBTENER DETALLE POSTULANTE POR POSTULANTE Y PROCESO
function buscarDetallePostulantesByDNIProcesoAdmision($conexion, $id_proceso, $dni_postulante)
{
	$sql = "SELECT COUNT(*) as cantidad FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante 
	WHERE p.Dni = '$dni_postulante' AND dp.Id_Proceso_Admision = '$id_proceso'";
	$res = mysqli_query($conexion, $sql);
	$cantidad = mysqli_fetch_array($res);
	$cantidad = $cantidad['cantidad'];
	return $cantidad;
}

//OBTENER DOCUMENTOS OBSERVADOS POR DETALLE POSTULACION
function bucarDocumentosObservadosPorDetallePostulacion($conexion, $id_detalle)
{
	$sql = "SELECT r.* FROM `documento_postulacion` dp INNER JOIN requisito r ON r.Id = dp.Id_Requisito WHERE dp.Id_Detalle_Postulacion = '$id_detalle' AND dp.Observado = 1";
	return mysqli_query($conexion, $sql);
}

// buscar todos los resultados que alcanzaron vacante en un proceso de admision
function buscarEstudiantesParaAdjudicar($conexion, $idProceso)
{
	$sql = "SELECT p.*, re.Id_Programa, pa.Periodo FROM proceso_admision pa INNER JOIN resultados_examen re ON re.Id_Proceso_Admision = pa.Id 
	INNER JOIN postulante p ON p.Id = re.Id_Postulante WHERE re.Condicion = 1 AND pa.Id = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

//ESTADISTICAS POR PROCESO

//buscar total de postulantes
function buscarTotalPostulantesPorProceso($conexion, $proceso)
{
	$sql = "SELECT p.*, dp.Fecha FROM `detalle_postulacion` dp INNER JOIN postulante p ON p.Id = dp.Id_Postulante WHERE dp.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}

//buscar total de vacantes en programas por proceso
function buscarTotalVacantesProgramaPorPeriodo($conexion, $periodo)
{
	$sql = "SELECT pe.nombre , SUM(cv.Vacantes) as Total_Vacantes FROM cuadro_vacantes cv INNER JOIN proceso_admision pa ON pa.Id = cv.Id_Proceso_Admision
	INNER JOIN programa_estudios pe ON pe.id = cv.Id_Programa WHERE pa.Periodo = '$periodo' GROUP BY cv.Id_Programa";
	return mysqli_query($conexion, $sql);
}

//buscar total de vacantes en programas por proceso
function buscarTotalVacantesPrograma($conexion)
{
	$sql = "SELECT pe.nombre , SUM(cv.Vacantes) as Total_Vacantes FROM cuadro_vacantes cv INNER JOIN proceso_admision pa ON pa.Id = cv.Id_Proceso_Admision
	INNER JOIN programa_estudios pe ON pe.id = cv.Id_Programa";
	return mysqli_query($conexion, $sql);
}

// buscar todos los resultados que alcanzaron vacante en un proceso de admision
function buscarAdmitidosPorProceso($conexion, $idProceso)
{
	$sql = "SELECT * FROM resultados_examen WHERE Condicion = '1' AND Id_Proceso_Admision = '$idProceso'";
	return mysqli_query($conexion, $sql);
}

function buscarAdmitidos($conexion)
{
	$sql = "SELECT * FROM resultados_examen WHERE Condicion = '1'";
	return mysqli_query($conexion, $sql);
}


// POSTULANTES X ESTADISTICAS

// PROGRAMAS
function obtenerTodoProgramaEstudios($conexion)
{
	$sql = "SELECT * FROM programa_estudios";
	return mysqli_query($conexion, $sql);
}
function obtenerPostulantesPorProgramaEstudios($conexion, $id)
{
	$sql = "SELECT dp.*, pe.nombre AS nombre_programa_estudios
            FROM detalle_postulacion dp
            INNER JOIN programa_estudios pe ON dp.Id_Programa_Estudio = pe.id
            WHERE pe.id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerProgramaEstudiosPorId($conexion, $id)
{
	$sql = "SELECT * FROM programa_estudios 
	WHERE Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerPostulantesPorProgramaEstudiosProceso($conexion, $id, $proceso)
{
	$sql = "SELECT dp.*, pe.nombre AS nombre_programa_estudios
            FROM detalle_postulacion dp
            INNER JOIN programa_estudios pe ON dp.Id_Programa_Estudio = pe.id
            WHERE pe.id = '$id' AND dp.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}

// POSTULANTES X MODALIDADES

function obtenerPostulantesPorModalidad($conexion, $id)
{
	$sql = "SELECT dp.*, m.*
            FROM detalle_postulacion dp
            INNER JOIN modalidad m ON dp.Id_Modalidad = m.Id
            WHERE m.Id = '$id'";
	return mysqli_query($conexion, $sql);
}

function obtenerPostulantesPorModalidadProceso($conexion, $id, $proceso)
{
	$sql = "SELECT dp.*, m.*
            FROM detalle_postulacion dp
            INNER JOIN modalidad m ON dp.Id_Modalidad = m.Id
            WHERE m.Id = '$id' AND dp.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}

// POSTULANTES X GENERO
function buscarTodosPostulantesPorGenero($conexion, $id_Genero)
{
	$sql = "SELECT * FROM postulante
	WHERE postulante.Sexo = $id_Genero";
	return mysqli_query($conexion, $sql);
}

function buscarTodosPostulantesPorGeneroProceso($conexion, $id_Genero, $proceso)
{
	$sql = "SELECT * FROM postulante p INNER JOIN detalle_postulacion dp ON dp.Id_Postulante = p.Id
	WHERE p.Sexo = '$id_Genero' AND dp.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}



// POSTULANTES X COLEGIOS 

function buscarTodosColegiosPorPostulante($conexion)
{
	$sql = "SELECT dp.*, c.*, dc.*
	FROM detalle_postulacion dp
	INNER JOIN detalle_colegio dc ON dp.Id_Detalle_Colegio = dc.Id
	INNER JOIN colegio c ON dc.Id_Colegio = c.Id
	";
	return mysqli_query($conexion, $sql);
}

function buscarTodosColegiosPorProceso($conexion, $id)
{
	$sql = "SELECT dp.*, c.*, dc.*
	FROM detalle_postulacion dp
	INNER JOIN detalle_colegio dc ON dp.Id_Detalle_Colegio = dc.Id
	INNER JOIN colegio c ON dc.Id_Colegio = c.Id WHERE dp.Id_Proceso_Admision = '$id'
	";
	return mysqli_query($conexion, $sql);
}

// POSTULANTES X MEDIOS DE DIFUSION

function buscarTodosMediosDifusionPorPostulante($conexion)
{
	$sql = "SELECT * FROM detalle_postulacion 
	";
	return mysqli_query($conexion, $sql);
}

// POSTULANTES X PAGO

function obtenerPostulantesPorMedioDifusion($conexion, $id)
{
	$sql = "SELECT * FROM detalle_postulacion 
			WHERE detalle_postulacion.Id_Metodo_Pago = $id";
	return mysqli_query($conexion, $sql);
}

function obtenerPostulantesPorMedioDifusionProceso($conexion, $id, $proceso)
{
	$sql = "SELECT * FROM detalle_postulacion 
			WHERE detalle_postulacion.Id_Metodo_Pago = $id AND detalle_postulacion.Id_Proceso_Admision = '$proceso'";
	return mysqli_query($conexion, $sql);
}

// POSTULANTES X PROCESO ADMISION


function obtenerPostulantesPorProcesoAdmision($conexion, $id)
{
	$sql = "SELECT * FROM detalle_postulacion 
			WHERE detalle_postulacion.Id_Proceso_Admision = $id";
	return mysqli_query($conexion, $sql);
}


//BUSQUEDAS O CONSULTAS DEL MODULO CAJA

// -------------------  FUNCIONES BUSCAR ------------------------------
function buscarConceptoIngresos($conexion)
{
	$sql = "SELECT * FROM concepto_ingreso";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoIngresosById($conexion, $id_ingreso)
{
	$sql = "SELECT * FROM concepto_ingreso WHERE id = '$id_ingreso'";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoEgresos($conexion)
{
	$sql = "SELECT * FROM concepto_egreso";
	return mysqli_query($conexion, $sql);
}

function buscarComprobante($conexion)
{
	$sql = "SELECT * FROM comprobantes_pago";
	return mysqli_query($conexion, $sql);
}

function buscarConceptoEgresosById($conexion, $id_egreso)
{
	$sql = "SELECT * FROM concepto_egreso WHERE id = '$id_egreso'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresos($conexion)
{
	$sql = "SELECT * FROM ingresos WHERE estado = 'PAGADO' ORDER BY fecha_pago DESC";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosTotales($conexion)
{
	$stmt = $conexion->prepare("SELECT monto_total FROM ingresos WHERE estado = 'PAGADO' ORDER BY fecha_pago DESC");
	$stmt->execute();
	$result = $stmt->get_result();
	$total = 0;
	while ($row = $result->fetch_assoc()) {
		$total += floatval(decryptText($row['monto_total']));
	}
	$stmt->close();
	return $total;
}

function buscarEgresosTotales($conexion)
{
	$stmt = $conexion->prepare("SELECT monto_total FROM egresos WHERE estado = 'PAGADO' ORDER BY fecha_pago DESC");
	$stmt->execute();
	$result = $stmt->get_result();
	$total = 0;
	while ($row = $result->fetch_assoc()) {
		$total += floatval(decryptText($row['monto_total']));
	}
	$stmt->close();
	return $total;
}

function buscarIngresosByComprobante($conexion, $nombre_compr)
{
	$sql = "SELECT * FROM ingresos WHERE tipo_comprobante = '$nombre_compr'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosByCodigo($conexion, $codigo)
{
	$sql = "SELECT * FROM ingresos WHERE codigo = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function buscarEgresos($conexion)
{
	$sql = "SELECT * FROM egresos WHERE estado = 'PAGADO'  ORDER BY fecha_pago DESC";
	return mysqli_query($conexion, $sql);
}

function buscarEgresosByCodigo($conexion, $codigo)
{
	$sql = "SELECT * FROM egresos WHERE comprobante = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosAnulados($conexion)
{
	$sql = "SELECT usuario, tipo_comprobante, codigo, monto_total, i.responsable as usuario_caja ,ia.responsable as responsable, motivo, fecha_anulacion FROM ingresos i INNER JOIN ingresos_anulados ia ON i.id = ia.id_ingreso";
	return mysqli_query($conexion, $sql);
}

function buscarEgresosAnulados($conexion)
{
	$sql = "SELECT empresa, ruc, concepto ,tipo_comprobante, comprobante ,monto_total, e.responsable as usuario_caja, ea.responsable as responsable, motivo, fecha_anulacion FROM egresos e INNER JOIN egresos_anulados ea ON e.id = ea.id_egreso";
	return mysqli_query($conexion, $sql);
}

function buscarComprobantesById($conexion, $id)
{
	$sql = "SELECT * FROM comprobantes_pago WHERE id = $id";
	return mysqli_query($conexion, $sql);
}

function buscarIngresosByFechas($conexion, $inicio, $fin)
{
	$sql = "SELECT * FROM ingresos	WHERE DATE(fecha_pago) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO';";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleIngresosByFechas($conexion, $inicio, $fin)
{
	$sql = "SELECT i.tipo_comprobante, i.codigo, i.dni, i.usuario, di.cantidad, ci.concepto, ci.monto, di.subtotal, i.monto_total, i.metodo_pago, i.fecha_pago,i.responsable
	FROM ingresos i INNER JOIN detalle_ingresos di ON i.id  = di.id_ingreso INNER JOIN concepto_ingreso ci ON ci.id = di.id_concepto
	WHERE DATE(i.fecha_pago) BETWEEN '$inicio' AND '$fin' AND i.estado = 'PAGADO'
	ORDER BY i.fecha_pago";
	return mysqli_query($conexion, $sql);
}

function buscarDetalleFlujoCaja($conexion, $inicio, $fin)
{
	$stmt_egresos = $conexion->prepare("SELECT concepto, monto_total FROM egresos WHERE DATE(fecha_registro) BETWEEN ? AND ? AND estado = 'PAGADO'");
	$stmt_egresos->bind_param("ss", $inicio, $fin);
	$stmt_egresos->execute();
	$result_egresos = $stmt_egresos->get_result();
	$egresos = [];
	while ($row = $result_egresos->fetch_assoc()) {
		$concepto = $row['concepto'];
		$monto = floatval(decryptText($row['monto_total']));
		if (!isset($egresos[$concepto])) {
			$egresos[$concepto] = 0;
		}
		$egresos[$concepto] += $monto;
	}
	$stmt_egresos->close();

	$stmt_ingresos = $conexion->prepare("SELECT ci.concepto, di.subtotal FROM concepto_ingreso ci JOIN detalle_ingresos di ON ci.id = di.id_concepto JOIN ingresos i ON di.id_ingreso = i.id WHERE DATE(i.fecha_pago) BETWEEN ? AND ? AND i.estado = 'PAGADO'");
	$stmt_ingresos->bind_param("ss", $inicio, $fin);
	$stmt_ingresos->execute();
	$result_ingresos = $stmt_ingresos->get_result();
	$ingresos = [];
	while ($row = $result_ingresos->fetch_assoc()) {
		$concepto = $row['concepto'];
		$monto = floatval($row['subtotal']);
		if (!isset($ingresos[$concepto])) {
			$ingresos[$concepto] = 0;
		}
		$ingresos[$concepto] += $monto;
	}
	$stmt_ingresos->close();

	$result = [];
	foreach ($egresos as $concepto => $monto) {
		$result[] = ['tipo' => 'EGRESO', 'concepto' => $concepto, 'suma_subtotales' => $monto];
	}
	foreach ($ingresos as $concepto => $monto) {
		$result[] = ['tipo' => 'INGRESO', 'concepto' => $concepto, 'suma_subtotales' => $monto];
	}

	return $result;
}
function buscarEgresosByFechas($conexion, $inicio, $fin)
{
	$sql = "SELECT * FROM egresos	WHERE DATE(fecha_registro) BETWEEN '$inicio' AND '$fin' AND estado = 'PAGADO';";
	return mysqli_query($conexion, $sql);
}

function buscarSaldoIncialByFechaInicio($conexion, $inicio)
{
	$stmt_ingresos = $conexion->prepare("SELECT monto_total FROM ingresos WHERE DATE(fecha_pago) < ? AND estado = 'PAGADO'");
	$stmt_ingresos->bind_param("s", $inicio);
	$stmt_ingresos->execute();
	$result_ingresos = $stmt_ingresos->get_result();
	$total_ingresos = 0;
	while ($row = $result_ingresos->fetch_assoc()) {
		$total_ingresos += floatval(decryptText($row['monto_total']));
	}
	$stmt_ingresos->close();

	$stmt_egresos = $conexion->prepare("SELECT monto_total FROM egresos WHERE DATE(fecha_registro) < ? AND estado = 'PAGADO'");
	$stmt_egresos->bind_param("s", $inicio);
	$stmt_egresos->execute();
	$result_egresos = $stmt_egresos->get_result();
	$total_egresos = 0;
	while ($row = $result_egresos->fetch_assoc()) {
		$total_egresos += floatval(decryptText($row['monto_total']));
	}
	$stmt_egresos->close();

	$saldo_inicial = $total_ingresos - $total_egresos;
	return $saldo_inicial;
}
function buscarTotalIngresoByFechas($conexion, $inicio, $fin)
{
	$stmt = $conexion->prepare("SELECT monto_total FROM ingresos WHERE DATE(fecha_pago) BETWEEN ? AND ? AND estado = 'PAGADO'");
	$stmt->bind_param("ss", $inicio, $fin);
	$stmt->execute();
	$result = $stmt->get_result();
	$montos = [];
	while ($row = $result->fetch_assoc()) {
		$montos[] = $row['monto_total'];
	}
	$stmt->close();
	return $montos;
}

function buscarTotalEgresoByFechas($conexion, $inicio, $fin)
{

	$stmt = $conexion->prepare("SELECT monto_total FROM egresos WHERE DATE(fecha_registro) BETWEEN ? AND ? AND estado = 'PAGADO'");
	$stmt->bind_param("ss", $inicio, $fin);
	$stmt->execute();
	$result = $stmt->get_result();
	$montos = [];
	while ($row = $result->fetch_assoc()) {
		$montos[] = $row['monto_total'];
	}
	$stmt->close();
	return $montos;
}

function buscarAnuncios($conexion)
{
	$sql = "SELECT  * FROM  anuncio WHERE tipo = 'COMUNICADO'";
	return mysqli_query($conexion, $sql);
}

function buscarPreguntasFrecuentes($conexion)
{
	$sql = "SELECT  * FROM  preguntas_frecuentes";
	return mysqli_query($conexion, $sql);
}

function buscarAnunciosActivos($conexion)
{
	$sql = "SELECT  * FROM  anuncio WHERE CURDATE() BETWEEN fecha_activa_inicio AND fecha_activa_fin";
	return mysqli_query($conexion, $sql);
}

function buscarEncuestas($conexion)
{
	$sql = "SELECT  * FROM  anuncio WHERE tipo = 'ENCUESTA'";
	return mysqli_query($conexion, $sql);
}


function buscarTodosTicketsAdm($conexion)
{
	$sql = "SELECT * FROM soporte_ticket";
	return mysqli_query($conexion, $sql);
}

function buscarTodosTickets($conexion, $nombre_usuario)
{
	$sql = "SELECT * FROM soporte_ticket WHERE usuario = '$nombre_usuario'";
	return mysqli_query($conexion, $sql);
}

function buscarTicketByCodigo($conexion, $codigo)
{
	$sql = "SELECT * FROM soporte_ticket WHERE codigo = '$codigo'";
	return mysqli_query($conexion, $sql);
}

function buscarTicketPorId($conexion, $id)
{
	$sql = "SELECT * FROM soporte_ticket WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarDocumentosDocente($conexion, $id)
{
	$sql = "SELECT * FROM documento_docente WHERE id_docente = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarDocumentosDocentePorId($conexion, $id)
{
	$sql = "SELECT * FROM documento_docente WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarDocumentosDocenteRepetidos($conexion, $nombre)
{
	$sql = "SELECT * FROM documento_docente WHERE archivo = '$nombre'";
	$res = mysqli_query($conexion, $sql);
	$cantidad = mysqli_num_rows($res);
	return $cantidad;
}

function buscarHorasSemanalesPorIdPud($conexion, $id)
{
	$sql = "SELECT 
		FLOOR(SUM(TIME_TO_SEC(TIMEDIFF(hora_fin, hora_inicio))) / 3600) AS total_horas,
		FLOOR(SUM(TIME_TO_SEC(TIMEDIFF(hora_fin, hora_inicio))) / 60) % 60 AS total_minutos
	FROM horario_programacion
	WHERE id_programacion_unidad = $id";
	return mysqli_query($conexion, $sql);
}


function buscarHorarioPorProgramacion($conexion, $id)
{
	$sql = "SELECT pas.semana,pas.fecha ,sa.* FROM `sesion_aprendizaje` sa 
	INNER JOIN programacion_actividades_silabo pas ON pas.id = sa.id_programacion_actividad_silabo 
	INNER JOIN silabo s ON s.id = pas.id_silabo 
	INNER JOIN programacion_unidad_didactica pud ON pud.id = s.id_programacion_unidad_didactica WHERE pud.id = $id";
	return mysqli_query($conexion, $sql);
}

function buscarHorarioPorProgramacionId($conexion, $id)
{
	$sql = "SELECT * FROM horario_programacion WHERE id_programacion_unidad = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarAsistenciaDocente($conexion, $id)
{
	$sql = "SELECT * FROM asistencia_docente ad INNER JOIN horario_programacion hp ON hp.id = ad.id_horario_programacion 
	WHERE hp.id_programacion_unidad = '$id' ORDER BY ad.fecha_asistencia ASC";
	return mysqli_query($conexion, $sql);
}
/*
function buscarAsistenciaDocenteId($conexion, $id){
	$sql = "SELECT ad.*, pud.id_unidad_didactica, hp.dia, hp.hora_inicio, hp.hora_fin FROM asistencia_docente ad INNER JOIN horario_programacion hp ON hp.id = ad.id_horario_programacion 
	INNER JOIN programacion_unidad_didactica pud ON pud.id = hp.id_programacion_unidad
	WHERE pud.id_docente = $id AND ad.estado = 'PENDIENTE' AND pud.activar_asistencia = 'SI' AND CURDATE() = ad.fecha_asistencia AND curtime() 
	BETWEEN hp.hora_inicio AND hp.hora_fin LIMIT 1";
	return mysqli_query($conexion, $sql);
}
*/
//PARA SERVIDOR 

function buscarAsistenciaDocenteId($conexion, $id)
{
	$sql = "SELECT ad.*, pud.id_unidad_didactica, hp.dia, hp.hora_inicio, hp.hora_fin
            FROM asistencia_docente ad 
            INNER JOIN horario_programacion hp ON hp.id = ad.id_horario_programacion 
            INNER JOIN programacion_unidad_didactica pud ON pud.id = hp.id_programacion_unidad INNER JOIN periodo_academico pa ON pa.id = pud.id_periodo_acad 
            WHERE pud.id_docente = $id AND ad.estado = 'PENDIENTE' AND pud.activar_asistencia = 'SI' 
            AND ad.fecha_asistencia < pa.fecha_fin AND CURRENT_DATE = ad.fecha_asistencia 
            AND DATE_SUB(CURRENT_TIME, INTERVAL 5 HOUR) BETWEEN hp.hora_inicio AND hp.hora_fin 
            LIMIT 1";
	return mysqli_query($conexion, $sql);
}

function buscarInfoSocioByIdEstudiante($conexion, $id)
{
	$sql = "SELECT * FROM informacion_socioeconomica WHERE id_estudiante = '$id' LIMIT 1";
	return mysqli_query($conexion, $sql);
}
function buscarProgramacionByIdDocentePeriodoCompleto($conexion, $id_docente, $id_periodo)
{
	$sql = "
        SELECT 
            pu.id AS id_programacion_unidad,
            pu.id_unidad_didactica,
            pu.id_docente,
            pu.id_periodo_acad,
            d.id AS docente_id,
            d.dni,
            d.apellidos_nombres,
            d.correo,
            d.telefono,
            d.nivel_educacion,
            d.profesion,
            d.id_programa_estudio AS docente_programa_estudio,
            pa.*,
            ud.id AS unidad_didactica_id,
            ud.descripcion,
            ud.id_programa_estudio AS unidad_didactica_programa_estudio,
            ud.creditos,
            ud.horas,
            ud.tipo
        FROM 
            programacion_unidad_didactica pu
        INNER JOIN 
            docente d ON d.id = pu.id_docente
        INNER JOIN 
            periodo_academico pa ON pa.id = pu.id_periodo_acad
        INNER JOIN 
            unidad_didactica ud ON ud.id = pu.id_unidad_didactica
        WHERE 
            pu.id_docente = '$id_docente' 
            AND pu.id_periodo_acad = '$id_periodo'
    ";
	return mysqli_query($conexion, $sql);
}

function calcularTotalHoras($conexion, $id_programacion_unidad)
{
	$sql_horas = "
        SELECT SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(hora_fin, hora_inicio)))) AS total_horas 
        FROM horario_programacion 
        WHERE id_programacion_unidad = '$id_programacion_unidad'
    ";

	return mysqli_query($conexion, $sql_horas);
}

//red social
function buscarRedesSociales($conexion)
{
	$sql = "SELECT * FROM redes_sociales";
	return mysqli_query($conexion, $sql);
}

//capacitaciones
function buscarCapacitaciones($conexion)
{
	$sql = "SELECT * FROM capacitaciones";
	return mysqli_query($conexion, $sql);
}

function buscarCapacitacionesActivos($conexion)
{
	$sql = "SELECT * 
			FROM capacitaciones
			WHERE CONCAT(fecha, ' ', hora) >= NOW()";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtAperturados($conexion)
{
	$sql = "SELECT * FROM efsrt WHERE estado != 0";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtAprobados($conexion)
{
	$sql = "SELECT id_estudiante FROM efsrt WHERE estado = 0 GROUP BY id_estudiante";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtAprobadosByEstudiante($conexion, $id_estudiante)
{
	$sql = "SELECT * FROM efsrt WHERE estado = 0 AND id_estudiante = '$id_estudiante'";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtByEstudiante($conexion, $id)
{
	$sql = "SELECT * FROM efsrt WHERE id_estudiante = '$id' AND estado != 0";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtById($conexion, $id)
{
	$sql = "SELECT * FROM efsrt WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarEfsrtConcluidasByEstudiante($conexion, $id)
{
	$sql = "SELECT * FROM efsrt WHERE id_estudiante = '$id' AND estado = 0";
	return mysqli_query($conexion, $sql);
}

function buscarConvalidaciones($conexion)
{
	$sql = "SELECT * FROM convalidacion";
	return mysqli_query($conexion, $sql);
}

function buscarConvalidacionesPorEstudiante($conexion, $id)
{
	$sql = "SELECT * FROM convalidacion WHERE id_estudiante = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarConvalidacionesPorEstudiantePeriodoSemestre($conexion, $id_estudiante, $periodo, $semestre)
{
	$sql = "SELECT * FROM convalidacion WHERE id_estudiante = '$id_estudiante' AND id_periodo_academico = '$periodo' 
	AND id_semestre = '$semestre'";
	return mysqli_query($conexion, $sql);
}

function buscarConfiguracionMatriculas($conexion)
{
	$sql = "SELECT * FROM configuracion_matricula";
	return mysqli_query($conexion, $sql);
}

function buscarConfiguracionMatriculaByPeriodo($conexion, $periodo)
{
	$sql = "SELECT * FROM configuracion_matricula WHERE periodo = '$periodo'";
	return mysqli_query($conexion, $sql);
}

function buscarMatriculaActiva($conexion)
{
	$sql = "SELECT * FROM configuracion_matricula WHERE CURDATE() BETWEEN fecha_inicio AND ultimo_dia_matricula LIMIT 1";
	$resultado = mysqli_query($conexion, $sql);
	return mysqli_fetch_assoc($resultado); // Devuelve un array asociativo o `null` si no hay matrícula activa
}

function buscarSolicitudesMatricula($conexion)
{
	$sql = "SELECT * FROM solicitud_matricula";
	return mysqli_query($conexion, $sql);
}

function buscarSolicitudesMatriculaPeriodo($conexion, $id)
{
	$sql = "SELECT * FROM solicitud_matricula WHERE id_configuracion_matricula = '$id' AND estado = 1";
	return mysqli_query($conexion, $sql);
}

function buscarSolicitudeMatriculaId($conexion, $id)
{
	$sql = "SELECT * FROM solicitud_matricula WHERE id = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarSolicitudesMatriculaByConfiguracion($conexion, $id)
{
	$sql = "SELECT * FROM solicitud_matricula WHERE id_configuracion_matricula = '$id'";
	return mysqli_query($conexion, $sql);
}

function buscarSolicitudesMatriculaByEstudiantePeriodo($conexion, $id_estudiante, $id_configuración)
{
	$sql = "SELECT * FROM solicitud_matricula WHERE id_estudiante = '$id_estudiante' AND id_configuracion_matricula = '$id_configuración'";
	return mysqli_query($conexion, $sql);
}
