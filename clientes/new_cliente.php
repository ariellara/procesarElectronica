<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">

    <head>
        <meta charset="UTF-8">

        <link rel="stylesheet" type="text/css" href="../jquery/themes/default/easyui.css">
        <link rel="stylesheet" type="text/css" href="../jquery/themes/icon.css">
        <link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
        <script type="text/javascript" src="../jquery/jquery.min.js"></script>
        <script type="text/javascript" src="../jquery/jquery.easyui.min.js"></script>
        <script src="../js/funciones.js"></script>

    </head>
</head>
<style>
    .form-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        /* 3 columnas de igual ancho */
        gap: 10px;
        /* Espacio entre columnas y filas */
    }

    .form-group {
        display: flex;
        flex-direction: column;
        /* Coloca los elementos verticalmente uno encima del otro */
        margin-bottom: 10px;
        /* Espacio inferior para cada grupo de formulario */
    }

    label {
        margin-bottom: 1px;
        /* Espacio entre la etiqueta y el campo de entrada */
        font-weight: bold;
        /* Opcional: negrita para las etiquetas */
    }

    /* Asegúrate de que los elementos de entrada se ajusten al contenedor */
    .easyui-textbox {
        width: 100%;
    }
</style>
<?php
include('datosFunciones.php');
include('../conexion/conexion.php');
?>

<body>
    <table align=center>
        <tr>
            <td>
                <div style="margin:20px 0;"></div>
                <div class="easyui-panel" title="Nuevo Cliente" style="width:900px;max-width:1000px;padding:10px 80px;"
                    value=>
                    <form id="ff" method="post">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="tipoIdentificacion">Tipo de Documento:</label>
                                <select class="easyui-select" style="width:100%" id="tipoIdentificacion">
                                    <option value="">Seleccione</option>
                                    <option value="13">Cedula</option>
                                    <option value="31">Nít</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="identificacion">Identificación</label>
                                <input class="easyui-textbox" id="identificacion" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <input class="easyui-textbox" id="nombre" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <input class="easyui-textbox" id="direccion" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label for="departamento">Departamento:</label>
                                <select class="easyui-select" style="width:100%" id="departamento"
                                    onchange="cargarMunicipios()">
                                    <option value="">Seleccione</option>
                                    <?php getDepartamenos($conn) ?>
                                </select>

                            </div>
                            <div class="form-group">
                                <label for="municipio">Municipio</label>
                                <select class="easyui-select" style="width:100%" id="municipio"
                                    onchange="cargarCodigo()">
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="pais">Pais</label>
                                <input class="easyui-textbox" id="pais" value="COLOMBIA" style="width:100%">
                            </div>
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <input class="easyui-textbox"  labelPosition="top"
                                    style="width:100%" id="telefono">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input class="easyui-textbox" id="email" style="width:100%">
                            </div>
                           
                            <div class="form-group">
                                <label for="tipo_p">Tipo Persona</label>
                                <select id="tipo_p" required class="easyui-select" style="width:100%">
                                    <?php selecTipopersona(2); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="res_fiscal">Responsabilidad Fiscal</label>
                                <select id="res_fiscal" class="easyui-select" style="width:100%">
                                    <?php selectResponsabilidadfiscal($conn, 'a'); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="res_regimen">Régimen </label>
                                <select id="res_regimen"  class="easyui-select" style="width:100%">
                                    <?php selectRegimen($conn, 'a'); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tributo">Tríbuto </label>
                                <select id="tributo"  class="easyui-select" style="width:100%">
                                    <?php selectTributo($conn, 'a'); ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="codigo_postal">Codigo Postal:</label>
                                <input type=text id="codigo_postal" style="width:100%" readonly>
                            </div>

                        </div>
                        <div id="mensajeError"></div>
                        <div class="alert alert-success" role="alert" style="display: none;"></div>
                        <div class="alert alert-danger" role="alert" style="display: none;"></div>
                        <div style="text-align:center;padding:5px 0">
                            <img id="cargando" src="../img/cargando.gif" height="25" width="25" style="display:none">
                            <input type="button" id="btnGuardar" class="btn btn-primary" value="Guardar"
                                onclick="guardarCliente()">
                            <input type="button" id="btnLimpiar" class="btn btn-primary" value="Limpiar"
                                onclick="clearForm()">
                        </div>
                        <div>
                            <a href="add_cliente.php" class="btn btn-link" target="admin">Regresar</a>
                        </div>
                    </form>



                </div>

            </td>
        <tr>
    </table>

</body>

</html>
<?php


?>