<?php include('funcionesMonitor.php');
include("../conexion/conexion.php");
$fecha_hoy = date('Y-m-d');

// Verificar si se ha enviado el formulario con filtros de fecha
if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {
    // Si hay parámetros de fecha, usar esas fechas para la consulta
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'];
    $factura->traerFacturas($conn, $fecha_inicio, $fecha_fin);
} else {
    // Si no se ha enviado filtro, cargar las facturas del día
    $factura->traerFacturas($conn, $fecha_hoy, $fecha_hoy);
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/negocio.js"></script>
    <script src="../js/jquery.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Enviós</title>
</head>

<body>
    <div id="loadingOverlay" class="loadingOverlay">
        <img src="../img/cargando.gif" width="30" height="30">
        Enviando...espere
    </div>

    <div class="container">
        <!-- Cabecera -->
        <div class="header">
            Monitor de Envíos Factura Electrónica
        </div>

        <!-- Filtros de búsqueda (Campos de fechas y botón buscar) -->
        <div class="header">
            <form method="post">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>

                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>

                <button type="submit" class="btn btn-primary">Buscar</button>
            </form>
        </div>

        <!-- Tabla de Facturas -->
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Número de Factura</th>
                        <th>Ticket</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Descripción</th>
                        <th>Ver</th>
                        <th>Enviar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $factura = new Factura();
                    $factura->traerFacturas($conn);
                    ?>
                </tbody>
            </table>
        </div>

        <!-- Pie de página -->
        <div class="footer">
            &copy; <?php print date("Y-m-d") . "-" . "Desarrollo independiente"; ?>
        </div>
    </div>

</body>

</html>