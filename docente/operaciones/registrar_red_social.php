<?php
// Inclusión de archivos necesarios
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../include/verificar_sesion_secretaria.php";

// Verificación de sesión
if (!verificar_sesion($conexion)) {
    echo "<script>
            alert('Error: Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('../login/');
          </script>";
    exit;
}

$iconos_redes_sociales = [
    'Facebook' => 'fa-facebook',
    'WhatsApp' => 'fa-whatsapp',
    'YouTube' => 'fa-youtube',
    'Instagram' => 'fa-instagram',
    'TikTok' => 'fa-tiktok',
    'X' => 'fa-twitter',
    'LinkedIn' => 'fa-linkedin',
];

// Validación de datos POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre_red_social = $_POST['nombre_red_social'] ?? '';
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $link = $_POST['link'] ?? '';
    $icono_clase = $iconos_redes_sociales[$nombre_red_social] ?? '';

    // Validaciones básicas de los campos
    if (empty($nombre_red_social) || empty($link)) {
        echo "<script>
                alert('Error: Los campos con * son obligatorios.');
                window.history.back();
              </script>";
        exit;
    }

    // Inserción segura utilizando sentencias preparadas
    $sql = "INSERT INTO redes_sociales (nombre_red_social, nombre_usuario, link, icono_clase) 
            VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);

    if ($stmt) {
        // Bind de parámetros
        $stmt->bind_param('ssss', $nombre_red_social, $nombre_usuario, $link, $icono_clase);

        // Ejecución de la consulta
        if ($stmt->execute()) {
            echo "<script>
                    alert('Red social registrada exitosamente.');
                    window.location = '../redes_sociales.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: No se pudo registrar la red social. Por favor, intente nuevamente.');
                    window.history.back();
                  </script>";
        }

        // Cierre del statement
        $stmt->close();
    } else {
        echo "<script>
                alert('Error: No se pudo preparar la consulta.');
                window.history.back();
              </script>";
    }

    // Cierre de conexión
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Método de solicitud no permitido.');
            window.history.back();
          </script>";
    exit;
}
?>
