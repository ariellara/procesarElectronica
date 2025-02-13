<?php

class Factura
{
    public function __construct() {}

    public function traerFacturas($conn)
    {
        $sql = "SELECT r.num_factura, r.ticket, r.cliente, r.estado, r.fecha_hora, r.mensaje, r.cufe FROM resultados r";

        if ($result = mysqli_query($conn, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                $enviar = "Enviado";
                $cufe = "";

                if ($row[3] != 0) {
                    $enviar = "<img src= ../img/cargando.gif width='30' height='30' style = display:none id=enviando><a href = #  onclick = enviarFactura($row[1])><img src='../img/enviar.png' width='25' height='20'></a>";
                }
                if(!empty($row[6])) {
                    $cufe = " <a href = https://api.taxxa.co/documentGet_pdf.dhtml?hash=$row[6] target=_blank >Ver </a>";

                }

                print "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[4]</td><td>$row[3]</td><td>$row[5]</td><td>$cufe<td>$enviar</td></tr>";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conn);
        }
    }
}
