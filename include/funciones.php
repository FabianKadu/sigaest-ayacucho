<?php
function contar_inasistencia($conexion, $id_silabo, $id_est)
{
    $b_prog_act_sil = buscarProgActividadesSilaboByIdSilabo($conexion, $id_silabo);
    $cont_inasistencia = 0;
    while ($r_prog_act_sil = mysqli_fetch_array($b_prog_act_sil)) {
        $id_prog_act_s = $r_prog_act_sil['id'];
        $b_sesion_aprendizaje = buscarSesionByIdProgramacionActividades($conexion, $id_prog_act_s);
        $r_b_sesion_apr = mysqli_fetch_array($b_sesion_aprendizaje);
        $id_sesion_apr = $r_b_sesion_apr['id'];
        $b_asistencia = buscarAsistenciaBySesionAndEstudiante($conexion, $id_sesion_apr, $id_est);
        $r_b_asistencia = mysqli_fetch_array($b_asistencia);

        if ($r_b_asistencia['asistencia'] == "F") {
            $cont_inasistencia += 1;
        }
    }
    return $cont_inasistencia;
}




function generar_llave()
{
    $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    function generate_string($input, $strength)
    {
        $input_length = strlen($input);
        $random_string = '';
        for ($i = 0; $i < $strength; $i++) {
            $random_character = $input[mt_rand(0, $input_length - 1)];
            $random_string .= $random_character;
        }
        return $random_string;
    }
    $llave = generate_string($permitted_chars, 20);
    return $llave;
}
//funcion para registrar sesion de ingreso

function reg_sesion($conexion, $id_docente, $token)
{
    $fecha_hora_inicio = date("Y-m-d H:i:s");
    $fecha_hora_fin = strtotime('+30 minute', strtotime($fecha_hora_inicio));
    $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);

    $insertar = "INSERT INTO sesion (id_docente, fecha_hora_inicio, fecha_hora_fin, token) VALUES ('$id_docente','$fecha_hora_inicio','$fecha_hora_fin','$token')";
    $ejecutar_insertar = mysqli_query($conexion, $insertar);
    if ($ejecutar_insertar) {
        //ultimo registro de sesion
        $id_sesion = mysqli_insert_id($conexion);
        return $id_sesion;
    } else {
        return 0;
    }
}

//registrar sesion estudiante

function reg_sesion_estudiante($conexion, $id_estudiante, $token)
{
    $fecha_hora_inicio = date("Y-m-d H:i:s");
    $fecha_hora_fin = strtotime('+30 minute', strtotime($fecha_hora_inicio));
    $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);

    $insertar = "INSERT INTO sesion_estudiante (id_estudiante, fecha_hora_inicio, fecha_hora_fin, token) VALUES ('$id_estudiante','$fecha_hora_inicio','$fecha_hora_fin','$token')";
    $ejecutar_insertar = mysqli_query($conexion, $insertar);
    if ($ejecutar_insertar) {
        //ultimo registro de sesion
        $id_sesion = mysqli_insert_id($conexion);
        return $id_sesion;
    } else {
        return 0;
    }
}

function sesion_si_activa($conexion, $id_sesion, $token)
{

    $hora_actuals = date("Y-m-d H:i:s");
    $hora_actual = strtotime('-1 minute', strtotime($hora_actuals));
    $hora_actual = date("Y-m-d H:i:s", $hora_actual);

    $b_sesion = buscarSesionLoginById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);

    $fecha_hora_fin_sesion = $r_b_sesion['fecha_hora_fin'];
    $fecha_hora_fin = strtotime('+1 hour', strtotime($fecha_hora_fin_sesion));
    $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);

    if ((password_verify($r_b_sesion['token'], $token)) && ($hora_actual <= $fecha_hora_fin)) {
        actualizar_sesion($conexion, $id_sesion);
        return true;
    } else {
        return false;
    }
}

