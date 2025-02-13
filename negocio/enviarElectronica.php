<?php
include('utilidadesElectronica.php');
function enviarFacturaElectronica($conn, $numeroFactura, $cmd): array
{
    $respuesta = array();
    try {

        $fechaActual = date('Y-m-d');
        $num_ticket = $numeroFactura;
        $obtenerLineas = obtenerLineasDetalles($cmd, $num_ticket);
        $adocumentitems = obtenerjTax($obtenerLineas);
        $datosEmpresa[] = array();
        $datosFacturaelectronica[] = array();
        $datosCliente[] = array();
        $datosEmpresaclean = retornarDatosempresa($cmd);
        $datosFacturaelectronicaclean = devolverDatoselectronica($cmd);
        $obtenerCliente = obtenerCliente($cmd, $num_ticket);
        $fechaformato = $fechaActual . "T" . $obtenerCliente['hora'];

        $numero_identificacion = $obtenerCliente['identificacion'];
        $numero_f = $obtenerCliente['numero_f'];
        $final_fac = $obtenerCliente['final_fac'];
        if (str_contains($numero_identificacion, "-")) {
            $partes = explode("-", $numero_identificacion);
            $numero_identificacion = $partes[0];
        }
        $jpartylegalentity = array(
            'wdoctype' => $obtenerCliente['tipoI'], //CC,NIT
            'sdocno' => $numero_identificacion,
            'scorporateregistrationschemename' => $obtenerCliente['nombre']
        );
        $jtaxrepresentativeparty = array(
            'wdoctype' => $obtenerCliente['tipoI'], //CC,NIT
            'sdocno' => $numero_identificacion
        );
        $apartytaxschemes[0] = array(
            'wdoctype' => $obtenerCliente['tipoI'], //CC,NIT
            'sdocno' => $numero_identificacion,
            'spartyname' => $obtenerCliente['nombre'],
            'sregistrationname' => $obtenerCliente['nombre']
        );
        $departamento = substr($obtenerCliente['cod_postal'], 0, 2);
        $municipio = substr($obtenerCliente['cod_postal'], 3, 3);
        ///aqiuo modificar
        $jregistrationaddress = array(
            'wdepartmentcode' => $departamento,
            'scityname' => $obtenerCliente['municipio'],
            'saddressline1' => $obtenerCliente['direccion'],
            'scountrycode' => 'CO',
            'sDepartmentName' => $obtenerCliente['departamento'],
            'sProvinceName' => $obtenerCliente['departamento'],
            'wprovincecode' => $obtenerCliente['cod_postal'], //CodigoDepartamento+CodigoMunicipio
            'szip' => $obtenerCliente['cod_postal']
        );
        $jphysicallocationaddress = array(
            'scityname' => $obtenerCliente['departamento'],
            'saddressline1' => $obtenerCliente['direccion'],
            'wcountrycode' => 'CO',
            'szip' => $datosEmpresaclean[10],
            'wdepartmentcode' => $departamento,
            'wprovincecode' => $obtenerCliente['cod_postal'],
            'sDepartmentName' => $obtenerCliente['departamento'],
            'sProvinceName' => $obtenerCliente['departamento'],
            'wlanguage' => 'es'
        );

        $jcontact = array(
            'selectronicmail' => $obtenerCliente['email'],
            'stelephone' => $obtenerCliente['telefono'],
            'jregistrationaddress' => $jregistrationaddress,
            'jphysicallocationaddress' => $jphysicallocationaddress
        );
        if ($obtenerCliente['tipo_persona'] == 1) {
            $orgaTipe = 'company';
        } else {
            $orgaTipe = 'person';
        }

        $datosCliente = retornaDatosCliente($cmd, $obtenerCliente['res_fiscal'], $obtenerCliente['regimen'], $obtenerCliente['tributo']);
        $arraydata = implode(',', $datosCliente);
        $datosClientet = explode(",", $arraydata);

        $jbuyer = array(
            'wlegalorganizationtype' => $orgaTipe, // company(Persona Juridica),person(Persona natural)
            'stributaryidentificationkey' => $datosClientet[4], //tabla fac_e_tipo_tributo
            'stributaryidentificationname' => $datosClientet[5],
            'sfiscalresponsibilities' => $datosClientet[0], //tabla fac_e_responsabilidades
            'sfiscalregime' => $datosClientet[2], // tabla fac_e_regimen
            'jpartylegalentity' => $jpartylegalentity,
            'jtaxrepresentativeparty' => $jtaxrepresentativeparty,
            'apartytaxschemes' => $apartytaxschemes,
            'jcontact' => $jcontact

        );
        $jpartylegalentity = array(
            'wdoctype' => 'NIT',
            'sdocno' => $datosEmpresaclean[7], //NIT de la Empresa
            'scorporateregistrationschemename' => $datosEmpresaclean[0],
        );
        $jtaxrepresentativeparty = array(
            'wdoctype' => 'NIT',
            'sdocno' => $datosEmpresaclean[7]//nit de la empresa
        );
        $apartytaxschemes[0] = array(
            'wdoctype' => 'NIT',
            'sdocno' => $datosEmpresaclean[7], //Nit de la Empresa
            'spartyname' => $datosEmpresaclean[0], //Nombre de la empresa
            'sregistrationname' => $datosEmpresaclean[0]//Nombre de la empresa

        );
        $jregistrationaddress = array(
            'wdepartmentcode' => $datosEmpresaclean[9], //Codigo del departamento donde se encuentra la empresa
            'scityname' => $datosEmpresaclean[2], //Nombre del municipio donde se encuentra la empresa
            'saddressline1' => $datosEmpresaclean[1], //Direccion de la empresa
            'scountrycode' => 'CO',
            'sdepartmentname' => $datosEmpresaclean[3], //Nombre del departamento
            'sprovincename' => $datosEmpresaclean[3], //Nombre del departamento
            'wprovincecode' => $datosEmpresaclean[9] . $datosEmpresaclean[8], //Codigo del departamento+codigo del municipio
            'szip' => $datosEmpresaclean[10]//Codigo del codigo postal de la empresa
        );
        $jphysicallocationaddress = array(
            'scityname' => $datosEmpresaclean[2], //Nombre del municipio donde se encuentra la empresa
            'saddressline1' => $datosEmpresaclean[1], //Direccion de la empresa
            'wcountrycode' => 'CO',
            'szip' => $datosEmpresaclean[10], //Codigo del codigo postal de la empresa
            'wdepartmentcode' => $datosEmpresaclean[9], //Codigo del departamento donde se encuentra la empresa
            'wprovincecode' => $datosEmpresaclean[9] . $datosEmpresaclean[8], //Codigo del departamento+codigo del municipio
            'sdepartmentname' => $datosEmpresaclean[3], //Nombre del departamento
            'sprovincename' => $datosEmpresaclean[3], //Nombre del departamento
            'wlanguage' => 'es'
        );
        $jcontact = array(
            'selectronicmail' => $datosEmpresaclean[6], //Correo de la empresa registrado en el portal de la DIAN
            'stelephone' => $datosEmpresaclean[5],
            'jregistrationaddress' => $jregistrationaddress,
            'jphysicallocationaddress' => $jphysicallocationaddress
        );
        $jseller = array(
            'wlegalorganizationtype' => 'company', //'company' : 'person',
            'stributaryidentificationkey' => $datosFacturaelectronicaclean[4], //tabla fac_e_tipo_tributo
            'stributaryidentificationname' => $datosFacturaelectronicaclean[5], ////tabla fac_e_tipo_tributo
            'sfiscalresponsibilities' => $datosFacturaelectronicaclean[0],
            'sfiscalregime' => $datosFacturaelectronicaclean[2],
            'jpartylegalentity' => $jpartylegalentity,
            'jtaxrepresentativeparty' => $jtaxrepresentativeparty,
            'apartytaxschemes' => $apartytaxschemes,
            'jcontact' => $jcontact
        );


        $control_actualizar = 1;
        $num_factura = devolverUltimaFactura($conn);
        $valida_incr = validarIncremento($cmd, $num_ticket);

        if ($numero_f == 0 && $final_fac == 0) {
            $num_factura += 1;
        } else {
            $num_factura = $numero_f;
            $control_actualizar = 0;
        }
        //$num_factura = '21390'; 
        $jDocument = array(
            'wdocumenttype' => 'Invoice',
            'wdocumentsubtype' => '9',
            'rdocumenttemplate' => '26190537',
            "stemplatename" => "formatTirillaGeneric",
            'sauthorizationprefix' => $datosFacturaelectronicaclean[6],
            'sdocumentsuffix' => $num_factura,
            'tissuedate' => $fechaformato,
            'tduedate' => $fechaActual, //$fechaformato
            'wpaymentmeans' => '1', //1:Contado;2:Credito
            'wpaymentmethod' => $obtenerCliente['forma_pago'], //10:Efectivo
            'wbusinessregimen' => '1', //1=Persona Juridica;2=Persona Natural
            'woperationtype' => '10', /* 10: Operacion Estandar */
            'snoteTop' => 'Esta factura se asimila a una la Letra de Cambio (Según el artículo 774 C.C)',
            'adocumentitems' => $adocumentitems,
            'jbuyer' => $jbuyer,
            'jseller' => $jseller
        );
        $ambiente = 2;
        $sEmail = 'demo@taxxa.co';
        $sPass = 'Demo2022*';
        $url = 'https://api.taxxa.co:81/api.djson?demo1';

        $jParams = array(
            'sEmail' => $sEmail,
            'sPass' => $sPass,
        );

        $jApiToken = array(
            'sMethod' => 'classTaxxa.fjTokenGenerate',
            'jParams' => $jParams
        );

        $token = array('jApi' => $jApiToken);
        $datatoken = json_encode($token);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $datatoken);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $restoken = curl_exec($ch);
        $rst = json_decode($restoken);
        $tokenApi = $rst->jret->stoken;

        $jParams = array(
            'wFormat' => 'taxxa.co.dian.document',
            'wVersionUBL' => 2,
            'wEnvironment' => $ambiente == 1 ? 'prod' : 'test',
            'jDocument' => $jDocument
        );

        $jApi = array(
            'sMethod' => 'classTaxxa.fjDocumentAdd',
            'jParams' => $jParams
        );

        $iNonce = 100 + rand();//Generar un consecutivo
        $factura = array(
            'sToken' => $tokenApi,
            'iNonce' => $iNonce,
            'jApi' => $jApi
        );

        json_encode($factura);
        file_put_contents("json.txt", json_encode($factura));
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($factura));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $resfac = curl_exec($ch);//Envio de la factura 

        $resultado = json_decode($resfac, true);
        if ($resultado == null) {
            $respuesta['mensaje'] = "No hay conexión a la pasarella";
            $respuesta['estado'] = false;

        }
        actualizarFactura($conn, $num_factura, $num_ticket, $control_actualizar);
        $resultadosFin = insertarResultados($cmd, $conn, $numero_identificacion, $num_factura, $num_ticket, $resultado);
        $respuesta["mensaje"] = $resultadosFin["mensaje"];
        $respuesta["estado"] = $resultadosFin["estado"];
        $respuesta["cufe"] = $resultadosFin["cufe"];


    } catch (Exception $e) {
        $respuesta["mensaje"] = $e->getMessage();
        $respuesta["estado"] = false;
    }
    return $respuesta;

}