<?php
include("../conexion/conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
   $cambiarPresentacion = cambiarPresentacion($conn);
   echo json_encode(['success' => true]);  
}

function cambiarPresentacion($conn)
{
    $respuesta["success"] = true;
    try{
        $consultarEstado = consultarEstado($conn);
        $cambiarEstado = cambiarEstado($conn , $consultarEstado);
        $respuesta["success"] = true;
    }
    catch(Exception $e)
    {
      $respuesta["success"] = false;
    }
    return $respuesta;

}

function consultarEstado($conn)
{
    $estado = 0;
    $sql = "select nit_res from tb_factura_electronica ";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $estado = $row[0];
        }
    }
    return $estado;
}
function cambiarEstado($conn , $estado)
{
    $nuevoEstado = ($estado == 0) ? 1 : 0;
    $sql = "UPDATE tb_factura_electronica set nit_res = '$nuevoEstado'";
    mysqli_query($conn, $sql);
}