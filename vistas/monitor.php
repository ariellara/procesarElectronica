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
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Número de Factura</th>
                        <th>Ticket</th>
                        <th>Fecha</th>
                        <th>Descripción</th>
                        <th>Ver</th>
                        <th>Enviar</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                     $factura = new Factura();
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
        </div>
        <div class="footer">
            &copy; <?php  print date("Y-m-d") . "-" . "Licencia Otorgada a:";  print $licencia ?>
        </div>
    </div>

</body>

</html>