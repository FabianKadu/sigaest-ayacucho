<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_caja.php");
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
  
$id = $_POST['id'];
$motivo = $_POST['motivo'];

	$insertar = "INSERT INTO `egresos_anulados`(`id_egreso`, `responsable`, `fecha_anulacion`, `motivo`) 
    VALUES ('$id','$usuario', CURRENT_TIMESTAMP() ,'$motivo')";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
	if ($ejecutar_insetar) {
        $consulta = "UPDATE `egresos` SET `estado`='ANULADO' WHERE id = $id";
        mysqli_query($conexion, $consulta);
			echo "<script>
                alert('Actualización Existosa');
                window.location= '../movimientos.php'
    			</script>";
	}else{
		echo "<script>
			alert('Error al registrar');
			window.history.back();
				</script>
			";
	};

mysqli_close($conexion);

}