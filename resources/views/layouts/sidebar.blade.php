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

        /* CSS untuk drawer sudah di-handle di app.blade.php */

        .drawer-content {
            padding: 0;
            height: 100vh;
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
            margin-top: 5px;
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

        /* Active state styling */
        .drawer-item.active {
            background-color: #dbeafe;
            border-left-color: #3b82f6;
            transform: translateX(2px);
        }

        .drawer-item.active .drawer-item-icon {
            color: #3b82f6;
            background-color: #bfdbfe;
            transform: scale(1.1);
        }

        .drawer-item.active .drawer-item-text {
            color: #1e40af;
            font-weight: 600;
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

        /* Hamburger menu icon transformation for perfect X */
        .menu-icon.active .menu-line:nth-child(1) {
            transform: rotate(45deg) translate(4.5px, 4.5px) !important;
        }

        .menu-icon.active .menu-line:nth-child(2) {
            opacity: 0 !important;
        }

        .menu-icon.active .menu-line:nth-child(3) {
            transform: rotate(-45deg) translate(4.5px, -4.5px) !important;
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

        /* CSS responsive untuk drawer sudah di-handle di app.blade.php */
    </style>
</head>
<body>

   <div class="drawer" id="drawer">
        <div class="drawer-content">
            @auth
                @if(Auth::user()->usertype == 'user')
                <div class="drawer-section">
                    <div class="drawer-item" onclick="navigateTo('kasir', this)">
                        <div class="drawer-item-icon"><img src="/images/kasir.png" alt="Cashier" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Cashier</div>
                    </div>
                    <div class="drawer-item" onclick="navigateTo('order-list', this)">
                        <div class="drawer-item-icon"><img src="/images/order_list.png" alt="Order List" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Daily Sales Report</div>
                    </div>
                </div>
                @endif
                @if(Auth::user()->usertype == 'admin')
                <div class="drawer-section">
                    <div class="drawer-item" onclick="navigateTo('dashboard', this)">
                        <div class="drawer-item-icon"><img src="/images/dashboard.png" alt="Dashboard" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Dashboard</div>
                    </div>
                    <div class="drawer-item" onclick="navigateTo('menu-management', this)">
                        <div class="drawer-item-icon"><img src="/images/menu_management.png" alt="Menu Management" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Menu Management</div>
                    </div>
                    <div class="drawer-item" onclick="navigateTo('category-management', this)">
                        <div class="drawer-item-icon"><img src="/images/category-management.png" alt="Category Management" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Category Management</div>
                    </div>
                    <div class="drawer-item" onclick="navigateTo('sales-report', this)">
                        <div class="drawer-item-icon"><img src="/images/sales_report.png" alt="Sales Report" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Sales Report</div>
                    </div>
                    <div class="drawer-item" onclick="navigateTo('account-management', this)">
                        <div class="drawer-item-icon"><img src="/images/account_management.png" alt="Account Management" class="drawer-item-icon-img"></div>
                        <div class="drawer-item-text">Account Management</div>
                    </div>
                </div>
                @endif
            @endauth
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
    // Function to set active state
    function setActiveItem(element) {
        // Remove active class from all drawer items
        const allItems = document.querySelectorAll('.drawer-item');
        allItems.forEach(item => {
            item.classList.remove('active');
        });
        
        // Add active class to clicked item
        if (element) {
            element.classList.add('active');
        }
    }

    // Function to navigate and set active state
    function navigateTo(page, element) {
        // Set active state immediately
        setActiveItem(element);
        
        // Navigate to page
        window.location.href = '/' + page;
    }

    function logout() {
        window.location.href = '{{ route('logout') }}';
    }

    // Set active state based on current page on page load
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        const drawerItems = document.querySelectorAll('.drawer-item');
        
        drawerItems.forEach(item => {
            const onclick = item.getAttribute('onclick');
            if (onclick && onclick.includes(currentPath.substring(1))) {
                item.classList.add('active');
            }
        });
    });
    </script>
</body>