function sesion_si_activa_estudiante($conexion, $id_sesion, $token)
{

    $hora_actuals = date("Y-m-d H:i:s");
    $hora_actual = strtotime('-1 minute', strtotime($hora_actuals));
    $hora_actual = date("Y-m-d H:i:s", $hora_actual);

    $b_sesion = buscarSesionEstudianteLoginById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);

    $fecha_hora_fin_sesion = $r_b_sesion['fecha_hora_fin'];
    $fecha_hora_fin = strtotime('+1 hour', strtotime($fecha_hora_fin_sesion));
    $fecha_hora_fin = date("Y-m-d H:i:s", $fecha_hora_fin);

    if ((password_verify($r_b_sesion['token'], $token)) && ($hora_actual <= $fecha_hora_fin)) {
        actualizar_sesion_estudiante($conexion, $id_sesion);
        return true;
    } else {
        return false;
    }
}

function actualizar_sesion($conexion, $id_sesion)
{
    $hora_actual = date("Y-m-d H:i:s");
    $nueva_fecha_hora_fin = strtotime('+60 minute', strtotime($hora_actual));
    $nueva_fecha_hora_fin = date("Y-m-d H:i:s", $nueva_fecha_hora_fin);

    $actualizar = "UPDATE sesion SET fecha_hora_fin='$nueva_fecha_hora_fin' WHERE id=$id_sesion";
    mysqli_query($conexion, $actualizar);
}

function actualizar_sesion_estudiante($conexion, $id_sesion)
{
    $hora_actual = date("Y-m-d H:i:s");
    $nueva_fecha_hora_fin = strtotime('+60 minute', strtotime($hora_actual));
    $nueva_fecha_hora_fin = date("Y-m-d H:i:s", $nueva_fecha_hora_fin);

    $actualizar = "UPDATE sesion_estudiante SET fecha_hora_fin='$nueva_fecha_hora_fin' WHERE id=$id_sesion";
    mysqli_query($conexion, $actualizar);
}


function buscar_docente_sesion($conexion, $id_sesion, $token)
{
    $b_sesion = buscarSesionLoginById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);
    if (password_verify($r_b_sesion['token'], $token)) {
        return $r_b_sesion['id_docente'];
    }
    return 0;
}

function buscar_estudiante_sesion($conexion, $id_sesion, $token)
{
    $b_sesion = buscarSesionEstudianteLoginById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);
    if (password_verify($r_b_sesion['token'], $token)) {
        return $r_b_sesion['id_estudiante'];
    }
    return 0;
}



function buscar_rol_sesion($conexion, $id_sesion, $token)
{
    $b_sesion = buscarSesionLoginById($conexion, $id_sesion);
    $r_b_sesion = mysqli_fetch_array($b_sesion);
    if (password_verify($r_b_sesion['token'], $token)) {
        $b_docente = buscarDocenteById($conexion, $r_b_sesion['id_docente']);
        $r_b_docente = mysqli_fetch_array($b_docente);
        return $r_b_docente['id_cargo'];
    }
    return 0;
}


// funciones para calificaciones --------------------------------------------------------

//funcion para calcular promedio de los criterios de evaluacion
function calc_criterios($conexion, $id_evaluacion)
{
    $b_criterio_evaluacion = buscarCriterioEvaluacionByEvaluacion($conexion, $id_evaluacion);
    $suma_criterios = 0;
    $cont_crit = 0;
    while ($r_b_criterio_evaluacion = mysqli_fetch_array($b_criterio_evaluacion)) {
        if (is_numeric($r_b_criterio_evaluacion['calificacion'])) {
            $suma_criterios += $r_b_criterio_evaluacion['calificacion'];
            $cont_crit += 1;
            //$suma_criterios += (($r_b_criterio_evaluacion['ponderado']/100)*$r_b_criterio_evaluacion['calificacion']);
        }
    }
    if ($cont_crit > 0) {
        $suma_criterios = round($suma_criterios / $cont_crit);
    } else {
        $suma_criterios = round($suma_criterios);
    }
    return $suma_criterios;
}

