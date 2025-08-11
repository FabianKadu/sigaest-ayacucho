<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador_secretaria.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('../index.php');
              </script>";
} else {


    if (!isset($_POST['id_prog'])) {
        echo "<script>
            alert('Sesion Caducada y/o Acceso Denegado');
			window.history.back();
		</script>
	";
    } else {
        $id_prog = $_POST['id_prog'];

        $b_prog = buscarProgramacionById($conexion, $id_prog);
        $res_b_prog = mysqli_fetch_array($b_prog);
        $b_periodo_acad = buscarPeriodoAcadById($conexion, $res_b_prog['id_periodo_acad']);
        $r_per_acad = mysqli_fetch_array($b_periodo_acad);
        $fecha_actual = strtotime(date("d-m-Y"));
        $fecha_fin_per = strtotime($r_per_acad['fecha_fin']);
        if ($fecha_actual <= $fecha_fin_per) {



            if (!isset($_POST['mostrar_calif_final'])) {
                $mostrar_calif_final = 0;
            } else {
                $mostrar_calif_final = 1;
            }
            $b_detalle_mat = buscarDetalleMatriculaByIdProgramacion($conexion, $id_prog);
            while ($r_b_det_mat = mysqli_fetch_array($b_detalle_mat)) {
                $id_det_mat = $r_b_det_mat['id'];
                $c_up_ord = "UPDATE detalle_matricula_unidad_didactica SET mostrar_calificacion	='$mostrar_calif_final' WHERE id='$id_det_mat'";
                mysqli_query($conexion, $c_up_ord);

                $b_calificacion = buscarCalificacionByIdDetalleMatricula($conexion, $r_b_det_mat['id']);
                $count = 1;
                while ($r_b_calificacion = mysqli_fetch_array($b_calificacion)) {
                    $id = $r_b_calificacion['id'];
                    if (!isset($_POST['mostrar_calif_' . $count])) {
                        $mostrar_calif = 0;
                    } else {
                        $mostrar_calif = 1;
                    }
                    $consulta = "UPDATE calificaciones SET mostrar_calificacion='$mostrar_calif' WHERE id='$id'";
                    mysqli_query($conexion, $consulta);
                    $count += 1;
                    /*$dato = $_POST['ponderad_' . $count];
            if (is_numeric($dato)) {
                $consulta = "UPDATE calificaciones SET ponderado='$dato' WHERE id='$id'";
                $ejec_consulta = mysqli_query($conexion, $consulta);
                $count += 1;
            }*/
                }
                if (isset($_POST['recuperacion_' . $r_b_det_mat['id']])) {
                    $recuperacion = $_POST['recuperacion_' . $r_b_det_mat['id']];


                    $act_recuperacion = "UPDATE detalle_matricula_unidad_didactica SET recuperacion='$recuperacion' WHERE id='$id_det_mat'";
                    $ejec_act_recuperacion = mysqli_query($conexion, $act_recuperacion);
                }
            }
            echo "<script>
			    window.location= '../calificaciones.php?id=" . $id_prog . "';
		        </script>
	            ";
        }else {
            echo "<script>
                alert('Periodo Finalizado, No puede Realizar Cambios');
			    window.location= '../calificaciones.php?id=" . $id_prog . "';
		        </script>
	            ";
        }
    }
}
