<?php
include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../../include/funciones.php");

include("../include/verificar_sesion_estudiante.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('index.php');
    		</script>";
} else {

  $id_estudiante_sesion = buscar_estudiante_sesion($conexion, $_SESSION['id_sesion_est'], $_SESSION['token']);

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 1. DATOS GENERALES
        $ap_nombres = $_POST['ap_nombres'];
        $correo = $_POST['correo'];
        $celular = $_POST['celular'];
        $domicilio = $_POST['domicilio'];
        $fecha_nac = $_POST['fecha_nac'];
        $edad = $_POST['edad'];
        $genero = $_POST['genero'];
        $id_genero = 2;
        if($genero == "Masculino") {
            $id_genero = 1;
        }

        // 2. ASPECTO FAMILIAR
        $familiares = isset($_POST['familiares']) ? implode(", ", $_POST['familiares']) : '';
        $cant_fami = $_POST['cant_fami'];
        $problema = $_POST['problema'];
        $fami_confianza = $_POST['fami_confianza'];

        // 3. ASPECTO LABORAL
        $familiares_lab = isset($_POST['familiares_lab']) ? implode(", ", $_POST['familiares_lab']) : '';
        $cant_ingreso = $_POST['cant_ingreso'];
        $pri_ingreso = $_POST['pri_ingreso'];
        $trabajo = $_POST['trabajo'];
        $gasto = $_POST['gasto'];

        // 4. ASPECTO VIVIENDA
        $tipo_casa = $_POST['tipo_casa'];
        $const_casa = $_POST['const_casa'];
        $servicio_casa = isset($_POST['servicio_casa']) ? implode(", ", $_POST['servicio_casa']) : '';
        $equipos = isset($_POST['equipos']) ? implode(", ", $_POST['equipos']) : '';
        $vehiculo = isset($_POST['vehiculo']) ? implode(", ", $_POST['vehiculo']) : '';
        $minutos = $_POST['minutos'];
        $condicion = $_POST['condicion'];

        // 5. ASPECTO SALUD
        $enfermedad = $_POST['enfermedad'];
        $seguro = $_POST['seguro'];
        $enfermedad_descripcion = isset($_POST['enfermedad_descripcion']) ? $_POST['enfermedad_descripcion'] : "Ninguno";

        //Actualizar información del estudiante
        $update = "UPDATE `estudiante`
        SET `apellidos_nombres` = '$ap_nombres',`id_genero` = $id_genero,
        `fecha_nac` = '$fecha_nac',`direccion` = '$domicilio',`correo` = '$correo',`telefono` = '$celular'
        WHERE `id` = $id_estudiante_sesion";
        mysqli_query($conexion, $update);

        $sql = "INSERT INTO `informacion_socioeconomica`
        (`familiares`, `cant_familiares`, `problema_hogar`, `familiar_confianza`, `trabajo_familiares`, `rango_ingreso`,
        `ingreso_prio`, `tipo_trabajo`, `gasto_trabajo`, `vivienda`, `tipo_vivienda`, `servicios`, `equipos_electronicos`,  
        `vehiculos`, `minutos_casa_insti`, `dificultad_fisica`, `seguro_salud`, `enfermedad`, `id_estudiante`,`condicion`)
        VALUES('$familiares',$cant_fami,'$problema','$fami_confianza','$familiares_lab','$cant_ingreso','$pri_ingreso',
        '$trabajo','$gasto','$tipo_casa','$const_casa','$servicio_casa','$equipos','$vehiculo','$minutos',
        '$enfermedad','$seguro','$enfermedad_descripcion', $id_estudiante_sesion, '$condicion')";
        $res = mysqli_query($conexion, $sql);

        if($res){
            echo "<script>
                alert('Su registro se ha realizado de manera exitosa!!');
                window.location.replace('../index.php');
                </script>";
        }else{
            echo "<script>
                alert('No se pudo guardar su información, comuniquese con soporte!!');
                window.history.back();
                </script>";
        }

    }else{
        exit;
    }
}