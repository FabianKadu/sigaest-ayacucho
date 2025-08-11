<?php
include "../../include/conexion.php";
include "../../include/busquedas.php";
include "../../include/funciones.php";
include "../../empresa/include/consultas.php";
include("../include/verificar_sesion_administrador.php");
if (!verificar_sesion($conexion)) {
	echo "<script>
				  alert('Error Usted no cuenta con permiso para acceder a esta página');
				  window.location.replace('../../login/');
			  </script>";
  }else {

    // Verificar si se ha enviado el formulario
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Obtener los datos del formulario
        $nombre = $_POST["nombre"];
        $tipo = $_POST["tipo_documento"];
        $id = $_POST["id"];
        $numeracion = cantidadDocumentos($conexion);

        // Datos del tercer paso (archivo cargado)
        $nombreArchivo = "DOC".$numeracion.".pdf";
        $rutaTemporal = $_FILES["documento"]["tmp_name"];
        $directorioDestino = '../../empresa/files/';

        // Mover el archivo de la ruta temporal al directorio destino
        move_uploaded_file($rutaTemporal, $directorioDestino . $nombreArchivo);

        // Aquí puedes realizar cualquier validación adicional de los datos antes de guardarlos en la base de datos

        $rutaFinal = $directorioDestino . $nombreArchivo;
        $rutaFinal = substr($rutaFinal,3);

        // Consulta para insertar los datos en la base de datos
        $sql = "INSERT INTO `oferta_documentos`(`id_ol`, `tipo_documento`, `nombre_documento`, `url_documento`, `propietario`)
            VALUES ( $id ,'$tipo', '$nombre', '$rutaFinal', 'iestp')";
        $res = mysqli_query($conexion, $sql);
        if ($res) {
            echo "<script>
            alert('Se ha guardado la convocatoria de manera exitosa!!');
            window.location.replace('../convocatoria_documento.php?id=". $id . "');
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
