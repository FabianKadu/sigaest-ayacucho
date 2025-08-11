<?php

include("../../include/conexion.php");
include("../../include/busquedas.php");
include("../include/consultas.php");
include("../include/verificar_sesion_empresa.php");
include("../operaciones/sesiones.php");

if (!verificar_sesion($conexion)) {
    echo "<script>
                alert('Error Usted no cuenta con permiso para acceder a esta página');
                window.location.replace('login/');
    		</script>";
} else {

    $id_empresa = $_SESSION['id_emp'];
    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $titulo = $_POST["titulo"];
        $ubicacion = $_POST["ubicacion"];
        $vacante = intval($_POST["vacante"]);
        $salario = floatval($_POST["salario"]);
        $modalidad = $_POST["modalidad"];
        $turno = $_POST["turno"];
        $inicio = $_POST["inicio"];
        $fin = $_POST["fin"];
        $url = $_POST["url"];
        $programas = isset($_POST['carreras']) ? $_POST['carreras'] : [];

        // Datos del segundo paso
        $requisitos = $conexion -> real_escape_string($_POST["requisitos"]);
        $funciones = $conexion -> real_escape_string($_POST["funciones"]);
        $beneficios = $conexion -> real_escape_string($_POST["beneficios"]);
        $condiciones = $conexion -> real_escape_string(isset($_POST["condiciones"]) ? $_POST["condiciones"] : "No se registraron condiciones.");

        
        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `oferta_laboral`(`id_empresa`, `titulo`, `ubicacion`, `funciones`, `requisitos`, `condiciones`, `beneficios`, `salario`, `vacantes`,`modalidad`, `turno`, `fecha_inicio`, `fecha_fin`, `link_postulacion`, `estado`, `fecha_estado`)
            VALUES ( $id_empresa ,'$titulo', '$ubicacion', '$funciones', '$requisitos', '$condiciones', '$beneficios', $salario , $vacante,  '$modalidad' , '$turno' ,'$inicio', '$fin', '$url', 'DISPONIBLE', CURRENT_TIMESTAMP())";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            $idOL = mysqli_insert_id($conexion);
            for ($i = 0; $i < count($programas); $i++) {
				$idProm = $programas[$i];
                $insert_programas = "INSERT INTO oferta_programas(id_ol, id_pr, propietario) VALUES ($idOL, $idProm, 'empresa')";
                mysqli_query($conexion, $insert_programas);
            }
            echo "<script>
            alert('Se ha guardado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatoria_documento.php?id=". $idOL . "');
            </script>";
        } else {
            echo "<script>
            alert('Ops, Ocurrio un error al guardar en base de datos!');
            window.history.back();
            </script>";
        }

        // Cerrar la conexión a la base de datos
        $conexion->close();
    }
}
?>
