<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");
include '../include/verificar_sesion_estudiante.php';
include("../../empresa/include/consultas.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('index.php');
          </script>";
} else {

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id_estudiante = $_POST['id_estudiante'] ?? null;
        $id_ajuste_matricula = $_POST['id_ajuste_matricula'] ?? null;
        $cursos_seleccionados = $_POST['cursos-seleccionados'] ?? '';
        $upload_dir = "../../documentos/pagos/";

        // Validaciones
        if (!$id_estudiante || !$id_ajuste_matricula || empty($cursos_seleccionados)) {
            die("Error: Datos incompletos.");
        }

        // Validar y procesar el archivo
        if (!isset($_FILES['file_pago']) || $_FILES['file_pago']['error'] !== UPLOAD_ERR_OK) {
            die("Error al subir el archivo.");
        }

        $file = $_FILES['file_pago'];
        $file_ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        if ($file_ext !== 'pdf') {
            die("Error: Solo se permiten archivos en formato PDF.");
        }

        // Crear el directorio si no existe
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }

        $file_name = "boucher_" . $id_estudiante . "_" . time() . ".pdf";
        $file_path = $upload_dir . $file_name;
        $file_name = "../documentos/pagos/" . $file_name;

        if (!move_uploaded_file($file['tmp_name'], $file_path)) {
            die("Error: No se pudo guardar el archivo.");
        }
        // Insertar la solicitud de matrícula
        // Verificar si el estudiante ya tiene una solicitud en este período de ajustes de matrícula
        $sql_check = "SELECT id FROM solicitud_matricula WHERE id_estudiante = $id_estudiante AND id_configuracion_matricula = $id_ajuste_matricula";
        $res_check = mysqli_query($conexion, $sql_check);

        if (mysqli_num_rows($res_check) > 0) {
            // Actualizar la solicitud existente
            $row = mysqli_fetch_assoc($res_check);
            $id_solicitud = $row['id'];

            $sql_update = "UPDATE solicitud_matricula SET boucher = '$file_name', programas = '$cursos_seleccionados', estado = 1 WHERE id = $id_solicitud";
            $res_update = mysqli_query($conexion, $sql_update);

            if ($res_update) {
                echo "<script>
                alert('Su solicitud de matrícula ha sido actualizada correctamente.');
                window.location.replace('solicitar_matricula.php');  
                </script>";
            } else {
                echo "<script>
                alert('Error al actualizar la solicitud de matrícula.');
                window.location.replace('solicitar_matricula.php');  
                </script>";
            }
        } else {
            // Insertar nueva solicitud
            $sql_insert = "INSERT INTO solicitud_matricula (id_estudiante, id_configuracion_matricula, boucher, programas, estado)
                       VALUES ($id_estudiante, $id_ajuste_matricula, '$file_name', '$cursos_seleccionados', 1)";
            $res_insert = mysqli_query($conexion, $sql_insert);

            if ($res_insert) {
                echo "<script>
                alert('Su solicitud de matrícula ha sido registrada correctamente.');
                window.location.replace('solicitar_matricula.php');  
                </script>";
            } else {
                echo "<script>
                alert('Error al registrar la solicitud de matrícula.');
                window.location.replace('solicitar_matricula.php');  
                </script>";
            }
        }


        // Redirigir a una página de éxito

    } 
}
