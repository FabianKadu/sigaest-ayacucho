<?php
function verificar_sesion($conexion){
	session_start();
	if (isset($_SESSION['id_sesion_emp'])) {

		$sesion_activa = sesion_si_activa_empresa($conexion, $_SESSION['id_sesion_emp'], $_SESSION['token']);
		if (!$sesion_activa) {
			echo "<script>
                alert('La Sesion Caducó, Inicie Sesión');
                window.location.replace('../empresa/login/cerrar_sesion_empresa.php');
    		</script>";
		}else {
				return 1;
		}
		
	}else {
		return 0;
	}
}
?>