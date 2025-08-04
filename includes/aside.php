<aside id="sidebar">
    <link rel="stylesheet" href="../assets/css/aside.css">

    <div class="sidebar-header">
        <span class="nav-text">SPA Management</span>
        <span class="sidebar-toggle" onclick="toggleSidebar()">☰</span>
    </div>

    <ul class="sidebar-nav">
        <!-- Dashboard -->
        <li><a href="/spa managent system/modules/dashboard/index.php" class="nav-link">
                <span class="nav-icon">📊</span> <span class="nav-text">Analytics</span>
            </a></li>

        <!-- Core Modules -->
        <li class="menu-header">Operations</li>
        <li><a href="/spa managent system/modules/clients/index.php" class="nav-link">
                <span class="nav-icon">👥</span> <span class="nav-text">Clients</span>
            </a></li>

        <li><a href="/spa managent system/modules/appointments/index.php" class="nav-link">
                <span class="nav-icon">📅</span> <span class="nav-text">Appointments</span>
            </a></li>



        <li><a href="/spa managent system/modules/payments/index.php" class="nav-link">
                <span class="nav-icon">💳</span> <span class="nav-text">Payments</span>
            </a></li>

        <li><a href="/spa managent system/modules/tips/record_tip.php" class="nav-link">
                <span class="nav-icon">💰</span> <span class="nav-text">Tips</span>
            </a></li>

        <li><a href="/spa managent system/modules/staff/index.php" class="nav-link">
                <span class="nav-icon">👨‍🔧</span> <span class="nav-text">Staff</span>
            </a></li>

        <li><a href="/spa managent system/modules/sales/index.php" class="nav-link">
                <span class="nav-icon">🛍️</span> <span class="nav-text">Sales</span>
            </a></li>

        <!-- Services -->
        <li class="menu-header">Services & Products</li>

        <li><a href="/spa managent system/modules/services/index.php" class="nav-link">
                <span class="nav-icon">💆</span> <span class="nav-text">Services</span>
            </a></li>

        <li><a href="/spa managent system/modules/service_categories/index.php" class="nav-link">
                <span class="nav-icon">🗂️</span> <span class="nav-text">Service Categories</span>
            </a></li>

        <li><a href="/spa managent system/modules/Products/index.php" class="nav-link">
                <span class="nav-icon">🧴</span> <span class="nav-text">Products</span>
            </a></li>



        <!-- Inventory -->
        <li class="has-submenu">
            <a href="#" class="nav-link">
                <span class="nav-icon">📦</span> <span class="nav-text">Inventory</span>
                <span class="dropdown-icon">▼</span>
            </a>
            <ul class="submenu">
                <li><a href="/spa managent system/modules/inventory/index.php" class="nav-link"><span class="nav-text">Stock</span></a></li>
                <li><a href="/spa managent system/modules/inventory/restock.php" class="nav-link"><span class="nav-text">Restock</span></a></li>
            </ul>
        </li>

        <li><a href="/spa managent system/modules/suppliers/index.php" class="nav-link">
                <span class="nav-icon">🚚</span> <span class="nav-text">Suppliers</span>
            </a></li>

        <!-- Payroll -->
        <li class="menu-header">Payroll</li>

        <li class="has-submenu">
            <a href="#" class="nav-link">
                <span class="nav-icon">🧾</span> <span class="nav-text">Payroll</span>
                <span class="dropdown-icon">▼</span>
            </a>
            <ul class="submenu">
                <li><a href="/spa managent system/modules/payroll/index.php" class="nav-link"><span class="nav-text">Payroll</span></a></li>
                <li><a href="/spa managent system/modules/payroll/generate_payroll.php" class="nav-link"><span class="nav-text">Generate Payroll</span></a></li>
            </ul>
        </li>

        <!-- Reports -->
        <li class="menu-header">Reports</li>

        <li><a href="/spa managent system/modules/Reports/daily_report_content.php" class="nav-link">
                <span class="nav-icon">📅</span> <span class="nav-text">Daily Report</span>
            </a></li>

        <li><a href="/spa managent system/modules/Reports/monthly.php" class="nav-link">
                <span class="nav-icon">📆</span> <span class="nav-text">Monthly Report</span>
            </a></li>

        <li><a href="/spa managent system/modules/Reports/role_report.php" class="nav-link">
                <span class="nav-icon">📄</span> <span class="nav-text">Role Report</span>
            </a></li>

        <!-- Settings -->
        <li class="menu-header">Settings</li>

        <li><a href="/spa managent system/modules/settings/global_commission.php" class="nav-link">
                <span class="nav-icon">⚙️</span> <span class="nav-text">Global Commission</span>
            </a></li>
    </ul>
</aside>

<script>
    function toggleSidebar() {
        document.getElementById("sidebar").classList.toggle("collapsed");
    }

    document.addEventListener("DOMContentLoaded", function() {
        const currentPath = window.location.pathname.replace(/\/+$/, "");
        const navLinks = document.querySelectorAll(".nav-link");

        navLinks.forEach(link => {
            const linkPath = new URL(link.href).pathname.replace(/\/+$/, "");
            if (linkPath === currentPath) {
                link.classList.add("active");

                const parent = link.closest(".has-submenu");
                if (parent) {
                    parent.classList.add("active");
                }
            }
        });
    });
</script>