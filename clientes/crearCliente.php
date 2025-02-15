<?php include ('funcionesUtilidades.php');
include ('funcionesCliente.php');
include ('../core/conexion.php'); ?>
<input type=hidden value=$id_factura name=id_factura>
<link href="css/estilo.css" rel="stylesheet" type="text/css" />
<script src="funcionesCliente.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function cargarCodigo() {
    let departamento = document.getElementById('departamento').value;
    let ciudad = document.getElementById('municipio').value;
    $.ajax({
        url: 'getMunicipios.php',
        type: 'GET',
        data: {
            departamento: departamento,
            ciudad: ciudad,
            tipo: 1
        },
        
        success: function (respuesta) {
            document.getElementById("cod_postal").value = respuesta[0].codigo;

        },
        error: function (xhr, status, error) {
            // Manejamos errores si los hay
            console.log('Error: ' + error);
        }
    });

}
function cargarMunicipios() {


let departamento = document.getElementById('departamento').value;
document.getElementById("municipio").innerHTML = "";


$.ajax({
    url: 'getMunicipios.php',
    type: 'GET',
    data: {
        departamento: departamento,
        tipo: 0
    },
    success: function (respuesta) {

        let select = document.getElementById("municipio");
        let option = document.createElement("option");
        select.appendChild(option);
        respuesta.forEach(function (opcion) {
            var option = document.createElement("option");
            option.value = opcion.id_municipio;
            option.text = opcion.nom_municipio;
            select.appendChild(option);
        });
    },
    error: function (xhr, status, error) {
        // Manejamos errores si los hay
        console.log('Error: ' + error);
    }
});


}
</script>
<form method="POST">
    <table class=table1>
    <tr><td colspan="3"><a href = "editarCliente.php" target = "admin" >Editar cliente</a></td></tr>
        <tr class=letras>
            <td> Tipo Identificación &nbsp;<br><br>
                <select name="tipoIdentificacion" id="tipoIdentificacion"  required>
                <option value="">--Seleccione-- </option>
                    <option value="CC">Cédula </option>
                    <option value="NIT">NIT</option>
                    <option value="CE">Cedula de Extranjeria</option>


                </select>
            </td>
        </tr>
        <tr class=letras>
            <td>Identificación &nbsp;<br><input type=number required name=iden_clie title="Ingrese la identificación">
            </td>
            <td>Cod Postal &nbsp;<br><input type=number required id="cod_postal" name=cod_postal readonly></td>
        </tr>
        <tr class=letras>
            <td>Nombre &nbsp;<br><input type=text name=razon_social size=40 required></td>
            <td>Dirección &nbsp;<br><input type=text required name=direccion value = "Sin definir"></td>
        </tr>
        <tr class=letras>

            <td> Deparamento<br>
                <select id="departamento" name="depar" onchange="cargarMunicipios()">
                    <option value>-Seleccione-</option>
                    <?php getDepartamenos($conn) ?>&nbsp;<br>
                </select>
            </td>
            <td>Municipio &nbsp;<br>
                <select name="municipio" id="municipio" onchange="cargarCodigo()">
                    <option value="">-Seleccione-</option>
            </td>
        </tr>
        <tr class=letras>
            <td>Pais &nbsp;<br><input type=text required name=pais value ="COLOMBIA"></td>
            <td>Contacto &nbsp;<br><input type=text required name=telefono value = "0"></td>
        </tr>
        <tr class=letras>
            <td>Email &nbsp;<br><input type=email required name=email size=40></td>
            <td>Tipo Persona &nbsp;<br><select name=tipo_p required>
                    <?php selecTipopersona(2); ?>

            </td>
        </tr>
        <tr class=letras>
            <td>Responsabilidad Fiscal&nbsp;<br>
                <select name=res_fiscal>

                    <?php selectResponsabilidadfiscal($conn, 'a'); ?>

            </td>
            <td>Régimen &nbsp;<br><select name=res_regimen>
                    <?php selectRegimen($conn, 'a'); ?>

            </td>
        </tr>
        <tr class=letras>
            <td>Tríbuto<br><select name=tributo>s
                    <?php selectTributo($conn, 'a'); ?>
            <td>
                <input type=submit value='Guardar Cliente' class=label>
            </td>
        </tr>
    </table>
</form>

<?php

if (isset($_POST["iden_clie"]) && !empty($_POST["iden_clie"])) {
    $identificacion = $_POST["iden_clie"];
    $nombre = $_POST["razon_social"];
    $codigoPostal = $_POST['cod_postal'];
    $direccion = $_POST['direccion'];
    $municipio = $_POST['municipio'];
    $departamento = $_POST['depar'];
    $pais = $_POST['pais'];
    $telefono = $_POST['telefono'];
    $email = $_POST['email'];
    $tipo_persona = $_POST['tipo_p'];
    $resFiscal = $_POST['res_fiscal'];
    $res_regimen = $_POST['res_regimen'];
    $tributo = $_POST['tributo'];
    $tipoIdentificacion = $_POST['tipoIdentificacion'];

    try {
        $sql = "INSERT INTO clientes(
                                  cod_cliente,
                                  razon_social,
                                  cod_postal,
                                  direccion,
                                  municipio,
                                  departamento,
                                  pais,
                                  telefono,
                                  email,
                                  identificacion, 
                                  tipo_persona,
                                  responsabilidad_fiscal,
                                  regimen_fiscal,
                                  tributo,tipoIdentificacion
                                     ) 
                                     VALUES 
                                     ('$identificacion',
                                     '$nombre',
                                     '$codigoPostal',
                                     '$direccion',
                                     '$municipio',
                                     '$departamento',
                                     '$pais',
                                     '$telefono',
                                     '$email',
                                     '$identificacion',
                                     '$tipo_persona',
                                     '$resFiscal',
                                     '$res_regimen',
                                     '$tributo',
                                     '$tipoIdentificacion'
                                      )";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            print "<script>alert('Cliente Creado');</script>";
        }
    } catch (Exception $e) {
        echo "A ocurrido un error: " . $e->getMessage();
    }



}

?>