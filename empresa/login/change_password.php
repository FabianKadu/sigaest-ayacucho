<?php
include "../../include/conexion.php";
include '../../include/busquedas.php';
include '../include/consultas.php';
include '../../include/funciones.php';

$id_emp = $_POST['id'];
$token = $_POST['token'];
$pass = $_POST['new_password'];

$pass_secure = password_hash($pass, PASSWORD_DEFAULT);

$b_emp = buscarEmpresaById($conexion, $id_emp);
$r_b_emp = mysqli_fetch_array($b_emp);
$validar = $r_b_emp['reset_password'];
$llave = $r_b_emp['token_password'];

if ($validar==1 && password_verify($llave, $token)) {
	//procedemos a actualizar el password utilizando el id de usuario
$update_pass = "UPDATE empresa SET password ='$pass_secure', reset_password=0, token_password=' ' WHERE id='$id_emp'";
$ejec_update_pass = mysqli_query($conexion, $update_pass);
if ($ejec_update_pass) {
	echo "<script>
			alert('Contraseña actualizado correctamente, Por favor Inicie Sesión');
			window.location= 'cerrar_sesion_empresa.php';
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