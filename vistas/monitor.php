<?php include('funcionesMonitor.php');
include("../conexion/conexion.php");
include("../conexion/credenciales.php");
$fecha_hoy = date('Y-m-d');
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    
    <script src="../js/negocio.js"></script>
    <script src="../js/jquery.min.js"></script>
    <script src="../js/negocio.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Enviós</title>
</head>

<body>
    <div id="loadingOverlay" class="loadingOverlay">
        <img src="../img/cargando.gif" width="30" height="30">
        Enviando...espere
    </div>

    <div class="container">

        <div class="header">
            Monitor de Envíos Factura Electrónica
        </div>

        <div class="header">

            <form method="post">
                <label for="fecha_inicio">Fecha Inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" required>

                <label for="fecha_fin">Fecha Fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" required>

                <button type="submit" class="btn btn-primary">Buscar</button>
                <button type="button" class="btn btn-primary" onclick="informeZ()">informe Z</button>

            </form>


        </div>
        <?php
        $factura = new Factura();
        $sumatoria = 0;
        $sumatoriaPorPago = $factura->obtenerSumatoriaPorFormaPago($conn);
        foreach ($sumatoriaPorPago as $formaPago => $total) {
            $sumatoria = $sumatoria + $total;
            $pagoFormato = $factura->devolverPagoFormateado($formaPago);
            echo "<font style='font-family: Consolas, monospace; font-size: 15px; color: #333; white-space: nowrap;'>$pagoFormato: $$total|</span>";
        }
        echo "<span style='font-family: Consolas, monospace; font-size: 15px; color: #333; white-space: nowrap;'>Total del día: $$sumatoria |</span>";
        ?>

        <div class="table-container">
            <table id="tablaResultados">
                <thead>
                    <tr>
                        <th>Factura Electrónica</th>
                        <th>Pedido</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Cliente</th>
                        <th>Valor</th>
                        <th>Ver</th>
                        <th>Enviar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php

                    if (isset($_POST['fecha_inicio']) && isset($_POST['fecha_fin'])) {

                        $fecha_inicio = $_POST['fecha_inicio'];
                        $fecha_fin = $_POST['fecha_fin'];
                        $factura->traerFacturas($conn, $fecha_inicio, $fecha_fin);
                    } else {
                        $factura->traerFacturas($conn, $fecha_hoy, $fecha_hoy);
                    }

                    ?>
                </tbody>
            </table>
            <div id="informeZ" style="display:none;">
                <h3><button onclick="generarInformeZ()">Generar InformeZ</button></h3>
                <h3>Buscar informe Z</h3>
                <label for="fechaInicio">Fecha Inicial:</label>
                <input type="date" id="fechaInicio" name="fechaInicio" required>

                <label for="fechaFin">Fecha Final:</label>
                <input type="date" id="fechaFin" name="fechaFin" required>

                <button onclick="generarInforme()">Buscar</button>

                <table id="tablaResultados">
                    <!-- Aquí irán los resultados -->
                </table>
            </div>


        </div>
        <div class="footer">
            &copy; <?php print date("Y-m-d") . "-" . "Licencia Otorgada a:";
            print $licencia ?>
        </div>
    </div>

</body>

</html>