<?php
include("../conexion/conexion.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $consultarInformeZ = traerInformez($conn, $data["fecha_inicio"], $data["fecha_fin"]);
    
    $html = '<table id="tablaResultados"><thead><tr><th>Id</th><th>No Z</th><th>Fecha</th><th>Ver</th></tr></thead><tbody>';
    foreach ($consultarInformeZ["datos"] as $informe) {
        $html .= "<tr>
                    <td>{$informe['Id']}</td>
                    <td>{$informe['consecutivo']}</td>
                    <td>{$informe['fecha']}</td>
                    <td><a href='../detallesInforme.php?id={$informe['Id']}' target='_blank'>Ver</a></td>
                  </tr>";
    }
    $html .= '</tbody></table>';
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'data' => $html]);
}
function traerInformez($conn, $fecha_inicio, $fecha_fin): array{
    $respuesta = array();
    $respuesta["estado"] = true;
    try{
        $sql = "SELECT Id, consecutivo, fecha FROM tb_informe_z WHERE fecha BETWEEN ? AND ?";
        $stmt = $conn->prepare($sql);
        if(!$stmt){throw new Exception("Error en la preparación de la consulta SQL.");}
        $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows > 0){
            $informes = array();
            while($row = $result->fetch_assoc()){
                $informes[] = $row;
            }
            $respuesta["datos"] = $informes;
        }else{
            $respuesta["mensaje"] = "No se encontraron informes en el rango de fechas proporcionado.";
            $respuesta["datos"] = [];
        }
        $stmt->close();
    }catch(Exception $e){
        $respuesta["estado"] = false;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;
}
