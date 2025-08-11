<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";

$id_proceso_admision = $_POST['proceso'];
$dni = $_POST['dni'];

$cantidad = buscarDetallePostulantesByDNIProcesoAdmision($conexion, $id_proceso_admision, $dni);

if (intval($cantidad) === 0) {

    // POSTULANTE
    $apellido_paterno = $_POST['paterno'];
    $apellido_materno = $_POST['materno'];
    $nombres = $_POST['nombres'];
    $carrera = $_POST['carrera'];
    
    $rutaTemporalficha = $_FILES['ficha']["tmp_name"];
    $nombreArchivo = $_FILES['ficha']["name"];

    // Obtener la extensión del archivo
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);
    // Nuevo nombre de archivo
    $nuevoNombre = $dni . '.' . $extension;
    $carpetaDestino = "../../documentos/fichas_postulante/";
    // Ruta de destino con el nuevo nombre de archivo
    $ficha = substr($carpetaDestino . $nuevoNombre, 3);

    $genero = $_POST['genero'];
    $fecha_nacimiento = $_POST['fecha_nacimiento'];
    $correo = $_POST['correo'];
    $celular = $_POST['celular'];
    $direccion = $_POST['direccion'];

    $sql = "INSERT INTO `postulante`(`Dni`, `Apellido_Paterno`, `Apellido_Materno`, `Nombres`, 
    `Sexo`, `Correo`, `Celular`, `Fecha_Nacimiento`, `Domicilio_Actual`, `ficha_postulante`, `id_programa`,`id_proceso` ) 
    VALUES ('$dni','$apellido_paterno','$apellido_materno','$nombres','$genero','$correo',
    '$celular','$fecha_nacimiento','$direccion', '$ficha', '$carrera', '$id_proceso_admision')";
    $res = mysqli_query($conexion, $sql);

    if ($res) {

        // Mover el archivo de la ruta temporal al directorio destino
        move_uploaded_file($rutaTemporalficha, $carpetaDestino . $nuevoNombre);

        echo "<script>
            alert('Postulante registrado correctamente');
            window.location.href = '../../docente/registrar_postulante.php?id=". $id_proceso_admision ."';
        </script>
        ";

    } else {
        echo "<script>
            alert('Error al registrar postulante');
            window.location.href = '../../docente/registrar_postulante.php?id=". $id_proceso_admision ."';
        </script>
        ";
    }
}
?>