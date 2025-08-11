<?php
include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");
include("../include/consultas.php");
include("../operaciones/sesiones.php");

$usuario = $_POST['usuario'];
$pass = $_POST['password'];

$ejec_busc = buscarEmpresaUsuario($conexion, $usuario);
$res_busc = mysqli_fetch_array($ejec_busc);
$cont = mysqli_num_rows($ejec_busc);

if (($cont == 1) && (password_verify($pass, $res_busc['password'])) && $res_busc['estado'] == "Activo") {
	$id_empresa = $res_busc['id'];

	session_start();
	$llave = generar_llave();

	$id_sesion = reg_sesion_empresa($conexion, $id_empresa, $llave);
	if ($id_sesion != 0) {
		$token = password_hash($llave, PASSWORD_DEFAULT);
		$_SESSION['id_sesion_emp'] = $id_sesion;
		$_SESSION['id_emp'] = $id_empresa;
		$_SESSION['token'] = $token;
		
		echo "<script> window.location.replace('../index.php'); </script>";
	} else {
		echo "<script>
                alert('Error al Iniciar Sesión. Intente Nuevamente');
    		</script>";
	}
} else {
	echo "<script>
                alert('Usuario o Contraseña incorrecto');
				window.location.replace('../login/');
    		</script>";
}
mysqli_close($conexion);