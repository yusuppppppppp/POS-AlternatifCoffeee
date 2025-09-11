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
        max-width: 1200px;
        margin: 0 auto;
        padding: 40px;
        background: transparent;
        min-height: 100vh;
        transition: margin-left 0.3s ease;
    }

    .container.drawer-open .order-container {
        margin-left: 0px !important;
        margin-right: 80px !important;
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
        color: white;
        text-decoration: none;
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
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 24px;
        margin-bottom: 32px;
    }

    .stat-card {
        background: white;
        padding: 28px 24px;
        border-radius: 18px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(226, 232, 240, 0.4);
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 
            0 8px 30px rgba(0, 0, 0, 0.1),
            0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .stat-card h3 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #2E4766;
        margin-bottom: 8px;
        line-height: 1.2;
    }

    .stat-card p {
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
        letter-spacing: 0.5px;
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
        grid-template-columns: 60px 80px 1fr 1fr 1fr 100px 60px;
        gap: 15px;
        align-items: center;
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .order-row {
        padding: 20px;
        border-bottom: 1px solid #e9ecef;
        display: grid;
        grid-template-columns: 60px 80px 1fr 1fr 1fr 100px 60px;
        gap: 15px;
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

    .order-number {
        font-weight: 600;
        color: #6c757d;
        font-size: 14px;
        text-align: center;
    }

    .order-id {
        font-weight: 600;
        color: #212529;
        font-size: 16px;
    }

    .customer-name {
        font-weight: 500;
        color: #212529;
        font-size: 14px;
    }

    .order-type {
        font-weight: 500;
        color: #212529;
        font-size: 14px;
        text-transform: capitalize;
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

    .no-orders {
        text-align: center;
        padding: 60px 20px;
        color: #6c757d;
        font-style: italic;
        font-size: 16px;
    }

    .pagination-container {
        margin-top: 32px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .pagination-info {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #64748b;
        font-size: 14px;
    }

    .per-page-selector label {
        font-weight: 600;
        color: #495057;
        font-size: 14px;
    }

    .per-page-selector select {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        border-radius: 8px;
        background: white;
        color: #374151;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .per-page-selector select:hover {
        border-color: #2E4766;
    }

    .per-page-selector select:focus {
        outline: none;
        border-color: #2E4766;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
    }

    .pagination-links {
        display: flex;
        justify-content: center;
        margin-top: 20px;
    }

    .pagination-links .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }

    .pagination-links .page-item {
        margin: 0;
    }

    .pagination-links .page-link {
        padding: 8px 12px;
        border: 1px solid #d1d5db;
        background: white;
        color: #374151;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.2s ease;
        font-size: 14px;
    }

    .pagination-links .page-link:hover {
        background: #f3f4f6;
        border-color: #2E4766;
        color: #2E4766;
    }

    .pagination-links .page-item.active .page-link {
        background: #2E4766;
        border-color: #2E4766;
        color: white;
    }

    .pagination-links .page-item.disabled .page-link {
        background: #f9fafb;
        border-color: #e5e7eb;
        color: #9ca3af;
        cursor: not-allowed;
    }

    /* Search Form Styles */
    .search-container {
        background: white;
        padding: 24px;
        border-radius: 16px;
        box-shadow: 
            0 4px 20px rgba(0, 0, 0, 0.06),
            0 1px 3px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(226, 232, 240, 0.4);
        margin-bottom: 24px;
    }

    .search-form {
        display: flex;
        align-items: center;
        margin-left: -10px;
        gap: 16px;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .search-input-group {
        position: relative;
        flex: 1;
        min-width: 300px;
        display: flex;
        align-items: center;
    }

    .search-input {
        width: 100%;
        padding: 14px 56px 14px 20px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: #374151;
        background: #f8fafc;
        transition: all 0.3s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #2E4766;
        background: white;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-button {
        position: absolute;
        right: -10px;
        top: 20px;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        border: none;
        border-radius: 8px;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(46, 71, 102, 0.2);
    }

    .search-button:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(46, 71, 102, 0.3);
    }

    .search-button:active {
        transform: translateY(-50%) scale(0.95);
    }

    .search-button:focus {
        outline: none;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.2);
    }

    .clear-search {
        padding: 10px 16px;
        background: #f3f4f6;
        color: #6b7280;
        text-decoration: none;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.3s ease;
        border: 1px solid #d1d5db;
    }

    .clear-search:hover {
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
        transform: translateY(-1px);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    /* Responsive Design */
    @media (max-width: 900px) {
        .order-container {
            margin-left: 0 !important;
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
        
        .stats-cards {
            grid-template-columns: 1fr 1fr;
        }

        .search-container {
            padding: 20px;
        }

        .search-input-group {
            min-width: 250px;
        }
    }

    @media (max-width: 768px) {
        .order-container {
            padding: 16px;
        }
        
        .stats-cards {
            grid-template-columns: 1fr;
            gap: 16px;
        }

        .search-container {
            padding: 16px;
        }

        .search-form {
            flex-direction: column;
            align-items: stretch;
        }

        .search-input-group {
            min-width: auto;
        }
        
        .table-header,
        .order-row {
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
        }

        .order-type,
        .order-date,
        .info-icon {
            grid-column: span 1;
            justify-self: start;
        }
        
        .info-icon {
            justify-self: end;
        }
    }

    @media (max-width: 480px) {
        .page-title {
            font-size: 1.5rem;
        }
        
        .page-subtitle {
            font-size: 1rem;
        }
        
        .table-header th,
        .order-row td {
            padding: 12px 8px;
        }
        
        .pagination-container {
            flex-direction: column;
            gap: 15px;
            text-align: center;
            padding: 16px;
        }
        
        .pagination-info,
        .per-page-selector {
            justify-content: center;
        }
        
        .pagination-links .pagination {
            flex-wrap: wrap;
            justify-content: center;
        }
    }
</style>

<div class="order-container">
    <!-- Enhanced Header -->
    <div class="page-header">
        <h2 class="page-title">Order List</h2>
        <p class="page-subtitle">Manage and monitor all sales transactions</p>
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

    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="{{ route('order-list') }}" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search }}" 
                    placeholder="Cari berdasarkan nama customer, tipe order, ID, total amount, nama kasir, atau nama menu..."
                    class="search-input"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if(!empty($search))
                <a href="{{ route('order-list') }}" class="clear-search">Clear Search</a>
            @endif
        </form>
    </div>

    @if($orders->count() > 0)
        <div class="order-table">
            <div class="table-header">
                <div>No.</div>
                <div>Order ID</div>
                <div>Customer</div>
                <div>Order Type</div>
                <div>Time</div>
                <div>Total Amount</div>
                <div>Action</div>
            </div>
            
            @foreach($orders as $index => $order)
                <div class="order-row" onclick="toggleOrderDetails({{ $index }})">
                    <div class="order-number">{{ $loop->iteration }}</div>
                    <div class="order-id">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="order-type">{{ $order->order_type }}</div>
                    <div class="order-date">{{ $order->created_at->format('h:i:s A') }}</div>
                    <div class="total-amount">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    <div class="info-icon">i</div>
                </div>
                
                <!-- Expandable order details -->
                <div class="order-details" id="details-{{ $index }}">
                    <div class="customer-info">
                        <p><strong>Customer:</strong> {{ $order->customer_name }}</p>
                        <p><strong>Type:</strong> {{ $order->order_type }}</p>
                        <p><strong>Waktu:</strong> {{ $order->created_at->format('h:i:s A') }}</p>
                        <p><strong>Kasir:</strong> {{ $order->user ? $order->user->name : 'Unknown' }}</p>
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
         
         <!-- Show Entries and Pagination -->
         <div class="pagination-container">
             <div class="pagination-info">
                 Showing {{ $orders->firstItem() ?? 0 }} to {{ $orders->lastItem() ?? 0 }} of {{ $orders->total() }} entries
             </div>
             
             <div class="per-page-selector">
                 <label for="per_page">Show:</label>
                 <select id="per_page" onchange="changePerPage(this.value)">
                     <option value="5" {{ $perPage == 5 ? 'selected' : '' }}>5</option>
                     <option value="10" {{ $perPage == 10 ? 'selected' : '' }}>10</option>
                     <option value="25" {{ $perPage == 25 ? 'selected' : '' }}>25</option>
                     <option value="50" {{ $perPage == 50 ? 'selected' : '' }}>50</option>
                     <option value="100" {{ $perPage == 100 ? 'selected' : '' }}>100</option>
                 </select>
                 <span>entries</span>
             </div>
         </div>
         
         <div class="pagination-links">
             {{ $orders->appends(['per_page' => $perPage, 'search' => $search])->links() }}
         </div>
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

// Function to change per page
function changePerPage(value) {
    const currentUrl = new URL(window.location);
    currentUrl.searchParams.set('per_page', value);
    currentUrl.searchParams.delete('page'); // Reset to first page when changing entries
    // Preserve search parameter if it exists
    const searchParam = currentUrl.searchParams.get('search');
    if (searchParam) {
        currentUrl.searchParams.set('search', searchParam);
    }
    window.location.href = currentUrl.toString();
}
</script>
@endsection