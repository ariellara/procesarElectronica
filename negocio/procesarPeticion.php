
<?php
include("enviarElectronica.php");
include("../conexion/conexion.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $factura = json_decode($_POST['factura'], true);

    if ($factura) {
        $enviarFacturaElectronica = enviarFacturaElectronica($conn, $factura, $cmd);
        echo json_encode(['success' => true, 'message' => 'Proceso Realizado, validar su envió en el monitor']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error al procesar la factura']);
    }
}
?>
