<?php

class Factura
{
    public function __construct() {}

    public function traerFacturas($conn)
    {
        $sql = "SELECT r.num_factura, r.ticket, r.cliente, r.estado, r.fecha_hora FROM resultados r";

        if ($result = mysqli_query($conn, $sql)) {
            while ($row = mysqli_fetch_row($result)) {
                $enviar = "Enviado";

                if ($row[3] != 0) {
                    $enviar = "<img src='../img/enviar.png' width='25' height='25'>";
                }

                print "<tr><td>$row[0]</td><td>$row[1]</td><td>$row[4]</td><td>$row[3]</td><td>$enviar</td></tr>";
            }
        } else {
            echo "Error en la consulta: " . mysqli_error($conn);
        }
    }
}
