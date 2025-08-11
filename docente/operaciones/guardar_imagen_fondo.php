<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
    echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
} else {
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['image'])) {
        $file = $_FILES['image'];
        $target_dir = "../../img/";
        $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $new_filename = "fondo." . $imageFileType;
        $target_file = $target_dir . $new_filename;

        // Verificar si se pudo mover el archivo
        if (move_uploaded_file($file['tmp_name'], $target_file)) {
            // Ruta relativa para guardar en la base de datos
            $relative_path = "../img/" . $new_filename;

            // Preparar la sentencia SQL para actualizar la imagen en la tabla 'sistema'
            $sql = "UPDATE sistema SET img_fondo = ? WHERE id = 1"; // Asumiendo que id = 1 es el registro a actualizar

            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("s", $relative_path);

            if ($stmt->execute()) {
                echo "<script>
					window.location = '../secretaria.php';
					</script>";
            } else {
                echo "<script>
                    alert('Hubo un error al guardar la ruta del archivo en la base de datos. Cargue otra imagen.');
					window.location= '../secretaria.php'
					</script>";
            }

            $stmt->close();
        } else {
            echo "<script>
                    alert('Hubo un error al mover la imagen al directorio destino. Pruebe con otro formato de imagen.');
					window.location= '../secretaria.php'
					</script>";
        }
    } else {
        echo "<script>
                    alert('Hubo un error al mover la imagen al directorio destino. Pruebe con otro formato de imagen.');
					window.location= '../secretaria.php'
					</script>";
    }

    $conexion->close();
}
