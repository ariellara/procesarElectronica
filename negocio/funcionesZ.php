<?php

function generarInformeZ($conn): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $ultimoConsecutivo = obtenerUltimoConsecutivo($conn) + 1;
        $facturasSinCerrar = obtenerFacturasSinCerrar($conn);
        if (empty($facturasSinCerrar["datos"])) {
            $respuesta["mensaje"] = "no hay datos";
            return $respuesta;
        }
        $cerrarFacturas = cerrarFacturas($conn, $facturasSinCerrar["datos"], $ultimoConsecutivo);
        $construirInforme = construirInformeZ($facturasSinCerrar["datos"]);
        $guardarInformeZ = guardarInformeZ($conn, $construirInforme, $ultimoConsecutivo);
        $respuesta["mensaje"] = $guardarInformeZ["mensaje"];
    } catch (\Exception $e) {
        $respuesta["mensaje"] = $e->getMessage();
        $respuesta["estado"] = false;
    }
    return $respuesta;
}

function guardarInformeZ($conn, $datosInforme, $consecutivo_z): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $fecha = date("Y-m-d");
        $datos = json_encode($datosInforme);
        $sql = "INSERT INTO tb_informe_z (consecutivo,fecha,resultado)
                       VALUES($consecutivo_z,'$fecha','$datos')";
        $resultado = mysqli_query($conn, $sql);
        if ($resultado) {
            $respuesta["mensaje"] = "Informe Z generado y Guardado";
        } else {
            $respuesta["estado"] = false;
            $respuesta["mensaje"] = "Informe Z  no generado ";
        }
    } catch (Exception $e) {
        $respuesta["estado"] = false;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;

}
function construirInformeZ($datosInforme): array
{

    $informe = array();
    $formasPagos = array();
    $cajeros = array();
    $totalVentas = 0;
    $sumatoriaPorCajero = [];
    $sumatoriaPorTipoPago = [];
    $informe["primerMovimiento"] = $datosInforme[0]["fecha_hora"];
    $ultimoRegistro = end($datosInforme);
    $informe["ultimoMovimiento"] = $ultimoRegistro["fecha_hora"];
    $informe["primerFactura"] = $datosInforme[0]["numero_fac_electronica"];
    $informe["ultimaFactura"] = $ultimoRegistro["numero_fac_electronica"];
    $informe["ventas"] = count($datosInforme);
    foreach ($datosInforme as $item) {
        $totalVentas += $item["pago_realizado"];
        $formasPagos[] = devolverPagoFormateado($item["forma_pagoaux"]);
        $cajeros[] = $item["cajero"];
    }
    $cajeros = array_unique($cajeros);
    $formasPagos = array_unique($formasPagos);
    foreach ($datosInforme as $item) {
        $cajero = $item["cajero"];
        $pago = $item["pago_realizado"];
        if (isset($sumatoriaPorCajero[$cajero])) {
            $sumatoriaPorCajero[$cajero]['total'] += $pago;
        } else {
            $sumatoriaPorCajero[$cajero] = [
                'cajero' => $cajero,
                'total' => $pago
            ];
        }
    }
    foreach ($datosInforme as $item) {
        $tipoPago = $item["forma_pagoaux"];
        $pago = $item["pago_realizado"];
        if (isset($sumatoriaPorTipoPago[$tipoPago])) {
            $sumatoriaPorTipoPago[$tipoPago]['total'] += $pago;
        } else {
            $sumatoriaPorTipoPago[$tipoPago] = [
                'tipoPago' => $tipoPago,
                'total' => $pago
            ];
        }
    }
    $informe["total"] = $totalVentas;
    $informe["empleados"] = $sumatoriaPorCajero;
    $informe["impuestos"] = ($totalVentas / 1.08);
    $informe["formaspago"] = $sumatoriaPorTipoPago;
    return $informe;

}

function cerrarFacturas($conn, $facturasSinCerrar, $consecutivo_z)
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        foreach ($facturasSinCerrar as $item) {
            $id = $item["num_ticket"];
            $sql = "UPDATE facturas SET consecutivo_z = '$consecutivo_z' WHERE num_ticket = '$id'";
            $resultado = mysqli_query($conn, $sql);
        }
        $respuesta["mensaje"] = "Cierre exitoso";

    } catch (\Exception $e) {
        $respuesta["estado"] = false;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;

}

