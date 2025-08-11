<?php
function verificar_sesion($conexion){
	session_start();
	if (isset($_SESSION['id_sesion_est'])) {

		$sesion_activa = sesion_si_activa_estudiante($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);
		if (!$sesion_activa) {
			echo "<script>
                alert('La Sesion Caducó, Inicie Sesión');
                window.location.replace('../../include/cerrar_sesion_estudiante.php');
    		</script>";
		}else {
				return 1;
		}
		
	}else {
		return 0;
	}
}
?>