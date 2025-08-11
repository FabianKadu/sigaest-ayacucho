<?php
// Add session start and check session variables
session_start();
if (!isset($_SESSION['id_sesion']) || !isset($_SESSION['token'])) {
    die("Acceso no autorizado.");
}

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");

$id_est = $_POST['id_est'];
$id_carrera = $_POST['carrera_m'];
$id_modulo = $_POST['modulo'];
$lugar = $_POST['lugar'];
$cargo = $_POST['cargo'];
$responsable = $_POST['responsable'];
$inicio = $_POST['inicio'];
$id_tutor = $_POST['tutor'];

// Insertar los datos en la base de datos
$sql = "INSERT INTO efsrt ( id_estudiante, id_programa, id_modulo, lugar, cargo_responsable, responsable, fecha_inicio, id_docente) 
        VALUES ( '$id_est', '$id_carrera', '$id_modulo', '$lugar', '$cargo', '$responsable', '$inicio', '$id_tutor')";

$res = mysqli_query($conexion, $sql);

if ($res) {
    // Obtener el ID del EFSRT recién insertado
    $id_efsrt = mysqli_insert_id($conexion);

    // Obtener información del docente para la auditoría
    $id_docente_sesion = buscar_docente_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);
    if (!$id_docente_sesion) {
        die("Error: Sesión de docente no válida.");
    }
    $b_docente = buscarDocenteById($conexion, $id_docente_sesion);
    $r_b_docente = mysqli_fetch_array($b_docente);

    // Insertar registro de auditoría
    $queryAudit = "INSERT INTO efsrt_auditoria (
        id_efsrt,
        id_usuario,
        nombre_usuario,
        accion
    ) VALUES (
        '$id_efsrt',
        '$id_docente_sesion',
        '" . mysqli_real_escape_string($conexion, $r_b_docente['apellidos_nombres']) . "',
        'Registro nuevo de EFSRT'
    )";

    $resultAudit = mysqli_query($conexion, $queryAudit);
    if (!$resultAudit) {
        error_log("Error en auditoría EFSRT: " . mysqli_error($conexion));
    }

    //TODO: Generar un PDF de presentación.
    echo "<script>
        alert('EFSRT registrado correctamente');
        window.location.href = '../apertura_efsrt.php';
    </script>";
} else {
    echo "<script>
        alert('Error al registrar EFSRT');
        window.location.href = '../apertura_efsrt.php';
    </script>";
}

mysqli_close($conexion);
