@extends('layouts.app')

@section('title', 'Menu Management')

@section('content')
<style>
    .menu-section {
        margin-left: 250px; /* Supaya tidak tertutup sidebar */
        padding: 10px 30px 30px 30px; /* padding atas diperkecil */
    }

    .category-btn {
        background-color: #2d4a70;
        color: #fff;
        border: none;
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 20px;
        transition: 0.3s ease;
    }

    .category-btn:hover {
        background-color: #1c3552;
    }

    .menu-table {
        width: 100%;
        border-collapse: collapse;
        background-color: #fff;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
    }

    .menu-table th, .menu-table td {
        padding: 14px 16px;
        text-align: left;
    }

    .menu-table th {
        background-color: #f5f5f5;
        font-weight: bold;
    }

    .menu-table tbody tr:nth-child(even) {
        background-color: #fafafa;
    }

    .menu-table img {
        border-radius: 6px;
        object-fit: cover;
        height: 60px;
    }

    .edit-btn, .delete-btn {
        border: none;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        margin-right: 6px;
    }

    .edit-btn {
        background-color: #f0ad4e;
        color: white;
    }

    .delete-btn {
        background-color: #d9534f;
        color: white;
    }

    .edit-btn:hover {
        background-color: #ec9c33;
    }

    .delete-btn:hover {
        background-color: #c9302c;
    }

    /* Modal styling (same, just added shadow and nicer border) */
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

    .form-group input, .form-group select {
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

    @media (max-width: 768px) {
        .menu-section {
            margin-left: 0;
            padding: 20px;
        }

        .menu-table img {
            height: 40px;
        }

        .modal-content {
            width: 95%;
            margin: 60px auto;
        }
    }
</style>

<div class="menu-section">
    <button class="category-btn" onclick="showModal('add')">Add New Menu +</button>
    <table class="menu-table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Preview</th>
                <th>Price</th>
                <th>Category</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody id="menuTableBody">
            @foreach($menus as $menu)
            <tr>
                <td>{{ $menu->name }}</td>
                <td><img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" width="80"></td>
                <td>Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td>{{ $menu->category }}</td>
                <td>
                    <button class="edit-btn" onclick="showModal('edit', '{{ $menu->id }}')">✏️</button>
                    <button class="delete-btn" onclick="deleteMenu('{{ $menu->id }}')">🗑️</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Modal -->
<div id="menuModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h2 id="modalTitle"></h2>
            <span class="close-btn" onclick="hideModal()">×</span>
        </div>
        <div class="modal-body">
            <form id="menuForm" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" id="menuId">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" name="name" id="name" required>
                </div>
                <div class="form-group">
                    <label>Price (Rp)</label>
                    <input type="number" name="price" id="price" required>
                </div>
                <div class="form-group">
                    <label>Category</label>
                    <select name="category" id="category" required>
                        <option value="Coffee">Coffee</option>
                        <option value="Non Coffee">Non Coffee</option>
                        <option value="Food">Food</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Image</label>
                    <input type="file" name="image" id="image" accept="image/*">
                </div>
                <button type="submit" id="modalSubmitBtn">Submit</button>
                <div id="errorMessage" style="display: none;"></div>
            </form>
        </div>
    </div>
</div>

<script>
function showModal(action, id = null) {
    const modal = document.getElementById('menuModal');
    const title = document.getElementById('modalTitle');
    const submitBtn = document.getElementById('modalSubmitBtn');
    const form = document.getElementById('menuForm');
    const errorMessage = document.getElementById('errorMessage');

    errorMessage.style.display = 'none';
    form.reset();
    modal.style.display = 'block';
    document.getElementById('menuId').value = '';
    document.getElementById('image').required = (action === 'add');

    if (action === 'add') {
        title.textContent = 'Add New Menu';
        submitBtn.textContent = 'Add Menu';
        form.setAttribute('data-action', '{{ route("menus.store") }}');
        form.setAttribute('data-method', 'POST');
    } else if (action === 'edit' && id) {
        title.textContent = 'Edit Menu';
        submitBtn.textContent = 'Update Menu';
        form.setAttribute('data-action', '{{ url("menus") }}/' + id);
        form.setAttribute('data-method', 'PUT');

        fetch('/api/menus/' + id)
            .then(res => res.json())
            .then(menu => {
                document.getElementById('menuId').value = menu.id;
                document.getElementById('name').value = menu.name;
                document.getElementById('price').value = menu.price;
                document.getElementById('category').value = menu.category;
            })
            .catch(() => {
                errorMessage.textContent = 'Failed to load menu';
                errorMessage.style.display = 'block';
            });
    }
}

function hideModal() {
    document.getElementById('menuModal').style.display = 'none';
}

document.getElementById('menuForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const form = this;
    const data = new FormData(form);
    const action = form.getAttribute('data-action');
    const method = form.getAttribute('data-method');

    if (method === 'PUT') {
        data.append('_method', 'PUT');
    }

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
            throw new Error(errorData.message || 'Gagal menyimpan data');
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
    });
});

function deleteMenu(id) {
    if (!confirm('Yakin hapus menu ini?')) return;

    fetch('/menus/' + id, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(res => {
        if (!res.ok) throw new Error('Gagal hapus');
        location.reload();
    })
    .catch(err => alert(err.message));
}

window.onclick = function(e) {
    const modal = document.getElementById('menuModal');
    if (e.target == modal) hideModal();
};
</script>

@endsection
