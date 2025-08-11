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
        $id = $_POST["id"];
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
        $sql = "UPDATE `oferta_laboral` SET `id_empresa`= $id_empresa ,`titulo`='$titulo',`ubicacion`='$ubicacion',`funciones`='$funciones',`requisitos`='$requisitos',
        `condiciones`='$condiciones',`beneficios`='$beneficios',`salario`= $salario,`vacantes`=$vacante,`modalidad`= '$modalidad',`turno`= '$turno',`fecha_inicio`='$inicio',`fecha_fin`='$fin',
        `link_postulacion`='$url',`estado`='',`fecha_estado`= DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 5 HOUR) WHERE id = $id";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            $insert_programas = "DELETE FROM oferta_programas WHERE id_ol = $id";
            mysqli_query($conexion, $insert_programas);
            for ($i = 0; $i < count($programas); $i++) {
				$idProm = $programas[$i];
                $insert_programas = "INSERT INTO oferta_programas(id_ol, id_pr,propietario) VALUES ($id, $idProm, 'empresa')";
                mysqli_query($conexion, $insert_programas);
            }
            echo "<script>
            alert('Se ha guardado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatoria.php');
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
