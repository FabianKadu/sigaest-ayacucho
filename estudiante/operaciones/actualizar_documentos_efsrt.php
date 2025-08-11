<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");
include '../include/verificar_sesion_estudiante.php';

if (!verificar_sesion($conexion)) {
    echo "<script>
                  alert('Error Usted no cuenta con permiso para acceder a esta página');
                  window.location.replace('index.php');
          </script>";
} else {
    $dni_est = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $id = $_POST['id'];
        // Obtener los datos del formulario
        // Procesar la carta de presentación
        $carta_file = $_FILES['carta_file'];
        $carta_extension = pathinfo($carta_file['name'], PATHINFO_EXTENSION);
        $carta_nombre = 'CP_' . $dni_est . '_' . $id . '.' . $carta_extension;
        $carta_destino = "../../documentos/efsrt/" . $carta_nombre;
        move_uploaded_file($carta_file['tmp_name'], $carta_destino);
        $carta_ruta = substr($carta_destino, 3);

        // Procesar el informe
        $informe_file = $_FILES['informe_file'];
        $informe_extension = pathinfo($informe_file['name'], PATHINFO_EXTENSION);
        $informe_nombre = 'IN_' . $dni_est . '_' . $id . '.' . $informe_extension;
        $informe_destino = "../../documentos/efsrt/" . $informe_nombre;
        move_uploaded_file($informe_file['tmp_name'], $informe_destino);
        $informe_ruta = substr($informe_destino, 3);

        // Actualizar la base de datos
        $sql_update = "UPDATE efsrt SET carta_presentacion = '$carta_ruta', informe = '$informe_ruta', estado=2 WHERE id = '$id'";
        $res = mysqli_query($conexion, $sql_update);

        if ($res) {
            echo "<script>
        alert('Documentos actualizados correctamente');
        window.location.href = '../efsrt.php';
    </script>";
        } else {
            echo "<script>
        alert('Error al actualizar documentos');
        window.location.href = '../efsrt.php';
    </script>";
        }
    }
}
