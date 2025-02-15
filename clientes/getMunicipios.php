<?php
$departamento = $_GET['departamento'];
$tipo = $_GET['tipo'];
include ('../conexion/conexion.php');
if ($tipo == 0) {
    $sql = "SELECT DISTINCT  id_municipio, nom_municipio from tb_municipios where id_departamento = '$departamento' ";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $opcion = array(
                "id_municipio" => $row[0],
                "nom_municipio" => $row[1],
            );
            $datos[] = $opcion;
        }

    }
} else {
    $ciudad = $_GET['ciudad'];
    $sql = "SELECT DISTINCT d.codigo_departamento, m.codigo_municipio 
    from tb_municipios m, tb_departamentos d 
    where d.id_departamento = m.id_departamento 
    and d.id_departamento = $departamento 
    and m.id_municipio = $ciudad" ;
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $opcion = array(
                "codigo" => $row[0].$row[1]
            );
            $datos[] = $opcion;
        }

    }

}
header('Content-Type: application/json');
$jsonData = json_encode($datos);
echo $jsonData;
?>