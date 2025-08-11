<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_administrador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    $id = $_POST['id'];

    $sql = "UPDATE oferta_laboral_propia SET estado = 'ARCHIVADO', fecha_estado = DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 HOUR) WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            alert('Se ha archivado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatorias.php');
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