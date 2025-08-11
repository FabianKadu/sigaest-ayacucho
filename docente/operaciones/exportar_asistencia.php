<?php
include("../../include/conexion.php");
include("../../include/busquedas.php");

// Configurar headers para Excel 97-2003
header('Content-Type: application/vnd.ms-excel; charset=utf-8');
header('Content-Disposition: attachment;filename="Reporte_Asistencia_' . $_GET['fecha'] . '.xls"');
header('Cache-Control: max-age=0');

// Agregar BOM para caracteres especiales
echo chr(0xEF) . chr(0xBB) . chr(0xBF);

$fecha = isset($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

// Estilos CSS para Excel
?>
<style>
    table {
        border-collapse: collapse;
        width: 100%;
        mso-border-spacing: 0;
    }

    th,
    td {
        border: 1px solid black;
        padding: 5px;
        mso-number-format: "\@";
        white-space: nowrap;
    }

    .header {
        background-color: #CCCCCC;
        font-weight: bold;
        text-align: center;
    }
</style>

<table>
    <thead>
        <tr>
            <th class="header" colspan="6" style="font-size: 16px;">REPORTE DE ASISTENCIA - <?php echo date("d/m/Y", strtotime($fecha)); ?></th>
        </tr>
        <tr>
            <th class="header">#</th>
            <th class="header">APELLIDOS Y NOMBRES</th>
            <th class="header">CARGO</th>
            <th class="header">FECHA</th>
            <th class="header">HORA</th>
            <th class="header">ESTADO</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT 
                    d.apellidos_nombres,
                    c.descripcion as cargo,
                    a.fecha_asistencia,
                    a.hora_asistencia,
                    a.permiso
                FROM asistencia_administrativo a
                INNER JOIN docente d ON a.docente_id = d.id
                INNER JOIN cargo c ON d.id_cargo = c.id
                WHERE DATE(a.fecha_asistencia) = ?
                ORDER BY d.apellidos_nombres ASC";

        $stmt = mysqli_prepare($conexion, $sql);
        mysqli_stmt_bind_param($stmt, "s", $fecha);
        mysqli_stmt_execute($stmt);
        $resultado = mysqli_stmt_get_result($stmt);

        $contador = 1;
        while ($data = mysqli_fetch_assoc($resultado)) {
        ?>
            <tr>
                <td style="text-align: center;"><?php echo $contador++; ?></td>
                <td><?php echo htmlspecialchars($data['apellidos_nombres']); ?></td>
                <td><?php echo htmlspecialchars($data['cargo']); ?></td>
                <td style="text-align: center;"><?php echo date("d/m/Y", strtotime($data['fecha_asistencia'])); ?></td>
                <td style="text-align: center;"><?php echo date("h:i:s A", strtotime($data['hora_asistencia'])); ?></td>
                <td style="text-align: center; <?php echo $data['permiso'] == 1 ? 'color: #FF0000;' : 'color: #008000;'; ?>">
                    <?php echo $data['permiso'] == 1 ? 'CON PERMISO' : 'ASISTIÓ'; ?>
                </td>
            </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<?php
mysqli_close($conexion);
?>