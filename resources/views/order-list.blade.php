@extends('layouts.app')
@section('title', 'Order List')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', sans-serif;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        color: #334155;
    }

    .order-container {
        max-width: 1000px;
        margin: -70px 20px 0px 400px;
        padding: 40px;
        background: transparent;
        min-height: 100vh;
    }

    .page-header {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        padding: 40px;
        border-radius: 24px;
        margin-bottom: 32px;
        box-shadow: 
            0 20px 40px rgba(46, 71, 102, 0.15),
            0 0 0 1px rgba(255, 255, 255, 0.1);
        position: relative;
        overflow: hidden;
    }

    .page-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.6;
    }

    .page-title {
        color: white;
        font-size: 2.25rem;
        font-weight: 700;
        margin-bottom: 8px;
        position: relative;
        z-index: 1;
    }

    .page-subtitle {
        color: rgba(255, 255, 255, 0.8);
        font-size: 1.1rem;
        margin-bottom: 24px;
        position: relative;
        z-index: 1;
    }

    .btn-download-pdf {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 12px 24px;
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, rgba(255, 255, 255, 0.1) 100%);
        color: white;
        border: 2px solid rgba(255, 255, 255, 0.3);
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        position: relative;
        z-index: 1;
        backdrop-filter: blur(10px);
    }

    .btn-download-pdf:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 0.3) 0%, rgba(255, 255, 255, 0.2) 100%);
        border-color: rgba(255, 255, 255, 0.5);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }

    .btn-download-pdf::before {
        content: '';
        background-image: url('/images/download.png');
        background-size: 25px 25px;
        background-repeat: no-repeat;
        background-position: center;
        width: 25px;
        height: 25px;
        display: inline-block;
    }

    .stats-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(226, 232, 240, 0.5);
        text-align: center;
    }

    .stat-card h3 {
        font-size: 2rem;
        font-weight: 700;
        color: #2E4766;
        margin-bottom: 8px;
    }

    .stat-card p {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .stat-card.orders h3 {
        color: #2E4766;
        font-weight: 750;
    }

    .stat-card.revenue h3 {
        color: #2E4766;
        font-weight: 750;
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

    /* Order details modal/expandable section (optional) */
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

    .pagination-container {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }

    /* Responsive Design */
    @media (max-width: 900px) {
        .order-container {
            margin-left: 0;
            padding: 20px;
            margin-top: 0;
        }
        
        .page-header {
            padding: 32px 24px;
            margin-bottom: 24px;
        }
        
        .page-title {
            font-size: 1.75rem;
        }
    }

    @media (max-width: 768px) {
        .order-container {
            padding: 16px;
        }
        
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
        
        .stats-cards {
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
    }

    @media (max-width: 480px) {
        .stats-cards {
            grid-template-columns: 1fr;
        }
        
        .page-title {
            font-size: 1.5rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
    }
</style>

<div class="order-container">
    <!-- Enhanced Header -->
    <div class="page-header">
        <h2 class="page-title">History Pembelian</h2>
        <p class="page-subtitle">Kelola dan pantau semua transaksi penjualan</p>
        <a href="{{ route('order-list.download-pdf') }}" class="btn-download-pdf">Download PDF</a>
    </div>

    <!-- Stats Cards -->
    <div class="stats-cards">
        <div class="stat-card orders">
            <h3>{{ $totalOrdersToday }}</h3>
            <p>Total Orders Hari Ini</p>
        </div>
        <div class="stat-card revenue">
            <h3>Rp {{ number_format($totalRevenueToday, 0, ',', '.') }}</h3>
            <p>Total Revenue</p>
        </div>
    </div>

    @if($orders->count() > 0)
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
                
                <!-- Expandable order details -->
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
        
        <div class="pagination-container">
            {{ $orders->links() }}
        </div>
    @else
        <div class="order-table">
            <p class="no-orders">Tidak ada pesanan hari ini.</p>
        </div>
    @endif
</div>

<script>
function toggleOrderDetails(index) {
    const details = document.getElementById('details-' + index);
    const isShowing = details.classList.contains('show');
    
    // Hide all other details first
    document.querySelectorAll('.order-details').forEach(detail => {
        detail.classList.remove('show');
    });
    
    // Toggle current details
    if (!isShowing) {
        details.classList.add('show');
    }
}

// Add loading animation for download button
document.addEventListener('DOMContentLoaded', function() {
    document.querySelector('.btn-download-pdf').addEventListener('click', function(e) {
        const btn = this;
        const originalText = btn.innerHTML;
        
        btn.innerHTML = '<span style="display:inline-block;width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-radius:50%;border-top-color:#fff;animation:spin 0.8s linear infinite;margin-right:8px;"></span>Generating...';
        btn.style.pointerEvents = 'none';
        
        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.style.pointerEvents = 'auto';
        }, 2000);
    });
});

// Add CSS animation for loading spinner
const style = document.createElement('style');
style.textContent = `
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
@endsection