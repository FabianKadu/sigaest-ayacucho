<?php
// link de github de libreria utilizada
// https://github.com/mk-j/PHP_XLSXWriter
include_once("../PHP_XLSXWriter/xlsxwriter.class.php");

include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");
include("include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
    $r_b_docente = mysqli_fetch_array($b_docente);

    $id_prog = $_POST['data'];
    $b_prog = buscarProgramacionById($conexion, $id_prog);
    $res_b_prog = mysqli_fetch_array($b_prog);
    if ($r_b_docente['id_cargo'] == 2 || ($res_b_prog['id_docente'] == $id_docente_sesion || $r_b_docente['id_cargo'] == 4)) {
        $mostrar_archivo = 1;
    } else {
        $mostrar_archivo = 0;
    }

    if (!($mostrar_archivo)) {
        //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
        //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
        echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.close();
		</script>
	";
    } else {
        /*header ("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
        header ("Content-Disposition: attachment; filename=plantilla.xls");*/

        $b_ud = buscarUdById($conexion, $res_b_prog['id_unidad_didactica']);
        $r_b_ud = mysqli_fetch_array($b_ud);
        $titulo_archivo = "Reporte_" . $r_b_ud['descripcion'] . "_" . date("d") . "_" . date("m") . "_" . date("Y");

        //generamos excel
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        error_reporting(E_ALL & ~E_NOTICE);

        $filename = $titulo_archivo . ".xlsx";
        header('Content-disposition: attachment; filename="' . XLSXWriter::sanitize_filename($filename) . '"');
        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        $writer = new XLSXWriter();

        $styles1 = array('font' => 'Calibri', 'font-size' => 11, 'font-style' => 'bold', 'fill' => '#eee', 'halign' => 'center', 'border' => 'left,right,top,bottom');

        $header = array(
            'NRO' => 'NRO', //text
            'CÓDIGO ALUMNO' => 'CÓDIGO ALUMNO', //text
            'ALUMNO' => 'ALUMNO',
            'NOTA' => 'NOTA',
        );

        //imprime encabezado
        $writer->writeSheetRow('Plantilla', $header, $styles1);



        $b_det_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
        $ord = 1;
        while ($r_b_det_mat = mysqli_fetch_array($b_det_mat)) {
            $id_mat = $r_b_det_mat['id_matricula'];
            $b_mat = buscarMatriculaById($conexion, $id_mat);
            $r_b_mat = mysqli_fetch_array($b_mat);
            $id_est = $r_b_mat['id_estudiante'];
            $b_est = buscarEstudianteById($conexion, $id_est);
            $r_b_est = mysqli_fetch_array($b_est);

            $b_calif = buscarCalificacionByIdDetalleMatricula($conexion, $r_b_det_mat['id']);
            $suma_calificacion = 0;
            $cont_calif = 0;
            while ($r_b_calif = mysqli_fetch_array($b_calif)) {

                $id_calificacion = $r_b_calif['id'];
                //buscamos las evaluaciones
                $suma_evaluacion = calc_evaluacion($conexion, $id_calificacion);
                $suma_calificacion += $suma_evaluacion;
                if ($suma_evaluacion > 0) {
                    $cont_calif += 1;
                }
            }

            if ($cont_calif > 0) {
                $suma_calificacion = round($suma_calificacion / $cont_calif);
            } else {
                $suma_calificacion = round($suma_calificacion);
            }
            if ($suma_calificacion != 0) {
                $calificacion_final = round($suma_calificacion);
            } else {
                $calificacion_final = "";
            }
            if ($r_b_det_mat['recuperacion'] != '') {
                $calificacion_final = $r_b_det_mat['recuperacion'];
            }
            if ($r_b_mat['licencia'] != "") {
                $calificacion_final = "Licencia";
            }

            //imprime contenido
            $writer->writeSheetRow('Plantilla', $rowdata = array($ord, $r_b_est['dni'], $r_b_est['apellidos_nombres'], $calificacion_final), $styles9);

            $ord += 1;
        }

        $writer->writeToStdOut();

        exit(0);
    }
}