//funcion para calcular la la evaluacion(criterio de evaluacion) por ponderado
function calc_evaluacion($conexion, $id_calificacion)
{
    $suma_evaluacion = 0;

    $b_evaluacion = buscarEvaluacionByIdCalificacion($conexion, $id_calificacion);
    while ($r_b_evaluacion = mysqli_fetch_array($b_evaluacion)) {
        $id_evaluacion = $r_b_evaluacion['id'];
        //buscamos los criterios de evaluacion
        $suma_criterios = calc_criterios($conexion, $id_evaluacion);

        if (is_numeric($r_b_evaluacion['ponderado'])) {
            $suma_evaluacion += ($r_b_evaluacion['ponderado'] / 100) * $suma_criterios;
        }
    }
    return round($suma_evaluacion);
}


//funcion para calcular la cantidad de ud desaprobadas de esrudiantes

function calc_ud_desaprobado_sin_recuperacion($conexion, $id_est, $per_select, $id_pe, $id_sem)
{

    //buscar si estudiante esta matriculado en una unidad didactica
    $b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);

    $cont_ud_desaprobadas = 0;
    while ($r_bb_ud = mysqli_fetch_array($b_ud_pe_sem)) {
        $id_udd = $r_bb_ud['id'];

        $b_prog_ud = buscarProgramacionByUd_Peridodo($conexion, $id_udd, $per_select);
        $r_b_prog_ud = mysqli_fetch_array($b_prog_ud);
        $id_prog = $r_b_prog_ud['id'];

        //buscar matricula de estudiante
        $b_mat_est = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $per_select);
        $r_b_mat_est = mysqli_fetch_array($b_mat_est);
        $id_mat_est = $r_b_mat_est['id'];
        //buscar detalle de matricula
        $b_det_mat_est = buscarDetalleMatriculaByIdMatriculaProgramacion($conexion, $id_mat_est, $id_prog);
        $r_b_det_mat_est = mysqli_fetch_array($b_det_mat_est);
        $cont_r_b_det_mat = mysqli_num_rows($b_det_mat_est);
        $id_det_mat = $r_b_det_mat_est['id'];
        if ($cont_r_b_det_mat > 0) {
            //echo "<td>SI</td>";

            //buscar las calificaciones
            $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_det_mat);

            $suma_calificacion = 0;
            $cont_calif = 0;
            while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {

                $id_calificacion = $r_b_calificacion['id'];
                //buscamos las evaluaciones
                $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);

                $suma_calificacion += $suma_evaluacion;
                if ($suma_evaluacion > 0) {
                    $cont_calif += 1;
                }
            }
            if ($cont_calif > 0) {
                $calificacion = round($suma_calificacion / $cont_calif);
            } else {
                $calificacion = round($suma_calificacion);
            }
            if ($calificacion != 0) {
                $calificacion = round($calificacion);
            } else {
                $calificacion = "";
            }
            //buscamos si tiene recuperacion
            /*if ($r_b_det_mat_est['recuperacion'] != '') {
                $calificacion = $r_b_det_mat_est['recuperacion'];
            }*/

            if ($calificacion > 12) {
                //echo '<td align="center" ><font color="blue">' . $calificacion . '</font></td>';
            } else {
                //echo '<td align="center" ><font color="red">' . $calificacion . '</font></td>';
                $cont_ud_desaprobadas += 1;
            }
        } else {
            $calificacion = 0;
            //echo '<td></td>';
        }
    }
    return $cont_ud_desaprobadas;
}



// funcion para calcular si estudiante se matriculo a todas las ud del semestre (en caso de repitencia)

