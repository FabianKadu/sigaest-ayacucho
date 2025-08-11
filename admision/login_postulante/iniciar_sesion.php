<?php
session_start(); 
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";

$usuario = $_POST['dni'];
$pass = $_POST['codigo_unico'];

$ejec_busc = obtenerDatosPostulantePorDniCodigoUnico($conexion, $usuario, $pass);
$res_busc = mysqli_fetch_array($ejec_busc);

if ($usuario == $res_busc['Dni'] && $pass == $res_busc['Codigo_Unico']) {
    // Asignar valores a las variables de sesión
    $_SESSION['Id_Postulante'] = $res_busc['Id_Postulante']; 
    $_SESSION['autenticado'] = true;


    // Redireccionas a la página sin pasar el ID en la URL
    header('Location: ../informacion_postulante.php');
} else {
    echo "<script>
                alert('Usuario o Contraseña incorrecto');
                window.location.replace('index.php');
          </script>";
}

mysqli_close($conexion);
?>
