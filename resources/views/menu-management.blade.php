@extends('layouts.app')

@section('title', 'Menu Management')

@section('content')
<style>
    .menu-section {
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

    .menu-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background-color: #fff;
        box-shadow: 0 2px 16px rgba(0, 0, 0, 0.08);
        border-radius: 16px;
        overflow: hidden;
    }
    .menu-table th, .menu-table td {
        padding: 18px 18px;
        text-align: left;
    }
    .menu-table th {
        background-color: #f7f9fb;
        font-weight: 700;
        color: #2d4a70;
        font-size: 15px;
        border-bottom: 2px solid #eaeaea;
    }
    .menu-table tbody tr {
        border-bottom: 1px solid #f0f0f0;
    }
    .menu-table tbody tr:last-child {
        border-bottom: none;
    }
    .menu-table tbody tr:nth-child(even) {
        background-color: #fcfdff;
    }
    .menu-table img {
        border-radius: 8px;
        object-fit: cover;
        height: 48px;
        width: 48px;
        box-shadow: 0 1px 4px rgba(0,0,0,0.08);
        border: 2px solid #f5f5f5;
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
    @media (max-width: 900px) {
        .menu-section {
            margin-left: 0;
            padding: 20px;
            max-width: 100vw;
        }
    }
    @media (max-width: 768px) {
        .menu-section {
            margin-left: 0px;
            padding: 10px;
        }
        .menu-table img {
            height: 36px;
            width: 36px;
        }
        .modal-content {
            width: 98%;
            margin: 40px auto;
        }
    }
</style>

<div class="menu-section">
    <button class="category-btn" onclick="showModal('add')">
         Add New Menu <img src="{{ asset('images/tambah.png') }}" alt="Add" style="height:22px;width:22px;margin-right:0px;vertical-align:middle;">
    </button>
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
                <td><img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" width="48"></td>
                <td>Rp. {{ number_format($menu->price, 0, ',', '.') }}</td>
                <td>{{ $menu->category }}</td>
                <td>
                    <button class="edit-btn" onclick="showModal('edit', '{{ $menu->id }}')">
                        <img src="{{ asset('images/edit.png') }}" alt="Edit" style="height:20px;width:20px;vertical-align:middle;">
                    </button>
                    <button class="delete-btn" onclick="deleteMenu('{{ $menu->id }}')">
                        <img src="{{ asset('images/hapus.png') }}" alt="Delete" style="height:20px;width:20px;vertical-align:middle;">
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <div class="pagination-container">
        {{ $menus->links() }}
    </div>
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
