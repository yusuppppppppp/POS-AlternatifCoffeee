@extends('layouts.app')

@section('title', 'Account Management')

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

        .account-section {
            margin-left: 12rem;
            padding: 40px;
            margin-top: -30px;
            max-width: 1100px;
            position: relative;
        }

        .container.drawer-open .account-section {
            margin-left: 0px !important;
            margin-right: 80px !important;
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

        .category-btn {
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

        .category-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .category-btn:hover::before {
            left: 100%;
        }

        .category-btn:hover {
            transform: translateY(-3px);
            box-shadow: 
                0 12px 35px rgba(46, 71, 102, 0.35),
                0 0 0 1px rgba(255, 255, 255, 0.1);
        }

        .category-btn:active {
            transform: translateY(-1px);
        }

        .category-btn-icon {
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

        .account-table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
        }

        .account-table th {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            padding: 24px 20px;
            text-align: left;
            font-weight: 700;
            color: #2E4766;
            font-size: 15px;
            border-bottom: 2px solid #e2e8f0;
            position: relative;
        }

        .account-table th:first-child {
            border-top-left-radius: 20px;
        }

        .account-table th:last-child {
            border-top-right-radius: 20px;
        }

        .account-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .account-table tbody tr:hover {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            transform: scale(1.01);
            box-shadow: 0 4px 15px rgba(46, 71, 102, 0.1);
        }

        .account-table tbody tr:last-child {
            border-bottom: none;
        }

        .account-table td {
            padding: 20px;
            vertical-align: middle;
            color: #475569;
            font-weight: 500;
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
            margin: 19vh auto;
            border-radius: 24px;
            box-shadow: 
                0 25px 50px rgba(46, 71, 102, 0.25),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            position: relative;
            animation: slideUp 0.4s ease-out;
            max-height: 80vh;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            transition: margin 0.3s ease;
        }

        .container.drawer-open .modal-content {
            margin-left: auto;
            margin-right: 360px;
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

        .modal-body {
            flex: 1;
            overflow-y: auto;
            padding-right: 8px;
        }

        .modal-body::-webkit-scrollbar {
            width: 6px;
        }

        .modal-body::-webkit-scrollbar-track {
            background: #f1f5f9;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .modal-body::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
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
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }
    .pagination {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        gap: 5px;
    }
    .pagination li {
        margin: 0;
    }
    .pagination a, .pagination span {
        display: inline-block;
        padding: 8px 12px;
        text-decoration: none;
        border: 1px solid #ddd;
        border-radius: 4px;
        color: #2d4a70;
        background-color: #fff;
        transition: all 0.2s;
    }
    .pagination a:hover {
        background-color: #2d4a70;
        color: #fff;
        border-color: #2d4a70;
    }
    .pagination .active span {
        background-color: #2d4a70;
        color: #fff;
        border-color: #2d4a70;
    }
    .pagination .disabled span {
        color: #999;
        background-color: #f5f5f5;
        border-color: #ddd;
        cursor: not-allowed;
    }
    @media (max-width: 900px) {
        .account-section {
            margin-left: 0;
            padding: 20px;
            max-width: 100vw;
        }
    }
    @media (max-width: 768px) {
        .account-section {
            margin-left: 0px;
            padding: 10px;
        }
        .modal-content {
            width: 98%;
            margin: 40px auto;
        }
    }
</style>

<div class="account-section">
    <div class="section-header">
        <h1 class="section-title">Account Management</h1>
        <p class="section-subtitle">Manage user accounts and permissions</p>
    </div>
    
    <button class="category-btn" onclick="openModal('add')">
        <div class="category-btn-icon">+</div>
        Add Account
    </button>
    
    <div id="alert" class="alert" style="display:none;"></div>
    
    <div class="table-container">
        <table class="account-table">
        <thead>
            <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            @forelse($users as $index => $user)
            <tr data-id="{{ $user->id }}">
                <td>{{ $users->firstItem() + $index }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ str_repeat('*', 8) }}</td>
                <td>
                    <div class="action-buttons">
                        <button class="edit-btn" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                            <img src="{{ asset('images/edit.png') }}" alt="Edit" style="height:18px;width:18px;">
                        </button>
                        <button class="delete-btn" onclick="deleteUser({{ $user->id }})">
                            <img src="{{ asset('images/hapus.png') }}" alt="Delete" style="height:18px;width:18px;">
                        </button>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;color:#888;padding:40px 0;">
                    <div style="font-size:18px;">No users found.<br>Click "Add Account" to create your first user account.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
        </table>
    </div>
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 class="modal-title" id="modalTitle">Add Account</h2>
            <button class="close-btn" onclick="closeModal()">×</button>
        </div>
        <div class="modal-body">
            <form id="userForm">
                <input type="hidden" id="userId" name="user_id">
                <div class="form-group">
                    <label for="userName">Name</label>
                    <input type="text" id="userName" name="name" required>
                </div>
                <div class="form-group">
                    <label for="userEmail">Email</label>
                    <input type="email" id="userEmail" name="email" required>
                </div>
                <div class="form-group">
                    <label for="userPassword">Password</label>
                    <input type="password" id="userPassword" name="password">
                </div>
                <button type="submit" id="modalSubmitBtn">Save</button>
                <div id="errorMessage" style="display: none;"></div>
            </form>
        </div>
    </div>
</div>

<script>
    const csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '{{ csrf_token() }}';

    function openModal(mode, userId = null, userName = '', userEmail = '') {
        const modal = document.getElementById('userModal');
        const modalTitle = document.getElementById('modalTitle');
        const userForm = document.getElementById('userForm');
        const submitBtn = document.getElementById('modalSubmitBtn');
        const errorMessage = document.getElementById('errorMessage');
        errorMessage.style.display = 'none';
        userForm.reset();
        document.getElementById('userId').value = '';
        document.getElementById('userPassword').required = (mode === 'add');

        if (mode === 'add') {
            modalTitle.textContent = 'Add Account';
            submitBtn.textContent = 'Save';
        } else {
            modalTitle.textContent = 'Edit Account';
            submitBtn.textContent = 'Update';
            document.getElementById('userId').value = userId;
            document.getElementById('userName').value = userName;
            document.getElementById('userEmail').value = userEmail;
            document.getElementById('userPassword').value = '';
            document.getElementById('userPassword').required = false;
        }
        modal.style.display = 'block';
    }

    function closeModal() {
        document.getElementById('userModal').style.display = 'none';
    }

    function editUser(id, name, email) {
        openModal('edit', id, name, email);
    }

    function deleteUser(id) {
        if (confirm('Are you sure you want to delete this user?')) {
            fetch(`/users/${id}`, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('User deleted successfully!', 'success');
                    document.querySelector(`tr[data-id="${id}"]`).remove();
                    const tbody = document.getElementById('userTableBody');
                    if (tbody.children.length === 0) {
                        tbody.innerHTML = `
                            <tr>
                                <td colspan="5" style="text-align:center;color:#888;padding:40px 0;">
                                    <div style="font-size:18px;">No users found.<br>Click \"Add Account\" to create your first user account.</div>
                                </td>
                            </tr>
                        `;
                    }
                } else {
                    showAlert('Error deleting user!', 'error');
                }
            })
            .catch(() => showAlert('Error deleting user!', 'error'));
        }
    }

    function showAlert(message, type) {
        const alert = document.getElementById('alert');
        alert.textContent = message;
        alert.className = `alert alert-${type}`;
        alert.style.display = 'block';
        setTimeout(() => {
            alert.style.display = 'none';
        }, 3000);
    }

    document.getElementById('userForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const userId = document.getElementById('userId').value;
        const isEdit = userId !== '';
        const url = isEdit ? `/users/${userId}` : '/users';
        const method = isEdit ? 'PUT' : 'POST';
        const data = {};
        formData.forEach((value, key) => {
            if (key !== 'user_id') data[key] = value;
        });
        if (isEdit && !data.password) {
            delete data.password;
        }
        fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showAlert(isEdit ? 'User updated successfully!' : 'User created successfully!', 'success');
                closeModal();
                const tbody = document.getElementById('userTableBody');
                if (isEdit) {
                    const row = document.querySelector(`tr[data-id="${userId}"]`);
                    if (row) {
                        row.children[1].textContent = data.user.name;
                        row.children[2].textContent = data.user.email;
                    }
                } else {
                    const emptyState = tbody.querySelector('td[colspan="5"]');
                    if (emptyState) tbody.innerHTML = '';
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-id', data.user.id);
                    const rowCount = tbody.children.length + 1;
                    newRow.innerHTML = `
                        <td>${rowCount}</td>
                        <td>${data.user.name}</td>
                        <td>${data.user.email}</td>
                        <td>${'*'.repeat(8)}</td>
                        <td>
                            <button class=\"edit-btn\" onclick=\"editUser(${data.user.id}, '${data.user.name}', '${data.user.email}')\">
                                <img src=\"{{ asset('images/edit.png') }}\" alt=\"Edit\" style=\"height:20px;width:20px;vertical-align:middle;\">
                            </button>
                            <button class=\"delete-btn\" onclick=\"deleteUser(${data.user.id})\">
                                <img src=\"{{ asset('images/hapus.png') }}\" alt=\"Delete\" style=\"height:20px;width:20px;vertical-align:middle;\">
                            </button>
                        </td>
                    `;
                    tbody.appendChild(newRow);
                }
            } else {
                showAlert(data.message || 'Error saving user!', 'error');
            }
        })
        .catch(() => showAlert('Error saving user!', 'error'));
    });

    window.onclick = function (event) {
        if (event.target === document.getElementById('userModal')) {
            closeModal();
        }
    }
    document.addEventListener('keydown', function (event) {
        if (event.key === 'Escape') {
            closeModal();
        }
    });
</script>
@endsection
