<?php
include("../conexion/conexion.php");
include("funcionesZ.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $generarInformeZ = generarInformeZ($conn);
    
    if ($generarInformeZ["estado"]) {
        echo json_encode(['success' => true, 'message' => $generarInformeZ["mensaje"]]);
    } else {
        echo json_encode(['success' => false, 'message' => $generarInformeZ["mensaje"]]);
    }
    

}