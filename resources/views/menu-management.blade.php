@extends('layouts.app')

@section('title', 'Menu Management')

@section('content')
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
                <div id="errorMessage" style="color: red; display: none;"></div>
            </form>
        </div>
    </div>
</div>

<style>
.modal {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,0.5);
    z-index: 1000;
}
.modal-content {
    background: #fff;
    padding: 20px;
    width: 90%;
    max-width: 500px;
    margin: 80px auto;
    border-radius: 10px;
    position: relative;
}
.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.close-btn {
    font-size: 24px;
    cursor: pointer;
}
.form-group {
    margin-bottom: 15px;
}
.form-group input, .form-group select {
    width: 100%;
    padding: 8px;
}
button#modalSubmitBtn {
    background-color: #3d5a80;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 6px;
    cursor: pointer;
}
button#modalSubmitBtn:hover {
    background-color: #2d4a70;
}
</style>

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
        form.setAttribute('data-method', 'POST');

        fetch('/api/menus/' + id)
            .then(res => res.json())
            .then(menu => {
                document.getElementById('menuId').value = menu.id;
                document.getElementById('name').value = menu.name;
                document.getElementById('price').value = menu.price;
                document.getElementById('category').value = menu.category;
            })
            .catch(err => {
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
    const menuId = document.getElementById('menuId').value;

    if (method === 'POST' && menuId) {
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
    .then(res => {
        if (!res.ok) throw new Error('Gagal menyimpan data');
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