function calcular_mat_ud($conexion, $id_est, $per_select, $id_pe, $id_sem)
{
    //buscar si estudiante esta matriculado en una unidad didactica
    $b_ud_pe_sem = buscarUdByCarSem($conexion, $id_pe, $id_sem);
    $cant_ud_sem = mysqli_num_rows($b_ud_pe_sem);

    $cant_matt = 0;

    while ($r_bb_ud = mysqli_fetch_array($b_ud_pe_sem)) {
        $id_udd = $r_bb_ud['id'];

        $b_prog_ud = buscarProgramacionByUd_Peridodo($conexion, $id_udd, $per_select);
        $r_b_prog_ud = mysqli_fetch_array($b_prog_ud);
        $id_prog = $r_b_prog_ud['id'];

        //buscar matricula de estudiante
        $b_mat_est = buscarMatriculaByEstudiantePeriodo($conexion, $id_est, $per_select);
        $r_b_mat_est = mysqli_fetch_array($b_mat_est);
        $id_mat_est = $r_b_mat_est['id'];
        //buscar detalle de matricula
        $b_det_mat_est = buscarDetalleMatriculaByIdMatriculaProgramacion($conexion, $id_mat_est, $id_prog);
        $cont_r_b_det_mat = mysqli_num_rows($b_det_mat_est);

        if ($cont_r_b_det_mat > 0) {
            $cant_matt += 1;
        } else {
        }
    }

    if ($cant_ud_sem === $cant_matt) {
        return 1;
    }
    return 0;
}

function obtenerCalificacionFinal($conexion, $id_detalle_matricula) {
    // Buscar la calificación de recuperación y la calificación final
    $detalle_matricula = buscarDetalleMatriculaById($conexion, $id_detalle_matricula);
    $r_detalle_matricula = mysqli_fetch_array($detalle_matricula);
    
    if (!$r_detalle_matricula) {
        return null; // Si no encuentra el detalle de matrícula
    }
    
    // Si hay una calificación de recuperación aprobatoria (mayor a 12), retornarla
    if ($r_detalle_matricula['recuperacion'] > 12) {
        return $r_detalle_matricula['recuperacion'];
    }

    // Calcular la calificación final en base a las evaluaciones
    $b_calificaciones = buscarCalificacionByIdDetalleMatricula($conexion, $id_detalle_matricula);
    $suma_calificacion = 0;
    $cont_calif = 0;

    while ($r_b_calificacion = mysqli_fetch_array($b_calificaciones)) {
        $id_calificacion = $r_b_calificacion['id'];
        // Calcular la suma de evaluaciones
        $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);
        $suma_calificacion += $suma_evaluacion;
        
        if ($suma_evaluacion > 0) {
            $cont_calif += 1;
        }
    }

    // Promediar las calificaciones
    if ($cont_calif > 0) {
        $calificacion_final = round($suma_calificacion / $cont_calif);
    } else {
        $calificacion_final = round($suma_calificacion);
    }

    // Retornar la calificación final calculada
    return $calificacion_final;
}


function url_host()
{
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
        $url = "https://";
    } else {
        $url = "http://";
    }
    //echo $url . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI'];
    return ($url . $_SERVER['HTTP_HOST'] . "/");
    //return($url . $_SERVER['HTTP_HOST'] .  $_SERVER['REQUEST_URI']);
}






//>>>>>>>>>>>>>>>>>>>>> FUNCION PARA REALIZAR PROGRAMACION DE UNIDAD DIDACTICA <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<

