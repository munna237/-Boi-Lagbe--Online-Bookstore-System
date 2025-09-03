<?php
if(!isset($_SESSION['admin_id'])){
   header('location:login.php');
}
?>
<!-- Sidebar -->
<aside class="sidebar">
    <div class="brand">
        <i class="fas fa-book brand-icon"></i>
        <span>BoiBD.com</span>
    </div>
    
    <div class="admin-profile">
        <div class="admin-avatar">
            <i class="fas fa-user"></i>
        </div>
        <div class="admin-info">
            <div class="admin-name"><?php echo $_SESSION['admin_name']; ?></div>
            <div class="admin-role">Administrator</div>
        </div>
    </div>

    <nav class="nav-menu">
        <ul class="nav-list">
            <li>
                <a href="admin_dashboard.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_dashboard.php' ? 'active' : ''; ?>">
                    <i class="fas fa-home nav-icon"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li>
                <a href="admin_products.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_products.php' ? 'active' : ''; ?>">
                    <i class="fas fa-box nav-icon"></i>
                    <span>Products</span>
                </a>
            </li>
            <li>
                <a href="admin_orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_orders.php' ? 'active' : ''; ?>">
                    <i class="fas fa-shopping-cart nav-icon"></i>
                    <span>Orders</span>
                </a>
            </li>
            <li>
                <a href="admin_users.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_users.php' ? 'active' : ''; ?>">
                    <i class="fas fa-users nav-icon"></i>
                    <span>Users</span>
                </a>
            </li>
            <li>
                <a href="admin_messages.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'admin_messages.php' ? 'active' : ''; ?>">
                    <i class="fas fa-envelope nav-icon"></i>
                    <span>Messages</span>
                </a>
            </li>
        </ul>
        
        <div class="nav-footer">
            <a href="logout.php" class="nav-link">
                <i class="fas fa-sign-out-alt nav-icon"></i>
                <span>Logout</span>
            </a>
        </div>
    </nav>
</aside> 
