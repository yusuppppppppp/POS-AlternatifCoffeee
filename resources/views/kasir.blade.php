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
        padding-left: 350px;
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
        width: 300px;
        border-radius: 20px;
        flex-shrink: 0;
        position: fixed;
        right: 20px;
        top: 100px;
        height: calc(100vh - 120px);
        overflow-y: auto;
        box-shadow: 0 12px 40px 0 rgba(44, 62, 80, 0.25), 0 1.5px 8px 0 rgba(44, 62, 80, 0.10);
    }

    .bills-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(10px);
        border-radius: 25px;
        padding: 25px;

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
    <img class="logo" src="/images/logo.png" alt="Logo">
    <div class="brand-info">
        <h1>Alternatif Coffee</h1>
        <p>
            @auth
                {{ Auth::user()->name }}
            @else
                Guest
            @endauth
        </p>
    </div>
</div>

<div class="container" id="container">
        <!-- Menu -->
        <div class="menu-section">
            <div class="category-filters">
                <button class="category-btn active" onclick="filterCategory('All')">All Category</button>
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
                <button class="checkout-btn" onclick="checkoutAndPrint()">Checkout</button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div id="paymentModal" class="payment-modal" style="display:none;">
    <div class="payment-modal-content">
        <div class="payment-header">
            <div class="payment-user">
                <img class="payment-user-icon" src="/images/user_icon.png" alt="User Icon" style="width: 40px; height: 40px; border-radius: 50%;" />
                <input type="text" id="customerNameInput" class="payment-user-name" placeholder="Nama Customer" style="font-weight:500; color:#2c3e50; border:none; background:transparent; outline:none; font-size:1rem;" />
            </div>
        </div>
        <div class="payment-summary">
            <h3>Payment Summary</h3>
            <div id="payment-items"></div>
            <div class="payment-subtotal-row"><b>Subtotal</b> <span id="payment-subtotal"></span></div>
            <div class="payment-balance-row"><b>Balance</b> <span id="payment-balance"></span></div>
        </div>
        <div class="payment-cash-input">
            <label for="cashInput"><b>Cash</b></label>
            <input id="cashInput" type="text" placeholder="Enter the amount of cash here" readonly />
        </div>
        <div class="payment-type-btns">
            <button id="dineInBtn" class="type-btn active">Dine in</button>
            <button id="takeAwayBtn" class="type-btn">Take Away</button>
        </div>
        <div class="payment-keypad">
            <div class="keypad-row">
                <button class="keypad-btn">1</button>
                <button class="keypad-btn">2</button>
                <button class="keypad-btn">3</button>
            </div>
            <div class="keypad-row">
                <button class="keypad-btn">4</button>
                <button class="keypad-btn">5</button>
                <button class="keypad-btn">6</button>
            </div>
            <div class="keypad-row">
                <button class="keypad-btn">7</button>
                <button class="keypad-btn">8</button>
                <button class="keypad-btn">9</button>
            </div>
            <div class="keypad-row">
            <button class="keypad-btn" onclick="printReceiptAndReset()">Enter</button>
                <button class="keypad-btn">0</button>
                <button class="keypad-btn">⌫</button>
            </div>
        </div>
    </div>
</div>

<div id="receiptContent" style="display: none;">
    <h2>Alternatif Coffee</h2>
    <hr>
    <ul id="receiptItems"></ul>
    <hr>
    <div class="line"><span>Total</span><span class="payment-total"></span></div>
    <div class="line"><span>Cash</span><span class="payment-cash"></span></div>
    <div class="line"><span>Balance</span><span class="payment-balance"></span></div>
    <hr>
    <div style="text-align:left; margin-bottom:2px;" id="receiptCustomerLabel"></div>
    <div style="text-align:left;" id="receiptCashierLabel"></div>
    <div style="text-align:left; margin-top:2px;" id="receiptOrderTypeLabel"></div>
</div>

<!-- Receipt Modal -->
<div id="receiptModal" style="display:none; position:fixed; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.4); z-index:3000; align-items:center; justify-content:center;">
    <div style="background:#fff; border-radius:16px; padding:32px 24px; min-width:320px; max-width:90vw; box-shadow:0 8px 32px rgba(0,0,0,0.2); position:relative;">
        <div id="receiptModalContent"></div>
        <button onclick="printReceiptFromModal()" style="margin-top:20px; width:100%; padding:10px 0; border:none; border-radius:8px; background:#27ae60; color:#fff; font-weight:600; font-size:1rem; cursor:pointer;">Print</button>
        <button onclick="closeReceiptModal()" style="margin-top:10px; width:100%; padding:10px 0; border:none; border-radius:8px; background:#2c3e50; color:#fff; font-weight:600; font-size:1rem; cursor:pointer;">Tutup</button>
    </div>