function realizar_programacion($conexion, $unidad_didactica, $id_ult_periodo, $docenteTeoria, $docentePractica, $semanas)
{

    $hoy = date("Y-m-d");

    $consulta = "INSERT INTO programacion_unidad_didactica (id_unidad_didactica, id_docente, id_docente_practica, id_periodo_acad, supervisado, reg_evaluacion, reg_auxiliar, prog_curricular, otros, logros_obtenidos, dificultades, sugerencias) VALUES ('$unidad_didactica','$docenteTeoria','$docentePractica' ,'$id_ult_periodo', 0, 0, 0, 0, 0, '', '', '')";
    $ejecutar_insertar = mysqli_query($conexion, $consulta);
    //buscamos el id de la programacion hecha
    $id_programacion = mysqli_insert_id($conexion);

    //crear silabo de la programacion hecha
    $metodologia = "Deductivo,Analítico,Aprendizaje basado en competencias";
    $recursos_didacticos = "Libros digitales,Foros,Chats,Video Tutoriales,Wikis,Videos explicativos";
    $sistema_evaluacion = "* La escala de calificación es vigesimal y el calificativo mínimo aprobatorio es trece (13). En todos los casos la fracción 0.5 o más se considera como una unidad a favor del estudiante.
    * El estudiante que en la evaluación de una o más Capacidades Terminales programadas en la Unidad Didáctica (Asignaturas), obtenga nota desaprobatoria entre diez (10) y doce (12), tiene derecho a participar en el proceso de recuperación programado.
    * El estudiante que después de realizado el proceso de recuperación obtuviera nota menor a trece (13), en una o más capacidades terminales de una Unidad Didáctica, desaprueba la misma, por tanto, repite la Unidad Didáctica.
    * El estudiante que acumulará inasistencias injustificadas en número igual o mayor al 30% del total de horas programadas en la Unidad Didáctica, será desaprobado en forma automática, sin derecho a recuperación.";
    $indicadores_evaluacion = "Identificación o reconocimiento del tema tratado, Valoración del dominio de los nuevos temas tratados, Capacidad de Resumen, Participación y contribución en clase, Capacidad para el trabajo en equipo";
    $tecnicas_evaluacion = "Observación,Exposición,Pruebas escritas,Estudio de caso,El debate,Exposición oral,Guías";

    $reg_silabo = "INSERT INTO silabo (id_programacion_unidad_didactica, id_coordinador, horario, metodologia, recursos_didacticos, sistema_evaluacion, estrategia_evaluacion_indicadores, estrategia_evaluacion_tecnica, promedio_indicadores_logro, recursos_bibliograficos_impresos, recursos_bibliograficos_digitales) VALUES ('$id_programacion','$docenteTeoria',' ','$metodologia','$recursos_didacticos','$sistema_evaluacion','$indicadores_evaluacion','$tecnicas_evaluacion',' ',' ',' ')";
    $ejec_reg_silabo = mysqli_query($conexion, $reg_silabo);
    if (!$ejec_reg_silabo) {
        /*echo "<script>
			alert('Error al Registrar Silabo');
			window.history.back();
		</script>
		";*/
        return 0;
    }

    //buscamos el id del silabo registrado mediante el id de la programacion
    $id_silabo = mysqli_insert_id($conexion);


    //buscamos los indicadores de logro de la unidad didactica para evitar hacer 16 busquedas lo hacemos antes del loop for
    $busc_logro_capacidad = buscarCapacidadesByIdUd($conexion, $unidad_didactica);
    $res_b_logro_capacidad = mysqli_fetch_array($busc_logro_capacidad);
    $id_capacidad = $res_b_logro_capacidad['id'];
    $id_competencia = $res_b_logro_capacidad['id_competencia'];
    $busc_ind_logro_capacidad = buscarIndicadorLogroCapacidadByIdCapacidad($conexion, $id_capacidad);
    $res_b_i_logro_capacidad = mysqli_fetch_array($busc_ind_logro_capacidad);
    $id_ind_logro_capacidad = $res_b_i_logro_capacidad['id']; // id indicador logro de capacidad
    $busc_ind_logro_competencia = buscarIndicadorLogroCompetenciasByIdCompetencia($conexion, $id_competencia);
    $res_b_i_logro_competencia = mysqli_fetch_array($busc_ind_logro_competencia);
    $id_ind_logro_competencia = $res_b_i_logro_competencia['id']; // id indicador logro competencia

    //crear la programacion del silabo por 17 semanas de clase - 17 sesiones
    //echo $id_silabo."<br>";
    for ($i = 1; $i <= $semanas; $i++) {

        $reg_prog_act_silabo = "INSERT INTO programacion_actividades_silabo (id_silabo, semana, fecha, elemento_capacidad, actividades_aprendizaje, contenidos_basicos, tareas_previas) VALUES ('$id_silabo', '$i', '$hoy',' ',' ',' ',' ')";
        $ejec_reg_prog_act_silabo = mysqli_query($conexion, $reg_prog_act_silabo);

        //buscamos el id de lo ingresado para registrar la siguiente tabla
        $id_prog_act_silabo = mysqli_insert_id($conexion);

        //procedemos a registrar la tabla sesion_aprendizaje-- 1 para cada tabla anterior
        $reg_sesion_aprendizaje = "INSERT INTO sesion_aprendizaje (id_programacion_actividad_silabo, tipo_actividad, tipo_sesion, fecha_desarrollo, id_ind_logro_competencia_vinculado, id_ind_logro_capacidad_vinculado, logro_sesion, bibliografia_obligatoria_docente, bibliografia_opcional_docente, bibliografia_obligatoria_estudiantes, bibliografia_opcional_estudiante, anexos) VALUES ('$id_prog_act_silabo',' ',' ','$hoy', '$id_ind_logro_competencia', '$id_ind_logro_capacidad',' ',' ',' ',' ',' ',' ')";
        $ejec_reg_sesion = mysqli_query($conexion, $reg_sesion_aprendizaje);

        //buscamos el id de la anterior tabla para hacer registros en las siguientes tablas
        $id_sesion = mysqli_insert_id($conexion);
        //echo $id_sesion." - ";
        //crearemos los momentos de la sesion 3 por cada sesion Inicio, Desarrollo y cierre
        for ($j = 1; $j <= 3; $j++) {
            if ($j == 1) {
                $momento = "Inicio";
            }
            if ($j == 2) {
                $momento = "Desarrollo";
            }
            if ($j == 3) {
                $momento = "Cierre";
            }
            // momentos de la sesion
            $reg_momentos_sesion = "INSERT INTO momentos_sesion_aprendizaje (id_sesion_aprendizaje, momento, estrategia, actividad, recursos, tiempo) VALUES ('$id_sesion', '$momento',' ',' ',' ',45)";
            $ejec_reg_momentos_sesion = mysqli_query($conexion, $reg_momentos_sesion);

            // actividad de evaluacion en la sesion
            $reg_act_eva = "INSERT INTO actividad_evaluacion_sesion_aprendizaje (id_sesion_aprendizaje, indicador_logro_sesion, tecnica, instrumentos, peso, momento) VALUES ('$id_sesion',' ',' ',' ',33,'$momento')";
            $ejec_reg_act_eva = mysqli_query($conexion, $reg_act_eva);
        }
    }
    //echo "<br>";
    if (!$ejec_reg_prog_act_silabo) {
        /*  echo "<script>
			alert('Error al Generar las programacion para el silabo');
			window.history.back();
		</script>
		";*/
        return 0;
    }
    if (!$ejec_reg_sesion) {
        /*echo "<script>
			alert('Error al Registrar las sesiones de aprendizaje');
			window.history.back();
		</script>
		";*/
        return 0;
    }
    if (!$ejec_reg_momentos_sesion) {
        /*echo "<script>
			        alert('Error al Registrar Momentos de la sesion M1');
			        window.history.back();
		        </script>
		        ";*/
        return 0;
    }
    if (!$ejec_reg_act_eva) {
        /* echo "<script>
			        alert('Error al Registrar Momentos de la sesion M2');
			        window.history.back();
		        </script>
		        ";*/
        return 0;
    }

    /*echo "<script>
                
                window.location= '../programacion.php'
    			</script>";*/
    return 1;
}


