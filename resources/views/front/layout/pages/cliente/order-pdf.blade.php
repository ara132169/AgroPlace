<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orden #{{ $order->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        .header h1 {
            color: #4CAF50;
            margin: 0;
            font-size: 28px;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .order-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 30px;
        }
        
        .order-info h2 {
            color: #4CAF50;
            margin-top: 0;
            font-size: 20px;
        }
        
        .info-grid {
            display: table;
            width: 100%;
        }
        
        .info-row {
            display: table-row;
        }
        
        .info-label, .info-value {
            display: table-cell;
            padding: 5px 0;
            vertical-align: top;
        }
        
        .info-label {
            font-weight: bold;
            width: 30%;
        }
        
        .info-value {
            width: 70%;
        }
        
        .addresses {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .address-section {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .address-section:first-child {
            margin-right: 2%;
        }
        
        .address-section h3 {
            color: #4CAF50;
            margin-top: 0;
            font-size: 16px;
        }
        
        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        
        .products-table th,
        .products-table td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }
        
        .products-table th {
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
        }
        
        .products-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }
        
        .total-section {
            text-align: right;
            margin-top: 20px;
        }
        
        .total-row {
            margin: 5px 0;
        }
        
        .total-final {
            font-size: 18px;
            font-weight: bold;
            color: #4CAF50;
            border-top: 2px solid #4CAF50;
            padding-top: 10px;
        }
        
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            color: #666;
            font-size: 12px;
        }
        
        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            color: white;
            font-size: 12px;
            text-transform: uppercase;
        }
        
        .status-paid {
            background-color: #28a745;
        }
        
        .status-pending {
            background-color: #ffc107;
            color: #333;
        }
        
        .status-cancelled {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>AgroPlace</h1>
        <p>Recibo de Compra</p>
        <p>{{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="order-info">
        <h2>Información del Pedido</h2>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Número de Orden:</div>
                <div class="info-value">#{{ $order->id }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Compra:</div>
                <div class="info-value">{{ $order->created_at->format('d/m/Y H:i:s') }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado del Pago:</div>
                <div class="info-value">
                    <span class="status-badge status-{{ $order->status }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>
            </div>
            @if($order->stripe_payment_intent_id)
            <div class="info-row">
                <div class="info-label">ID de Transacción:</div>
                <div class="info-value">{{ $order->stripe_payment_intent_id }}</div>
            </div>
            @endif
        </div>
    </div>

    @php
        // Los datos de facturación y envío están en campos separados en el modelo Order
        // Si no hay campos de facturación separados, usamos los datos de envío
        $billingData = [
            'name' => $order->shipping_name ?? 'N/A',
            'email' => $order->shipping_email ?? 'N/A',
            'phone' => $order->shipping_phone ?? 'N/A',
            'address' => $order->shipping_address ?? 'N/A',
            'city' => $order->shipping_city ?? 'N/A',
            'state' => $order->shipping_state ?? 'N/A',
            'zip' => $order->shipping_cp ?? 'N/A',
            'company' => $order->shipping_company ?? null,
            'country' => $order->shipping_country ?? 'N/A'
        ];
        
        $shippingData = [
            'name' => $order->shipping_name ?? 'N/A',
            'address' => $order->shipping_address ?? 'N/A',
            'city' => $order->shipping_city ?? 'N/A',
            'state' => $order->shipping_state ?? 'N/A',
            'zip' => $order->shipping_cp ?? 'N/A',
            'company' => $order->shipping_company ?? null,
            'country' => $order->shipping_country ?? 'N/A'
        ];
    @endphp

    <div class="addresses">
        <div class="address-section">
            <h3>Dirección de Facturación</h3>
            <p><strong>{{ $billingData['name'] }}</strong></p>
            @if($billingData['company'])
                <p>{{ $billingData['company'] }}</p>
            @endif
            <p>{{ $billingData['email'] }}</p>
            <p>{{ $billingData['phone'] }}</p>
            <p>{{ $billingData['address'] }}</p>
            <p>{{ $billingData['city'] }}, {{ $billingData['state'] }} {{ $billingData['zip'] }}</p>
            <p>{{ $billingData['country'] }}</p>
        </div>
        
        <div class="address-section">
            <h3>Dirección de Envío</h3>
            <p><strong>{{ $shippingData['name'] }}</strong></p>
            @if($shippingData['company'])
                <p>{{ $shippingData['company'] }}</p>
            @endif
            <p>{{ $shippingData['address'] }}</p>
            <p>{{ $shippingData['city'] }}, {{ $shippingData['state'] }} {{ $shippingData['zip'] }}</p>
            <p>{{ $shippingData['country'] }}</p>
        </div>
    </div>

    <h2 style="color: #4CAF50;">Productos Comprados</h2>
    <table class="products-table">
        <thead>
            <tr>
                <th>Producto</th>
                <th>Cantidad</th>
                <th>Precio Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($order->items as $item)
                <tr>
                    <td>
                        {{ $item->product->name ?? 'Producto no disponible' }}
                        @if($item->product && $item->product->seller)
                            <br><small>Vendedor: {{ $item->product->seller->name }}</small>
                        @endif
                    </td>
                    <td>{{ $item->quantity }}</td>
                    <td>${{ number_format($item->price, 2) }}</td>
                    <td>${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center;">No se encontraron productos en esta orden</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        @php
            $subtotal = $order->items->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $shipping = 0; // Aquí puedes agregar cálculo de envío si lo tienes
            $tax = 0; // Aquí puedes agregar cálculo de impuestos si lo tienes
        @endphp
        
        <div class="total-row">
            <strong>Subtotal: ${{ number_format($subtotal, 2) }}</strong>
        </div>
        
        @if($shipping > 0)
        <div class="total-row">
            Envío: ${{ number_format($shipping, 2) }}
        </div>
        @endif
        
        @if($tax > 0)
        <div class="total-row">
            Impuestos: ${{ number_format($tax, 2) }}
        </div>
        @endif
        
        <div class="total-row total-final">
            <strong>Total: ${{ number_format($order->total, 2) }}</strong>
        </div>
    </div>

    <div class="footer">
        <p>Gracias por tu compra en AgroMarket</p>
        <p>Para cualquier consulta, contáctanos a través de nuestro sitio web</p>
        <p>Este es un documento generado automáticamente</p>
    </div>
</body>
</html>