@extends('layouts.app')
@section('title', 'Sales Report')

@section('content')
<style>
    .order-container {
        max-width: 1000px;
        margin: -100px 20px 0px 400px;
        padding: 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        min-height: 100vh;
        position: relative;
    }
    
    .order-container::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 200px;
        background: linear-gradient(135deg, #2E4766 0%, #1a2f42 100%);
        border-radius: 0 0 50px 50px;
        z-index: 0;
        opacity: 0.08;
    }
    
    .order-table {
        background: white;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(46, 71, 102, 0.15);
        border: 1px solid rgba(46, 71, 102, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .table-header {
        background: #2E4766;
        padding: 18px 20px;
        border-bottom: none;
        display: grid;
        grid-template-columns: 60px 80px 1fr 1fr 1fr 120px 60px;
        gap: 15px;
        align-items: center;
        font-weight: 600;
        color: white;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
    
    .order-row {
        padding: 16px 20px;
        border-bottom: 1px solid #e2e8f0;
        display: grid;
        grid-template-columns: 60px 80px 1fr 1fr 1fr 120px 60px;
        gap: 15px;
        align-items: center;
        transition: background-color 0.2s ease;
        cursor: pointer;
    }
    
    .order-row:hover {
        background-color: #f8fafc;
    }
    
    .order-row:last-child {
        border-bottom: none;
    }
    
    .order-number {
        font-weight: 600;
        color: #64748b;
        font-size: 13px;
        text-align: center;
    }
    
    .order-id {
        font-weight: 700;
        color: #2E4766;
        font-size: 14px;
        padding: 4px 8px;
        background: rgba(46, 71, 102, 0.08);
        border-radius: 6px;
        text-align: center;
    }
    
    .customer-name {
        font-weight: 500;
        color: #1e293b;
        font-size: 14px;
    }
    
    .order-type {
        font-weight: 500;
        color: #2E4766;
        font-size: 13px;
        text-transform: capitalize;
        padding: 4px 10px;
        border-radius: 12px;
        text-align: start;
        display: inline-block;
    }
    
    .total-amount {
        font-weight: 600;
        color: #2E4766;
        font-size: 14px;
    }
    
    .order-date {
        color: #64748b;
        font-size: 13px;
        font-weight: 500;
    }
    
    .info-icon {
        width: 28px;
        height: 28px;
        background: #2E4766;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .info-icon:hover {
        background: #1a2f42;
        transform: scale(1.05);
    }
    
    .no-orders {
        text-align: center;
        padding: 60px 20px;
        color: #64748b;
        font-style: italic;
        font-size: 18px;
        font-weight: 500;
    }
    
    .page-title {
        text-align: center;
        margin-bottom: 40px;
        font-weight: 700;
        font-size: 28px;
        color: #2E4766;
        position: relative;
        z-index: 1;
    }
    
    .order-details {
        display: none;
        padding: 20px;
        background: #f8fafc;
        border-top: 1px solid #e2e8f0;
        animation: slideDown 0.3s ease-out;
    }
    
    @keyframes slideDown {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .order-details.show {
        display: block;
    }
    
    .order-details h4 {
        margin-bottom: 15px;
        color: #2E4766;
        font-size: 16px;
        font-weight: 600;
        position: relative;
        padding-bottom: 8px;
    }
    
    .order-details h4::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 40px;
        height: 2px;
        background: #2E4766;
        border-radius: 1px;
    }
    
    .order-details ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }
    
    .order-details li {
        padding: 8px 0;
        border-bottom: 1px solid rgba(226, 232, 240, 0.8);
        color: #64748b;
        font-weight: 500;
        transition: color 0.2s ease;
    }
    
    .order-details li:hover {
        color: #2E4766;
    }
    
    .order-details li:last-child {
        border-bottom: none;
    }
    
    .customer-info {
        margin-bottom: 15px;
        padding: 15px;
        background: white;
        border-radius: 8px;
        border-left: 3px solid #2E4766;
        box-shadow: 0 2px 8px rgba(46, 71, 102, 0.1);
    }
    
    .customer-info p {
        margin: 5px 0;
        color: #64748b;
        font-weight: 500;
    }
    
    .customer-info p strong {
        color: #2E4766;
        font-weight: 600;
    }

    /* Simple Search and Filter Container */
    .search-filter-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        border: 1px solid #e2e8f0;
        margin-bottom: 20px;
        margin-top: -20px;
        position: relative;
        z-index: 1;
    }

    .container-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    .container-header h3 {
        color: #475569;
        font-size: 16px;
        font-weight: 600;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .container-header h3::before {
        font-size: 16px;
    }

    .search-section {
        margin-bottom: 15px;
    }

    .search-form {
        display: flex;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
    }

    .search-input-group {
        position: relative;
        flex: 1;
        min-width: 250px;
    }

    .search-input {
        width: 100%;
        padding: 10px 40px 10px 12px;
        border: 1px solid #d1d5db;
        border-radius: 6px;
        font-size: 14px;
        color: #374151;
        background: white;
        transition: all 0.2s ease;
        outline: none;
    }

    .search-input:focus {
        border-color: #475569;
        box-shadow: 0 0 0 2px rgba(71, 85, 105, 0.1);
    }

    .search-input::placeholder {
        color: #9ca3af;
    }

    .search-button {
        position: absolute;
        right: 6px;
        top: 50%;
        transform: translateY(-50%);
        background: #475569;
        border: none;
        border-radius: 4px;
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .search-button:hover {
        background: #374151;
    }

    .clear-search {
        padding: 8px 12px;
        background: #f3f4f6;
        color: #6b7280;
        text-decoration: none;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
        transition: all 0.2s ease;
        border: 1px solid #d1d5db;
    }

    .clear-search:hover {
        background: #e5e7eb;
        color: #374151;
        text-decoration: none;
    }

    .filter-section {
        padding-top: 15px;
        border-top: 1px solid #e2e8f0;
    }

    .filter-section h4 {
        color: #475569;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-section h4::before {
        font-size: 14px;
    }

    .filter-form {
        display: flex;
        gap: 12px;
        align-items: center;
        justify-content: flex-start;
        flex-wrap: wrap;
    }

    .filter-form input[type="date"], 
    .filter-form input[type="number"],
    .pdf-download-form select {
        padding: 8px 12px;
        border-radius: 4px;
        border: 1px solid #d1d5db;
        background: white;
        font-size: 13px;
        transition: all 0.2s ease;
        min-width: 120px;
    }
    
    .filter-form input[type="date"]:focus,
    .filter-form input[type="number"]:focus,
    .pdf-download-form select:focus {
        border-color: #475569;
        box-shadow: 0 0 0 2px rgba(71, 85, 105, 0.1);
    }

    .filter-form button, 
    .filter-form a.reset-btn,
    .pdf-download-form button {
        padding: 10px 20px;
        font-weight: 600;
        font-size: 14px;
        border-radius: 8px;
        transition: all 0.3s ease;
        outline: none;
        border: none;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .filter-form button,
    .pdf-download-form button {
        background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
        color: white;
    }

    .filter-form button:hover,
    .pdf-download-form button:hover {
        background: linear-gradient(135deg, #1e3a5f 0%, #2E4766 100%);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(46, 71, 102, 0.3);
    }

    .filter-form a.reset-btn {
        background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
        color: #475569;
        border: 1px solid rgba(148, 163, 184, 0.3);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .filter-form a.reset-btn:hover {
        background: linear-gradient(135deg, #e2e8f0, #cbd5e1);
        color: #334155;
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    /* PDF Download Section */
    .pdf-download-section {
        margin-bottom: 25px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(46, 71, 102, 0.1);
        border: 1px solid rgba(46, 71, 102, 0.1);
        position: relative;
        z-index: 1;
    }
    
    .pdf-download-section h3 {
        margin-bottom: 16px;
        color: #2E4766;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .pdf-download-section h3::before {
        font-size: 20px;
    }

    /* Pagination */
    .pagination-container {
        margin-top: 10px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        background: white;
        padding: 20px;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(46, 71, 102, 0.1);
        border: 1px solid rgba(46, 71, 102, 0.1);
        position: relative;
        z-index: 1;
    }

    .pagination-info {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 14px;
        font-weight: 500;
    }

    .per-page-selector {
        display: flex;
        align-items: center;
        gap: 8px;
        color: #64748b;
        font-size: 14px;
    }

    .per-page-selector label {
        font-weight: 600;
        color: #2E4766;
        font-size: 14px;
    }

    .per-page-selector select {
        padding: 6px 12px;
        border: 2px solid #e2e8f0;
        border-radius: 6px;
        background: #f8fafc;
        color: #2E4766;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .per-page-selector select:hover {
        border-color: #2E4766;
    }

    .per-page-selector select:focus {
        outline: none;
        border-color: #2E4766;
        box-shadow: 0 0 0 3px rgba(46, 71, 102, 0.1);
        background: white;
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
        gap: 4px;
    }

    .pagination-links .page-item {
        margin: 0;
    }

    .pagination-links .page-link {
        padding: 8px 12px;
        border: 1px solid #e2e8f0;
        background: white;
        color: #2E4766;
        text-decoration: none;
        border-radius: 6px;
        transition: all 0.3s ease;
        font-size: 14px;
        font-weight: 500;
    }

    .pagination-links .page-link:hover {
        background: #2E4766;
        border-color: #2E4766;
        color: white;
    }

    .pagination-links .page-item.active .page-link {
        background: #2E4766;
        border-color: #2E4766;
        color: white;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .order-container {
            margin: -60px 10px 50px 10px;
            padding: 15px;
        }
        
        .table-header,
        .order-row {
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 10px;
            padding: 15px;
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
        
        .search-filter-container,
        .pdf-download-section {
            margin-left: 0;
            margin-right: 0;
        }
        
        .search-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .search-input-group {
            min-width: auto;
        }
        
        .filter-form {
            flex-direction: column;
            align-items: stretch;
            gap: 12px;
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
        
        .page-title {
            font-size: 24px;
            margin-bottom: 30px;
        }
    }

    /* Custom scrollbar */
    ::-webkit-scrollbar {
        width: 6px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f5f9;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb {
        background: #2E4766;
        border-radius: 3px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #1a2f42;
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
    
    <!-- Unified Search and Filter Container -->
    <div class="search-filter-container">
        <div class="container-header">
            <h3>Search & Filter</h3>
        </div>
        
        <!-- Search Section -->
        <div class="search-section">
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
        
        <!-- Filter Section -->
        <div class="filter-section">
            <h4>Filter Options</h4>
            <form method="GET" class="filter-form">
                <input type="date" name="date" value="{{ request('date') }}" placeholder="Tanggal">
                @if(!empty($search ?? ''))
                    <input type="hidden" name="search" value="{{ $search }}">
                @endif
                <button type="submit">Filter</button>
                <a href="{{ route('sales-report') }}" class="reset-btn">Reset</a>
            </form>
        </div>
    </div>

    @if(isset($orders) && $orders->count() > 0)
        <div class="order-table">
            <div class="table-header">
                <div>No.</div>
                <div>Order ID</div>
                <div>Customer</div>
                <div>Order Type</div>
                <div>Date</div>
                <div>Total Amount</div>
                <div>Actions</div>
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
