<?php

function guardarFactura($datos, $conn, $cliente): array
{
    $respuesta = [];
    $respuesta["estado"] = false;
    $respuesta["mensaje"] = "Error desconocido";

    try {
        $id_tiquet = $datos["id_tiquet"];
        $hora_tiquet = $datos["horatiquet"];
        $fecha = $datos["fecha_tiquet"];
        $total = (float) $datos["total"];
        $bi = (float) $datos["bi"];
        $id_modo_pago = devolverFormapago($datos["id_modo_pago"]);
        $id_camarero = mysqli_real_escape_string($conn, $datos["id_camarero"]);
        $cod_cliente = $cliente;
        $caja = 1;
        $sql = "INSERT INTO facturas
                                    (num_ticket,fecha,fecha_hora,cod_cliente,caja,pago_realizado,forma_pago,bi, cajero) 
                              VALUES('$id_tiquet', '$fecha', '$hora_tiquet', '$cod_cliente', '$caja', '$total', '$id_modo_pago', '$bi', '$id_camarero')";
        if (mysqli_query($conn, $sql)) {
            $respuesta["estado"] = true;
            $respuesta["mensaje"] = "Registro de facturas exitoso";
        }
        else
        {
            $respuesta["mensaje"] = "no se registro la factura";
        }                      
        

    } catch (Exception $e) {
        $respuesta["mensaje"] = "Excepción: " . $e->getMessage();
    }

    return $respuesta;
}

function guardarDetallesFactura($detalesFactura, $conn)
{
    $respuesta = [
        "estado" => false,
        "mensaje" => "Error desconocido"
    ];

    $sql = "INSERT INTO fac_detalles (num_ticket, linea, articulo, descripcion, coste, cantidad, precio, iva)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

    mysqli_begin_transaction($conn);

    try {
        $stmt = mysqli_prepare($conn, $sql);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . mysqli_error($conn));
        }

        mysqli_stmt_bind_param($stmt, "ssssddds", $num_ticket, $linea, $articulo, $descripcion, $coste, $cantidad, $precio, $ipc);

        foreach ($detalesFactura as $item) {
            $num_ticket = $item["id_venta"];
            $linea = $item["id_linea"];
            $articulo = $item["id_complementog"];
            $descripcion = $item["complementog"];
            $coste = $item["precio"];
            $cantidad = $item["cantidad"];
            $precio = $item["PVPTiquet"];
            $ipc = $item["avgiva"];

            if (!mysqli_stmt_execute($stmt)) {
                throw new Exception("Error al insertar detalle: " . mysqli_stmt_error($stmt));
            }
        }

        mysqli_commit($conn);

        $respuesta["estado"] = true;
        $respuesta["mensaje"] = "Detalles insertados correctamente";
        $respuesta["numeroT"] = $num_ticket;

    } catch (Exception $e) {
        mysqli_rollback($conn);
        $respuesta["mensaje"] = "Excepción: " . $e->getMessage();
    } finally {
        mysqli_stmt_close($stmt);
    }

    return $respuesta;
}

function devolverFormapago($formadePAgo)
{
    switch ($formadePAgo) {
        case "01":
            return 10;
        case "02":
        case "03":
            return 49;
        case "04":
        case "05":
        case "06":
            return 47;
        case "07":
            return 48;
        default:
            return 'ZZZ';
    }
}

function obtenerClientes($conn): array
{
    $respuesta = array();
    try {
        $sql = "SELECT cod_cliente, razon_social FROM clientes";
        if ($result = mysqli_query($conn, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                $respuestaClientes[] = array(
                    "cod_cliente" => $row[0],
                    "razon_social" => $row[1]
                );
            }
        }
        $respuesta["estado"] = true;
        $respuesta["datos"] = $respuestaClientes;

    } catch (Exception $e) {
        $respuesta["estado"] = false;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;
}
