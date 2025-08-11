<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";


$proceso = $_POST['id_proceso'];
$modalidad = $_POST['id_modalidad'];


	$ejec_cons = buscarRequisitosGeneralesPorProceso($conexion, $proceso);
	$res_especiales = buscarRequisitosEspecificosPorProcesoModalidad($conexion, $proceso, $modalidad);

	$cadena = '';

	while ($req_generales = mysqli_fetch_array($ejec_cons)) {
		if($req_generales['Titulo'] !== 'Fotografías'){
			$cadena=$cadena.'
				<div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
					<label class="control-label">'. $req_generales['Titulo'] .' *:
					</label>
					<div class="">
						<input type="file" class="form-control" name="'. $req_generales['Id'] .'" required="required" onchange="loadRequisito(event)" accept=".pdf">
					</div>
				</div>
				<?php }?>';
	}}

	while ($req_especiales = mysqli_fetch_array($res_especiales)) {
		$cadena=$cadena.'
			<div class="form-group form-group col-md-6 col-sm-6 col-xs-12">
				<label class="control-label">'. $req_especiales['Titulo'] .' *:
				</label>
				<div class="">
					<input type="file" class="form-control" name="'. $req_especiales['Id'] .'" required="required" onchange="loadRequisito(event)" accept=".pdf">
				</div>
			</div>
			<?php }?>';
	}
	echo $cadena;

?>