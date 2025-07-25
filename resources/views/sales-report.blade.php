@extends('layouts.app')
@section('title', 'Sales Report')

@section('content')
<style>
    .order-container {
        max-width: 900px;
        margin: -60px 20px 100px 450px;
        padding: 20px;
        background-color: #f8f9fa;
        min-height: 100vh;
    }
    .order-table {
        background-color: #fff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
    }
    .table-header {
        background-color: #f8f9fa;
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 60px;
        gap: 20px;
        align-items: center;
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }
    .order-row {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: grid;
        grid-template-columns: 1fr 1fr 1fr 60px;
        gap: 20px;
        align-items: center;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    .order-row:hover {
        background-color: #f8f9fa;
    }
    .order-row:last-child {
        border-bottom: none;
    }
    .order-id {
        font-weight: 600;
        color: #212529;
        font-size: 16px;
    }
    .total-amount {
        font-weight: 600;
        color: #212529;
        font-size: 16px;
    }
    .order-date {
        color: #6c757d;
        font-size: 14px;
    }
    .info-icon {
        width: 24px;
        height: 24px;
        border: 2px solid #6c757d;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 14px;
        font-weight: bold;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    .info-icon:hover {
        border-color: #007bff;
        color: #007bff;
        background-color: #f0f8ff;
    }
    .no-orders {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        font-style: italic;
        font-size: 16px;
    }
    .page-title {
        text-align: center;
        margin-bottom: 30px;
        color: #212529;
        font-weight: 600;
        font-size: 24px;
    }
    .order-details {
        display: none;
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e9ecef;
    }
    .order-details.show {
        display: block;
    }
    .order-details h4 {
        margin-bottom: 15px;
        color: #495057;
        font-size: 16px;
    }
    .order-details ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .order-details li {
        padding: 8px 0;
        border-bottom: 1px solid #dee2e6;
        color: #495057;
    }
    .order-details li:last-child {
        border-bottom: none;
    }
    .customer-info {
        margin-bottom: 15px;
        padding: 10px;
        background-color: #fff;
        border-radius: 6px;
        border-left: 3px solid #007bff;
    }
    .customer-info p {
        margin: 5px 0;
        color: #495057;
    }
    @media (max-width: 768px) {
        .table-header,
        .order-row {
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .order-date,
        .info-icon {
            grid-column: span 2;
            justify-self: start;
        }
        .info-icon {
            justify-self: end;
        }
    }
    .filter-form {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
        align-items: center;
        justify-content: flex-end;
    }
    .filter-form input[type="date"], .filter-form input[type="number"] {
        padding: 6px 10px;
        border-radius: 4px;
        border: 1px solid #ccc;
    }
    .filter-form button, .filter-form a.reset-btn {
        padding: 8px 20px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 15px;
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
        outline: none;
        border: none;
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(46,71,102,0.07);
        margin-left: 0;
        text-decoration: none;
        display: inline-block;
    }
    .filter-form button {
        background: #2E4766;
        color: #fff;
        border: none;
        margin-right: 8px;
    }
    .filter-form button:hover {
        background: linear-gradient(90deg, #3b6ca8 60%, #2E4766 100%);
        color: #fff;
        box-shadow: 0 4px 16px rgba(46,71,102,0.13);
    }
    .filter-form a.reset-btn {
        background: #f3f6fa;
        color: #2E4766;
        border: 1px solid #c3d0e6;
        margin-left: 0;
    }
    .filter-form a.reset-btn:hover {
        background: #e2eaf6;
        color: #1a2a3c;
        border-color: #8fa8c9;
        box-shadow: 0 2px 8px rgba(46,71,102,0.09);
    }
</style>

<div class="order-container">
    <h2 class="page-title" style="font-weight: 650;">Sales Report</h2>
    <form method="GET" class="filter-form">
        <input type="date" name="date" value="{{ request('date') }}" placeholder="Tanggal">
        <input type="number" name="year" min="2000" max="2100" value="{{ request('year') }}" placeholder="Tahun" style="width:100px;">
        <button type="submit">Filter</button>
        <a href="{{ route('sales-report') }}" class="reset-btn">Reset</a>
    </form>
    @if(isset($orders) && $orders->count() > 0)
        <div class="order-table">
            <div class="table-header">
                <div>Order ID</div>
                <div>Total Order</div>
                <div>Date</div>
                <div></div>
            </div>
            @foreach($orders as $index => $order)
                <div class="order-row" onclick="toggleOrderDetails({{ $index }})">
                    <div class="order-id">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="total-amount">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    <div class="order-date">{{ $order->created_at->format('n/j/Y') }}</div>
                    <div class="info-icon">i</div>
                </div>
                <div class="order-details" id="details-{{ $index }}">
                    <div class="customer-info">
                        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Type:</strong> {{ $order->order_type }}</p>
                        <p><strong>Waktu:</strong> {{ $order->created_at->format('h:i:s A') }}</p>
                    </div>
                    <h4>Items Ordered:</h4>
                    <ul>
                        @foreach($order->items as $item)
                            <li>{{ $item->quantity }}x {{ $item->menu_name }} - Rp. {{ number_format($item->price, 0, ',', '.') }}</li>
                        @endforeach
                    </ul>
                </div>
            @endforeach
        </div>
        <div class="pagination-container" style="margin-top: 24px; display: flex; justify-content: center;">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    @else
        <div class="order-table">
            <p class="no-orders">Tidak ada data penjualan.</p>
        </div>
    @endif
</div>
<script>
function toggleOrderDetails(index) {
    const details = document.getElementById('details-' + index);
    const isShowing = details.classList.contains('show');
    document.querySelectorAll('.order-details').forEach(detail => {
        detail.classList.remove('show');
    });
    if (!isShowing) {
        details.classList.add('show');
    }
}
</script>
@endsection
