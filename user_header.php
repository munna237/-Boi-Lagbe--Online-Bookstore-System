<?php
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message success-message">
            <div class="message-content">
                <i class="fas fa-check-circle message-icon"></i>
                <span>'.$message.'</span>
            </div>
            <button class="message-close" onclick="this.parentElement.remove();">
                <i class="fas fa-times"></i>
            </button>
        </div>
    ';    
    } 
}

$cart_count = 0;
if(isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    $cart_count = mysqli_num_rows($select_cart);
} else if(isset($_SESSION['cart'])) {
    $cart_count = count($_SESSION['cart']);
}
?>

<header class="header">
    <div class="header-content">
        <a href="home.php" class="logo">
            <i class="fas fa-book"></i>
            <span>Boi Lagbe!</span>
        </a>

        <?php include 'navbar.php'; ?>

        <div class="icons">
            <a href="cart.php" class="cart-icon">
                <i class="fas fa-shopping-cart"></i>
                <?php if($cart_count > 0): ?>
                    <span class="cart-count"><?php echo $cart_count; ?></span>
                <?php endif; ?>
            </a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <div class="user-dropdown">
                    <button class="user-btn">
                        <i class="fas fa-user"></i>
                    </button>
                    <div class="dropdown-content">
                        <a href="profile.php">Profile</a>
                        <a href="orders.php">My Orders</a>
                        <a href="logout.php">Logout</a>
                    </div>
                </div>
            <?php else: ?>
                <a href="login.php" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <span>Login</span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</header>

<style>
.header {
    position: sticky;
    top: 0;
    z-index: 1000;
    background: white;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.header-content {
    max-width: 1200px;
    margin: 0 auto;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.logo {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 1.5rem;
    font-weight: 700;
    color: #2563eb;
    text-decoration: none;
}

.icons {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.cart-icon {
    position: relative;
    color: #374151;
    font-size: 1.25rem;
    text-decoration: none;
}

.cart-count {
    position: absolute;
    top: -8px;
    right: -8px;
    background: #2563eb;
    color: white;
    font-size: 0.75rem;
    font-weight: 600;
    padding: 0.25rem 0.5rem;
    border-radius: 999px;
    min-width: 18px;
    text-align: center;
}

.user-dropdown {
    position: relative;
}

.user-btn {
    background: none;
    border: none;
    color: #374151;
    font-size: 1.25rem;
    cursor: pointer;
    padding: 0.5rem;
}

.dropdown-content {
    display: none;
    position: absolute;
    right: 0;
    top: 100%;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    min-width: 160px;
    z-index: 1000;
}

.dropdown-content a {
    display: block;
    padding: 0.75rem 1rem;
    color: #374151;
    text-decoration: none;
    transition: background 0.2s;
}

.dropdown-content a:hover {
    background: #f3f4f6;
}

.user-dropdown:hover .dropdown-content {
    display: block;
}

.login-btn {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 1rem;
    background: #2563eb;
    color: white;
    text-decoration: none;
    border-radius: 6px;
    font-weight: 500;
    transition: background 0.2s;
}

.login-btn:hover {
    background: #1d4ed8;
}

@media (max-width: 768px) {
    .header-content {
        padding: 0.75rem;
        flex-wrap: wrap;
    }

    .icons {
        order: 2;
    }
}

/* Message Styling */
.message {
    max-width: 1200px;
    margin: 1.5rem auto;
    padding: 1rem 1.5rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    background-color: #d1e7dd;
    border: 1px solid #badbcc;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.message-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.message-icon {
    font-size: 1.25rem;
    color: #198754;
    flex-shrink: 0;
}

.message span {
    color: #0f5132;
    font-weight: 600;
    font-size: 0.95rem;
    line-height: 1.4;
}

.message-close {
    background: none;
    border: none;
    padding: 0.5rem;
    margin: -0.5rem;
    cursor: pointer;
    color: #0f5132;
    opacity: 0.7;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.message-close:hover {
    opacity: 1;
    transform: scale(1.1);
}

.message-close:focus {
    outline: none;
    box-shadow: 0 0 0 2px rgba(15, 81, 50, 0.2);
    border-radius: 4px;
}

@media (max-width: 768px) {
    .message {
        margin: 1rem;
        padding: 0.75rem 1rem;
    }
    
    .message span {
        font-size: 0.9rem;
    }
}
</style>

<script>
function toggleUserMenu() {
    const dropdown = document.querySelector('.user-dropdown');
    dropdown.classList.toggle('active');
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(event) {
        const isClickInside = dropdown.contains(event.target) || 
                            event.target.closest('.user-btn');
        
        if (!isClickInside && dropdown.classList.contains('active')) {
            dropdown.classList.remove('active');
        }
    });
}

// Add auto-dismiss functionality for messages
document.addEventListener('DOMContentLoaded', function() {
    const messages = document.querySelectorAll('.message');
    messages.forEach(message => {
        // Auto-dismiss after 5 seconds
        setTimeout(() => {
            message.style.opacity = '0';
            message.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                message.remove();
            }, 300);
        }, 5000);
    });
});
</script>