//>>>>>>>>>>>>>>>>>>>>> FIN DE FUNCION PARA REALIZAR PROGRAMACION DE UNIDAD DIDACTICA <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<




//>>>>>>>>>>>>>>>>>>>>> INICIO DE FUNCION PARA VER LA CANTIDAD DE CRITERIOS DE EVALUACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<
function buscar_cantidad_criterios_programacion($conexion, $id_prog, $det_evaluacion, $nro_calif)
{
    $b_det_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
    if (mysqli_num_rows($b_det_mat) < 1) {
        // si no hay ningun matriculado regresamos 5 como los criterios de evaluacion
        return 1;
    }
    $r_b_det_mat = mysqli_fetch_array($b_det_mat);
    
    $b_califacion = buscarCalificacionByIdDetalleMatricula_nro($conexion, $r_b_det_mat['id'], $nro_calif);
    $r_b_calificacion = mysqli_fetch_array($b_califacion);

    $b_evaluacion = buscarEvaluacionByIdCalificacion_detalle($conexion, $r_b_calificacion['id'], $det_evaluacion);
    $r_b_evaluacion = mysqli_fetch_array($b_evaluacion);

    $b_crit_evaluacion = buscarCriterioEvaluacionByEvaluacion($conexion, $r_b_evaluacion['id']);
    $cant_crit = mysqli_num_rows($b_crit_evaluacion);
    if ($cant_crit<1) {
        return 1;
    }else {
        return $cant_crit;
    }
    
}
//>>>>>>>>>>>>>>>>>>>>> FIN DE FUNCION PARA VER LA CANTIDAD DE CRITERIOS DE EVALUACION <<<<<<<<<<<<<<<<<<<<<<<<<<<<<<




