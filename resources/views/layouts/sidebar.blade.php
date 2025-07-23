    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f5f5f5;
        }

        .drawer {
            width: 280px;
            height: 100vh;
            background-color: white;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1000;
        }

        .drawer-header {
            background: linear-gradient(135deg, #4a5568 0%, #2d3748 100%);
            padding: 20px;
            text-align: center;
            position: relative;
        }

        .drawer-header::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            /* background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="20" cy="20" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="80" cy="80" r="1" fill="rgba(255,255,255,0.1)"/><circle cx="40" cy="60" r="1" fill="rgba(255,255,255,0.1)"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>'); */
            opacity: 0.3;
        }

        .menu-toggle {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: 8px;
            border-radius: 4px;
            transition: background-color 0.2s;
        }

        .menu-toggle:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .menu-lines {
            width: 20px;
            height: 2px;
            background-color: currentColor;
            position: relative;
            display: block;
        }

        .menu-lines::before,
        .menu-lines::after {
            content: '';
            position: absolute;
            width: 100%;
            height: 2px;
            background-color: currentColor;
            left: 0;
        }

        .menu-lines::before {
            top: -6px;
        }

        .menu-lines::after {
            bottom: -6px;
        }

        .drawer-content {
            padding: 0;
            height: calc(100vh - 80px);
            overflow-y: auto;
        }

        .drawer-section {
            border-bottom: 1px solid #e2e8f0;
        }

        .drawer-section:last-child {
            border-bottom: none;
            margin-top: auto;
        }

        .drawer-section-title {
            background-color: #f8f9fa;
            padding: 12px 20px;
            font-size: 14px;
            font-weight: 600;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-top: 1px solid #e2e8f0;
            border-bottom: 1px solid #e2e8f0;
        }

        .drawer-item {
            margin-top: 20px;
            display: flex;
            align-items: center;
            padding: 16px 20px;
            cursor: pointer;
            transition: all 0.2s ease;
            border-left: 4px solid transparent;
            background-color: white;
        }

        .drawer-item:hover {
            background-color: #f8fafc;
            border-left-color: #3b82f6;
            transform: translateX(2px);
        }

        .drawer-item:active {
            background-color: #e2e8f0;
        }

        .drawer-item-icon {
            width: 24px;
            height: 24px;
            margin-right: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            background-color: #f1f5f9;
            border-radius: 6px;
            padding: 4px;
            transition: all 0.2s ease;
        }
        .drawer-item-icon-img {
            width: 24px;
            height: 24px;
            object-fit: contain;
            border-radius: 6px;
            display: block;
        }

        .drawer-item:hover .drawer-item-icon {
            color: #3b82f6;
            background-color: #dbeafe;
            transform: scale(1.1);
        }

        .drawer-item-text {
            font-size: 15px;
            font-weight: 500;
            color:rgb(0, 0, 0);
            transition: color 0.2s ease;
        }

        .drawer-item:hover .drawer-item-text {
            color: #1e40af;
        }

        .drawer-item-text a {
            text-decoration: none;
            color: inherit;
            display: block;
            width: 100%;
        }

        /* Special styling for logout */
        .drawer-section-logout {
            margin-top: 20px;
            border-top: 1px solid #fee2e2;
        }

        .drawer-section-logout:hover {
            background-color: #fef2f2;
            border-left-color: #ef4444;
        }

        .drawer-section-logout .drawer-item-icon {
            color: #dc2626;
            background-color: #fee2e2;
        }

        .drawer-section-logout:hover .drawer-item-icon {
            color: #dc2626;
            background-color: #fecaca;
        }

        .drawer-section-logout .drawer-item-text {
            color: #dc2626;
        }

        /* Scrollbar styling */
        .drawer-content::-webkit-scrollbar {
            width: 4px;
        }

        .drawer-content::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .drawer-content::-webkit-scrollbar-thumb {
            background: #c1c1c1;
            border-radius: 2px;
        }

        .drawer-content::-webkit-scrollbar-thumb:hover {
            background: #a1a1a1;
        }

        /* Animation for smooth transitions */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .drawer-item {
            animation: slideIn 0.3s ease forwards;
        }

        .drawer-item:nth-child(1) { animation-delay: 0.1s; }
        .drawer-item:nth-child(2) { animation-delay: 0.2s; }
        .drawer-item:nth-child(3) { animation-delay: 0.3s; }
        .drawer-item:nth-child(4) { animation-delay: 0.4s; }
        .drawer-item:nth-child(5) { animation-delay: 0.5s; }
        .drawer-item:nth-child(6) { animation-delay: 0.6s; }

        /* Demo content area */
        .main-content {
            margin-left: 280px;
            padding: 20px;
            min-height: 100vh;
            background-color: #f8fafc;
        }
    </style>
</head>
<body>

    <div class="drawer" id="drawer">
        <div class="drawer-header">
            <button class="menu-toggle">
                <span class="menu-lines"></span>
            </button>
        </div>
        <div class="drawer-content">
            <div class="drawer-section">
                <div class="drawer-item" onclick="navigateTo('kasir')">
                    <div class="drawer-item-icon"><img src="/images/kasir.png" alt="Cashier" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Cashier</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('order-list')">
                    <div class="drawer-item-icon"><img src="/images/order_list.png" alt="Order List" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Order List</div>
                </div>
            </div>
            <div class="drawer-section">
                <div class="drawer-section-title">Admin</div>   
                <div class="drawer-item" onclick="navigateTo('dashboard')">
                    <div class="drawer-item-icon"><img src="/images/dashboard.png" alt="Dashboard" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Dashboard</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('menu-management')">
                    <div class="drawer-item-icon"><img src="/images/menu_management.png" alt="Menu Management" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Menu Management</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('sales-report')">
                    <div class="drawer-item-icon"><img src="/images/sales_report.png" alt="Sales Report" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Sales Report</div>
                </div>
                <div class="drawer-item" onclick="navigateTo('account-management')">
                    <div class="drawer-item-icon"><img src="/images/account_management.png" alt="Account Management" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">Account Management</div>
                </div>
            </div>
            <div class="drawer-section-logout">
                <div class="drawer-item" onclick="logout()">
                    <div class="drawer-item-icon"><img src="/images/logout.png" alt="Logout" class="drawer-item-icon-img"></div>
                    <div class="drawer-item-text">
                        <a href="{{ route('logout') }}" style="text-decoration: none; color: inherit;">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <script>
    function navigateTo(page) {
        window.location.href = '/' + page;
    }

    function logout() {
        window.location.href = '{{ route('logout') }}';
    }
    </script>
</body>