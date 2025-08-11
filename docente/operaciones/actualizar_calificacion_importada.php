<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
} else {

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id_ud = $_POST['id_ud'];
        $calificacion = $_POST['calificacion'];
        $dni_estudiante = $_POST['dni'];  // Recibir el DNI del estudiante

        // Obtener la información completa de la unidad didáctica usando la función buscarUdById()
        $res_unidad_didactica = buscarUdById($conexion, $id_ud);
        if (mysqli_num_rows($res_unidad_didactica) > 0) {
            $unidad_didactica = mysqli_fetch_array($res_unidad_didactica);

            $descripcion = $unidad_didactica['descripcion'];
            $id_programa = $unidad_didactica['id_programa_estudio'];
            $creditos = $unidad_didactica['creditos'];
            $periodo = " ";

        } else {
            echo "No se encontró la unidad didáctica.";
            exit;
        }

        // Verificar si el registro proviene de notashistoricas
        $res_calificacion = getNotasImportadaByDniAndIdUd($conexion, $dni_estudiante, $id_ud);
        if (mysqli_num_rows($res_calificacion) == 1) {
            $query = "UPDATE notas_antiguo SET calificacion = ? WHERE dni = ? AND id_unidad_didactica = ?";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "isi", $calificacion, $dni_estudiante, $id_ud);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Calificación actualizada";
            } else {
                echo "Error al actualizar";
            }
        }else{
            // Si no existe el registro, lo insertamos
            $query = "INSERT INTO notas_antiguo (dni, id_programa, unidad_didactica, cantidad_creditos, calificacion, semestre_academico, periodo, id_unidad_didactica, date_create) 
                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW())";
            $stmt = mysqli_prepare($conexion, $query);
            mysqli_stmt_bind_param($stmt, "sisdisis", $dni_estudiante, $id_programa, $descripcion, $creditos, $calificacion, $periodo, $periodo, $id_ud);
            mysqli_stmt_execute($stmt);

            if (mysqli_stmt_affected_rows($stmt) > 0) {
                echo "Nuevo registro creado con éxito";
            } else {
                echo "Error al crear el nuevo registro";
            }
        }
    }
    mysqli_close($conexion);
}
