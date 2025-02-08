<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tabla de Facturas</title>
    <style>
        /* Estilos generales */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            height: 100vh; /* Alto completo de la pantalla */
            display: flex;
            justify-content: center; /* Centrado horizontal */
            align-items: flex-start; /* Alinea desde el inicio en el eje vertical */
        }

        .container {
            width: 90vw; /* Ocupa el 90% del ancho de la pantalla */
            height: calc(100vh - 40px); /* Alto completo de la pantalla con márgenes arriba y abajo */
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            margin-top: 20px; /* Espacio en la parte superior */
            margin-bottom: 20px; /* Espacio en la parte inferior */
        }

        /* Estilo para la cabecera */
        .header {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 10px;
            font-size: 18px;
        }

        /* Estilo para la tabla */
        .table-container {
            padding: 15px;
            overflow-x: auto;
            flex-grow: 1; /* La tabla ocupará el resto del espacio disponible */
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: center;
        }

        th, td {
            padding: 8px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        /* Estilo para el pie de página */
        .footer {
            background-color: #4CAF50;
            color: white;
            text-align: center;
            padding: 8px;
            font-size: 12px;
        }

        /* Botón de Enviar */
        .btn-enviar {
            background-color: #008CBA;
            color: white;
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-enviar:hover {
            background-color: #005f6b;
        }
    </style>
</head>
<body>

<div class="container">
    <!-- Cabecera -->
    <div class="header">
        Facturas Pendientes
    </div>

    <!-- Tabla de Facturas -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th>Número de Factura</th>
                    <th>Ticket</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Enviar</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>001</td>
                    <td>#12345</td>
                    <td>2025-02-08</td>
                    <td>Pendiente</td>
                    <td><a href="#" class="btn-enviar">Enviar</a></td>
                </tr>
                <tr>
                    <td>002</td>
                    <td>#12346</td>
                    <td>2025-02-07</td>
                    <td>Completada</td>
                    <td><a href="#" class="btn-enviar">Enviar</a></td>
                </tr>
              
                <!-- Agregar más filas según sea necesario -->
            </tbody>
        </table>
    </div>

    <!-- Pie de página -->
    <div class="footer">
        &copy; 2025 Empresa XYZ. Todos los derechos reservados.
    </div>
</div>

</body>
</html>
