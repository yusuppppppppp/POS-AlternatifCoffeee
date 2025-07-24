<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h2 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background: #f2f2f2; }
        .order-items { margin: 0; padding-left: 15px; }
        .order-items li { margin-bottom: 2px; }
    </style>
</head>
<body>
    <h2>History Pembelian Hari Ini ({{ date('d-m-Y') }})</h2>
    <table>
        <thead>
            <tr>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Date</th>
                <th>Total</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr>
                    <td>{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->order_type }}</td>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td>Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <ul class="order-items">
                        @foreach($order->items as $item)
                            <li>{{ $item->quantity }}x {{ $item->menu_name }} - Rp. {{ number_format($item->price, 0, ',', '.') }}</li>
                        @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 