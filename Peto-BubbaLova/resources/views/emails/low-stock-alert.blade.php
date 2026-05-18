<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .container { max-width: 600px; background: #ffffff; margin: 0 auto; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-top: 6px solid #e53e3e; }
        .header { background: #fff5f5; padding: 30px; text-align: center; }
        .header h1 { color: #c53030; margin: 0; font-size: 24px; text-transform: uppercase; letter-spacing: 1px; }
        .content { padding: 30px; color: #4a5568; }
        .alert-box { background: #fffaf0; border-left: 4px solid #f6ad55; padding: 15px; margin-bottom: 25px; border-radius: 4px; }
        table { width: 100%; border-collapse: separate; border-spacing: 0; margin-top: 10px; border-radius: 8px; overflow: hidden; border: 1px solid #e2e8f0; }
        th { background: #edf2f7; color: #2d3748; padding: 15px; text-align: left; font-size: 13px; font-weight: 700; text-transform: uppercase; }
        td { padding: 15px; border-top: 1px solid #e2e8f0; font-size: 15px; }
        .stock-badge { background: #fed7d7; color: #9b2c2c; padding: 4px 10px; border-radius: 9999px; font-weight: bold; font-size: 14px; }
        .footer { background: #f8fafc; padding: 20px; text-align: center; font-size: 12px; color: #718096; border-top: 1px solid #e2e8f0; }
        .button { display: inline-block; padding: 12px 24px; background-color: #4c51bf; color: #ffffff !important; text-decoration: none; border-radius: 6px; font-weight: 600; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>⚠️ Alerta de Stock Crítico</h1>
        </div>
        <div class="content">
            <div class="alert-box">
                Estimado Administrador, se han detectado materiales que requieren reposición inmediata para asegurar la operación.
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th>Material</th>
                        <th>Estado Actual</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lowStockMaterials as $material)
                    <tr>
                        <td style="font-weight: 500;">{{ $material->name }}</td>
                        <td><span class="stock-badge">{{ $material->quantity }} unidades</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="text-align: center;">
                <a href="{{ url('/admin') }}" class="button">Gestionar Inventario</a>
            </div>
        </div>
        <div class="footer">
            Este es un aviso automático del sistema de inventario BubbaLova.<br>
            &copy; {{ date('Y') }} BubbaLova - Gestión Administrativa.
        </div>
    </div>
</body>
</html>
