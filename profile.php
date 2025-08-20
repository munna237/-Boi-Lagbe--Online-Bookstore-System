<?php
@include 'config.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('location:login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = []; // Initialize message array

// Handle Profile Update
if (isset($_POST['update_profile'])) {
    // --- Sanitize and update name and email ---
    $update_name = mysqli_real_escape_string($conn, $_POST['update_name']);
    $update_email = mysqli_real_escape_string($conn, $_POST['update_email']);

    mysqli_query($conn, "UPDATE `register` SET name = '$update_name', email = '$update_email' WHERE id = '$user_id'") or die('query failed');

    // --- Securely handle password update ---
    $new_pass = $_POST['new_pass'];
    $confirm_pass = $_POST['confirm_pass'];
    $old_pass = $_POST['old_pass'];

    // Only proceed if one of the password fields is filled
    if (!empty($new_pass) || !empty($confirm_pass) || !empty($old_pass)) {
        // Fetch the current hashed password from the database
        $user_data = mysqli_fetch_assoc(mysqli_query($conn, "SELECT password FROM `register` WHERE id = '$user_id'"));
        $current_hashed_password = $user_data['password'];

        // Verify the old password against the one in the database
        if (!password_verify($old_pass, $current_hashed_password)) {
            $message[] = 'Old password not matched!';
        } elseif ($new_pass != $confirm_pass) {
            $message[] = 'Confirm password not matched!';
        } elseif (empty($new_pass)) {
            $message[] = 'New password cannot be empty!';
        } else {
            // Hash the new password before storing it
            $hashed_new_password = password_hash($new_pass, PASSWORD_BCRYPT);
            mysqli_query($conn, "UPDATE `register` SET password = '$hashed_new_password' WHERE id = '$user_id'") or die('query failed');
            $message[] = 'Password updated successfully!';
        }
    } else {
        $message[] = 'Profile details updated successfully!';
    }
}

// Fetch all necessary data once
$user_query = mysqli_query($conn, "SELECT * FROM `register` WHERE id = '$user_id'");
$fetch_user = mysqli_fetch_assoc($user_query);

$orders_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id = '$user_id' ORDER BY placed_on DESC");
$total_orders = mysqli_num_rows($orders_query);

$total_spent_query = mysqli_query($conn, "SELECT SUM(total_price) as total_spent FROM `orders` WHERE user_id = '$user_id' AND payment_status = 'completed'");
$total_spent_result = mysqli_fetch_assoc($total_spent_query);
$total_spent = $total_spent_result['total_spent'] ?? 0;

// Generate the initial for the avatar
$user_initial = strtoupper(substr($fetch_user['name'], 0, 1));

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($fetch_user['name']); ?>'s Profile</title>

    <!-- Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

    <!-- FIXED: Link to the main stylesheet FIRST to get CSS variables -->
    <link rel="stylesheet" href="style.css">

    <!-- FIXED: Link to the profile-specific stylesheet SECOND -->
    <link rel="stylesheet" href="profile.css">

</head>
<body>

<!-- ADD THIS HTML SNIPPET to profile.php -->
<?php include 'user_header.php'; ?>

<header class="content-header">
    <div class="header-left">
        <h1 class="page-title">My Profile</h1>
    </div>
    <div class="header-right">
        <!-- You can add action buttons here in the future if needed -->
        <!-- Example: <a href="shop.php" class="btn btn-primary">Go to Shop</a> -->
    </div>
</header>

<!-- Your existing PHP message code and profile-container div go here... -->

<?php @include 'includes/header.php'; ?>

