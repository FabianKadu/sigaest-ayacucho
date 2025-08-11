<?php

include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../login/');
			  </script>";
  }else {
    $id_proceso_admision = $_GET['id'];
    $res_aptos = buscarEstudiantesParaAdjudicar($conexion, $id_proceso_admision);
    while($aptos = mysqli_fetch_array($res_aptos)){
        $dni = $aptos['Dni'];
        $ApellidosNombres = $aptos['Apellido_Paterno'].' '.$aptos['Apellido_Materno'].' '.$aptos['Nombres'];
        $genero = intval($aptos['Sexo']);
        $fecha_nac = $aptos['Fecha_Nacimiento'];
        $direccion = $aptos['Domicilio_Actual'];
        $anioIngreso = substr($aptos['Periodo'],0,4);
        $programa = $aptos['Id_Programa'];
        $correo = $aptos['Correo'];
        $telefono = $aptos['Celular'];
        $semestre = 1;
        $seccion = 'A';
        $turno = "MAÑANA";
        $discapacidad = $aptos['Presenta_Discapacidad'];
        if($discapacidad == 0){
            $discapacidad = "NO";
        }else{
            $discapacidad = "SI";
        }
        $egresado = 'NO';
        $pass = $dni;
	    $pass_secure = password_hash($pass, PASSWORD_DEFAULT);

        $insertar = "INSERT INTO `estudiante`(`dni`, `apellidos_nombres`, `id_genero`, `fecha_nac`, `direccion`, `correo`,
        `telefono`, `anio_ingreso`, `id_programa_estudios`, `id_semestre`, `seccion`, `turno`, `discapacidad`, `egresado`, `password`) 
        VALUES ('$dni','$ApellidosNombres','$genero','$fecha_nac','$direccion','$correo',
        '$telefono','$anioIngreso','$programa','$semestre','$seccion','$turno','$discapacidad','NO','$pass')";
        $ejecutar_insetar = mysqli_query($conexion, $insertar);
    }

    echo "<script>
        alert('Los postulantes aptos ya se encuentra en la relación de estudiantes para el proceso de matrícula!.');
        window.history.back();
        </script>";
  }
?>