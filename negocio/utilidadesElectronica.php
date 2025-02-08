<?php
function obtenerLineasDetalles($cmd, $num_ticket)
{

    $sql = "SELECT linea,
                   articulo,
                   descripcion,
                   coste,
                   cantidad,
                   precio,descuento,iva FROM fac_detalles WHERE num_ticket='$num_ticket'";
    $rs = $cmd->query($sql);
    $objs_detalle = $rs->fetchAll();
    return $objs_detalle;

}

function obtenerJtax($objs_detalle)
{
    $adocumentitems = array();
    $jtax[] = array();
    $jiva = array();
    foreach ($objs_detalle as $obj) {

        if ($obj['iva'] == 8) {
            $jiva = array(
                'nrate' => $obj['iva'],
                'scode' => '00',
                'sname' => 'INC'
            );
            $jtax = array(
                'jinc' => $jiva
            );
        }

        if (($obj['iva'] == 19) || ($obj['iva'] == 5) || ($obj['iva'] == 0)) {
            $jiva = array(
                'nrate' => $obj['iva'],
                'scode' => '01',
                'sname' => 'IVA'
            );
            $jtax = array(
                'jiva' => $jiva
            );
        }

        $adocumentitems[] = array(
            'sdescription' => $obj['descripcion'],
            'sstandarditemidentification' => $obj['articulo'],
            'nunitprice' => $obj['coste'],
            'ntotal' => $obj['precio'] * $obj['cantidad'],
            'nquantity' => $obj['cantidad'],
            'jtax' => $jtax
        );

    }
    return $adocumentitems;


}

function obtenerCliente($cmd, $num_ticket)
{
    $sql = "SELECT clientes.identificacion 
                  ,clientes.razon_social AS nombre,
                  clientes.direccion,clientes.cod_postal,
                  clientes.municipio,
                  clientes.departamento,
                  clientes.telefono,
                  clientes.email,
                  facturas.fecha AS fecha,
                  facturas.fecha_hora as hora,
                  facturas.forma_pago as forma_pago,
                  facturas.numero_fac_electronica as numero_f,
                  facturas.estado_final as final_fac,
                  clientes.tipo_persona as tipo_persona,
                  clientes.responsabilidad_fiscal as res_fiscal,
                  clientes.regimen_fiscal as regimen,
                  clientes.tributo as tributo,
                  clientes.tipoIdentificacion as tipoI
        FROM clientes INNER JOIN facturas ON(facturas.cod_cliente=clientes.cod_cliente) WHERE facturas.num_ticket= '$num_ticket' LIMIT 1";
    $rs = $cmd->query($sql);
    $obj_factura = $rs->fetch();
    return $obj_factura;

}


function retornarDatosempresa($cmd)
{
    $datosE[] = array();
    $datosCodigos[] = array();
    $sql = "SELECT nombre,direccion,ciudad,departamento,pais,telefono,correo,nit, cod_postal
    from datos_empresa ";
    $rs = $cmd->query($sql);
    $obj_empresa = $rs->fetch();
    $datosE[0] = $obj_empresa['nombre'];
    $datosE[1] = $obj_empresa['direccion'];
    $datosE[2] = $obj_empresa['ciudad'];
    $datosE[3] = $obj_empresa['departamento'];
    $datosE[4] = $obj_empresa['pais'];
    $datosE[5] = $obj_empresa['telefono'];
    $datosE[6] = $obj_empresa['correo'];
    $datosE[7] = $obj_empresa['nit'];
    $auxCiudad = $obj_empresa['ciudad'];
    $auxNit = $obj_empresa['nit'];
    $datosCodigos = retornarCodigos($cmd, $auxCiudad, $obj_empresa['departamento']);
    $tipoPersona = devolverPersona($auxNit);
    $arraydata = implode(',', $datosCodigos);
    $codClean = explode(",", $arraydata);
    $datosE[8] = $datosCodigos[0];//cod municipio
    $datosE[9] = $datosCodigos[1];//cod_depa
    $datosE[10] = $datosCodigos[2];//cod_postal
    $datosE[11] = $tipoPersona;

    return $datosE;

}
function retornarCodigos($cmd, $ciudad, $departamento)
{
    $codigos[] = array();

    $sql = "SELECT tb_municipios.codigo_municipio as cod_municipio, tb_departamentos.codigo_departamento as cod_departamento, tb_municipios.cod_postal as cod_postal
    from tb_municipios,tb_departamentos 
    where nom_municipio = '$ciudad' 
    AND  nom_departamento = '$departamento'
    and
    tb_departamentos.id_departamento=tb_municipios.id_departamento";
    $rs = $cmd->query($sql);
    $obj_codigos = $rs->fetch();

    $codigos[0] = $obj_codigos['cod_municipio'];
    $codigos[1] = $obj_codigos['cod_departamento'];
    $codigos[2] = $obj_codigos['cod_postal'];
    return $codigos;

}
function devolverPersona($identificacion)
{
    if (str_contains($identificacion, "-")) {
        return "1";
    } else {
        return "2";
    }
}