/// FIN DE FUNCIONES DE CALIFICACIONES ------------------------------------------------------------
function determinarEstadoAdmision($fechaInicio, $fechaFin) {
    
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
        return "Terminado";
    }
}

//FUNCIONES PARA CODIGO DE POSTULACION DE PROCESO DE ADMISION
function generarCodigoAdmision($dni, $numero) {
   // Obtener los últimos 2 dígitos del año actual
   $año = substr(date("Y"), -2);
    
   // Obtener los últimos 2 dígitos del DNI
   $ultimos_dni = substr($dni, -2);
   
   // Completar el número de 1, 2 o 3 dígitos con ceros por delante para tener 4 dígitos
   $numero_completo = str_pad($numero, 4, "0", STR_PAD_LEFT);
   
   // Generar el código numérico concatenando las partes
   $codigo = $año . $ultimos_dni . $numero_completo;
   
   return $codigo;
}

function determinarPorcentajeAvence($conexion, $id_sesion_aprendizaje){
    // Seleccionar los campos de la tabla sesion_aprendizaje
    $query_sesion = "SELECT `tipo_actividad`,
                            `tipo_sesion`,
                            `fecha_desarrollo`,
                            `logro_sesion`,
                            `bibliografia_obligatoria_docente`,
                            `bibliografia_opcional_docente`,
                            `bibliografia_obligatoria_estudiantes`,
                            `bibliografia_opcional_estudiante`,
                            `anexos`,
                            `ta_sesion`
                     FROM `sesion_aprendizaje`
                     WHERE `id` = ?";
                     
    $stmt_sesion = $conexion->prepare($query_sesion);
    $stmt_sesion->bind_param('i', $id_sesion_aprendizaje);
    $stmt_sesion->execute();
    $result_sesion = $stmt_sesion->get_result()->fetch_assoc();
    
    // Seleccionar los campos de la tabla momentos_sesion_aprendizaje
    $query_momentos = "SELECT `momento`, `estrategia`, `actividad`, `recursos`, `tiempo`
                       FROM `momentos_sesion_aprendizaje`
                       WHERE `id_sesion_aprendizaje` = ?";
                       
    $stmt_momentos = $conexion->prepare($query_momentos);
    $stmt_momentos->bind_param('i', $id_sesion_aprendizaje);
    $stmt_momentos->execute();
    $result_momentos = $stmt_momentos->get_result()->fetch_assoc();
    
    // Seleccionar los campos de la tabla actividad_evaluacion_sesion_aprendizaje
    $query_actividades = "SELECT `tecnica`, `instrumentos`, `peso`, `momento`
                          FROM `actividad_evaluacion_sesion_aprendizaje`
                          WHERE `id_sesion_aprendizaje` = ?";
                          
    $stmt_actividades = $conexion->prepare($query_actividades);
    $stmt_actividades->bind_param('i', $id_sesion_aprendizaje);
    $stmt_actividades->execute();
    $result_actividades = $stmt_actividades->get_result()->fetch_assoc();
    
    // Contar la cantidad total de campos y los campos no vacíos
    $totalCampos = 0;
    $camposCompletados = 0;

    foreach (array_merge($result_sesion, $result_momentos, $result_actividades) as $campo) {
        $totalCampos++;
        if (!is_null($campo) && $campo !== " ") {
            $camposCompletados++;
        }
    }
        
    // Calcular el porcentaje de campos completados
    $porcentajeCompletado = round(($camposCompletados / $totalCampos) * 100);    
    return intval($porcentajeCompletado);
}

