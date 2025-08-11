<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../include/consultas.php");
include("../include/verificar_sesion_empresa.php");
include("../operaciones/sesiones.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('login/');
    		</script>";
} else {

    $id = $_POST['id'];

    $sql = "UPDATE oferta_laboral SET estado = 'ARCHIVADO' WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            alert('Se ha archivado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatoria.php');
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar en base de datos!');
            window.history.back();
            </script>";
        }
    // Cerrar la conexión a la base de datos
    $conexion->close();
}

?>