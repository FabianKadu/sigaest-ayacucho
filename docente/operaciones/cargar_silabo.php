<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_docente_coordinador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
}else {
    if(isset($_FILES['documento'])) {
        $file = $_FILES['documento'];
        $id_silabo = $_POST['id_silabo'];

        // Verificar que el archivo es un PDF
        $fileType = pathinfo($file['name'], PATHINFO_EXTENSION);
        if($fileType != 'pdf') {
            echo "Error: El archivo debe ser un PDF.";
            exit;
        }
    
        // Verificar el tamaño del archivo (10 MB)
        if($file['size'] > 10 * 1024 * 1024) {
            echo "Error: El archivo es demasiado grande. Debe ser menor a 10 MB.";
            exit;
        }
    
        // Ruta donde se guardará el archivo
        $targetDir = "../../documentos/silabos/";
    
        // Verificar si la carpeta silabos no existe y crearla
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
    
        $targetFile = $targetDir .'Silabo-'.$id_silabo.'pdf';
    
        // Mover el archivo al directorio de destino
        if(move_uploaded_file($file['tmp_name'], $targetFile)) {
            $documentodb = substr($targetFile,3);

            $actualizar = "UPDATE silabo SET documento='$documentodb' WHERE  id='$id_silabo'";
            $ejecutar = mysqli_query($conexion, $actualizar);
            if($ejecutar){
                echo "<script>
                    alert('El documento se ha cargado de manera exitosa!');
                    window.history.back();
                </script>
                ";
            }else{
                echo "<script>
                    alert('No se pudo guardar el documento!');
                    window.history.back();
                </script>
                ";
            }

        } else {
            echo "<script>
                    alert('No se pudo guardar el documento!');
                    window.history.back();
                </script>
                ";
        }
    } else {
        echo "<script>
                    alert('No se pudo guardar el documento!');
                    window.history.back();
                </script>
                ";
    }


    
  }