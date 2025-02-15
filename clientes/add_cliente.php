<?php

include ('datosFunciones.php');
$pagina_actual = isset($_GET['pagina']) ? intval($_GET['pagina']) : 1;

?>

<head>
	<meta charset="UTF-8">

	<link rel="stylesheet" type="text/css" href="../jquery/themes/default/easyui.css">
	<link rel="stylesheet" type="text/css" href="../jquery/themes/icon.css">
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.css">
	<script type="text/javascript" src="../jquery/jquery.min.js"></script>
	<script type="text/javascript" src="../jquery/jquery.easyui.min.js"></script>
</head>

<table align=center width=300>
	<td>

		<h1 class="page-header">
        <span class="pull-right">
            </span class= "pull-left">
            Gestión de Clientes
            </span>
			<span class="pull-right">

				<a href="new_cliente.php" class="btn btn-success" >Nuevo Cliente</a>
			</span>
		</h1>
		<table id="dg" align=center class="easyui-datagrid" title="Clientes" style="width:750px;height:340px; ">

			<thead>
				<tr>

					<th data-options="field:'Identificación',width:100">Identificación</th>
					<th data-options="field:'Nombre',width:250">Nombre</th>
					<th data-options="field:'Email',width:180">Email</th>
					<th data-options="field:'editar',width:60"></th>
					<th data-options="field:'accion',width:60"> Eliminar</th>

				</tr>

			</thead>
			<?php clientesTodos($pagina_actual); ?>
    
		</table>
</table>
<div class="alert alert-danger" role="alert" style="display: none;">

</div>
<div class="alert alert-danger" role="alert" style="display: none;">

</div>