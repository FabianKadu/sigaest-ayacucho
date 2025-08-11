<?php
$host = "localhost";
$db = "sigaest";
$user_db = "root";
$pass_db = "";
$mysqldumpPath = 'C:\\xampp\\mysql\\bin\\mysqldump.exe'; // Ruta a mysqldump en XAMPP Local
// $mysqldumpPath = '/usr/bin/mysqldump'; // Ruta común a mysqldump en cPanel

$conexion = mysqli_connect($host, $user_db, $pass_db, $db);

if ($conexion) {
	date_default_timezone_set("America/Lima");
} else {
	echo "error de conexion a la base de datos";
}

$conexion->set_charset("utf8");