</div>


<style>
.payment-modal {
    position: fixed;
    top: 80px; left: 1045px; right: 0; bottom: 0;
    background: rgba(255, 255, 255, 0);
    z-index: 2000;
    display: flex;
    align-items: center;
    justify-content: center;
}
.payment-modal-content {
    background: #fff;
    border-radius: 18px;
    padding: 32px 18px 18px 18px;
    width: 350px;
    max-width: 95vw;
    box-shadow: 0 8px 32px rgba(0,0,0,0.2);
    display: flex;
    flex-direction: column;
    align-items: stretch;
}
.payment-header {
    display: flex;
    align-items: center;
    margin-bottom: 18px;
}
.payment-user {
    display: flex;
    align-items: center;
    gap: 10px;
}
.payment-user-icon {
    font-size: 2rem;
    background: #2c3e50;
    color: #fff;
    border-radius: 50%;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.payment-user-name {
    font-weight: 500;
    color: #2c3e50;
}
.payment-summary {
    background: #fff;
    border-radius: 10px;
    padding: 10px 0 10px 0;
    margin-bottom: 10px;
    max-height: 180px;
    overflow-y: auto;
}
#payment-items {
    margin-bottom: 8px;
}
.payment-summary-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 2px;
}
.payment-subtotal-row, .payment-cash-row, .payment-balance-row {
    display: flex;
    justify-content: space-between;
    margin-top: 4px;
}
.payment-cash-input {
    margin: 10px 0 10px 0;
}
.payment-cash-input input {
    width: 100%;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ccc;
    font-size: 1rem;
    margin-top: 2px;
    background: #f8fafc;
}
.payment-type-btns {
    display: flex;
    gap: 10px;
    margin-bottom: 10px;
}
.type-btn {
    flex: 1;
    padding: 10px 0;
    border-radius: 8px;
    border: 2px solid #2c3e50;
    background: #fff;
    color: #2c3e50;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s, color 0.2s;
}
.type-btn.active, .type-btn:hover {
    background: #2c3e50;
    color: #fff;
}
.payment-keypad {
    margin-top: 8px;
}
.keypad-row {
    display: flex;
    gap: 8px;
    margin-bottom: 8px;
}
.keypad-btn {
    flex: 1;
    padding: 14px 0;
    border-radius: 8px;
    border: 1px solid #2c3e50;
    background: #f8fafc;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.keypad-btn:active {
    background: #2c3e50;
    color: #fff;
}
@media (max-width: 400px) {
    .payment-modal-content { width: 98vw; padding: 10px 2vw; }
}
@media print {
    body * { display: none !important; }
    #receiptModal, #receiptModal * { display: block !important; }
    #receiptModal { position: static !important; background: none !important; box-shadow: none !important; }
    #receiptModalContent { box-shadow: none !important; background: #fff !important; }
}
</style>

