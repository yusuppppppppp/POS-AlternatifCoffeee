@extends('layouts.app')

@section('title', 'Account Management')

@section('content')
<style>
    .account-section {
        margin-left: 285px;
        padding: 0px 40px 40px 40px;
        margin-top: -75px;
        max-width: 2100px;
    }
    .category-btn {
        background-color: #2d4a70;
        color: #fff;
        border: none;
        padding: 12px 28px;
        border-radius: 10px;
        font-size: 15px;
        cursor: pointer;
        margin-bottom: 28px;
        margin-left: 0;
        font-weight: 500;
        transition: 0.2s;
        display: flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 16px rgba(44, 74, 112, 0.18), 0 1.5px 4px rgba(44,74,112,0.10);
    }
    .category-btn:hover {
        background-color: #1c3552;
    }
    .account-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: #fff;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
    }
    .account-table th, .account-table td {
        padding: 18px 18px;
        text-align: left;
    }
    .account-table th {
        background-color: #f7f9fb;
        font-weight: 700;
        color: #2d4a70;
        font-size: 15px;
        border-bottom: 2px solid #eaeaea;
    }
    .account-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }
    .account-table tbody tr:last-child {
        border-bottom: none;
    }
    .account-table tbody tr:nth-child(even) {
        background-color: #fcfdff;
    }
    .edit-btn, .delete-btn {
        border: none;
        padding: 7px 11px;
        border-radius: 7px;
        font-size: 18px;
        cursor: pointer;
        margin-right: 4px;
        transition: background 0.2s, color 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }
    .edit-btn {
        background-color: #fffbe6;
        color: #f0ad4e;
        border: 1px solid #ffe6a1;
    }
    .delete-btn {
        background-color: #fff0f0;
        color: #d9534f;
        border: 1px solid #ffd6d6;
    }
    .edit-btn:hover {
        background-color: #ffe6a1;
        color: #fff;
    }
    .delete-btn:hover {
        background-color: #ffd6d6;
        color: #fff;
    }
    .modal {
        display: none;
        position: fixed;
        top: 0; left: 0; right: 0; bottom: 0;
        background: rgba(0,0,0,0.4);
        z-index: 1000;
    }
    .modal-content {
        background: white;
        padding: 25px;
        width: 90%;
        max-width: 500px;
        margin: 80px auto;
        border-radius: 12px;
        box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        position: relative;
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .close-btn {
        font-size: 24px;
        cursor: pointer;
        color: #aaa;
    }
    .close-btn:hover {
        color: #000;
    }
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        font-weight: 600;
        display: block;
        margin-bottom: 5px;
    }
    .form-group input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }
    #modalSubmitBtn {
        background-color: #3d5a80;
        color: white;
        border: none;
        padding: 10px;
        border-radius: 6px;
        width: 100%;
        font-size: 16px;
        cursor: pointer;
        transition: background 0.3s;
    }
    #modalSubmitBtn:hover {
        background-color: #2d4a70;
    }
    #errorMessage {
        margin-top: 10px;
        color: red;
        font-size: 14px;
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
    <button class="category-btn" onclick="openModal('add')">
        Add Account <img src="{{ asset('images/tambah.png') }}" alt="Add" style="height:22px;width:22px;margin-right:0px;vertical-align:middle;">
    </button>
    <div id="alert" class="alert" style="display:none;"></div>
    <table class="account-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Password</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="userTableBody">
            @forelse($users as $user)
            <tr data-id="{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ str_repeat('*', 8) }}</td>
                <td>
                    <button class="edit-btn" onclick="editUser({{ $user->id }}, '{{ $user->name }}', '{{ $user->email }}')">
                        <img src="{{ asset('images/edit.png') }}" alt="Edit" style="height:20px;width:20px;vertical-align:middle;">
                    </button>
                    <button class="delete-btn" onclick="deleteUser({{ $user->id }})">
                        <img src="{{ asset('images/hapus.png') }}" alt="Delete" style="height:20px;width:20px;vertical-align:middle;">
                    </button>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align:center;color:#888;padding:40px 0;">
                    <div style="font-size:18px;">No users found.<br>Click "Add Account" to create your first user account.</div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="pagination-container">
        {{ $users->links() }}
    </div>
</div>

<!-- Modal -->
<div id="userModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle">Add Account</h2>
            <span class="close-btn" onclick="closeModal()">&times;</span>
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
                                <td colspan="4" style="text-align:center;color:#888;padding:40px 0;">
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
                        row.children[0].textContent = data.user.name;
                        row.children[1].textContent = data.user.email;
                    }
                } else {
                    const emptyState = tbody.querySelector('td[colspan="4"]');
                    if (emptyState) tbody.innerHTML = '';
                    const newRow = document.createElement('tr');
                    newRow.setAttribute('data-id', data.user.id);
                    newRow.innerHTML = `
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
