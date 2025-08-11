<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_administrador.php");

if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    $id = $_POST["id"];
    $nombre_empresa = $_POST["nombre_empresa"];
    $ruc = $_POST["ruc"];
    $ubicacion = $_POST["ubicacion"];
    $correo = $_POST["correo"];
    $celular = $_POST["celular"];

    $nombreArchivo = $_FILES['logo']['name'];
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    $tipoArchivo = $_FILES['logo']['type'];
    $tamañoArchivo = $_FILES['logo']['size'];
    $tempArchivo = $_FILES['logo']['tmp_name'];
    $errorArchivo = $_FILES['logo']['error'];

    $rutaDestino = "";
    $tieneLogo = false;

    $empresa = buscarEmpresaById($conexion, $id);
    $empresa = mysqli_fetch_array($empresa);
    $empresaLogo = $empresa['ruta_logo'];

    if($empresaLogo != "files/img_defaul_empresa.png"){
        $tieneLogo = true;
    }

    if($errorArchivo === 0) {
        $rutaDestino = '../../empresa/files/logo_' .$id.$extension;
        move_uploaded_file($tempArchivo, $rutaDestino);
        $tieneLogo = true;
    } else {
        if(!$tieneLogo){
            $rutaDestino = '../../empresa/files/img_defaul_empresa.png';
        }else{
            $rutaDestino = "../../empresa/".$empresaLogo;
        }
    }

    $rutaDestino = substr($rutaDestino,14);

    // Consulta para insertar los datos en la base de datos
    $sql = "UPDATE `empresa` SET `razon_social`='$nombre_empresa', `ruc`='$ruc',  `correo_institucional`='$correo',`ubicacion`='$ubicacion',`ruta_logo`='$rutaDestino',
    `celular_telefono`='$celular' WHERE id = $id";
    $res = mysqli_query($conexion, $sql);
    if ($res) {
        
        echo "<script>
        alert('Se ha actualizado a la empresa!!');
        window.location.replace('../empresas.php');
        </script>";
    } else {
        echo "<script>
        alert('Ops, Ocurrio un error al guardar!
        Recuerde no utilizar un RUC o el correo electronico ya registrados.');
        window.history.back();
        </script>";
    }
    // Cerrar la conexión a la base de datos
    $conexion->close();
}
?>
