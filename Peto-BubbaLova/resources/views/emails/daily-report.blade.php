<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f0f2f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border-top: 6px solid #4f46e5; }
        .header { background: #eef2ff; padding: 35px 30px; text-align: center; }
        .header h1 { color: #4338ca; margin: 0; font-size: 26px; font-weight: 800; letter-spacing: -0.5px; }
        .header p { color: #6366f1; margin: 5px 0 0 0; font-weight: 600; text-transform: uppercase; font-size: 13px; }
        .content { padding: 40px 35px; color: #374151; line-height: 1.6; }
        .intro { font-size: 18px; font-weight: 600; color: #111827; margin-bottom: 20px; }
        .info-card { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 10px; padding: 20px; margin-bottom: 30px; }
        .feature-list { list-style: none; padding: 0; margin: 0; }
        .feature-item { padding: 12px 15px; border-bottom: 1px solid #f3f4f6; display: flex; align-items: center; }
        .feature-item:last-child { border-bottom: none; }
        .bullet { color: #4f46e5; margin-right: 12px; font-weight: bold; }
        .footer { background: #ffffff; padding: 25px; text-align: center; font-size: 13px; color: #9ca3af; border-top: 1px solid #f3f4f6; }
        .button { display: inline-block; padding: 14px 30px; background-color: #4338ca; color: #ffffff !important; text-decoration: none; border-radius: 8px; font-weight: 700; margin-top: 25px; box-shadow: 0 4px 6px -1px rgba(79, 70, 229, 0.2); }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <p>Resumen Administrativo</p>
            <h1>Reporte Diario de Pedidos</h1>
        </div>
        <div class="content">
            <div class="intro">Hola, Administrador</div>
            
            <p>Se ha generado el reporte consolidado de las operaciones realizadas en <strong>BubbaLova</strong>.</p>

            <div class="info-card">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">FECHA DEL REPORTE</div>
                <div style="font-size: 20px; font-weight: 800; color: #1f2937;">{{ $date }}</div>
            </div>

            <p>En el documento <strong>PDF adjunto</strong> encontrará el desglose detallado que incluye:</p>
            
            <div class="feature-list">
                <div class="feature-item"><span style="color: #4f46e5; font-weight: bold; margin-right: 10px;">✓</span> Resumen de ventas por cajero.</div>
                <div class="feature-item"><span style="color: #4f46e5; font-weight: bold; margin-right: 10px;">✓</span> Listado detallado de pedidos y productos.</div>
                <div class="feature-item"><span style="color: #4f46e5; font-weight: bold; margin-right: 10px;">✓</span> Análisis de insumos y materiales utilizados.</div>
                <div class="feature-item"><span style="color: #4f46e5; font-weight: bold; margin-right: 10px;">✓</span> Gran total de ingresos del día.</div>
            </div>

            <div style="text-align: center;">
                <a href="{{ url('/admin') }}" class="button">Ir al Panel de Control</a>
            </div>
        </div>
        <div class="footer">
            Este es un reporte automático generado por el sistema BubbaLova.<br>
            <strong>&copy; {{ date('Y') }} BubbaLova - Gestión Administrativa</strong>
        </div>
    </div>
</body>
</html>
