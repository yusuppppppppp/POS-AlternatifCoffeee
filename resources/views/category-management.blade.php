@extends('layouts.app')

@section('title', 'Category Management')

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

        .category-section {
            margin-left: 350px;
            padding: 40px;
            margin-top: -75px;
            max-width: 1100px;
            position: relative;
        }

        .section-header {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            padding: 32px;
            border-radius: 20px;
            margin-bottom: 32px;
            box-shadow: 
                0 20px 40px rgba(46, 71, 102, 0.15),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .section-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="25" cy="25" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="75" cy="75" r="1" fill="rgba(255,255,255,0.05)"/><circle cx="50" cy="10" r="0.5" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
            opacity: 0.6;
        }

        .section-title {
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
            position: relative;
            z-index: 1;
        }

        .section-subtitle {
            color: rgba(255, 255, 255, 0.8);
            font-size: 1rem;
            position: relative;
            z-index: 1;
        }

        .add-category-btn {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            color: #fff;
            border: none;
            padding: 16px 32px;
            border-radius: 16px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-bottom: 32px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 
                0 8px 25px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        .add-category-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .add-category-btn:hover::before {
            left: 100%;
        }

        .add-category-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 35px rgba(46, 71, 102, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .add-category-btn:active {
            transform: translateY(-1px);
        }

        .add-category-btn-icon {
            width: 24px;
            height: 24px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
        }

        .table-container {
            background: white;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 
                0 20px 40px rgba(0, 0, 0, 0.08),
                0 0 0 1px rgba(226, 232, 240, 0.5);
            border: 1px solid rgba(226, 232, 240, 0.3);
        }

        .category-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .category-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 24px 20px;
            text-align: left;
            font-weight: 700;
            color: #2E4766;
            font-size: 15px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .category-table th:first-child {
            border-top-left-radius: 20px;
        }

        .category-table th:last-child {
            border-top-right-radius: 20px;
        }

        .category-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .category-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(46, 71, 102, 0.1);
        }

        .category-table tbody tr:last-child {
            border-bottom: none;
        }

        .category-table td {
            padding: 20px;
            vertical-align: middle;
            color: #475569;
            font-weight: 500;
        }

        .category-name {
            font-weight: 600;
            color: #2E4766;
            font-size: 16px;
        }

        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .edit-btn, .delete-btn {
            border: none;
            padding: 10px;
            border-radius: 12px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            position: relative;
            overflow: hidden;
        }

        .edit-btn {
            background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
            color: #f59e0b;
            box-shadow: 
                0 4px 12px rgba(245, 158, 11, 0.2),
                0 0 0 1px rgba(245, 158, 11, 0.1);
        }

        .delete-btn {
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            box-shadow: 
                0 4px 12px rgba(220, 38, 38, 0.2),
                0 0 0 1px rgba(220, 38, 38, 0.1);
        }

        .edit-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(245, 158, 11, 0.3);
        }

        .delete-btn:hover {
            transform: translateY(-2px);
            background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
            color: white;
            box-shadow: 0 8px 20px rgba(220, 38, 38, 0.3);
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(46, 71, 102, 0.8);
            backdrop-filter: blur(12px);
            z-index: 3000;
            animation: fadeIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        .modal-content {
            background: linear-gradient(145deg, #ffffff 0%, #f8fafc 100%);
            padding: 40px;
            width: 90%;
            max-width: 500px;
            margin: 5vh auto;
            border-radius: 24px;
            box-shadow: 
                0 25px 50px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            animation: slideUp 0.4s ease-out;
            max-height: 90vh;
            overflow-y: auto;
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 32px;
            position: relative;
            padding-bottom: 20px;
        }

        .modal-header::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, #2E4766, #4a6b8a, #2E4766);
            border-radius: 1px;
        }

        .modal-title {
            color: #2E4766;
            font-size: 1.75rem;
            font-weight: 700;
            margin: 0;
        }

        .close-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: rgba(46, 71, 102, 0.1);
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            color: #2E4766;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .close-btn:hover {
            background: rgba(46, 71, 102, 0.2);
            transform: rotate(90deg);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group label {
            font-weight: 600;
            color: #2E4766;
            display: block;
            margin-bottom: 8px;
            font-size: 15px;
        }

        .form-group input {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 15px;
            transition: all 0.3s ease;
            background: #fff;
            font-family: inherit;
        }

        .form-group input:focus {
            outline: none;
            border-color: #2E4766;
            box-shadow: 
                0 0 0 3px rgba(46, 71, 102, 0.1),
                0 4px 12px rgba(46, 71, 102, 0.1);
            transform: translateY(-1px);
        }

        #modalSubmitBtn {
            background: linear-gradient(135deg, #2E4766 0%, #3a5a7f 100%);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            width: 100%;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 
                0 4px 15px rgba(46, 71, 102, 0.3),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
            position: relative;
            overflow: hidden;
        }

        #modalSubmitBtn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        #modalSubmitBtn:hover::before {
            left: 100%;
        }

        #modalSubmitBtn:hover {
            transform: translateY(-2px);
            box-shadow: 
                0 8px 25px rgba(46, 71, 102, 0.4),
                inset 0 1px 0 rgba(255, 255, 255, 0.1);
        }

        #modalSubmitBtn:active {
            transform: translateY(0);
        }

        #modalSubmitBtn:disabled {
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
        }

        .loading {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
            margin-right: 8px;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        #errorMessage {
            margin-top: 16px;
            padding: 12px 16px;
            background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
            color: #dc2626;
            font-size: 14px;
            border-radius: 8px;
            border: 1px solid #fca5a5;
            display: none;
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

        /* Loading state for buttons */
        .btn-loading {
            position: relative;
            color: transparent;
        }

        .btn-loading::after {
            content: '';
            position: absolute;
            width: 16px;
            height: 16px;
            top: 50%;
            left: 50%;
            margin-left: -8px;
            margin-top: -8px;
            border: 2px solid rgba(255,255,255,0.3);
            border-radius: 50%;
            border-top-color: #fff;
            animation: spin 0.8s ease-in-out infinite;
        }

        @media (max-width: 900px) {
            .category-section {
                margin-left: 0;
                padding: 20px;
                max-width: 100vw;
                margin-top: 0;
            }
            
            .section-header {
                padding: 24px;
                margin-bottom: 24px;
            }
            
            .section-title {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .category-section {
                padding: 16px;
            }
            
            .modal-content {
                width: 95%;
                padding: 24px;
                margin: 2vh auto;
            }
            
            .modal-title {
                font-size: 1.5rem;
            }
            
            .add-category-btn {
                padding: 12px 24px;
                font-size: 14px;
            }
            
            .category-table th,
            .category-table td {
                padding: 12px 8px;
                font-size: 14px;
            }
            
            .action-buttons {
                flex-direction: column;
                gap: 4px;
            }
            
            .edit-btn, .delete-btn {
                width: 32px;
                height: 32px;
            }
        }
</style>

<div class="category-section">
    <div class="section-header">
        <h1 class="section-title">Category Management</h1>
        <p class="section-subtitle">Manage your menu categories</p>
    </div>
    
    <button class="add-category-btn" onclick="showModal('add')">
        <div class="add-category-btn-icon">+</div>
        Add New Category
    </button>
    
    <!-- Search Form -->
    <div class="search-container">
        <form method="GET" action="{{ route('category-management') }}" class="search-form">
            <div class="search-input-group">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ $search ?? '' }}" 
                    placeholder="Search categories..."
                    class="search-input"
                >
                <button type="submit" class="search-button">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M21 21L16.514 16.506L21 21ZM19 10.5C19 15.194 15.194 19 10.5 19C5.806 19 2 15.194 2 10.5C2 5.806 5.806 2 10.5 2C15.194 2 19 5.806 19 10.5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            @if(!empty($search ?? ''))
                <a href="{{ route('category-management') }}" class="clear-search">Clear Search</a>
            @endif
        </form>
    </div>
    
    <div class="table-container">
        <table class="category-table">
            <thead>
                <tr>
                    <th style="width: 60px;">No</th>
                    <th>Category Name</th>
                    <th style="width: 120px;">Actions</th>
                </tr>
            </thead>
            <tbody id="categoryTableBody">
                @foreach($categories as $index => $category)
                <tr>
                    <td style="text-align: center; font-weight: 600; color: #2E4766;">{{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                    <td class="category-name">{{ $category->name }}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="edit-btn" onclick="showModal('edit', '{{ $category->id }}')">
                                <img src="{{ asset('images/edit.png') }}" alt="Edit" style="height:18px;width:18px;">
                            </button>
                            <button class="delete-btn" onclick="deleteCategory('{{ $category->id }}')">
                                <img src="{{ asset('images/hapus.png') }}" alt="Delete" style="height:18px;width:18px;">
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
    <!-- Show Entries and Pagination -->
    <div class="pagination-container">
        <div class="pagination-info">
            Showing {{ $categories->firstItem() ?? 0 }} to {{ $categories->lastItem() ?? 0 }} of {{ $categories->total() }} entries
        </div>
        
        <div class="per-page-selector">
            <label for="per_page">Show:</label>
            <select id="per_page" onchange="changePerPage(this.value)">
                <option value="5" {{ request('per_page', 10) == 5 ? 'selected' : '' }}>5</option>
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span>entries</span>
        </div>
    </div>
    
    <div class="pagination-links">
        {{ $categories->appends(['per_page' => request('per_page', 10), 'search' => request('search')])->links() }}
    </div>
</div>

<!-- Modal -->
<div id="categoryModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle"></h2>
            <button class="close-btn" onclick="hideModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="categoryForm">
                @csrf
                <input type="hidden" name="id" id="categoryId">
                <div class="form-group">
                    <label for="name">Category Name</label>
                    <input type="text" name="name" id="name" required placeholder="Enter category name">
                </div>
                <button type="submit" id="modalSubmitBtn">
                    <span class="loading" style="display: none;"></span>
                    <span class="btn-text">Submit</span>
                </button>
                <div id="errorMessage"></div>
            </form>
        </div>
    </div>
</div>

<script>
function showModal(action, id = null) {
    const modal = document.getElementById('categoryModal');
    const title = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const btnText = submitBtn.querySelector('.btn-text');
    const form = document.getElementById('categoryForm');
    const errorMessage = document.getElementById('errorMessage');

    errorMessage.style.display = 'none';
    form.reset();
    modal.style.display = 'block';
    document.getElementById('categoryId').value = '';

    if (action === 'add') {
        title.textContent = 'Add New Category';
        btnText.textContent = 'Add Category';
        form.setAttribute('data-action', '{{ route("categories.store") }}');
        form.setAttribute('data-method', 'POST');
    } else if (action === 'edit' && id) {
        title.textContent = 'Edit Category';
        btnText.textContent = 'Update Category';
        form.setAttribute('data-action', '{{ url("categories") }}/' + id);
        form.setAttribute('data-method', 'PUT');

        fetch('/api/categories/' + id)
            .then(res => res.json())
            .then(category => {
                document.getElementById('categoryId').value = category.id;
                document.getElementById('name').value = category.name;
            })
            .catch(() => {
                errorMessage.textContent = 'Failed to load category';
                errorMessage.style.display = 'block';
            });
    }
}

function hideModal() {
    document.getElementById('categoryModal').style.display = 'none';
}

// Function to handle show entries change
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

document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const submitBtn = document.getElementById('modalSubmitBtn');
    const loading = submitBtn.querySelector('.loading');
    const btnText = submitBtn.querySelector('.btn-text');
    const data = new FormData(form);
    const action = form.getAttribute('data-action');
    const method = form.getAttribute('data-method');

    if (method === 'PUT') {
        data.append('_method', 'PUT');
    }

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.classList.add('btn-loading');
    loading.style.display = 'inline-block';
    btnText.style.display = 'none';

    fetch(action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: data
    })
    .then(async res => {
        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Failed to save data');
        }
        return res.json();
    })
    .then(() => {
        hideModal();
        location.reload();
    })
    .catch(err => {
        const errBox = document.getElementById('errorMessage');
        errBox.textContent = err.message;
        errBox.style.display = 'block';
    })
    .finally(() => {
        // Hide loading state
        submitBtn.disabled = false;
        submitBtn.classList.remove('btn-loading');
        loading.style.display = 'none';
        btnText.style.display = 'inline';
    });
});

function deleteCategory(id) {
    if (!confirm('Are you sure you want to delete this category?')) return;

    fetch('/categories/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(async res => {
        if (!res.ok) {
            const errorData = await res.json();
            throw new Error(errorData.message || 'Failed to delete');
        }
        return res.json();
    })
    .then(() => {
        location.reload();
    })
    .catch(err => {
        alert(err.message);
    });
}

window.onclick = function(e) {
    const modal = document.getElementById('categoryModal');
    if (e.target == modal) hideModal();
};
</script>

@endsection 