<?php
if(!empty($message)){
   foreach($message as $msg){
      echo '
      <div class="message">
         <span>'.htmlspecialchars($msg).'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<!-- FIXED: Changed class to match the CSS file for consistency -->
<div class="profile-container">
    <!-- Sidebar -->
    <aside class="profile-sidebar">
        <div class="user-info">
            <div class="avatar"><?php echo htmlspecialchars($user_initial); ?></div>
            <h3 class="username"><?php echo htmlspecialchars($fetch_user['name']); ?></h3>
            <p class="user-email"><?php echo htmlspecialchars($fetch_user['email']); ?></p>
        </div>
        <nav class="profile-nav">
             <ul class="nav-list">
                <li><a href="#dashboard" class="nav-link active"><i class="fas fa-tachometer-alt"></i><span>Dashboard</span></a></li>
                <li><a href="#update-profile" class="nav-link"><i class="fas fa-user-edit"></i><span>Update Profile</span></a></li>
                <li><a href="#order-history" class="nav-link"><i class="fas fa-history"></i><span>Order History</span></a></li>
                <li><a href="logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i><span>Logout</span></a></li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="profile-content">
        <!-- Dashboard Section -->
        <section id="dashboard" class="profile-section">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Account Overview</h2>
                </div>
                <div class="card-body">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <div class="icon"><i class="fas fa-shopping-cart"></i></div>
                            <div class="value"><?php echo $total_orders; ?></div>
                            <div class="label">Total Orders</div>
                        </div>
                        <div class="stat-card">
                            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                            <div class="value">$<?php echo number_format($total_spent, 2); ?></div>
                            <div class="label">Total Spent</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Update Profile Section -->
        <section id="update-profile" class="profile-section">
            <div class="card">
                 <div class="card-header">
                    <h2 class="card-title">Update Your Information</h2>
                </div>
                <div class="card-body">
                    <form action="" method="post">
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="update_name" class="form-label">Username</label>
                                <input type="text" id="update_name" name="update_name" value="<?php echo htmlspecialchars($fetch_user['name']); ?>" required class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="update_email" class="form-label">Email Address</label>
                                <input type="email" id="update_email" name="update_email" value="<?php echo htmlspecialchars($fetch_user['email']); ?>" required class="form-control">
                            </div>
                        </div>
                        <h3 class="card-title" style="margin-top: 2rem; margin-bottom: 1rem; font-size: 1.1rem; border-top: 1px solid var(--border); padding-top: 1.5rem;">Change Password (Optional)</h3>
                        <div class="form-grid">
                            <div class="form-group">
                                <label for="old_pass" class="form-label">Old Password</label>
                                <input type="password" id="old_pass" name="old_pass" placeholder="Enter current password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="new_pass" class="form-label">New Password</label>
                                <input type="password" id="new_pass" name="new_pass" placeholder="Enter new password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirm_pass" class="form-label">Confirm New Password</label>
                                <input type="password" id="confirm_pass" name="confirm_pass" placeholder="Confirm new password" class="form-control">
                            </div>
                        </div>
                        <div class="form-actions">
                             <input type="submit" value="Save Changes" name="update_profile" class="btn btn-primary">
                        </div>
                    </form>
                </div>
            </div>
        </section>

        <!-- Order History Section -->
        <section id="order-history" class="profile-section">
            <div class="card">
                <div class="card-header">
                    <h2 class="card-title">Your Order History</h2>
                </div>
                <div class="card-body" style="padding:0;">
                    <div class="table-responsive">
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>Placed On</th>
                                    <th>Products</th>
                                    <th>Total Price</th>
                                    <th>Payment Status</th>
                                </tr>
                            </thead>
                            <tbody>
                            <?php if ($total_orders > 0): ?>
                                <?php mysqli_data_seek($orders_query, 0); // Reset pointer to loop again ?>
                                <?php while($fetch_orders = mysqli_fetch_assoc($orders_query)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($fetch_orders['placed_on']); ?></td>
                                    <td><?php echo htmlspecialchars($fetch_orders['total_products']); ?></td>
                                    <td>$<?php echo number_format($fetch_orders['total_price'], 2); ?></td>
                                    <td>
                                        <span class="badge-pill <?php echo htmlspecialchars($fetch_orders['payment_status']); ?>">
                                            <?php echo htmlspecialchars($fetch_orders['payment_status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">
                                        <!-- FIXED: Empty state now matches CSS for better styling -->
                                        <div class="empty-state">
                                            <i class="fas fa-box-open"></i>
                                            <p>You haven't placed any orders yet.</p>
                                            <a href="shop.php" class="btn btn-primary">Start Shopping</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>

    </main>
</div>

<?php @include 'includes/footer.php'; ?>

<!-- Custom JS for active link scrolling -->
<script>
document.addEventListener('DOMContentLoaded', () => {
    const sections = document.querySelectorAll('.profile-section');
    const navLinks = document.querySelectorAll('.profile-nav a.nav-link');
    
    // Smooth scroll for nav links and update URL hash
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const targetId = this.getAttribute('href');
            if (targetId.startsWith('#')) {
                e.preventDefault();
                document.querySelector(targetId).scrollIntoView({
                    behavior: 'smooth'
                });
                // Update URL without reloading page for better navigation
                history.pushState(null, null, targetId);
            }
        });
    });

    // Observer to update active link based on scroll position
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').substring(1) === entry.target.id) {
                        link.classList.add('active');
                    }
                });
            }
        });
    }, { rootMargin: '-40% 0px -60% 0px' }); // Adjust margins to activate when section is in middle of viewport

    sections.forEach(section => {
        observer.observe(section);
    });
});
</script>

</body>
</html>