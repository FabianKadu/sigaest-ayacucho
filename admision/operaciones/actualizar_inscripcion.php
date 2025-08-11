<?php
session_start(); 
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";

$detalle_postulacion = $_POST['id_detalle_postulante'];
$res_documentos = bucarDocumentosObservadosPorDetallePostulacion($conexion, $detalle_postulacion);
$id_postulante = $_SESSION['Id_Postulante'];
$res_postulante = buscarPostulantePorId($conexion, $id_postulante);
$postulante = mysqli_fetch_array($res_postulante);
$dni = $postulante['Dni'];

if (!isset($_SESSION['autenticado']) || $_SESSION['autenticado'] !== true) {
    echo "<script>
            alert('No tiene acceso a esta página. Por favor, inicie sesión.');
            window.location.href = '../admision/login_postulante/index.php';
          </script>";
    exit; 
}

if(!isset($_SESSION['Id_Postulante'])) {
    echo "<script>
    alert('No tiene acceso a esta página. Por favor, inicie sesión.');
    window.location.href = '../admision/login_postulante/index.php';
    </script>";
    exit; 
  
  } else {
    $carpetaDestino = "../utils/documentos/";
    while ($row = mysqli_fetch_array($res_documentos)){ 
        if($row['Titulo'] === "Fotografías"){
    
            $id = $row['Id'];

            $rutaTemporalFotografia = $_FILES['fotografia']["tmp_name"];
            $nombreArchivo = $_FILES['fotografia']["name"];
    
            // Obtener la extensión del archivo
            $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
            // Nuevo nombre de archivo
            $nuevoNombre = $dni .'.'. $extension;
            // Ruta de destino con el nuevo nombre de archivo
            $fotografia = substr($carpetaDestino . $nuevoNombre,3);

            move_uploaded_file($rutaTemporalFotografia, $carpetaDestino . $nuevoNombre);

            $insertar = "UPDATE `postulante` SET `Fotografia`='$fotografia' WHERE Id = $id_postulante";
            $ejecutar_insetar = mysqli_query($conexion, $insertar);

            $update = "UPDATE `documento_postulacion` SET `Observado`='0' WHERE Id_Detalle_Postulacion = $detalle_postulacion AND Id_Requisito = $id";
            $ejecutar_update = mysqli_query($conexion, $update);

            
    
        }else{
            $id = $row['Id'];
            $nombreArchivo = $row['Id'].$dni.".pdf";
            $rutaTemporal = $_FILES[$id]["tmp_name"];
            $rutaFinal = substr($carpetaDestino . $nombreArchivo,3);
            
            move_uploaded_file($rutaTemporal, $carpetaDestino . $nombreArchivo);

            $update = "UPDATE `documento_postulacion` SET `Observado`='0' WHERE Id_Detalle_Postulacion = $detalle_postulacion AND Id_Requisito = $id";
            $ejecutar_update = mysqli_query($conexion, $update);
        }
    }

    $insertar = "UPDATE `detalle_postulacion` SET `Estado`='4' WHERE Id = $detalle_postulacion";
	$ejecutar_insetar = mysqli_query($conexion, $insertar);
    if($ejecutar_insetar){
        echo "<script>
                alert('Actualización Existosa');
                window.location= '../informacion_postulante.php'
    			</script>";
    }
    else{
        echo "<script>
			alert('Error al registrar');
			window.history.back();
				</script>
			";
    }
}