function devolverDatoselectronica($cmd)
{

    $datosE[] = array();

    $sql = "SELECT  
                 tipo_persona, 
                 responsabilidad_fiscal , 
                 regimen_fiscal , 
                 tributo , 
                 prefijo, 
                 n_comprobante_actual,
                 url_service_pdf,
                 usuario ,
                 clave,
                 url_envio_json,
                 ambiente

                  from tb_factura_electronica ";
    $rs = $cmd->query($sql);
    $obj_empresa = $rs->fetch();
    $id_responsabilidadfiscal = $obj_empresa['responsabilidad_fiscal'];
    $id_regimenfiscal = $obj_empresa['regimen_fiscal'];
    $id_tributo = $obj_empresa['tributo'];



    $sqlResponsabilidad = "SELECT  codigo, significado from fac_e_responsabilidades where id_responsabilidad = $id_responsabilidadfiscal";
    $rs = $cmd->query($sqlResponsabilidad);
    $obj_responsabilidadFiscal = $rs->fetch();

    $sqlRegimen = "SELECT codigo, significado from fac_e_regimen where id_regimen =  $id_regimenfiscal";
    $rs = $cmd->query($sqlRegimen);
    $obj_regimenfiscal = $rs->fetch();

    $sqlTributo = "SELECT codigo, significado from  fac_e_tipo_tributo where  id_tributo = $id_tributo";
    $rs = $cmd->query($sqlTributo);
    $obj_tributo = $rs->fetch();


    $datosE[0] = $obj_responsabilidadFiscal['codigo'];//codigo de R-99-PN 	
    $datosE[1] = $obj_responsabilidadFiscal['significado'];//No aplica-Otros
    $datosE[2] = $obj_regimenfiscal['codigo'];// 	49 	
    $datosE[3] = $obj_regimenfiscal['significado'];// No Responsable de IVA.
    $datosE[4] = $obj_tributo['codigo'];//zz
    $datosE[5] = $obj_tributo['significado'];//No aplica
    $datosE[6] = $obj_empresa['prefijo'];
    $datosE[7] = $obj_empresa['tipo_persona'];
    $datosE[8] = $obj_empresa['url_service_pdf'];
    $datosE[9] = $obj_empresa['usuario'];
    $datosE[10] = $obj_empresa['clave'];
    $datosE[11] = $obj_empresa['url_envio_json'];
    $datosE[12] = $obj_empresa['ambiente'];

    return $datosE;

}
function retornaDatosCliente($cmd, $res_fiscal, $regimen, $tributo)
{
    $datosE[] = array();
    $sqlResponsabilidad = "SELECT  codigo, significado from fac_e_responsabilidades where id_responsabilidad = $res_fiscal";
    $rs = $cmd->query($sqlResponsabilidad);
    $obj_responsabilidadFiscal = $rs->fetch();

    $sqlRegimen = "SELECT codigo, significado from fac_e_regimen where id_regimen =  $regimen";
    $rs = $cmd->query($sqlRegimen);
    $obj_regimenfiscal = $rs->fetch();

    $sqlTributo = "SELECT codigo, significado from  fac_e_tipo_tributo where  id_tributo = $tributo";
    $rs = $cmd->query($sqlTributo);
    $obj_tributo = $rs->fetch();


    $datosE[0] = $obj_responsabilidadFiscal['codigo'];//codigo de R-99-PN 	
    $datosE[1] = $obj_responsabilidadFiscal['significado'];//No aplica-Otros
    $datosE[2] = $obj_regimenfiscal['codigo'];// 	49 	
    $datosE[3] = $obj_regimenfiscal['significado'];// No Responsable de IVA.
    $datosE[4] = $obj_tributo['codigo'];//zz
    $datosE[5] = $obj_tributo['significado'];//No aplica

    return $datosE;
}
function devolverUltimaFactura($conn)
{
    $sql = "select n_comprobante_actual from tb_factura_electronica";
    if ($result = mysqli_query($conn, $sql)) {

        while ($row = mysqli_fetch_row($result)) {
            $consecutivo_factura = $row[0];
        }
    }
    return $consecutivo_factura;
}
function validarIncremento($cmd, $num_t)
{
    $sql = " SELECT  numero_fac_electronica from facturas where num_ticket = '$num_t'";
    $rs = $cmd->query($sql);
    $obj_estado = $rs->fetch();
    return $obj_estado['numero_fac_electronica'];

}

