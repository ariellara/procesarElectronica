<?php
include("../negocio/funcionesZ.php");
include("../conexion/conexion.php");
include("funcionesMonitor.php");

$numeroZ = $_GET["id"];
$traerInformacionEmpresa = consultarDatosEmpresa($conn);
$tinformacionEmpresa = $traerInformacionEmpresa["datos"];
$traerInformacionZ = consultarInformeZ($conn, $numeroZ);
$datosInformeZ = json_decode($traerInformacionZ["datos"], true);
$obtenerDatosE = consultarDatosElectronica($conn);
$datosElectronica = $obtenerDatosE["datos"];

?>
<div>
    <table>
        <tr align=center><td><?php print $tinformacionEmpresa['razonSocial'] ?></td></tr>
        <tr align=center><td><?php print $tinformacionEmpresa['nit'] ?></td></tr>
        <tr align=center><td><?php print $tinformacionEmpresa['direccion'] ?></td></tr>
        <tr align=center><td class=letrasgrandes>INFORME Z <?php print $numeroZ; ?></td></tr>
        <tr align=left><td class=fontbold>Fecha del informe:<?php  print date("Y-m-d hh:mm:ss"); ?></td></tr>
        <tr align=left><td class=font>Primer Movimiento: <?php print $datosInformeZ["primerMovimiento"];?></td></tr>
        <tr align=left><td class=font>Ultimo Movimiento: <?php print $datosInformeZ["ultimoMovimiento"];?></td></tr>
        <tr align=left><td class=font></td></tr>
        <tr align=left><td class=fontbold>Res F Electrónica Dian No <?php print $datosElectronica["resolucion_dian"] ?></td></tr>
        <tr align=left><td class=fontbold>Desde: <?php print $datosElectronica["n_fac_inicial"] ?> Hasta: <?php print $datosElectronica["n_fac_inicial"] ?> </td></tr>
        <tr align=left><td class=font>Primera Factura: <?php print $datosElectronica["prefijo"].$datosInformeZ["primerFactura"]?> </td></tr>
        <tr align=left><td class=font>Ultima Factura: <?php print $datosElectronica["prefijo"].$datosInformeZ["ultimaFactura"]?></td></tr>
        <tr align=left><td class=font>Total Facturas Electrónicas: <?php print $datosInformeZ["ventas"] ?></td></tr>
        <tr align=left><td class=font> </td></tr>
        <tr align=left><td class=font> </td></tr>
        <tr align=left><td class=font> </td></tr>
        <tr align=left><td class=fontbold>VENTAS: $ <?php  print number_format($datosInformeZ["total"],2)?></td></tr>
        <tr align=left><td class=fontbold>Empleados:
       <?php
        foreach ($datosInformeZ["empleados"] as $item)
        {
            $empleado =  devolverNombreCajero($conn,$item["cajero"]);
            $total = $item["total"];
            print "<br>".$empleado["datos"].".....................$".number_format($total,2);
        }
        ?>
        </td></tr>
        <tr align=left><td class=fontbold>Impuestos: </td></tr>
        <tr align=left><td class=font>Valor Impuesto... 8% 
        <?php 
        $vr_base = $datosInformeZ["total"]/1.08;
        $ver_descuento = $datosInformeZ["total"]-  $vr_base;
        print "<br> ";  
        print "Valor Impuesto... $".number_format($ver_descuento,2,".",",");
        print "<br>";
        print "Valor Base....... $".number_format($vr_base,2,".",",");
        print "<br>";
        print "Valor Total...... $".number_format( $datosInformeZ["total"],2,".",",");   
        ?>
        </td></tr>
        <tr align=left><td class=fontbold>Formas de pago: 
            <?php
            foreach ($datosInformeZ["formaspago"] as $item)
            {
                $tipopago = devolverPagoFormateado($item["tipoPago"]);
                $total = $item["total"];
                print "<br>".$tipopago.".....................$".number_format($total,2);
            }
            ?>
        </td></tr>
                    
    </table>
</div>

<style>
        table {
            border-collapse: collapse;
            border: 2px solid black; /* Borde en el contorno de la tabla */
            width: 300px;
            margin: 0 auto;
        }
    .font
    {
     color: #0d0d0e;
      font-weight: normal;
      font-size: 12px;
      font-family:consolas;
    
      text-align: left;
    }
    .fontbold
    {
        font-weight: bold;
        color: #0d0d0e;
      
      font-size: 12px;
      font-family:consolas;
    
      text-align: left;
    }
    .letrasgrandes
    {
     color: #0d0d0e;
      font-weight: normal;
      font-size: 15px;
      font-family:consolas;
    
      text-align: center;
    }
        
</style>