<div class="sidebar" id="sidebar">
    <div class="sidebar-content">
        <!-- Store Header -->
        <div class="store-header">
            <!-- <div class="store-icon">
                <i class="fas fa-store"></i>
            </div> -->
            <div class="store-info">
                <div class="store-name">Inventory System</div>
                <div class="store-breadcrumb">Management System</div>
            </div>
            <button class="sidebar-toggle" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="fas fa-angle-left"></i>
            </button>
        </div>

        <!-- Navigation Menu -->
        <ul class="sidebar-nav">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="fas fa-home"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item dropdown">
                <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="collapse" data-bs-target="#inventoryDropdown" aria-expanded="false">
                    <i class="fas fa-boxes"></i>
                    <span>Inventory</span>
                    <i class="fas fa-chevron-down ms-auto"></i>
                </a>
                <div class="collapse" id="inventoryDropdown">
                    <ul class="dropdown-menu-custom">
                        <li>
                            <a href="products.php" class="dropdown-item-custom">
                                <i class="fas fa-box"></i>
                                <span>Products</span>
                            </a>
                        </li>
                        <li>
                            <a href="categories.php" class="dropdown-item-custom">
                                <i class="fas fa-tags"></i>
                                <span>Categories</span>
                            </a>
                        </li>
                        <li>
                            <a href="suppliers.php" class="dropdown-item-custom">
                                <i class="fas fa-truck"></i>
                                <span>Suppliers</span>
                            </a>
                        </li>
                        <li>
                            <a href="stock.php" class="dropdown-item-custom">
                                <i class="fas fa-warehouse"></i>
                                <span>Stock Management</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li class="nav-item">
                <a href="sales.php" class="nav-link">
                    <i class="fas fa-shopping-cart"></i>
                    <span>Sales</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="reports.php" class="nav-link">
                    <i class="fas fa-chart-bar"></i>
                    <span>Reports</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="users.php" class="nav-link">
                    <i class="fas fa-users"></i>
                    <span>Users</span>
                </a>
            </li>
        </ul>
    </div>

    <!-- User Profile Dropdown -->
    <div class="user-profile dropdown">
        <a class="d-flex align-items-center profile-toggle text-decoration-none dropdown-toggle" href="#" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
            <div class="profile-avatar me-2">
                <?php 
                    $name = $_SESSION['name'] ?? 'User';
                    $initials = strtoupper(substr($name, 0, 1) . (strpos($name, ' ') !== false ? substr($name, strpos($name, ' ') + 1, 1) : ''));
                    echo $initials;
                ?>
            </div>
            <div class="profile-details">
                <div class="profile-name"><?php echo htmlspecialchars($_SESSION['name'] ?? 'User'); ?></div>
                <div class="profile-email"><?php echo htmlspecialchars($_SESSION['email'] ?? 'guest@system.com'); ?></div>
            </div>
        </a>
        <ul class="dropdown-menu" aria-labelledby="userDropdown">
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-user me-2"></i> View profile
                </a>
            </li>
            <li>
                <a class="dropdown-item" href="#">
                    <i class="fas fa-cog me-2"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        toggleBtn.addEventListener('click', function() {
            sidebar.classList.toggle('collapsed');
            this.querySelector('i').classList.toggle('fa-angle-left');
            this.querySelector('i').classList.toggle('fa-angle-right');
        });
    });
</script>