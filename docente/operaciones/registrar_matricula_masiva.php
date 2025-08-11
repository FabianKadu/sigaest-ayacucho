<?php

try {
    include '../include/verificar_sesion_secretaria_operaciones.php';
    include "../../include/conexion.php";
    include "../../include/busquedas.php";
include "../../include/funciones.php";
} catch (PDOException $e) {
    echo "<script>alert('error al conectar con la base de datos');</script>";
}

$d1 = $_POST['d1'];
$d2 = $_POST['d2'];
$d3 = $_POST['d3'];
$d4 = $_POST['d4'];


$fecha = "2020-03-11 00:00:00";
$estado = "1";
return "<script>alert('error al conectar con la base de datos');</script>";
echo $d1." - ".$d2." - ".$d3." - ".$d4;

