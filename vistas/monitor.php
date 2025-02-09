<?php  include ('funcionesMonitor.php');
include ("../conexion/conexion.php");
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="../css/estilos.css">
    <script src="../js/negocio.js"></script>
    <script src="../js/jquery.min.js"></script>
</body>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor de Enviós</title>
</head>
<body>

<div class="container">
    <!-- Cabecera -->
    <div class="header">
        Monitor de Enviós Factura Electrónica
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
        &copy; <?php print date("Y-mm-dd")."-"."Desarrollo independiente";?>
    </div>
</div>

</body>
</html>
