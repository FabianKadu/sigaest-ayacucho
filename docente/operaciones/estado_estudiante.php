<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_secretaria.php");

if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    $id_estudiante = $_GET["id"];

    $res_estudiante = buscarEstudianteById($conexion, $id_estudiante);
    $estudiante = mysqli_fetch_array($res_estudiante);
    if($estudiante['egresado'] == "NO"){
        $sql = "UPDATE `estudiante` SET `egresado`='SI' WHERE id = $id_estudiante";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            window.history.back();
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar!');
            window.history.back();
            </script>";
        }
    }else{
        $sql = "UPDATE `estudiante` SET `egresado`='NO' WHERE id = $id_estudiante";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            window.history.back();
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar!');
            window.history.back();
            </script>";
        }
    }
    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>
