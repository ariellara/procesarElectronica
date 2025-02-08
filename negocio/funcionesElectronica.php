<?php

function guardarFactura($datos, $conn): array
{
    $respuesta = [];
    $respuesta["estado"] = false;
    $respuesta["mensaje"] = "Error desconocido";

    try {
        $id_tiquet = $datos["id_tiquet"];
        $fecha_tiquet =  '$datos["fecha_tiquet"]';
        $hora_tiquet = $datos["horatiquet"];
        $total = (float) $datos["total"];
        $bi = (float) $datos["bi"];
        $id_modo_pago = mysqli_real_escape_string($conn, $datos["id_modo_pago"]);
        $id_camarero = mysqli_real_escape_string($conn, $datos["id_camarero"]);
        $cod_cliente = 222222222222;
        $caja = 1;

        $sql = "INSERT INTO facturas (num_ticket, fecha, fecha_hora, cod_cliente, caja, pago_realizado, forma_pago, bi, cajero)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt === false) {
            $respuesta["mensaje"] = "Error al preparar la consulta: " . mysqli_error($conn);
            return $respuesta;
        }

        mysqli_stmt_bind_param($stmt, "iissdssss", $id_tiquet, $fecha_tiquet, $hora_tiquet, $cod_cliente, $caja, $total, $id_modo_pago, $bi, $id_camarero);   $ejecucion = mysqli_stmt_execute($stmt);

        if ($ejecucion) {
            $respuesta["estado"] = true;
            $respuesta["mensaje"] = "Factura insertada correctamente";
        } else {
            $respuesta["mensaje"] = "Error al insertar la factura: " . mysqli_stmt_error($stmt);
        }

        mysqli_stmt_close($stmt);

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