function obtenerFacturasSinCerrar($conn): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $sql = "SELECT  
                f.num_ticket,
                f.numero_fac_electronica,
                f.pago_realizado, 
                f.cajero,
                f.forma_pagoaux,
                r.fecha_hora
            FROM 
                resultados r, facturas f
            WHERE 
                r.ticket = f.num_ticket
                AND f.consecutivo_z = 0";

        $resultado = mysqli_query($conn, $sql);

        if ($resultado) {
            $facturas = [];

            while ($fila = mysqli_fetch_assoc($resultado)) {
                $facturas[] = $fila;
            }

            $respuesta["datos"] = $facturas;
        } else {
            $respuesta["datos"] = [];
        }
    } catch (PDOException $e) {
        $respuesta["estado"] = false;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;
}

function obtenerUltimoConsecutivo($conexion)
{
    $query = "SELECT consecutivo_z FROM facturas WHERE consecutivo_z != 0 ORDER BY consecutivo_z DESC LIMIT 1";
    $resultado = mysqli_query($conexion, $query);

    if ($resultado && mysqli_num_rows($resultado) > 0) {
        $fila = mysqli_fetch_assoc($resultado);
        return $fila['consecutivo_z'];
    } else {
        return 0;
    }
}

function devolverPagoFormateado($tipoPago): string
{
    $formatoPago = "";
    switch ($tipoPago) {
        case "01":
            $formatoPago = "Efectivo";
            break;
        case "02":
            $formatoPago = "Tarjeta Debito";
            break;
        case "03":
            $formatoPago = "Datafono";
            break;
        case "04":
            $formatoPago = "Nequi";
            break;
        case "05":
            $formatoPago = "Tranferencia";
            break;
        case "06":
            $formatoPago = "Daviplata";
            break;
        case "07";
            $formatoPago = "Tarjeta Crédito";
            break;
        default:
            $formatoPago = "Pago Desconocido";
            break;
    }
    return $formatoPago;
}

function traerInformeZ($conn, $fecha_inicio, $fecha_fin)
{
    $sql = "SELECT Id, consecutivo, fecha FROM tb_informe_z 
    WHERE fecha BETWEEN ? AND ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
    $stmt->execute();
    $result = $stmt->get_result();
}

function consultarDatosEmpresa($conn): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $sql = "SELECT  razonSocial, direccion, nit FROM datos_empresa";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $respuesta["datos"] = $row;
            }
        } else {
            $respuesta["estado"] = false;
            $respuesta["mensaje"] = "No se encontraron datos de la empresa.";
        }
        $stmt->close();
    } catch (PDOException $e) {
        $respuesta["mensaje"] = $e->getMessage();
        $respuesta["estado"] = false;
    }
    return $respuesta;
}

function consultarInformeZ($conn, $numeroZ): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $sql = "SELECT resultado FROM tb_informe_z WHERE consecutivo = '$numeroZ'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $respuesta["datos"] = $row["resultado"];
        } else {
            $respuesta["estado"] = false;
            $respuesta["mensaje"] = "No se encontró un informe con el consecutivo proporcionado.";
        }
    } catch (mysqli_sql_exception $e) {
        $respuesta["mensaje"] = $e->getMessage();
        $respuesta["estado"] = false;
    }
    return $respuesta;
}

function consultarDatosElectronica($conn): array
{
    $respuesta = array();
    $respuesta["estado"] = true;
    try {
        $sql = "SELECT  resolucion_dian, prefijo, n_fac_inicial, n_comprobante_final FROM tb_factura_electronica";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $respuesta["datos"] = $row;
            }
        } else {
            $respuesta["estado"] = false;
            $respuesta["mensaje"] = "No se encontraron datos de electronica.";
        }
        $stmt->close();
    } catch (PDOException $e) {
        $respuesta["mensaje"] = $e->getMessage();
        $respuesta["estado"] = false;
    }
    return $respuesta;
}
