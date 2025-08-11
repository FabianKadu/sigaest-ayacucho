<?php
include "../../include/conexion.php";
include '../../include/busquedas.php';
include '../../include/funciones.php';

$id_estudiante = $_POST['id'];
$token = $_POST['token'];
$pass = $_POST['new_password'];

$pass_secure = password_hash($pass, PASSWORD_DEFAULT);

$b_estudiante = buscarEstudianteById($conexion, $id_estudiante);
$r_b_estudiante = mysqli_fetch_array($b_estudiante);
$validar = $r_b_estudiante['reset_password'];
$llave = $r_b_estudiante['token_password'];

if ($validar==1 && password_verify($llave, $token)) {
	//procedemos a actualizar el password utilizando el id de usuario
$update_pass = "UPDATE estudiante SET password='$pass_secure', reset_password=0, token_password=' ' WHERE id='$id_estudiante'";
$ejec_update_pass = mysqli_query($conexion, $update_pass);
if ($ejec_update_pass) {
	echo "<script>
			alert('Contraseña actualizado correctamente, Por favor Inicie Sesión');
			window.location= '../../include/cerrar_sesion_estudiante.php';
		</script>
	";
}else{
	echo "<script>
			alert('Error al actualizar contraseña, Intente de Nuevo');
			window.history.back();
		</script>
	";
}
}else {
	echo "<script>
			alert('Error, Link caducado');
			window.history.back();
		</script>
	";
}

?>