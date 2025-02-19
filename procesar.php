<?php
include('negocio/funcionesElectronica.php');
include('negocio/enviarElectronica.php');
include('conexion/conexion.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['data'])) {

        $datosFactura = json_decode($_POST['data'], true);
        $insertarDatosFactura = guardarFactura($datosFactura["datosTiquet"], $conn, $datosFactura["cliente"]);
        if (!$insertarDatosFactura["estado"]) {
            $response = [
                "estado" => 'error',
                "mensaje" => 'El proceso de insertar facturas no se proceso.',
                "cufe" => ''
            ];
            mysqli_close($conn);
            $cmd = null;
            echo json_encode($response);
        }
        $insertarDetallesFactura = guardarDetallesFactura($datosFactura["detallesTiquet"], $conn);
        if (!$insertarDetallesFactura["estado"]) {
            $response = [
                "estado" => 'error',
                "mensaje" => 'El proceso de insertar detalles facturas no se proceso.',
                "cufe" => ''
            ];
            mysqli_close($conn);
            $cmd = null;
            echo json_encode($response);
        }
        $enviarFacturaElectronica = enviarFacturaElectronica($conn, $insertarDetallesFactura["numeroT"], $cmd);

        $response = [
            "estado" => 'success',
            "mensaje" => $enviarFacturaElectronica['mensaje'],
            "cufe" => $enviarFacturaElectronica['cufe']
        ];
        mysqli_close($conn);
        $cmd = null;
        echo json_encode($response);
    } else {
        echo json_encode([
            "estado" => 'error',
            "mensaje" => 'no se recibio los datos.',
            "cufe" => ''
        ]);
    }
}