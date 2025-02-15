<script src="../js/producto.js"></script>
<script src="../js/funciones.js"></script>
<?php

function datosEmpresa($conn)
{
    include('../conexion/conexion.php');

    $sql = "SELECT razonSocial, direccion, ciudad , departamento , telefono , nit FROM datos_empresa";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<label class=pieInformacion>Licencia otorgada a:";
            print $row[0];
            print "</label> ";
            print "<label class=pieInformacion>Nit:";
            print $row[5];
            print "</label>";

        }

    }

}

function configuracionEmpresa()
{

    include("../conexion/conexion.php");
    $sql = "SELECT nit, razonSocial, direccion, ciudad , departamento , telefono ,correo from datos_empresa";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<tr>
                <td field='id'>$row[0]</td> 
                <td field='descripcion'>$row[1]</td> 
                <td field='modulo'>Datos Empresa</td>    
                <td field='editar' align='center'>";
            $data = [
                'nit' => $row[0],
                'razonsocial' => $row[1],
                'direccion' => $row[2],
                'ciudad' => $row[3],
                'departamento' => $row[4],
                'telefono' => $row[5],
                'correo' => $row[6]
            ];
            $json_data = urlencode(json_encode($data));
            print "<a href='edit_configuracion.php?data=$json_data'><img src='../img/editar.png' class='icono' height='25' width='25' alt='Editar'></a>";

        }

        mysqli_free_result($result);
    }


}
function configuracionFacturaElectronica()
{

    include("../conexion/conexion.php");
    $sql = "SELECT n_fac_inicial, n_comprobante_actual, n_comprobante_final, 
                    prefijo , resolucion_dian , fecha_forma_res,
                    fecha_vencimiento,codigo_postal 
            FROM tb_factura_electronica";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<tr>
                <td field='id'>$row[0]</td> 
                <td field='descripcion'>$row[1]</td> 
                <td field='modulo'>Datos Factura electrónica</td>    
                <td field='editar' align='center'>";
            $data = [
                'n_fac_inicial' => $row[0],
                'n_comprobante_actual' => $row[1],
                'n_comprobante_final' => $row[2],
                'prefijo' => $row[3],
                'resolucion_dian' => $row[4],
                'fecha_forma_res' => $row[5],
                'fecha_vencimiento' => $row[5],
                'codigo_postal' => $row[7],
            ];
            $json_data = urlencode(json_encode($data));
            print "<a href='edit_facturaElectronica.php?data=$json_data'><img src='../img/editar.png' class='icono' height='25' width='25' alt='Editar'></a>";

        }

        mysqli_free_result($result);
    }


}
function productos($pagina_actual = 1, $elementos_por_pagina = 10)
{
    include("../conexion/conexion.php");
    $offset = ($pagina_actual - 1) * $elementos_por_pagina;

    $total_query = "SELECT COUNT(*) as total FROM tb_productos";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_productos = $total_row['total'];


    $total_paginas = ceil($total_productos / $elementos_por_pagina);

    $sql = "SELECT * FROM tb_productos LIMIT $elementos_por_pagina OFFSET $offset";

    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<tr>
                <td field='itemid'>$row[0]</td> 
                <td field='nombre'>$row[1]</td>   
                <td field='precio'>$row[2]</td>
                <td field='ipc'>$row[3]</td>    
                <td field='iva'>$row[4]</td>   
                <td field='editar' align='center'>";


            print "<a href='edit_producto.php?item=$row[0]&nombre=$row[1]&precio=$row[2]&ipc=$row[3]&iva=$row[4]'><img src='../img/editar.png' class='icono' heigth =25 width=25></a>";

            print "</td>
                <td field='eliminar'><a href='#' onclick='eliminarProducto($row[0])'><img src='../img/eliminar.png' class='icono' heigth =25 width=25></a></td>      
            </tr>";
        }

        mysqli_free_result($result);


        print "<tr><td></td><td>";
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                print "<strong>$i</strong> ";
            } else {
                print "<a href='?pagina=$i'>$i</a> ";
            }
        }
        print "</td></tr>";
    }
}

function cargarProductos()
{

    include('../conexion/conexion.php');
    $sql = "SELECT * FROM tb_productos";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $datos = $row[0] . "-" . $row[1] . "-" . $row[2] . "-" . $row[3] . "-" . $row[4];
            print "<option value = '$datos'>$row[1]</option>";
        }
    }

}

function clientes()
{
    include('../conexion/conexion.php');
    $sql = "SELECT cod_cliente, razon_social FROM clientes order by razon_social";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) { {
                print "<option value = '$row[0]'>$row[1]</option>";
            }
        }

    }
}

