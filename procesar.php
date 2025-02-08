<?php
include ('negocio/funcionesElectronica.php');
include('negocio/enviarElectronica.php');
include ('conexion/conexion.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['data'])) {
       
        sleep(1);
        $datosFactura = json_decode($_POST['data'], true);
        $insertarDatosFactura = guardarFactura($datosFactura["datosTiquet"], $conn);
        if(!$insertarDatosFactura["estado"])
        {
            $response = [
                "estado" => "error",
                "mensaje" => "El proceso de insertar facturas no se proceso."
            ];
            echo json_encode($response);
        }
        $insertarDetallesFactura = guardarDetallesFactura($datosFactura["detallesTiquet"], $conn);
        if(!$insertarDetallesFactura["estado"])
        {
            $response = [
                "estado" => "error",
                "mensaje" => "El proceso de insertar detalles facturas no se proceso."
            ];
            echo json_encode($response);
        }
        $enviarFacturaElectronica = enviarFacturaElectronica($conn, $insertarDetallesFactura["numeroT"], $cmd);

        $response = [
            "estado" => "success",
            "mensaje" => "El proceso ha terminado exitosamente después de 10 segundos."
        ];
        echo json_encode($response);
    }
    else{
        sleep(3);
        echo json_encode([
            "estado" => "error",
            "mensaje" => "No se recibieron los datos correctamente."
        ]);
    }
}