<?php

    include("../../include/conexion.php");
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre_empresa = $_POST["nombre_empresa"];
        $ruc = $_POST["ruc"];
        $ubicacion = $_POST["ubicacion"];
        $contacto = $_POST["contacto"];
        $cargo = $_POST["cargo"];
        $correo = $_POST["correo"];
        $celular = $_POST["celular"];

        $nombreArchivo = $_FILES['logo']['name'];
        $tipoArchivo = $_FILES['logo']['type'];
        $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
        $tamañoArchivo = $_FILES['logo']['size'];
        $tempArchivo = $_FILES['logo']['tmp_name'];
        $errorArchivo = $_FILES['logo']['error'];

        $rutaDestino = "";

        if ($tamañoArchivo === 0) {
            // No se ha subido ningún archivo
            $rutaDestino = '../files/img_defaul_empresa.png';
        }
        // Verificar si no hubo errores al subir la imagen
        if($errorArchivo === 0) {
            // Mover la imagen de la ubicación temporal a la ubicación deseada
            $rutaDestino = '../files/' .$ruc.$extension;
            move_uploaded_file($tempArchivo, $rutaDestino);
        
        }
        
        $rutaDestino = substr($rutaDestino,3);

        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `empresa`(`razon_social`, `ruc`, `correo_institucional`, `ubicacion`, `contacto`, `cargo` , `celular_telefono`, `estado`, `usuario`, `ruta_logo`)
            VALUES ('$nombre_empresa' ,'$ruc', '$correo', '$ubicacion', '$contacto', '$cargo', '$celular', 'Por confirmar', '$correo', '$rutaDestino')";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            alert('Su registro ha sido exitoso! Se le enviará un correo una vez que se valide la información proporcionada.');
            window.location.replace('../contactar.php');
            </script>";
        } else {
            echo "<script>
            alert('No se puede registrar! Recuerde no registrar una empresa ya existente.');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
?>
