<?php
session_start();
include("../include/conexion.php");
include("../include/busquedas.php");
include("../include/funciones.php");

$id_cargo = buscar_rol_sesion($conexion, $_SESSION['id_sesion'], $_SESSION['token']);


switch ($id_cargo) {
    case 1:
        header("location: docente.php");
        break;
    case 2:
        header("location: secretaria.php");
        break;
    case 3:
        header("location: jefe_unidad_academica.php");
        break;
    case 4:
        header("location: coordinador.php");
        break;
    case 5:
        header("location: docente.php");
        break;
    case 6:
        header("location: ../caja/index.php");
        break;
    case 7:
        header("location: ../bolsa_laboral/index.php");
        break;
    case 8:
        header("location: secretaria.php");
        break;
    case 9:
        header("location: secretaria.php");
        break;
    default:
        header("location: ../login/");
        break;
}
/*
if (isset($_SESSION['id_director'])) {
    header("location: direccion.php");
} elseif (isset($_SESSION['id_secretario'])) {
    header("location: secretaria.php");
} elseif (isset($_SESSION['id_coordinador_academico'])) {
    header("location: unidad_academica.php");
} elseif (isset($_SESSION['id_jefe_area'])) {
    header("location: coordinador.php");
} elseif (isset($_SESSION['id_docente'])) {
    header("location: docente.php");
} else {
    header("location: login/");
}
*/