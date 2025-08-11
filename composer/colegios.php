<?php
include "../include/busquedas.php";
include "../include/conexion.php";
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$inputFileName = "Colegios.xlsx";

    $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
    /**  Identify the type of $inputFileName  **/
    $inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);
    /**  Create a new Reader of the type that has been identified  **/
    $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
    /**  Load $inputFileName to a Spreadsheet Object  **/
    $spreadsheet = $reader->load($inputFileName);

    // Seleccionar la primera hoja del documento
    $hoja = $spreadsheet->getActiveSheet()->toArray();

    $calificaciones = [];

    foreach ($hoja as $row){
        $codigo = $row[0];
        $nombre = $row[1];//numero
        $region = $row[2];
        $provincia = $row[3];//numero
        $distrito = $row[4];//numero
        
                    $insertar = "INSERT INTO colegio (Codigo_Modular, Nombre, Departamento, Provincia, Distrito) 
                    VALUES ('$codigo','$nombre','$region', '$provincia', '$distrito')";
                    $ejecutar_insetar = mysqli_query($conexion, $insertar);
                
    }
    echo "<script>
        alert('Se ha registrado todos los archivos');
    </script>
    ";
    exit;