<script>
window.currentCashierName = @json(Auth::user()->name ?? 'Guest');
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

    function checkoutAndPrint() {
        if (Object.keys(cart).length === 0) {
            alert("Cart is empty!");
            return;
        }
        // Tampilkan modal pembayaran
        showPaymentModal();
    }

    function showPaymentModal() {
        // Sembunyikan bill section
        document.querySelector('.bills-section').style.display = 'none';
        // Tampilkan modal
        document.getElementById('paymentModal').style.display = 'flex';
        // Render ringkasan pembayaran
        renderPaymentSummary();
        // Reset input cash dan balance
        document.getElementById('cashInput').value = '';
        document.getElementById('payment-balance').textContent = '';
        // Set default tipe dine in
        document.getElementById('dineInBtn').classList.add('active');
        document.getElementById('takeAwayBtn').classList.remove('active');
    }

    function renderPaymentSummary() {
        const itemsDiv = document.getElementById('payment-items');
        itemsDiv.innerHTML = '';
        for (let name in cart) {
            const item = cart[name];
            const row = document.createElement('div');
            row.className = 'payment-summary-row';
            row.innerHTML = `<span>${item.name} ${item.quantity}x</span><span>Rp. ${(item.price * item.quantity).toLocaleString()}</span>`;
            itemsDiv.appendChild(row);
        }
        document.getElementById('payment-subtotal').textContent = 'Rp. ' + totalAmount.toLocaleString();
    }

    // Keypad logic
    let cashValue = '';
    document.querySelectorAll('.keypad-btn').forEach(btn => {
        btn.onclick = function() {
            const val = this.textContent;
            if (val === '⌫') {
                cashValue = cashValue.slice(0, -1);
            } else if (val === 'Enter') {
                // Validasi nama customer
                const customerName = document.getElementById('customerNameInput').value.trim();
                if (!customerName) {
                    alert('Nama Customer wajib diisi!');
                    document.getElementById('customerNameInput').focus();
                    return;
                }
                // Hitung balance
                const cash = parseInt(cashValue || '0');
                const balance = cash - totalAmount;
                document.getElementById('payment-balance').textContent = 'Rp. ' + (balance >= 0 ? balance.toLocaleString() : '0');
                // Jika cash cukup, lakukan print dan reset
                if (cash >= totalAmount) {
                    setTimeout(() => {
                        printReceiptAndReset(cash, balance);
                    }, 500);
                }
            } else {
                if (cashValue.length < 9) cashValue += val;
            }
            document.getElementById('cashInput').value = cashValue ? parseInt(cashValue).toLocaleString() : '';
        };
    });

    // Tipe pembayaran
    document.getElementById('dineInBtn').onclick = function() {
        this.classList.add('active');
        document.getElementById('takeAwayBtn').classList.remove('active');
    };
    document.getElementById('takeAwayBtn').onclick = function() {
        this.classList.add('active');
        document.getElementById('dineInBtn').classList.remove('active');
    };

    function printReceiptAndReset(cash, balance) {
    // Perbarui isi struk
    const receiptItems = document.getElementById('receiptItems');
    receiptItems.innerHTML = '';

    let total = 0;

    for (const itemKey in cart) {
        const item = cart[itemKey];
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const li = document.createElement('li');
        li.innerHTML = `
            <div class="line">
                <span>${item.quantity}x ${item.name}</span>
                <span>Rp. ${itemTotal.toLocaleString()}</span>
            </div>`;
        receiptItems.appendChild(li);
    }

    total = Math.round(total); // pembulatan aman

    // Tampilkan ke struk
    document.querySelector('.payment-total').textContent = `Rp. ${total.toLocaleString()}`;
    document.querySelector('.payment-cash').textContent = `Rp. ${cash.toLocaleString()}`;
    document.querySelector('.payment-balance').textContent = `Rp. ${balance.toLocaleString()}`;
    // Set nama pembeli dan kasir di struk
    const customerName = document.getElementById('customerNameInput').value || 'Unknown';
    document.getElementById('receiptCustomerLabel').textContent = 'Customer: ' + customerName;
    document.getElementById('receiptCashierLabel').textContent = 'Kasir: ' + (window.currentCashierName || 'Guest');
    // Set order type di struk
    const orderType = document.getElementById('dineInBtn')?.classList.contains('active') ? 'Dine in' : 'Take Away';
    document.getElementById('receiptOrderTypeLabel').textContent = 'Order: ' + orderType;
    // Tampilkan struk di modal, bukan window baru
    const receiptModal = document.getElementById('receiptModal');
    const receiptModalContent = document.getElementById('receiptModalContent');
    receiptModalContent.innerHTML = document.getElementById('receiptContent').innerHTML;
    receiptModal.style.display = 'flex';

    // Simpan ke database via fetch POST
    fetch('/save-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            customer_name: customerName,
            order_type: document.getElementById('dineInBtn')?.classList.contains('active') ? 'Dine in' : 'Take Away',
            total_amount: total,
            cash_paid: cash,
            balance: balance,
            items: Object.values(cart)
        })
    })
    .then(res => res.json())
    .then(data => {
        // Order berhasil disimpan, tidak perlu refresh langsung
        // Bisa refresh setelah tutup modal jika diinginkan
    })
    .catch(error => {
        console.error('Error saving order:', error);
        alert('Gagal menyimpan order!');
        window.location.reload();
    });

    // Reset cart dan tampilan
    cart = {};
    updateBillDisplay();
    document.querySelector('.payment-total').textContent = '';
    document.querySelector('.payment-cash').textContent = '';
    document.querySelector('.payment-balance').textContent = '';
    receiptItems.innerHTML = '';
}

function printReceiptFromModal() {
    // Print hanya area receiptModalContent tanpa reload atau mengganti body
    const printContents = document.getElementById('receiptModalContent').innerHTML;
    const printWindow = window.open('', '', 'height=700,width=550'); 
    printWindow.document.write('<html><head><title>Print Receipt</title>');
    printWindow.document.write('<style>@media print { body { margin:0; } .line { display:flex; justify-content:space-between; } #receiptModalContent { min-width:500px; max-width:90vw; margin:auto; } }</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div id="receiptModalContent" style="min-width:500px;max-width:90vw;margin:auto;">');
    printWindow.document.write(printContents);
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}

function closeReceiptModal() {
    document.getElementById('receiptModal').style.display = 'none';
    window.location.reload(); // refresh setelah tutup modal
}

window.onload = loadMenuItems;

</script>
@endsection