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
} else {

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre_empresa = $_POST["nombre_empresa"];
        $ubicacion = $_POST["ubicacion"];
        $celular = $_POST["celular"];
        $correo = $_POST["correo"];
        $ruc = $_POST["ruc"];

        $nombreArchivo = $_FILES['logo']['name'];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $tipoArchivo = $_FILES['logo']['type'];
        $tamañoArchivo = $_FILES['logo']['size'];
        $tempArchivo = $_FILES['logo']['tmp_name'];
        $errorArchivo = $_FILES['logo']['error'];

        $rutaDestino = "";

        if ($tamañoArchivo === 0) {
            // No se ha subido ningún archivo
            $rutaDestino = '../../empresa/files/img_defaul_empresa.png';
        }
        // Verificar si no hubo errores al subir la imagen
        if ($errorArchivo === 0) {
            // Mover la imagen de la ubicación temporal a la ubicación deseada
            $rutaDestino = '../../empresa/files/logo_' . $nombre_empresa . $extension;
            move_uploaded_file($tempArchivo, $rutaDestino);
        }

        $rutaDestino = substr($rutaDestino, 14);

        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `empresa`(`razon_social`, `ruc`, `correo_institucional`, `ubicacion`, `celular_telefono`, `estado`,`ruta_logo`)
            VALUES ('$nombre_empresa' ,'$ruc', '$correo', '$ubicacion', '$celular', 'Activo', '$rutaDestino')";
        $res = mysqli_query($conexion, $sql);

        if ($res) {
		
    			echo "<script>
                    window.location= '../empresas.php'
        			</script>";
    			
    	}else{
    		echo "<script>
    			alert('Error al registrar en la base de datos.');
    			window.history.back();
    				</script>
    			";
    	}
        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
}