function obtenerColor($porcentaje) {
    if ($porcentaje >= 0 && $porcentaje <= 33) {
        return 'danger';
    } elseif ($porcentaje >= 34 && $porcentaje <= 90) {
        return 'warning';
    } elseif ($porcentaje >= 91 && $porcentaje <= 100) {
        return 'success';
    } else {
        return ' '; // En caso de que el porcentaje esté fuera del rango esperado
    }
}

function convertirFormatoFecha($fecha) {
    if (strpos($fecha, ' ') !== false) {
        // La fecha contiene hora, la separamos
        $partesFechaHora = explode(' ', $fecha); // [0] = fecha, [1] = hora
        $fechaPartes = explode('-', $partesFechaHora[0]); // [0] = año, [1] = mes, [2] = día
        $horaPartes = explode(':', $partesFechaHora[1]); // [0] = horas, [1] = minutos, [2] = segundos

        // Retornar solo el año y la hora en formato "YYYY HH:MM"
        return $fechaPartes[2] . '/' . $fechaPartes[1] . '/' . $fechaPartes[0] . ' '. $partesFechaHora[1];
    } else {
        // La fecha no contiene hora, solo extraemos el año
        $partes = explode('-', $fecha);
        return $partes[2] . '/' . $partes[1] . '/' . $partes[0];
    }
        
}

function convertirHora($hora24) {
    // Verificar que el formato sea correcto
    if (!preg_match("/^(2[0-3]|[01][0-9]):[0-5][0-9]:[0-5][0-9]$/", $hora24)) {
        return "Formato inválido";
    }

    // Separar la hora, minutos y segundos
    list($hora, $minutos, $segundos) = explode(":", $hora24);

    // Convertir hora de 24 a 12 horas
    $periodo = (int)$hora < 12 ? "A.M." : "P.M.";
    $hora12 = (int)$hora % 12;
    $hora12 = $hora12 == 0 ? 12 : $hora12; // Si es 0, se considera 12 en formato de 12 horas

    // Formatear la hora en el formato deseado
    return sprintf("%02d:%02d %s", $hora12, $minutos, $periodo);
}

function encryptText($plainText) {
    // $config = include __DIR__ . '/../config.php'; // Asegúrate de que el path es correcto
    // $key = $config['ENCRYPTION_KEY']; 
    
    // $method = 'AES-256-CBC';
    // $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length($method));
    // $encrypted = openssl_encrypt($plainText, $method, $key, 0, $iv);
    // return base64_encode($iv . $encrypted);
    return $plainText;
}

function decryptText($encryptedText) {
    // $config = include __DIR__ . '/../config.php';
    // $key = $config['ENCRYPTION_KEY']; 

    // $method = 'AES-256-CBC';
    // $data = base64_decode($encryptedText);
    // $iv_length = openssl_cipher_iv_length($method);
    // $iv = substr($data, 0, $iv_length);
    // $encrypted = substr($data, $iv_length);
    // return openssl_decrypt($encrypted, $method, $key, 0, $iv);
    return $encryptedText;
}