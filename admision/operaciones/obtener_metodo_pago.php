<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$id = $_POST['id_metodo'];


	$ejec_cons = buscarTodosMetodosPagoPorId($conexion, $id);

	while ($metodo = mysqli_fetch_array($ejec_cons)) {
		$banco = "";
		$cuenta = "";
		$cci = "";
		if($metodo['Banco'] !== "No requiere") $banco = 'Entidad: ' .$metodo['Banco'];
		if($metodo['Cuenta'] !== "No requiere" ) $cuenta = 'Cuenta: '.$metodo['Cuenta'];
		if($metodo['CCI'] !== "No requiere") $cci = 'CCI: '.$metodo['CCI'];
		$cadena= '
		<div class="custom-container">
		<div class="custom-card">
			<p class="custom-card-title">'.$metodo['Metodo'] .'<br> '. $banco .'<br> '. $cuenta  .' <br>'. $cci .'</p>
			<div class="custom-card-content">
				<p class="custom-card-text">'.$metodo['Titular'].'</p>
			</div>
		</div>
	</div>';
	}

	echo $cadena;

?>