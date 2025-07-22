<div class="drawer" id="drawer">
    <div class="drawer-header">
        <h3>Menu</h3>
    </div>
    <div class="drawer-content">
        <div class="drawer-section">
            <div class="drawer-item" onclick="navigateTo('kasir')">
                <div class="drawer-item-icon">🛒</div>
                <div class="drawer-item-text">Cashier</div>
            </div>
            <div class="drawer-item" onclick="navigateTo('order-list')">
                <div class="drawer-item-icon">📋</div>
                <div class="drawer-item-text">Order List</div>
            </div>
        </div>
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

<script>
function navigateTo(page) {
    window.location.href = '/' + page;
}

function logout() {
    window.location.href = '{{ route('logout') }}';
}
</script>