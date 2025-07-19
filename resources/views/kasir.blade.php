<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alternatif Coffee - Menu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #e8f4f8 0%, #d6e9f0 100%);
            min-height: 100vh;
        }

        /* Header */
        .header {
            background: #3d5a80;
            padding: 15px 20px;
            display: flex;
            align-items: center;
            color: white;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            z-index: 1001;
        }

        .menu-icon {
            display: flex;
            flex-direction: column;
            gap: 4px;
            margin-right: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .menu-icon:hover {
            transform: scale(1.1);
        }

        .menu-line {
            width: 25px;
            height: 3px;
            background: white;
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        /* Animated hamburger menu */
        .menu-icon.active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
        }

        .menu-icon.active .menu-line:nth-child(2) {
            opacity: 0;
        }

        .menu-icon.active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
        }

        .logo {
            width: 50px;
            height: 50px;
            background: #f8f9fa;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
        }

        .brand-info h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 2px;
        }

        .brand-info p {
            font-size: 14px;
            opacity: 0.9;
        }

        /* Drawer Overlay */
        .drawer-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .drawer-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        /* Drawer */
        .drawer {
            position: fixed;
            top: 0;
            left: -300px;
            width: 300px;
            height: 100vh;
            background: white;
            z-index: 1001;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
        }

        .drawer.active {
            left: 0;
        }

        .drawer-header {
            background: #3d5a80;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .drawer-content {
            flex: 1;
            padding: 20px 0;
        }

        .drawer-section {
            margin-bottom: 30px;
        }

        .drawer-section-title {
            background: #f8f9fa;
            padding: 15px 20px;
            font-weight: 600;
            color: #3d5a80;
            text-align: center;
            border-radius: 25px;
            margin: 0 20px 15px;
        }

        .drawer-item {
            display: flex;
            align-items: center;
            padding: 15px 30px;
            color: #333;
            text-decoration: none;
            transition: all 0.3s ease;
            cursor: pointer;
        }

        .drawer-item:hover {
            background: #f8f9fa;
            color: #3d5a80;
        }

        .drawer-item-icon {
            width: 24px;
            height: 24px;
            margin-right: 15px;
            background: #3d5a80;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .drawer-item-text {
            font-weight: 500;
        }

        /* Main Container */
        .container {
            display: flex;
            padding: 20px;
            gap: 20px;
            max-width: 1400px;
            margin: 0 auto;
            transition: all 0.3s ease;
        }

        .container.drawer-open {
            margin-left: 300px;
        }

        /* Left Side - Menu */
        .menu-section {
            flex: 2;
        }

        /* Category Filters */
        .category-filters {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .category-btn {
            padding: 12px 25px;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: 14px;
        }

        .category-btn.dropdown {
            background: #f8f9fa;
            color: #666;
            position: relative;
        }

        .category-btn.dropdown::after {
            content: '▼';
            margin-left: 10px;
            font-size: 12px;
        }

        .category-btn.active {
            background: #3d5a80;
            color: white;
        }

        .category-btn.inactive {
            background: #3d5a80;
            color: white;
            opacity: 0.8;
        }

        /* Menu Grid */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 20px;
        }

        .menu-item {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
            cursor: pointer;
        }

        .menu-item:hover {
            transform: translateY(-5px);
        }

        .menu-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .menu-item-info {
            padding: 20px;
        }

        .menu-item h3 {
            font-size: 18px;
            color: #3d5a80;
            margin-bottom: 5px;
            font-weight: 600;
        }

        .menu-item .price {
            color: #666;
            font-weight: 500;
        }

        /* Right Side - Bills */
        .bills-section {
            flex: 1;
            max-width: 400px;
        }

        .bills-card {
            background: white;
            border-radius: 20px;
            padding: 25px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            height: fit-content;
        }

        .bills-title {
            font-size: 24px;
            color: #3d5a80;
            margin-bottom: 25px;
            font-weight: 700;
        }

        .bill-item {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #f0f0f0;
        }

        .quantity-controls {
            display: flex;
            flex-direction: column;
            gap: 5px;
            margin-right: 15px;
        }

        .quantity-btn {
            width: 35px;
            height: 35px;
            border: none;
            border-radius: 50%;
            background: #3d5a80;
            color: white;
            font-size: 18px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .quantity-btn:hover {
            background: #2d4a70;
            transform: scale(1.1);
        }

        .quantity {
            text-align: center;
            font-weight: 600;
            color: #3d5a80;
            margin: 5px 0;
        }

        .bill-item img {
            width: 60px;
            height: 60px;
            border-radius: 10px;
            object-fit: cover;
            margin-right: 15px;
        }

        .bill-item-info {
            flex: 1;
        }

        .bill-item-name {
            font-weight: 600;
            color: #3d5a80;
            margin-bottom: 3px;
        }

        .bill-item-price {
            color: #666;
            font-size: 14px;
        }

        /* Checkout Section */
        .checkout-section {
            display: flex;
            gap: 15px;
            margin-top: 30px;
            align-items: center;
        }

        .checkout-btn {
            background: #3d5a80;
            color: white;
            border: none;
            padding: 15px 30px;
            border-radius: 15px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .checkout-btn:hover {
            background: #2d4a70;
            transform: translateY(-2px);
        }

        .total-section {
            background: #f8f9fa;
            padding: 15px 20px;
            border-radius: 15px;
            text-align: center;
        }

        .total-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 5px;
        }

        .total-amount {
            font-size: 18px;
            font-weight: 700;
            color: #3d5a80;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                flex-direction: column;
                padding: 15px;
            }

            .container.drawer-open {
                margin-left: 0;
            }

            .drawer {
                width: 280px;
                left: -280px;
            }

            .menu-grid {
                grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            }

            .bills-section {
                max-width: none;
            }

            .header {
                padding: 10px 15px;
            }

            .brand-info h1 {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <!-- Drawer Overlay -->
    <div class="drawer-overlay" id="drawer-overlay" onclick="closeDrawer()"></div>

    <!-- Drawer -->
    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <h3>Menu</h3>
        </div>
        <div class="drawer-content">
            <!-- Cashier Section -->
            <div class="drawer-section">
                <div class="drawer-item" onclick="navigateTo('cashier')">
                    <div class="drawer-item-icon">🛒</div>
                    <div class="drawer-item-text">Cashier</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('order-list')">
                    <div class="drawer-item-icon">📋</div>
                    <div class="drawer-item-text">Order List</div>
                </div>
            </div>

            <!-- Admin Section -->
            <div class="drawer-section">
                <div class="drawer-section-title">Admin</div>
                <div class="drawer-item" onclick="navigateTo('dashboard')">
                    <div class="drawer-item-icon">🏠</div>
                    <div class="drawer-item-text">Dashboard</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('menu-management')">
                    <div class="drawer-item-icon">🍽️</div>
                    <div class="drawer-item-text">Menu Management</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('sales-report')">
                    <div class="drawer-item-icon">⏰</div>
                    <div class="drawer-item-text">Sales Report</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('account-management')">
                    <div class="drawer-item-icon">👤</div>
                    <div class="drawer-item-text">Account Management</div>
                </div>
            </div>

            <!-- Logout Section -->
            <div class="drawer-section">
                <div class="drawer-item" onclick="logout()">
                    <div class="drawer-item-icon">🚪</div>
                    <div class="drawer-item-text">
                        <a href="{{ route('logout') }}" style="text-decoration: none; color: inherit;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Header -->
    <div class="header">
        <div class="menu-icon" id="menu-icon" onclick="toggleDrawer()">
            <div class="menu-line"></div>
            <div class="menu-line"></div>
            <div class="menu-line"></div>
        </div>
        <div class="logo">
            <!-- Logo placeholder - replace with your logo -->
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="width: 30px; height: 30px;">
        </div>
        <div class="brand-info">
            <h1>Alternatif Coffee</h1>
            <p>Lukmin Tajinan</p>
        </div>
    </div>

    <div class="container" id="container">
        <!-- Menu Section -->
        <div class="menu-section">
            <!-- Category Filters -->
            <div class="category-filters">
                <button class="category-btn dropdown">All Category</button>
                <button class="category-btn active">Coffee</button>
                <button class="category-btn inactive">Non Coffee</button>
                <button class="category-btn inactive">Food</button>
            </div>

            <!-- Menu Grid -->
            <div class="menu-grid">
                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

                <div class="menu-item" onclick="addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg')">
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=400&h=300&fit=crop" alt="Nasi Goreng">
                    <div class="menu-item-info">
                        <h3>Nasi Goreng</h3>
                        <p class="price">Rp. 15.000</p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Bills Section -->
        <div class="bills-section">
            <div class="bills-card">
                <h2 class="bills-title">Bills</h2>
                
                <div id="bill-items">
                    <!-- Bill items will be populated by JavaScript -->
                </div>

                <div class="checkout-section">
                    <button class="checkout-btn">Checkout</button>
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
        let isDrawerOpen = false;

        // Drawer Functions
        function toggleDrawer() {
            const drawer = document.getElementById('drawer');
            const overlay = document.getElementById('drawer-overlay');
            const menuIcon = document.getElementById('menu-icon');
            const container = document.getElementById('container');

            isDrawerOpen = !isDrawerOpen;

            if (isDrawerOpen) {
                drawer.classList.add('active');
                overlay.classList.add('active');
                menuIcon.classList.add('active');
                if (window.innerWidth > 768) {
                    container.classList.add('drawer-open');
                }
                document.body.style.overflow = 'hidden';
            } else {
                drawer.classList.remove('active');
                overlay.classList.remove('active');
                menuIcon.classList.remove('active');
                container.classList.remove('drawer-open');
                document.body.style.overflow = 'auto';
            }
        }

        function closeDrawer() {
            if (isDrawerOpen) {
                toggleDrawer();
            }
        }

        // Navigation Functions
        function navigateTo(page) {
            console.log(`Navigating to: ${page}`);
            // Add your navigation logic here
            closeDrawer();
        }

        function logout() {
            console.log('Logging out...');
            // Add your logout logic here
            closeDrawer();
        }

        // Cart Functions
        function addToCart(name, price, image) {
            if (cart[name]) {
                cart[name].quantity += 1;
            } else {
                cart[name] = {
                    name: name,
                    price: price,
                    quantity: 1,
                    image: image
                };
            }
            updateBillDisplay();
        }

        function updateQuantity(name, change) {
            if (cart[name]) {
                cart[name].quantity += change;
                if (cart[name].quantity <= 0) {
                    delete cart[name];
                }
            }
            updateBillDisplay();
        }

        function updateBillDisplay() {
            const billItemsContainer = document.getElementById('bill-items');
            const totalAmountElement = document.getElementById('total-amount');
            
            billItemsContainer.innerHTML = '';
            totalAmount = 0;

            for (const itemName in cart) {
                const item = cart[itemName];
                totalAmount += item.price * item.quantity;

                const billItem = document.createElement('div');
                billItem.className = 'bill-item';
                billItem.innerHTML = `
                    <div class="quantity-controls">
                        <button class="quantity-btn" onclick="updateQuantity('${itemName}', 1)">+</button>
                        <div class="quantity">${item.quantity}</div>
                        <button class="quantity-btn" onclick="updateQuantity('${itemName}', -1)">−</button>
                    </div>
                    <img src="https://images.unsplash.com/photo-1612929633738-8fe44f7ec841?w=100&h=100&fit=crop" alt="${item.name}">
                    <div class="bill-item-info">
                        <div class="bill-item-name">${item.name}</div>
                        <div class="bill-item-price">Rp. ${item.price.toLocaleString()}</div>
                    </div>
                `;
                billItemsContainer.appendChild(billItem);
            }

            totalAmountElement.textContent = `Rp. ${totalAmount.toLocaleString()}`;
        }

        // Handle window resize
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 768) {
                document.getElementById('container').classList.remove('drawer-open');
            } else if (isDrawerOpen) {
                document.getElementById('container').classList.add('drawer-open');
            }
        });

        // Close drawer on escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape' && isDrawerOpen) {
                closeDrawer();
            }
        });

        // Initialize with some items in cart for demo
        addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg');
        addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg');
        addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg');
        addToCart('Nasi Goreng', 15000, 'nasigoreng.jpg');
    </script>
</body>
</html>