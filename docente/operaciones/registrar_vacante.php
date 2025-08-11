<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include("../include/verificar_sesion_secretaria.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta p¨¢gina');
				  window.location.replace('../login/');
			  </script>";
    }else {
        // Verificar si se han enviado datos mediante POST
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Recuperar los datos del formulario
            $id_programa = $_POST['id_programa'];
            $periodo = $_POST['periodo'];
            $total_vacante = $_POST['total_vacante'];
            $vacantes_modalidad = $_POST['vacantes_modalidad'];
            $id_cvs = $_POST['id_cvs'];

            // Recuperar las modalidades
            $res_modalidades = buscarModalidadPorPeriodo($conexion, $periodo);
            // Inicializar un contador
            $contador = 0;
            $total_vacantes_exonerado = 0;
            
            // Iterar sobre las modalidades
            while ($modalidad = mysqli_fetch_array($res_modalidades)) {
                $id_cv =  $id_cvs[$contador];
                $id_modalidad = $modalidad['Id'];
                if ($modalidad['Descripcion'] !== "Ordinario") {
                    $vacante =  $vacantes_modalidad[$contador];
                    $consulta = "UPDATE `cuadro_vacantes` SET `Vacantes`='$vacante' WHERE Id = '$id_cv' AND Id_Programa = '$id_programa' AND Id_Modalidad = '$id_modalidad'";
                    mysqli_query($conexion,$consulta);
                    $total_vacantes_exonerado += $vacantes_modalidad[$contador];
                } else { 
                    $vacante = $total_vacante - $total_vacantes_exonerado;
                    $consulta = "UPDATE `cuadro_vacantes` SET `Vacantes`='$vacante' WHERE Id = '$id_cv' AND Id_Programa = '$id_programa' AND Id_Modalidad = '$id_modalidad'";
                    mysqli_query($conexion,$consulta);
                }

                // Incrementar el contador
                $contador++;
            }
            echo "<script>
				window.history.back();
					</script>
				";
        } else {
            echo "<script>
				alert('Error al registrar, verifique la informaci¨®n proporcionada');
				window.history.back();
					</script>
				";
        }

    } 
?>