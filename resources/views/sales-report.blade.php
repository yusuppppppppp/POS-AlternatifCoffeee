@extends('layouts.app')
@section('title', 'Sales Report')

@section('content')
<style>
    .order-container {
        max-width: 1000px;
        margin: -60px 20px 100px 400px;
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
        gap: 16px;
        flex-wrap: wrap;
    }

    .search-input-group {
        position: relative;
        flex: 1;
        min-width: 300px;
    }

    .search-input {
        width: 100%;
        padding: 14px 50px 14px 20px;
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
        right: 8px;
        top: 50%;
        transform: translateY(-50%);
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        border: none;
        border-radius: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .search-button:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-50%) scale(1.05);
        box-shadow: 0 4px 12px rgba(46, 71, 102, 0.3);
    }

    .search-button:active {
        transform: translateY(-50%) scale(0.95);
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

    /* Responsive Design for Search */
    @media (max-width: 900px) {
        .search-container {
            margin: 0 20px 24px 20px;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input-group {
            min-width: auto;
        }
        
        .pdf-download-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .period-selector {
            justify-content: space-between;
        }
        
        .custom-date-range {
            flex-direction: column;
            gap: 8px;
        }
    }
</style>

<div class="order-container">
    <h2 class="page-title" style="font-weight: 650;">Sales Report</h2>
    
    <!-- PDF Download Section -->
    <div class="pdf-download-section" style="margin-bottom: 24px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
        <h3 style="margin-bottom: 16px; color: #2E4766; font-size: 18px;">Download PDF Report</h3>
        <form method="GET" action="{{ route('sales-report.download-pdf') }}" class="pdf-download-form" style="display: flex; gap: 16px; align-items: center; flex-wrap: wrap;">
            <div class="period-selector" style="display: flex; align-items: center; gap: 8px;">
                <label for="period" style="font-weight: 600; color: #495057; font-size: 14px;">Period:</label>
                <select name="period" id="period" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; background: white; font-size: 14px;">
                    <option value="today">Hari Ini</option>
                    <option value="week">Minggu Ini</option>
                    <option value="month">Bulan Ini</option>
                    <option value="custom">Custom Range</option>
                </select>
            </div>
            
            <div class="custom-date-range" id="customDateRange" style="display: none; gap: 8px; align-items: center;">
                <input type="date" name="start_date" id="start_date" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;">
                <span style="color: #6c757d;">to</span>
                <input type="date" name="end_date" id="end_date" style="padding: 8px 12px; border-radius: 6px; border: 1px solid #ccc; font-size: 14px;">
            </div>
            
            @if(!empty($search ?? ''))
                <input type="hidden" name="search" value="{{ $search }}">
            @endif
            
            <button type="submit" style="padding: 10px 20px; background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%); color: white; border: none; border-radius: 8px; font-weight: 600; cursor: pointer; transition: all 0.3s ease;" onmouseover="this.style.background='linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%)'; this.style.transform='translateY(-2px)'; this.style.boxShadow='0 4px 12px rgba(46, 71, 102, 0.3)'" onmouseout="this.style.background='linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%)'; this.style.transform='translateY(0)'; this.style.boxShadow='none'">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" style="margin-right: 8px; vertical-align: middle;">
                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                Download PDF
            </button>
        </form>
    </div>
    
    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="{{ route('sales-report') }}" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Cari berdasarkan nama customer, tipe order, ID, total amount, nama kasir, atau nama menu..."
                    class="search-input"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if(!empty($search ?? ''))
                <a href="{{ route('sales-report') }}" class="clear-search">Clear Search</a>
            @endif
        </form>
    </div>
    
    <form method="GET" class="filter-form">
        <input type="date" name="date" value="{{ request('date') }}" placeholder="Tanggal">
        @if(!empty($search ?? ''))
            <input type="hidden" name="search" value="{{ $search }}">
        @endif
        <button type="submit">Filter</button>
        <a href="{{ route('sales-report') }}" class="reset-btn">Reset</a>
    </form>

    @if(isset($orders) && $orders->count() > 0)
        <div class="order-table">
            <div class="table-header">
                <div>No.</div>
                <div>Order ID</div>
                <div>Customer</div>
                <div>Order Type</div>
                <div>Date</div>
                <div>Total Amount</div>
                <div>Action</div>
            </div>
            @foreach($orders as $index => $order)
                <div class="order-row" onclick="toggleOrderDetails({{ $index }})">
                    <div class="order-number">{{ $loop->iteration }}</div>
                    <div class="order-id">{{ str_pad($order->id, 3, '0', STR_PAD_LEFT) }}</div>
                    <div class="customer-name">{{ $order->customer_name }}</div>
                    <div class="order-type">{{ $order->order_type }}</div>
                    <div class="order-date">{{ $order->created_at->format('n/j/Y') }}</div>
                    <div class="total-amount">Rp. {{ number_format($order->total_amount, 0, ',', '.') }}</div>
                    <div class="info-icon">i</div>
                </div>
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
             {{ $orders->appends(['per_page' => $perPage, 'search' => $search ?? '', 'date' => request('date')])->links() }}
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
    // Preserve date parameter if it exists
    const dateParam = currentUrl.searchParams.get('date');
    if (dateParam) {
        currentUrl.searchParams.set('date', dateParam);
    }
    window.location.href = currentUrl.toString();
}

// Function to handle period selection for PDF download
document.addEventListener('DOMContentLoaded', function() {
    const periodSelect = document.getElementById('period');
    const customDateRange = document.getElementById('customDateRange');
    
    if (periodSelect) {
        periodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRange.style.display = 'flex';
            } else {
                customDateRange.style.display = 'none';
            }
        });
    }
});
</script>
@endsection
