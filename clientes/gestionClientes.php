<?php
include('../conexion/conexion.php');
$accion = $_POST['accion'];

$response = array();
if ($accion == "nuevo") {
    $tipoIdentificacion = $_POST["tipoIdentificacion"];
    $identificacion = $_POST["identificacion"];
    $departamento = $_POST["departamento"];
    $municipio = $_POST["municipio"];
    $email = $_POST["email"];
    $pais = $_POST["pais"];
    $telefono = $_POST["telefono"];
    $codigo_postal = $_POST["codigo_postal"];
    $tipo_p = $_POST["tipo_p"];
    $res_fiscal = $_POST["res_fiscal"];
    $res_regimen = $_POST["res_regimen"];
    $tributo = $_POST["tributo"];
    $nombre = $_POST["nombre"];
    $direccion = $_POST["direccion"];
    $devolverDepartamento = devolverDepartamento($departamento, $conn);
    $devolverMunicipio = devolverMunicipio($municipio, $departamento, $conn);
    $retornarResFiscar = devolverResFiscal($res_fiscal, $conn);
    $retornarResRegimen = devolverResRegimen($res_regimen, $conn);
    $tributodev = retornarTributo($tributo, $conn);

    try {
        $sql = "INSERT INTO clientes(
        cod_cliente,
        razon_social,
        cod_postal,
        direccion,
        municipio,
        departamento,
        pais,
        telefono,
        email,
        identificacion, 
        tipo_persona,
        responsabilidad_fiscal,
        regimen_fiscal,
        tributo,tipoIdentificacion
           ) 
           VALUES 
           ('$identificacion',
           '$nombre',
           '$codigo_postal',
           '$direccion',
           '$devolverMunicipio',
           '$devolverDepartamento',
           '$pais',
           '$telefono',
           '$email',
           '$identificacion',
           '$tipo_p',
           '$res_fiscal',
           '$res_regimen',
           '$tributo',
           '$tipoIdentificacion'
            )";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            $response['success'] = true;
            $response['mensaje'] = "Registro Exitoso";
    
        } else {
            $response['success'] = false;
            $response['mensaje'] = "Error al registrar el producto: " . mysqli_error($conn);
        }
        echo json_encode($response);
    } catch (\Exception $e) {
        $response['success'] = false;
        $response['mensaje'] = "Error al registrar el producto: " . mysqli_error($conn);
        echo json_encode($response);
    }

}
else
{
    $id = $_POST['id'];
    $sql = "DELETE FROM clientes where identificacion = $id";
    if (mysqli_query($conn, $sql)) {

        $response['success'] = true;
        $response['mensaje'] = "Eliminación Exitosa";
    } else {

        $response['success'] = false;
        $response['mensaje'] = "Error al eliminar el cliente: " . mysqli_error($conn);
    }
    echo json_encode($response);
}



function devolverDepartamento($departament, $conn)
{
    $depar = "no define";
    $sql = "select nom_departamento from tb_departamentos where id_departamento = '$departament'";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $depar = $row[0];
        }
        return $depar;
    }
}

function devolverMunicipio($municipio, $departament, $conn)
{
    $muni = "no define";
    $sql = "select DISTINCT nom_municipio from tb_municipios where id_departamento = '$departament' and id_municipio = '$municipio'";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $muni = $row[0];
        }
        return $muni;
    }
}

function devolverResFiscal($resFiscal, $conn)
{
    $muni = "no define";
    $sql = "select codigo from fac_e_responsabilidades where id_responsabilidad = '$resFiscal' ";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $muni = $row[0];
        }
        return $muni;
    }
}

function devolverResRegimen($res_regimen, $conn)
{
    $muni = "no define";
    $sql = "select codigo from fac_e_regimen where id_regimen = '$res_regimen' ";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $muni = $row[0];
        }
        return $muni;
    }
}

function retornarTributo($tributo, $conn)
{
    $muni = "no define";
    $sql = "select codigo from fac_e_tipo_tributo where id_tributo = '$tributo' ";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $muni = $row[0];
        }
        return $muni;
    }
}


