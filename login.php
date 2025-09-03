<?php

include 'config.php';
session_start();

if(isset($_POST['submit'])){

    $email=mysqli_real_escape_string($conn,$_POST['email']);
    
    
    if(empty($_POST['password'])){
        $message[] = 'Password cannot be empty!';
    } else {
        $password=mysqli_real_escape_string($conn,md5($_POST['password']) );

        $select_users=mysqli_query($conn,"SELECT * FROM `register` WHERE email='$email' AND password='$password'") or die('query failed');

        if(mysqli_num_rows($select_users) > 0){
            $row=mysqli_fetch_assoc($select_users);

            if($row['user_type'] =='admin'){
                $_SESSION['admin_name']=$row['name'];
                $_SESSION['admin_email']=$row['email'];
                $_SESSION['admin_id']=$row['id'];
                header('location:admin_dashboard.php');

            }elseif($row['user_type'] =='user'){
                $_SESSION['user_name']=$row['name'];
                $_SESSION['user_email']=$row['email'];
                $_SESSION['user_id']=$row['id'];

                // Transfer guest cart items to user cart if any
                if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
                    foreach($_SESSION['cart'] as $item) {
                        // Check if item already exists in user's cart
                        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '{$item['name']}' AND user_id = '{$row['id']}'") or die('query failed');
                        
                        if(mysqli_num_rows($check_cart) == 0) {
                            // Add item to user's cart
                            mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('{$row['id']}', '{$item['name']}', '{$item['price']}', '{$item['quantity']}', '{$item['image']}')") or die('query failed');
                        }
                    }
                    // Clear session cart after transfer
                    $_SESSION['cart'] = array();
                }

                // Check if there's a redirect URL stored in session
                if(isset($_SESSION['redirect_after_login'])) {
                    $redirect = $_SESSION['redirect_after_login'];
                    unset($_SESSION['redirect_after_login']); // Clear the stored URL
                    header('location: ' . $redirect);
                } else {
                    header('location:home.php');
                }
                exit();
            }
        }else{
            $message[]='Incorrect email or password';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Boi Lagbe!</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Custom CSS -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

<?php
if(isset($message)){
    foreach($message as $message){
        echo '
        <div class="message '.($message === 'Password cannot be empty!' ? 'message-error' : '').'">
            <span>'.$message.'</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
    ';    
    }
}
?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Welcome Back</h1>
            <p>Sign in to continue to Boi Lagbe!</p>
        </div>

        <form action="" method="post" class="auth-form" id="loginForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input type="password" name="password" id="password" required placeholder="Enter your password">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword()"></i>
                </div>
                <div class="error-message" id="passwordError"></div>
            </div>
            
            <button type="submit" name="submit" class="btn btn-primary w-full">Sign In</button>
        </form>
        
        <div class="auth-footer">
            <p>Don't have an account? <a href="register.php">Sign Up</a></p>
        </div>
    </div>
</div>

<style>
.auth-container {
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: var(--spacing-lg);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
}

.auth-card {
    background-color: var(--surface);
    border-radius: 12px;
    box-shadow: var(--shadow-lg);
    width: 100%;
    max-width: 400px;
    padding: var(--spacing-xl);
}

.auth-header {
    text-align: center;
    margin-bottom: var(--spacing-xl);
}

.auth-header h1 {
    font-size: 1.875rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: var(--spacing-xs);
}

.auth-header p {
    color: var(--text-secondary);
}

.auth-form {
    margin-bottom: var(--spacing-xl);
}

.form-group {
    margin-bottom: var(--spacing-md);
}

.form-group label {
    display: block;
    margin-bottom: var(--spacing-xs);
    color: var(--text-primary);
    font-weight: 500;
}

.password-input {
    position: relative;
}

.password-input input {
    padding-right: 2.5rem;
}

.toggle-password {
    position: absolute;
    right: var(--spacing-md);
    top: 50%;
    transform: translateY(-50%);
    color: var(--text-secondary);
    cursor: pointer;
}

.w-full {
    width: 100%;
}

.auth-footer {
    text-align: center;
    color: var(--text-secondary);
}

.auth-footer a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 500;
}

.auth-footer a:hover {
    text-decoration: underline;
}

.message {
    position: fixed;
    top: 20px;
    left: 50%;
    transform: translateX(-50%);
    z-index: 1000;
    padding: 1rem 2rem;
    border-radius: 8px;
    display: flex;
    align-items: center;
    gap: 1rem;
    background: white;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    animation: slideDown 0.3s ease-out;
}

.message-error {
    background-color: #fee2e2;
    border: 2px solid #ef4444;
    color: #991b1b;
    font-weight: 500;
}

.message span {
    font-size: 1rem;
}

.message i {
    cursor: pointer;
    color: currentColor;
    opacity: 0.7;
    transition: opacity 0.2s;
}

.message i:hover {
    opacity: 1;
}

.error-message {
    color: #dc2626;
    font-size: 0.875rem;
    margin-top: 0.25rem;
    min-height: 1.25rem;
}

input:invalid {
    border-color: #dc2626;
}

@keyframes slideDown {
    from {
        transform: translate(-50%, -100%);
        opacity: 0;
    }
    to {
        transform: translate(-50%, 0);
        opacity: 1;
    }
}
</style>

<script>
function togglePassword() {
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.querySelector('.toggle-password');
    
    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        toggleIcon.classList.remove('fa-eye');
        toggleIcon.classList.add('fa-eye-slash');
    } else {
        passwordInput.type = 'password';
        toggleIcon.classList.remove('fa-eye-slash');
        toggleIcon.classList.add('fa-eye');
    }
}

function validateForm() {
    let isValid = true;
    const password = document.getElementById('password');
    const passwordError = document.getElementById('passwordError');
    
    // Reset error message
    passwordError.textContent = '';
    
    // Check if password is empty
    if (password.value.trim() === '') {
        passwordError.textContent = 'Password cannot be empty';
        isValid = false;
    }
    
    return isValid;
}
</script>

</body>
</html>
