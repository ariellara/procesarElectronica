<?php
include('funcionesElectronica.php');
include('../conexion/conexion.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['data'])) {

        $obtenerClientes = obtenerClientes($conn);
        if (!$obtenerClientes["estado"]) {
            $response = [
                "estado" => 'error',
                "mensaje" => 'El proceso de insertar facturas no se proceso.',
            ];
            echo json_encode($response);
        }
        $response = [
            "estado" => $obtenerClientes["estado"],
            "datos" => $obtenerClientes["datos"],
        ];
        echo json_encode($response);


    } else {

        echo json_encode([
            "estado" => 'error',
            "mensaje" => 'no se recibio los datos.',
            "cufe" => ''
        ]);
    }
}