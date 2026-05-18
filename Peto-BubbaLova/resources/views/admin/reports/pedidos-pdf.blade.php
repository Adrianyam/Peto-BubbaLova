<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Pedidos - {{ $date }}</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }
        .logo-text {
            font-size: 24px;
            font-weight: bold;
            color: #4f46e5;
        }
        .stats {
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th {
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: left;
        }
        td {
            border: 1px solid #d1d5db;
            padding: 8px;
        }
        .total-row {
            font-weight: bold;
            background-color: #f9fafb;
        }
        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            text-transform: uppercase;
        }
        .footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
            border-top: 1px solid #d1d5db;
            padding-top: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-text">BubbaLova</div>
        <div>Reporte de Ventas y Pedidos</div>
        <div>{{ $date }}</div>
    </div>

    <div class="stats">
        <h3>Resumen por Cajero</h3>
        <table>
            <thead>
                <tr>
                    <th>Cajero</th>
                    <th>Cantidad de Pedidos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cashierStats as $stat)
                <tr>
                    <td>{{ $stat->name }} {{ $stat->last_name }}</td>
                    <td>{{ $stat->orders_count }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h3>Registro de Actividad</h3>
    <table>
        <thead>
            <tr>
                <th>Hora</th>
                <th>Cajero</th>
                <th>Cliente</th>
                <th>Productos</th>
                <th>Insumos</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @php $granTotal = 0; @endphp
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->created_at->format('h:i A') }}</td>
                <td>{{ $order->user->name }}</td>
                <td>{{ $order->customer_name ?? 'N/A' }}</td>
                <td>
                    @foreach($order->items as $item)
                        • {{ $item->quantity }}x {{ $item->product->name }}<br>
                    @endforeach
                </td>
                <td>
                    @foreach($order->materials as $material)
                        • {{ $material->pivot->quantity }}x {{ $material->name }}<br>
                    @endforeach
                </td>
                <td>${{ number_format($order->total, 2) }}</td>
            </tr>
            @php $granTotal += $order->total; @endphp
            @endforeach
            <tr class="total-row">
                <td colspan="5" style="text-align: right;">TOTAL DEL DÍA:</td>
                <td>${{ number_format($granTotal, 2) }}</td>
            </tr>
        </tbody>
    </table>

    <div class="footer">
        Generado el {{ now()->format('d/m/Y h:i A') }} - BubbaLova Management System
    </div>
</body>
</html>