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
  }else {

    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
	$usuario = buscarDocenteById($conexion, $id_docente_sesion);
	$usuario = mysqli_fetch_array($usuario);
	$usuario = $usuario['apellidos_nombres'];


    $obs = "Editado por ". $usuario;

    $id = $_POST['id'];
    $calificacion = $_POST['calificacion_final'];
    $semestre = $_POST['semestre_academico'];
    $unidad_didactica = $_POST['unidad_didactica'];
    
    $sql = "UPDATE notas_antiguo SET calificacion=$calificacion, semestre_academico ='$semestre', unidad_didactica = '$unidad_didactica' , observacion='$obs' WHERE id=$id";
    $ejec_consulta = mysqli_query($conexion, $sql);
    if ($ejec_consulta) {
        echo "<script>
            alert('Registro Actualizado de manera Correcta');

        </script>
    ";
    }else {
        echo "<script>
            alert('Error al Actualizar Registro, por favor contacte con el administrador');

        </script>
    ";
    }
    mysqli_close($conexion);
}

