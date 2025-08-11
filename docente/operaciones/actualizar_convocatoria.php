<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $id = $_POST["id"];
        $empresa = $_POST["empresa"];
        $titulo = $_POST["titulo"];
        $ubicacion = $_POST["ubicacion"];        
        $turno = $_POST["turno"];
        $modalidad = $_POST["modalidad"];
        $vacante = intval($_POST["vacante"]);
        $salario = floatval($_POST["salario"]);
        $inicio = $_POST["inicio"];
        $fin = $_POST["fin"];
        $url = $_POST["url"];
        $programas = isset($_POST['carreras']) ? $_POST['carreras'] : [];

        // Datos del segundo paso
        $requisitos = $_POST["requisitos"];
        $funciones = $_POST["funciones"];
        $beneficios = $_POST["beneficios"];
        $condiciones = isset($_POST["condiciones"]) ? $_POST["condiciones"] : "";


        
        // Consulta para insertar los datos en la base de datos
        $sql = "UPDATE `oferta_laboral_propia` SET `empresa`= '$empresa' ,`titulo`='$titulo',`ubicacion`='$ubicacion',`funciones`='$funciones',`requisitos`='$requisitos',
        `condiciones`='$condiciones',`beneficios`='$beneficios',`salario`= $salario,`vacantes`=$vacante,`turno`= '$turno',`modalidad`='$modalidad',`fecha_inicio`='$inicio',`fecha_fin`='$fin',
        `link_postulacion`='$url',`estado`='',`fecha_estado`= CURRENT_TIMESTAMP() WHERE id = $id";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            $delete_programas = "DELETE FROM oferta_programas WHERE id_ol = $id AND propietario = 'iestp'";
            mysqli_query($conexion, $delete_programas);
            for ($i = 0; $i < count($programas); $i++) {
				$idProm = $programas[$i];
                echo $idProm;
                $insert_programas = "INSERT INTO `oferta_programas`(`id_ol`, `id_pr`, `propietario`) VALUES ($id, $idProm, 'iestp')";
                mysqli_query($conexion, $insert_programas);
            }
            echo "<script>
            alert('Se ha guardado la convocatoria de manera exitosa!!');
            window.location.replace('../mis_convocatorias.php');
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