function actualizarFactura($conn, $num_factura, $num_ticket, $control_actualizar)
{
    if ($control_actualizar == 1) {
        $update = "UPDATE tb_factura_electronica set n_comprobante_actual = '$num_factura'";
        mysqli_query($conn, $update);
        $update = "UPDATE facturas set numero_fac_electronica = '$num_factura' where num_ticket = '$num_ticket'";
        mysqli_query($conn, $update);
    }
}
function insertarResultados($conn, $numero_identificacion, $num_factura, $num_ticket, $resultado)
{
    $respuesta = array();
    
    $estado = $resultado["rerror"];
    $cufe = "";
    $rtaxxadocument = "";
    date_default_timezone_set('America/Bogota');
    $fecha_actual = date('Y-m-d H:i:s');
    if ($estado != 0) {
        $mensaje = mysqli_real_escape_string($conn, $resultado["smessage"]);
        $insertar = insertarResultadosBD($conn, $num_factura, $num_ticket, $numero_identificacion, $estado, $mensaje, $cufe, $rtaxxadocument, $fecha_actual);
        return $insertar;
    }


    if ($estado == 3344) {

        $mensajes = $resultado->smessage->string->{0};
    }

    if ($estado == 1) {

        $mensajes = $resultado->smessage->string->{0};

    }

    if ($estado == 0) {
        $cufe = $resultado->jret->scufe;
        $rtaxxadocument = $resultado->jret->rtaxxadocument;
        $mensajes = "Documento enviado a la DIAN Exitosamente";

    }
    if ($estado == 0) {
        $sql = "UPDATE facturas set estado_final = 1 , cufe = '$cufe' where num_ticket = '$num_ticket'";
        mysqli_query($conn, $sql);

    }
    if ($estado > 10) {
        $mensajes = $mensaje = $resultado->smessage;

    }
    if ($estado > 1 and $estado < 10) {
        $mensajes = $resultado->smessage->string;

    }

    $insertar = insertarResultadosBD($conn, $num_factura, $num_ticket, $numero_identificacion, $estado, $mensaje, $cufe, $rtaxxadocument, $fecha_actual);
    return $insertar;
}

function insertarResultadosBD($conn, $num_factura, $num_ticket, $numero_identificacion, $estado, $mensaje, $cufe, $rtaxxadocument, $fecha_actual)
{
    try {
        $sql = "INSERT INTO resultados (
        num_factura,
        ticket,
        cliente,
        estado,
        mensaje,
        cufe,
        rtaxxadocument,
        fecha_hora
        )
        VALUES 
        (
        '$num_factura',
        '$num_ticket',
        '$numero_identificacion',
        '$estado',
        '$mensaje',
        '$cufe',
        '$rtaxxadocument',
        '$fecha_actual'
        )";
        if (mysqli_query($conn, $sql)) {
            $respuesta["estado"] = true;
        } else {
            
            $respuesta["estado"] = false;

        }
    } catch (PDOException $e) {
        $respuesta["estado"] = true;
        $respuesta["mensaje"] = $e->getMessage();
    }
    return $respuesta;

}


