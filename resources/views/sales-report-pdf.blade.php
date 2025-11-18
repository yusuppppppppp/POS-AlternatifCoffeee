<html>
<head>
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 12px; 
            margin: 20px;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 10px; 
            color: #2E4766;
            font-size: 18px;
        }
        h2 { 
            text-align: center; 
            margin-bottom: 20px; 
            color: #495057;
            font-size: 14px;
        }
        .summary-section {
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        .summary-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        .summary-item {
            display: table-cell;
            width: 33.33%;
            text-align: center;
            padding: 10px;
            border: 1px solid #dee2e6;
        }
        .summary-label {
            font-weight: bold;
            color: #495057;
            font-size: 11px;
        }
        .summary-value {
            font-size: 14px;
            font-weight: bold;
            color: #2E4766;
            margin-top: 5px;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 6px; 
            text-align: left; 
            font-size: 10px;
        }
        th { 
            background: #2E4766; 
            color: white;
            font-weight: bold;
        }
        .order-items { 
            margin: 0; 
            padding-left: 15px; 
            font-size: 9px;
        }
        .order-items li { 
            margin-bottom: 2px; 
        }
        .period-info {
            text-align: center;
            margin-bottom: 15px;
            color: #6c757d;
            font-style: italic;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Sales Report</h1>
    
    @php
        $periodText = '';
        // Use filterType if available, otherwise fall back to period
        $displayType = $filterType ?? $period ?? 'single';
        
        switch($displayType) {
            case 'single':
                // Check if we have a specific date from request or orders
                if (isset($selectedDate) && !empty($selectedDate)) {
                    $dateObj = \Carbon\Carbon::parse($selectedDate);
                    $periodText = 'Single Date (' . $dateObj->format('d-m-Y') . ')';
                } elseif (isset($orders) && $orders->count() > 0) {
                    $firstOrderDate = $orders->first()->created_at->format('d-m-Y');
                    $periodText = 'Single Date (' . $firstOrderDate . ')';
                } else {
                    $periodText = 'Single Date';
                }
                break;
            case 'range':
                $periodText = 'Date Range';
                if (isset($startDate) && isset($endDate) && !empty($startDate) && !empty($endDate)) {
                    $startDateObj = \Carbon\Carbon::parse($startDate);
                    $endDateObj = \Carbon\Carbon::parse($endDate);
                    $periodText .= ' (' . $startDateObj->format('d-m-Y') . ' - ' . $endDateObj->format('d-m-Y') . ')';
                } elseif (isset($orders) && $orders->count() > 0) {
                    $firstDate = $orders->last()->created_at->format('d-m-Y');
                    $lastDate = $orders->first()->created_at->format('d-m-Y');
                    $periodText .= ' (' . $firstDate . ' - ' . $lastDate . ')';
                }
                break;
            case 'week':
                $periodText = 'Mingguan (' . now()->startOfWeek()->format('d-m-Y') . ' - ' . now()->endOfWeek()->format('d-m-Y') . ')';
                break;
            case 'month':
                $periodText = 'Bulanan (' . now()->format('F Y') . ')';
                break;
            case 'today':
                $periodText = 'Today (' . now()->format('d-m-Y') . ')';
                break;
            case 'custom':
                $periodText = 'Custom Range';
                if (isset($orders) && $orders->count() > 0) {
                    $firstDate = $orders->last()->created_at->format('d-m-Y');
                    $lastDate = $orders->first()->created_at->format('d-m-Y');
                    $periodText .= ' (' . $firstDate . ' - ' . $lastDate . ')';
                }
                break;
            default:
                $periodText = 'Sales Report';
        }
    @endphp
    
    <div class="period-info">{{ $periodText }}</div>
    
    <div class="summary-section">
        <div class="summary-grid">
            <div class="summary-item">
                <div class="summary-label">Total Orders</div>
                <div class="summary-value">{{ $totalOrders }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Total Revenue</div>
                <div class="summary-value">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
            </div>
            <div class="summary-item">
                <div class="summary-label">Average Order Value</div>
                <div class="summary-value">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</div>
            </div>
        </div>
    </div>
    
    <table>
        <thead>
            <tr>
                <th>No.</th>
                <th>Order ID</th>
                <th>Customer</th>
                <th>Type</th>
                <th>Date & Time</th>
                <th>Total</th>
                <th>Kasir</th>
                <th>Items</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</td>
                    <td>{{ $order->customer_name }}</td>
                    <td>{{ $order->order_type }}</td>
                    <td>{{ $order->created_at->format('d-m-Y H:i') }}</td>
                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>{{ $order->user ? $order->user->name : 'Unknown' }}</td>
                    <td>
                        <ul class="order-items">
                        @foreach($order->items as $item)
                            <li>{{ $item->quantity }}x {{ $item->menu_name }} - Rp {{ number_format($item->price, 0, ',', '.') }}</li>
                        @endforeach
                        </ul>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    
    <div class="footer">
        Generated on {{ now()->format('d-m-Y H:i:s') }} by {{ $downloadedBy }} | Alternatif Coffee
    </div>
</body>
</html>