@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<div class="container" id="container">
    <div class="menu-section">
        <div class="category-filters">
            <button class="category-btn" onclick="filterCategory('All')">All Category</button>
            <button class="category-btn" onclick="filterCategory('Coffee')">Coffee</button>
            <button class="category-btn" onclick="filterCategory('Non Coffee')">Non Coffee</button>
            <button class="category-btn" onclick="filterCategory('Food')">Food</button>
        </div>

        <div class="menu-grid" id="menuGrid">
            <!-- Menu items will be loaded dynamically -->
        </div>
    </div>

    <div class="bills-section">
        <div class="bills-card">
            <h2 class="bills-title">Bills</h2>
            <div id="bill-items"></div>
            <div class="checkout-section">
                <button class="checkout-btn" onclick="checkout()">Checkout</button>
                <button class="checkout-btn" onclick="printReceipt()">Print</button>
                <div class="total-section">
                    <div class="total-label">Total</div>
                    <div class="total-amount" id="total-amount">Rp. 0</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    let cart = {};
    let totalAmount = 0;
    let allMenus = [];

    function loadMenuItems() {
        fetch('/api/menus')
            .then(res => res.json())
            .then(data => {
                allMenus = data.map(menu => ({
                    ...menu,
                    image_url: menu.image_path ? `/storage/${menu.image_path}` : 'default.jpg'
                }));
                renderMenuItems(allMenus);
            });
    }

    function renderMenuItems(menus) {
        const grid = document.getElementById('menuGrid');
        grid.innerHTML = '';
        menus.forEach(menu => {
            const el = document.createElement('div');
            el.className = 'menu-item';
            el.onclick = () => addToCart(menu.name, menu.price, menu.image_url);
            el.innerHTML = `
                <img src="${menu.image_url}" alt="${menu.name}">
                <div class="menu-item-info">
                    <h3>${menu.name}</h3>
                    <p class="price">Rp. ${parseInt(menu.price).toLocaleString()}</p>
                </div>
            `;
            grid.appendChild(el);
        });
    }

    function filterCategory(category) {
        const filtered = category === 'All'
            ? allMenus
            : allMenus.filter(menu => menu.category === category);
        renderMenuItems(filtered);
    }

    function addToCart(name, price, image) {
        if (!cart[name]) {
            cart[name] = { name, price, quantity: 0, image };
        }
        cart[name].quantity++;
        updateBillDisplay();
    }

    function updateBillDisplay() {
        const bill = document.getElementById('bill-items');
        bill.innerHTML = '';
        totalAmount = 0;

        for (const name in cart) {
            const item = cart[name];
            totalAmount += item.price * item.quantity;

            const div = document.createElement('div');
            div.className = 'bill-item';
            div.innerHTML = `
                <div class="quantity-controls">
                    <button class="quantity-btn" onclick="updateQuantity('${name}', 1)">+</button>
                    <div class="quantity">${item.quantity}</div>
                    <button class="quantity-btn" onclick="updateQuantity('${name}', -1)">−</button>
                </div>
                <img src="${item.image}" alt="${item.name}" width="60">
                <div class="bill-item-info">
                    <div class="bill-item-name">${item.name}</div>
                    <div class="bill-item-price">Rp. ${item.price.toLocaleString()}</div>
                </div>
            `;
            bill.appendChild(div);
        }

        document.getElementById('total-amount').textContent = `Rp. ${totalAmount.toLocaleString()}`;
    }

    function updateQuantity(name, change) {
        if (cart[name]) {
            cart[name].quantity += change;
            if (cart[name].quantity <= 0) delete cart[name];
            updateBillDisplay();
        }
    }

    function checkout() {
        if (Object.keys(cart).length === 0) {
            alert("Cart is empty!");
            return;
        }

        alert("Pembayaran berhasil! Total: Rp. " + totalAmount.toLocaleString());
        cart = {};
        updateBillDisplay();
    }

    function printReceipt() {
        let printWindow = window.open('', '', 'width=600,height=600');
        let html = `<h2>Alternatif Coffee</h2><hr><ul>`;

        for (let item in cart) {
            html += `<li>${cart[item].quantity}x ${item} - Rp. ${(cart[item].price * cart[item].quantity).toLocaleString()}</li>`;
        }

        html += `</ul><hr><strong>Total: Rp. ${totalAmount.toLocaleString()}</strong>`;
        printWindow.document.write(html);
        printWindow.document.close();
        printWindow.print();
    }

    window.addEventListener('load', loadMenuItems);
</script>
@endsection
