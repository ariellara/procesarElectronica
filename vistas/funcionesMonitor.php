<?php

class Factura
{
    public function __construct()
    {
    }

    public function traerFacturas($conn, $fecha_inicio, $fecha_fin)
    {

        $sql = "SELECT f.numero_fac_electronica, 
                       f.num_ticket,
                       r.fecha_hora,
                       r.cufe , 
                       c.razon_social,
                       f.pago_realizado, 
                       r.mensaje,
                       r.estado
                       from facturas f, resultados r, clientes c
                where 
                f.num_ticket = r.ticket
                and 
                f.cod_cliente = c.cod_cliente
                and
                r.fecha >= '$fecha_inicio' and r.fecha <= '$fecha_fin'
                ORDER BY r.fecha DESC";
                
        if ($result = mysqli_query($conn, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                $enviar = "Enviado";
                $cufe = "";
                if ($row[7] != 0) {
                    $enviar = "<img src= ../img/cargando.gif width='30' height='30' style = display:none id=enviando><a href = #  onclick = enviarFactura($row[1])><img src='../img/enviar.png' width='25' height='20'></a>";
                }
                if (!empty($row[3])) {
                    $cufe = " <a href = https://api.taxxa.co/documentGet.dhtml?hash=$row[3] target=_blank >Ver </a>";
                }
                print "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[2]</td><td>$row[6]</td><td>$row[4]</td><td> $$row[5]</td><td>$cufe<td>$enviar</td></tr>";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conn);
        }
    }

    public function obtenerSumatoriaPorFormaPago($conexion)
    {
        $query = "
            SELECT 
                forma_pagoaux,
                SUM(pago_realizado) AS total_pago
            FROM 
                facturas
            WHERE
                DATE(fecha) = CURDATE()
            GROUP BY 
                forma_pagoaux";

        $resultado = mysqli_query($conexion, $query);
        $resultados = array();

        if ($resultado) {
            while ($row = mysqli_fetch_assoc($resultado)) {
                $resultados[$row['forma_pagoaux']] = $row['total_pago'];
            }
        }

        return $resultados;
    }

    public function devolverPagoFormateado($tipoPago): string
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

}
