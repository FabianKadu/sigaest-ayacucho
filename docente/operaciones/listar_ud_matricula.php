<?php
include '../../include/conexion.php';
include "../../include/busquedas.php";
include "../../include/funciones.php";

$response = array();

if (isset($_POST['datos'])) {
    $arr_uds = $_POST['datos'];
    $cadena = '';
    $total_creditos = 0;

    foreach ($arr_uds as $id) {
        $ejec_cons = buscarProgramacionById($conexion, $id);
        $mostrar = mysqli_fetch_array($ejec_cons);
        $id_ud = $mostrar['id_unidad_didactica'];
        $busc_ud = buscarUdById($conexion, $id_ud);
        $res_ud = mysqli_fetch_array($busc_ud);
        $total_creditos += $res_ud['creditos'];
        $cadena .= '<div class="checkbox"><label><input type="checkbox" name="uds_matri" value="'.$id.'" onchange="gen_arr_mat();" checked>'.$res_ud['descripcion'].'</label></div>';
    }

    $response['cadena'] = $cadena;
    $response['total_creditos'] = $total_creditos . ' créditos a matricular';
} else {
    $response['error'] = "Aún no hay Unidades Didácticas Agregadas para la Matrícula";
}

// Enviar respuesta JSON
header('Content-Type: application/json');
echo json_encode($response);



?>