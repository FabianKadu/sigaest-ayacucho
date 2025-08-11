<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");

// Verificar sesión
if (!verificar_sesion($conexion)) {
    echo "<script>
            alert('Error: Usted no cuenta con permiso para acceder a esta página');
            window.location.replace('../login/');
          </script>";
    exit;
}

function validarContraseña( $password_db, $password ){

    if(password_verify($password, $password_db)){
        return 1;
    }

    return 0;

}

// Validar datos del formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $password = $_POST['password'];
    $password_db = $_POST['password_db'];
    $id_periodo = $_POST['id_periodo'];

    if (!$id_periodo) {
        echo "<script>
                alert('Error: ID del estudiante no proporcionado.');
                window.history.back();
              </script>";
        exit;
    }

    if( validarContraseña($password_db, $password) ){
        $query = "UPDATE periodo_academico SET is_active = 0 WHERE id = ?";
        $stmt = $conexion->prepare($query);
        $stmt->bind_param("i", $id_periodo);

        if ($stmt->execute()) {
            echo "<script>
                    alert('Periodo académico cerrado correctamente.');
                    window.history.back();
                  </script>";
        } else {
            echo "<script>
                    alert('Error: No se pudo cerrar el periodo académico.');
                    window.history.back();
                  </script>";
        }
    } else {
        echo "<script>
                alert('Error: Contraseña incorrecta.');
                window.history.back();
              </script>";
    }    

    // Cerrar conexión
    $conexion->close();
} else {
    echo "<script>
            alert('Error: Solicitud no válida.');
            window.history.back();
          </script>";
}
?>