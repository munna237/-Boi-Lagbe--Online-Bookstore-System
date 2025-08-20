<?php

include 'config.php';

if(isset($_POST['submit'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    
    // Check if password fields are empty
    if(empty($_POST['password']) || empty($_POST['cpassword'])){
        $message[] = 'Password fields cannot be empty!';
    } else {
        $password = mysqli_real_escape_string($conn, md5($_POST['password']));
        $cpassword = mysqli_real_escape_string($conn, md5($_POST['cpassword']));
        $user_type = $_POST['user_type'];

        // Check if email already exists in the database (regardless of user_type)
        $select_users = mysqli_query($conn, "SELECT * FROM `register` WHERE email='$email'") or die('query failed');

        if(mysqli_num_rows($select_users) > 0){
            $message[] = 'Email address already exists! Please use a different email.';
        } else {
            if($password != $cpassword){
                $message[] = 'Confirm password not matched!';
            } else {
                mysqli_query($conn, "INSERT INTO `register`(name, email, password, user_type) VALUES('$name', '$email', '$password', '$user_type')") or die('query failed');
                $message[] = 'Registered Successfully!';
                header('location:login.php');
            }
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
    <title>Register - BookExpress</title>

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
        <div class="message '.($message === 'Email address already exists! Please use a different email.' || $message === 'Password fields cannot be empty!' || $message === 'Confirm password not matched!' ? 'message-error' : '').'">
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
            <h1>Create Account</h1>
            <p>Join BookExpress and start exploring</p>
        </div>

        <form action="" method="post" class="auth-form" id="registerForm" onsubmit="return validateForm()">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" name="name" id="name" required placeholder="Enter your full name">
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" required placeholder="Enter your email">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="password-input">
                    <input type="password" name="password" id="password" required placeholder="Create a password">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('password')"></i>
                </div>
                <div class="error-message" id="passwordError"></div>
            </div>

            <div class="form-group">
                <label for="cpassword">Confirm Password</label>
                <div class="password-input">
                    <input type="password" name="cpassword" id="cpassword" required placeholder="Confirm your password">
                    <i class="fas fa-eye toggle-password" onclick="togglePassword('cpassword')"></i>
                </div>
                <div class="error-message" id="cpasswordError"></div>
            </div>
        
            <div class="form-group">
                <label for="user_type">Account Type</label>
                <select name="user_type" id="user_type" required>
                    <option value="user">Customer</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        
            <button type="submit" name="submit" class="btn btn-primary w-full">Create Account</button>
        </form>
        
        <div class="auth-footer">
            <p>Already have an account? <a href="login.php">Sign In</a></p>
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

select {
    appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
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
function togglePassword(inputId) {
    const passwordInput = document.getElementById(inputId);
    const toggleIcon = passwordInput.nextElementSibling;
    
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
    const cpassword = document.getElementById('cpassword');
    const passwordError = document.getElementById('passwordError');
    const cpasswordError = document.getElementById('cpasswordError');
    
    // Reset error messages
    passwordError.textContent = '';
    cpasswordError.textContent = '';
    
   
    if (password.value.trim() === '') {
        passwordError.textContent = 'Password cannot be empty';
        isValid = false;
    }
    
   
    if (cpassword.value.trim() === '') {
        cpasswordError.textContent = 'Confirm password cannot be empty';
        isValid = false;
    }
    
    
    if (password.value !== cpassword.value && password.value.trim() !== '' && cpassword.value.trim() !== '') {
        cpasswordError.textContent = 'Passwords do not match';
        isValid = false;
    }
    
    return isValid;
}
</script>

</body>
</html>