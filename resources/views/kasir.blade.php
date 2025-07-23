@extends('layouts.app')

@section('title', 'Kasir')

@section('content')
<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        background: #f8fafc;
        min-height: 100vh;
    }

    /* Header */
    .header {
        background: rgba(44, 62, 80, 0.95);
        backdrop-filter: blur(10px);
        color: white;
        padding: 15px 20px;
        display: flex;
        align-items: center;
        gap: 15px;
        box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        z-index: 1000;
    }

    .hamburger {
        display: flex;
        flex-direction: column;
        gap: 4px;
        cursor: pointer;
    }

    .hamburger div {
        width: 25px;
        height: 3px;
        background: white;
        border-radius: 2px;
    }

    .logo {
        width: 50px;
        height: 50px;
        background: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        color: #2c3e50;
        font-size: 20px;
    }

    .brand-info h1 {
        font-size: 24px;
        margin-bottom: 2px;
    }

    .brand-info p {
        font-size: 14px;
        opacity: 0.8;
    }

    /* Main Container */
    .container {
        display: flex;
        padding: 20px;
        gap: 10px;
        max-width: 100%;
        margin:40px 0 0 0;
        min-height: calc(100vh - 80px);
    }

    /* Menu Section */
    .menu-section {
        flex: 1;
        padding-left: 290px;
    }

    .category-filters {
        display: flex;
        gap: 15px;
        margin-bottom: 30px;
        flex-wrap: wrap;
    }

    .category-btn {
        padding: 12px 24px;
        border: none;
        border-radius: 25px;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.9);
        color: #2c3e50;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
    }

    .category-btn.active,
    .category-btn:hover {
        background: #2c3e50;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.2);
    }

    .dropdown-indicator {
        margin-left: 8px;
        font-size: 12px;
    }

    .menu-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        max-width: 100%;
    }

    .menu-item {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 20px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    }

    .menu-item:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.2);
    }

    .menu-item img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }

    .menu-item-info {
        padding: 15px;
    }

    .menu-item-info h3 {
        font-size: 16px;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 5px;
    }

    .menu-item-info .price {
        color: #7f8c8d;
        font-size: 14px;
        font-weight: 500;
    }

    /* Bills Section */
    .bills-section {
        width: 400px;
        flex-shrink: 0;
        position: fixed;
        right: 20px;
        top: 100px;
        height: calc(100vh - 120px);
        overflow-y: auto;
    }

    .bills-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 25px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .bills-title {
        font-size: 24px;
        font-weight: 700;
        color: #2c3e50;
        margin-bottom: 20px;
    }

    .bill-items-container {
        flex: 1;
        overflow-y: auto;
        margin-bottom: 20px;
        min-height: 300px;
    }

    .bill-item {
        display: flex;
        align-items: center;
        gap: 15px;
        margin-bottom: 15px;
        padding: 10px;
        border-radius: 15px;
        background: rgba(248, 249, 250, 0.8);
    }

    .quantity-controls {
        display: flex;
        flex-direction: column;
        gap: 5px;
        align-items: center;
    }

    .quantity-btn {
        width: 30px;
        height: 30px;
        border: none;
        border-radius: 50%;
        background: #2c3e50;
        color: white;
        font-weight: bold;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }

    .quantity-btn:hover {
        background: #34495e;
        transform: scale(1.1);
    }

    .quantity {
        font-weight: 600;
        color: #2c3e50;
        min-width: 20px;
        text-align: center;
    }

    .bill-item img {
        width: 60px;
        height: 60px;
        border-radius: 15px;
        object-fit: cover;
    }

    .bill-item-info {
        flex: 1;
    }

    .bill-item-name {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 3px;
    }

    .bill-item-price {
        color: #7f8c8d;
        font-size: 14px;
    }

    .checkout-section {
        border-top: 2px solid #ecf0f1;
        padding-top: 20px;
        margin-top: auto;
    }

    .total-section {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 15px;
        background: rgba(52, 73, 94, 0.1);
        border-radius: 15px;
    }

    .total-label {
        font-size: 18px;
        font-weight: 600;
        color: #2c3e50;
    }

    .total-amount {
        font-size: 20px;
        font-weight: 700;
        color: #2c3e50;
    }

    .checkout-btn {
        width: 48%;
        margin: 5px 1%;
        padding: 15px;
        border: none;
        border-radius: 15px;
        font-weight: 700;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        background: linear-gradient(135deg, #2c3e50 0%, #34495e 100%);
        color: white;
        box-shadow: 0 5px 20px rgba(44, 62, 80, 0.3);
    }

    .checkout-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 62, 80, 0.4);
    }

    .checkout-btn:active {
        transform: translateY(0);
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .container {
            padding-right: 440px;
        }
        
        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .header {
            position: relative;
        }

        .container {
            flex-direction: column;
            padding: 20px;
            margin-top: 0;
        }

        .menu-section {
            padding-right: 0;
        }

        .menu-grid {
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .bills-section {
            position: static;
            width: 100%;
            height: auto;
        }

        .bills-card {
            position: static;
            height: auto;
        }

        .brand-info h1 {
            font-size: 20px;
        }

        .checkout-btn {
            width: 100%;
            margin: 5px 0;
        }
    }

    /* Scrollbar Styling */
    .bill-items-container::-webkit-scrollbar {
        width: 6px;
    }

    .bill-items-container::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .bill-items-container::-webkit-scrollbar-thumb {
        background: #2c3e50;
        border-radius: 10px;
    }

    .bills-section::-webkit-scrollbar {
        width: 6px;
    }

    .bills-section::-webkit-scrollbar-track {
        background: rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .bills-section::-webkit-scrollbar-thumb {
        background: #2c3e50;
        border-radius: 10px;
    }
</style>

<!-- Header -->
<div class="header">
    <div class="hamburger">
        <div></div>
        <div></div>
        <div></div>
    </div>
    <div class="logo">A</div>
    <div class="brand-info">
        <h1>Alternatif Coffee</h1>
        <p>Lukmin Tajinan</p>
    </div>
</div>

<div class="container" id="container">
    <!-- Menu -->
    <div class="menu-section">
        <div class="category-filters">
            <button class="category-btn active" onclick="filterCategory('All')">
                All Category <span class="dropdown-indicator">▼</span>
            </button>
            <button class="category-btn" onclick="filterCategory('Coffee')">Coffee</button>
            <button class="category-btn" onclick="filterCategory('Non Coffee')">Non Coffee</button>
            <button class="category-btn" onclick="filterCategory('Food')">Food</button>
        </div>

        <div class="menu-grid" id="menuGrid">
            <!-- Menu items will be loaded dynamically -->
        </div>
    </div>

    <!-- Bills -->
    <div class="bills-section">
        <div class="bills-card">
            <h2 class="bills-title">Bills</h2>
            <div class="bill-items-container" id="bill-items"></div>
            <div class="checkout-section">
                <div class="total-section">
                    <div class="total-label">Total</div>
                    <div class="total-amount" id="total-amount">Rp. 0</div>
                </div>
                <button class="checkout-btn" onclick="checkout()">Checkout</button>
                <button class="checkout-btn" onclick="printReceipt()">Print</button>
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
        // Update active button
        document.querySelectorAll('.category-btn').forEach(btn => btn.classList.remove('active'));
        event.target.classList.add('active');
        
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
                <img src="${item.image}" alt="${item.name}">
                <div class="bill-item-info">
                    <div class="bill-item-name">${item.name}</div>
                    <div class="bill-item-price">Rp. ${parseInt(item.price).toLocaleString()}</div>
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