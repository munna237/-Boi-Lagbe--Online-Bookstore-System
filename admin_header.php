<?php
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message">
                <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    ';    
    } 
}
?>

<div class="app-container">
    <!-- Sidebar -->
    <aside class="sidebar">
        <a href="admin_dashboard.php" class="brand">
            <i class="fas fa-book brand-icon"></i>
                <span>Boi Lagbe!</span>
            </a>
        
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
                    <a href="admin_dashboard.php" class="nav-link">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span>Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="admin_products.php" class="nav-link">
                        <i class="fas fa-box nav-icon"></i>
                        <span>Products</span>
                    </a>
                </li>
                <li>
                    <a href="admin_orders.php" class="nav-link">
                        <i class="fas fa-shopping-cart nav-icon"></i>
                        <span>Orders</span>
                    </a>
                </li>
                <li>
                    <a href="admin_users.php" class="nav-link">
                        <i class="fas fa-users nav-icon"></i>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="admin_messages.php" class="nav-link">
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

    <!-- Main Content -->
    <main class="main-content">
        <header class="content-header">
            <div class="header-left">
                <button class="btn-icon menu-toggle d-lg-none">
                    <i class="fas fa-bars"></i>
                </button>
                <h1 class="page-title">Page</h1>
            </div>
            
            <div class="header-right">
                <div class="search-bar">
                    <i class="fas fa-search search-icon"></i>
                    <input type="text" class="search-input" placeholder="Search...">
                </div>
                
                <button class="btn-icon notification-badge">
                        <i class="fas fa-bell"></i>
                    </button>
                
                <div class="admin-profile d-none d-md-flex">
                    <div class="admin-avatar">
                        <i class="fas fa-user"></i>
                        </div>
                    <div class="admin-info">
                        <div class="admin-name"><?php echo $_SESSION['admin_name']; ?></div>
                        <div class="admin-role">Administrator</div>
                    </div>
                </div>
            </div>
        </header>
        
        <div class="content-wrapper">
            <div class="breadcrumb"></div>
            <!-- Page content will be inserted here -->
        </div>
    </main>
</div>

<script>
// Mobile menu toggle
document.querySelector('.menu-toggle')?.addEventListener('click', () => {
    document.querySelector('.sidebar').classList.toggle('active');
});

// Close mobile menu when clicking outside
document.addEventListener('click', (e) => {
    const sidebar = document.querySelector('.sidebar');
    const menuToggle = document.querySelector('.menu-toggle');
    
    if (sidebar?.classList.contains('active') && 
        !sidebar.contains(e.target) && 
        !menuToggle.contains(e.target)) {
        sidebar.classList.remove('active');
    }
});

// Auto-hide toast messages
document.querySelectorAll('.toast-message').forEach(toast => {
    setTimeout(() => {
        toast.style.animation = 'slideOut 0.3s ease-out forwards';
        setTimeout(() => toast.remove(), 300);
    }, 5000);
});

// Search functionality
document.querySelector('.search-input')?.addEventListener('input', (e) => {
    const searchTerm = e.target.value.toLowerCase();
    // Add your search logic here
});
</script>
