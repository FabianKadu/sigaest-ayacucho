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

// Diccionario de iconos para redes sociales
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
    $id = $_POST['id'] ?? null;
    $nombre_red_social = $_POST['nombre_red_social'] ?? '';
    $nombre_usuario = $_POST['nombre_usuario'] ?? '';
    $link = $_POST['link'] ?? '';

    // Validaciones básicas
    if (empty($id) || empty($nombre_red_social) || empty($nombre_usuario) || empty($link)) {
        echo "<script>
                alert('Error: Todos los campos son obligatorios.');
                window.history.back();
              </script>";
        exit;
    }

    // Determinar la clase del icono a partir del diccionario
    $icono_clase = $iconos_redes_sociales[$nombre_red_social] ?? '';

    if (empty($icono_clase)) {
        echo "<script>
                alert('Error: Red social seleccionada no válida.');
                window.history.back();
              </script>";
        exit;
    }

    // Consulta de actualización utilizando sentencias preparadas
    $consulta = "UPDATE redes_sociales 
                 SET nombre_red_social = ?, nombre_usuario = ?, link = ?, icono_clase = ? 
                 WHERE id = ?";
    $stmt = $conexion->prepare($consulta);

    if ($stmt) {
        // Bind de parámetros
        $stmt->bind_param('ssssi', $nombre_red_social, $nombre_usuario, $link, $icono_clase, $id);

        // Ejecución de la consulta
        if ($stmt->execute()) {
            echo "<script>
                    alert('Red social actualizada exitosamente.');
                    window.location = '../redes_sociales.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error: No se pudo actualizar la red social. Por favor, intente nuevamente.');
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