function clientesTodos($pagina_actual = 1, $elementos_por_pagina = 10)
{
    include("../conexion/conexion.php");
    $offset = ($pagina_actual - 1) * $elementos_por_pagina;

    $total_query = "SELECT COUNT(*) as total FROM clientes";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_productos = $total_row['total'];
    $total_paginas = ceil($total_productos / $elementos_por_pagina);
    $sql = "SELECT * FROM clientes LIMIT $elementos_por_pagina OFFSET $offset";

    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<tr>
                <td field='Identificación'>$row[0]</td> 
                <td field='Nombre'>$row[1]</td>   
                <td field='Email'>$row[9]</td>
                <td field='editar'> ";


            // print "<a href='edit_producto.php?item=$row[0]&nombre=$row[1]&precio=$row[2]&ipc=$row[3]&iva=$row[4]'><img src='../img/editar.png' class='icono' heigth =25 width=25></a>";

            print "</td>
                <td field='accion'><a href='#' onclick='eliminarCliente($row[0])'><img src='../img/eliminar.png' class='icono' heigth =25 width=25></a></td>      
            </tr>";
        }

        mysqli_free_result($result);


        print "<tr><td></td><td>";
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                print "<strong>$i</strong> ";
            } else {
                print "<a href='?pagina=$i'>$i</a> ";
            }
        }
        print "</td></tr>";
    }
}

function listaFacturas($pagina_actual = 1, $elementos_por_pagina = 10)
{
    include("../conexion/conexion.php");

    $urlJson = devolverUrl($conn);
    $offset = ($pagina_actual - 1) * $elementos_por_pagina;

    $total_query = "SELECT COUNT(*) as total FROM resultados";
    $total_result = mysqli_query($conn, $total_query);
    $total_row = mysqli_fetch_assoc($total_result);
    $total_productos = $total_row['total'];
    $total_paginas = ceil($total_productos / $elementos_por_pagina);
    $sql = "SELECT * FROM resultados ORDER BY fecha_hora DESC LIMIT $elementos_por_pagina OFFSET $offset";


    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            $cufe = $row[6];
          
            print "<tr>
                <td field='Fecha'>$row[8]</td> 
                <td field='Factura'>$row[1]</td>   
                <td field='Ticket'>$row[2]</td> 
                <td field='Cliente'>$row[3]</td>
                <td field='editar'> <a href = construc_factura.php?cufe=$cufe&urlJson=$urlJson target=_blank >Detalles </a></td></tr> 
              </tr>";           
        }

        mysqli_free_result($result);


        print "<tr><td></td><td>";
        for ($i = 1; $i <= $total_paginas; $i++) {
            if ($i == $pagina_actual) {
                print "<strong>$i</strong> ";
            } else {
                print "<a href='?pagina=$i'>$i</a> ";
            }
        }
        print "</td></tr>";
    }
}

function getDepartamenos($conn)
{


    $sql = "SELECT id_departamento , nom_departamento from tb_departamentos ";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            print "<option value=$row[0]>" . $row[1] . "</option>";
        }
    }

}

function selecTipopersona($tipo)
{
    if ($tipo == 1) {
        print " <option value =1 selected>Persona Juridica</option>
           <option value =2 >Natural</option>";
    }
    if ($tipo == 2) {
        print " <option value =1>Persona Juridica</option>
           <option value =2 selected>Natural</option>";
    }

}

function selectResponsabilidadfiscal($conn, $res_fiscal)
{
    $sql = "select *from fac_e_responsabilidades";
    if ($result = mysqli_query($conn, $sql)) {
        while ($row = mysqli_fetch_row($result)) {
            if (!$row[0] == 0)
                if ($row[0] == 9) {
                    print "<option value=$row[0]  selected>$row[1].$row[2]</option>";
                } else {
                    print "<option value=$row[0]  >$row[1].$row[2]</option>";
                }

        }
    }
    mysqli_free_result($result);
}

function selectRegimen($conn, $regimen)
{
    $sql = "select *from fac_e_regimen";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            if (!$row[0] == 0)
                if ($row[0] == 4) {
                    print "<option value=$row[0]  selected>$row[1].$row[2]</option>";
                } else {
                    print "<option value=$row[0]  >$row[1].$row[2]</option>";
                }

        }
    }
    mysqli_free_result($result);
}

function selectTributo($conn, $tributo)
{
    $sql = "select *from fac_e_tipo_tributo";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            if (!$row[0] == 0)
                if ($row[0] == 8) {
                    print "<option value=$row[0]  selected>$row[1].$row[2]</option>";
                } else {
                    print "<option value=$row[0]  >$row[1].$row[2]</option>";
                }

        }
    }
    mysqli_free_result($result);
}
function devolverUrl($conn)
{
    $sql = "SELECT  url_service_pdf  from tb_factura_electronica ";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $url = $row[0];
        }
        return $url;
    }


}
?>