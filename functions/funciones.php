<?php

require '../phpqrcode/qrlib.php';

function generarQRBoleta($texto, $nombre_archivo){

    // Ruta donde deseas guardar la imagen en tu proyecto
    $rutaCodigoQR = '../img/QRCode/';
    $filename = $nombre_archivo.'.png';
    $rutaImagen = '../img/QRCode/'.$nombre_archivo.'.png';

    QRcode::png($texto,$rutaImagen,"M",10,3);

    return $rutaImagen;
}

function convertirNumeroALetra($numero) {
    $diccionario = [
        0 => 'Cero',
        1 => 'Uno',
        2 => 'Dos',
        3 => 'Tres',
        4 => 'Cuatro',
        5 => 'Cinco',
        6 => 'Seis',
        7 => 'Siete',
        8 => 'Ocho',
        9 => 'Nueve',
        10 => 'Diez',
        11 => 'Once',
        12 => 'Doce',
        13 => 'Trece',
        14 => 'Catorce',
        15 => 'Quince',
        16 => 'Dieciséis',
        17 => 'Diecisiete',
        18 => 'Dieciocho',
        19 => 'Diecinueve',
        20 => 'Veinte',
    ];

    return isset($diccionario[$numero]) ? $diccionario[$numero] : '';
}

function obtenerFecha(){
    //CONFIGURAR FECHA
    $traducciones = array(
        'Monday'    => 'Lunes',
        'Tuesday'   => 'Martes',
        'Wednesday' => 'Miércoles',
        'Thursday'  => 'Jueves',
        'Friday'    => 'Viernes',
        'Saturday'  => 'Sábado',
        'Sunday'    => 'Domingo',
        'January'   => 'Enero',
        'February'  => 'Febrero',
        'March'     => 'Marzo',
        'April'     => 'Abril',
        'May'       => 'Mayo',
        'June'      => 'Junio',
        'July'      => 'Julio',
        'August'    => 'Agosto',
        'September' => 'Septiembre',
        'October'   => 'Octubre',
        'November'  => 'Noviembre',
        'December'  => 'Diciembre'
    );

    // Crear una instancia de DateTime
    $fechaActual = new DateTime();

    // Obtener el nombre del día y mes en español
    $nombreDia = $traducciones[$fechaActual->format('l')];
    $nombreMes = $traducciones[$fechaActual->format('F')];

    // Imprimir la fecha formateada en español
    $fecha = $nombreDia . ' ' . $fechaActual->format('j') . ' de ' . $nombreMes . ' del ' . $fechaActual->format('Y');

    return $fecha;
}

function verificarDatos($conexion, $dni, $id_periodo){
    include("../include/conexion.php");
    $estudiante_res = buscarEstudianteByDni($conexion, $dni);
    $estudiante_res = mysqli_fetch_array($estudiante_res);
    $es_matriculado = buscarMatriculado($conexion, $estudiante_res['id'], $id_periodo);
    $cant_filas = mysqli_fetch_row($es_matriculado);

    if($cant_filas == 0){
        return false;
    }
    return true;
}

function determinarParidad($cadena) {
    $ocurrenciasI = substr_count($cadena, 'I');
    $ocurrenciasII = substr_count($cadena, 'II');

    if ($ocurrenciasI == 1) {
        return 'IMPAR';
    } else {
        return 'PAR'; // No cumple ninguna condición
    }
}

?>