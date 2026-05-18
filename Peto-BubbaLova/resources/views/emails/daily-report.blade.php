<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: sans-serif; line-height: 1.6; color: #333; }
        .container { padding: 20px; border: 1px solid #eee; border-radius: 5px; max-width: 600px; }
        .header { border-bottom: 2px solid #4f46e5; padding-bottom: 10px; margin-bottom: 20px; }
        .footer { margin-top: 20px; font-size: 12px; color: #777; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2 style="color: #4f46e5;">Reporte Diario de Pedidos</h2>
        </div>
        <p>Hola Administrador,</p>
        <p>Se adjunta el reporte detallado de los pedidos realizados el día <strong>{{ $date }}</strong> en BubbaLova.</p>
        <p>En el documento PDF adjunto encontrarás:</p>
        <ul>
            <li>Resumen de ventas por cajero.</li>
            <li>Listado detallado de cada pedido y sus productos.</li>
            <li>Detalle de insumos utilizados.</li>
            <li>Gran total de ventas del día.</li>
        </ul>
        <div class="footer">
            Este es un correo automático generado por el sistema de gestión BubbaLova.
        </div>
    </div>
</body>
</html>