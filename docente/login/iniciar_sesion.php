<?php
include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");
$usuario = $_POST['usuario'];
$pass = $_POST['password'];

$ejec_busc = buscarDocenteByDni($conexion, $usuario);
$res_busc = mysqli_fetch_array($ejec_busc);
$cont = mysqli_num_rows($ejec_busc);

if (($cont == 1) && (password_verify($pass, $res_busc['password']))) {
	$id_docente = $res_busc['id'];
	$cargo_docente = $res_busc['id_cargo'];
	$buscar_periodo = buscarPresentePeriodoAcad($conexion);
	$res_b_periodo = mysqli_fetch_array($buscar_periodo);
	$presente_periodo = $res_b_periodo['id_periodo_acad'];
	if ($res_busc['activo'] != 1) {
		echo "<script>
                alert('Error, Usted no se encuentra activo en el sistema, Por Favor Contacte con el Administrador');
                window.location.replace('../login/');
    		</script>";
	} else {
		session_start();
		$llave = generar_llave();
		if ($cargo_docente != 0) {
			$id_sesion = reg_sesion($conexion, $id_docente, $llave);
			if ($id_sesion != 0) {
				$token = password_hash($llave, PASSWORD_DEFAULT);
				$_SESSION['id_sesion'] = $id_sesion;
				$_SESSION['periodo'] = $presente_periodo;
				$_SESSION['token'] = $token;
				echo "<script> window.location.replace('../index.php'); </script>";
			} else {
				echo "<script>
                alert('Error al Iniciar Sesión. Intente Nuevamente');
    		</script>";
			}
		} else {
			echo "<script>
                alert('Error en cargo, contacte administrador');
				window.location.replace('../login/');
    		</script>";
		}
	}
} else {
	echo "<script>
                alert('Usuario o Contraseña incorrecto');
				window.location.replace('../login/');
    		</script>";
}
mysqli_close($conexion);
