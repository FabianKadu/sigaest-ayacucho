<?php
include 'include/verificar_sesion_secretaria.php';
include '../include/conexion.php';
include("../include/busquedas.php");
include("../include/funciones.php");

$id_pe = $_POST['carrera_m'];
$id_sem = $_POST['semestre'];

$b_periodo_act = buscarPresentePeriodoAcad($conexion);
$r_b_periodo_act = mysqli_fetch_array($b_periodo_act);
$id_periodo_actual = $r_b_periodo_act['id'];

$b_pe = buscarCarrerasById($conexion, $id_pe);
$r_b_pe = mysqli_fetch_array($b_pe);

$b_sem = buscarSemestreById($conexion, $id_sem);
$r_b_sem = mysqli_fetch_array($b_sem);

if (isset($_SESSION['id_secretario'])) {
    $mostrar_archivo = 1;
} else {
    $mostrar_archivo = 0;
}


if (!($mostrar_archivo)) {
    //echo "<h1 align='center'>No tiene acceso a la evaluacion de la Unidad Didáctica</h1>";
    //echo "<br><h2><center><a href='javascript:history.back(-1);'>Regresar</a></center></h2>";
    echo "<script>
			alert('Error Usted no cuenta con los permisos para acceder a la Página Solicitada');
			window.close();
		</script>
	";
} else {
    /*header ("Content-Type: application/vnd.ms-excel; charset=iso-8859-1");
    header ("Content-Disposition: attachment; filename=plantilla.xls");*/

    $b_ud = buscarUdByCarSem($conexion, $id_pe, $id_sem);
    $count_ud = mysqli_num_rows($b_ud);

    $titulo_archivo = "PlantillaMatricula_" . $r_b_pe['nombre'] . "_" . $r_b_sem['descripcion'] . "_" . date("d") . "_" . date("m") . "_" . date("Y");

?>

    <!DOCTYPE html>
    <html lang="es">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title></title>
        <!--<script src="https://unpkg.com/xlsx@0.16.9/dist/xlsx.full.min.js"></script>
        <script src="https://unpkg.com/file-saverjs@latest/FileSaver.min.js"></script>
        <script src="https://unpkg.com/tableexport@latest/dist/js/tableexport.min.js"></script>-->
        <script src="../include/excel_generador/xlsx.full.min.js"></script>
        <script src="../include/excel_generador/FileSaver.min.js"></script>
        <script src="../include/excel_generador/tableexport.min.js"></script>

        <style>
            p.verticalll {
                /* idéntico a rotateZ(45deg); */

                writing-mode: vertical-lr;
                transform: rotate(180deg);


            }
        </style>
    </head>

    <body>
        <?php echo $titulo_archivo; ?>
        <input type="hidden" id="nombre_archivo" value="<?php echo $titulo_archivo; ?>">
        <table border="1" id="tabla">
            <thead>
                <tr>
                    <th colspan="2">PROGRAMA DE ESTUDIOS: </th>
                    <th><?php echo $r_b_pe['nombre']; ?></th>
                    <th>SEMESTRE : </th>
                    <th><?php echo $r_b_sem['descripcion']; ?></th>
                    <th colspan="<?php echo $count_ud; ?>" align="center"> UNIDADES DIDACTICAS</th>
                </tr>
                <tr>
                    <th>NRO</th>
                    <th>DNI</th>
                    <th>APELLIDOS Y NOMBRES</th>
                    <th>SEXO(F/M)</th>
                    <th>DISCAPACIDAD(SI/NO)</th>
                    <?php
                    $contar_ud_prog = 0;
                    $b_udd = buscarUdByCarSem($conexion, $id_pe, $id_sem);
                    while ($r_b_ud = mysqli_fetch_array($b_udd)) { 
                        $id_uds = $r_b_ud['id'];
                        $b_prog_ud = buscarProgramacionByUd_Peridodo($conexion, $id_uds, $id_periodo_actual);
                        $r_b_prog_ud = mysqli_fetch_array($b_prog_ud);
                        ?>
                        <th>
                            <?php echo $r_b_ud['descripcion']; ?>
                        </th>
                    <?php } ?>
                </tr>
            </thead>
            <tbody>
                <?php for ($i = 1; $i <= 40; $i++) { ?>
                    <tr>
                    <td><?php echo $i; ?></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <?php for ($j=0; $j < $count_ud ; $j++) { ?>
                        <td>
                        </td>
                    <?php } ?>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </body>
    <!-- script para exportar a excel -->
    <script>
        let nombre = document.getElementById("nombre_archivo");
        const $tabla = document.querySelector("#tabla");
        window.addEventListener("load", function() {
            let tableExport = new TableExport($tabla, {
                exportButtons: false, // No queremos botones
                filename: nombre.value, //Nombre del archivo de Excel
                sheetname: "Hoja2", //Título de la hoja
            });
            let datos = tableExport.getExportData();
            let preferenciasDocumento = datos.tabla.xlsx;
            tableExport.export2file(preferenciasDocumento.data, preferenciasDocumento.mimeType, preferenciasDocumento.filename, preferenciasDocumento.fileExtension, preferenciasDocumento.merges, preferenciasDocumento.RTL, preferenciasDocumento.sheetname);

        });
    </script>


    </html>



<?php
    //window.close()
}
?>