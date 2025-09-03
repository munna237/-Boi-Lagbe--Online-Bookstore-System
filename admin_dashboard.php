<?php

include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

// Get total counts with error handling
$total_pendings = 0;
$select_pending = mysqli_query($conn, "SELECT COUNT(*) as total FROM `orders` WHERE payment_status = 'pending'");
if($select_pending && mysqli_num_rows($select_pending) > 0){
    $fetch_pendings = mysqli_fetch_assoc($select_pending);
    $total_pendings = $fetch_pendings['total'];
}

$total_completed = 0;
$select_completed = mysqli_query($conn, "SELECT COUNT(*) as total FROM `orders` WHERE payment_status = 'completed'");
if($select_completed && mysqli_num_rows($select_completed) > 0){
    $fetch_completed = mysqli_fetch_assoc($select_completed);
    $total_completed = $fetch_completed['total'];
}

$total_orders = 0;
$select_orders = mysqli_query($conn, "SELECT COUNT(*) as total FROM `orders`");
if($select_orders && mysqli_num_rows($select_orders) > 0){
    $fetch_orders = mysqli_fetch_assoc($select_orders);
    $total_orders = $fetch_orders['total'];
}

$total_products = 0;
$select_products = mysqli_query($conn, "SELECT COUNT(*) as total FROM `products`");
if($select_products && mysqli_num_rows($select_products) > 0){
    $fetch_products = mysqli_fetch_assoc($select_products);
    $total_products = $fetch_products['total'];
}

$total_users = 0;
$select_users = mysqli_query($conn, "SELECT COUNT(*) as total FROM `register` WHERE user_type = 'user'");
if($select_users && mysqli_num_rows($select_users) > 0){
    $fetch_users = mysqli_fetch_assoc($select_users);
    $total_users = $fetch_users['total'];
}

$total_admins = 0;
$select_admins = mysqli_query($conn, "SELECT COUNT(*) as total FROM `register` WHERE user_type = 'admin'");
if($select_admins && mysqli_num_rows($select_admins) > 0){
    $fetch_admins = mysqli_fetch_assoc($select_admins);
    $total_admins = $fetch_admins['total'];
}

// Get message count
$total_messages = 0;
$select_messages = mysqli_query($conn, "SELECT COUNT(*) as total FROM `message`");
if($select_messages && mysqli_num_rows($select_messages) > 0){
    $fetch_messages = mysqli_fetch_assoc($select_messages);
    $total_messages = $fetch_messages['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Dashboard | Admin Panel</title>

   <link rel="preconnect" href="https://fonts.googleapis.com">
   <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
   <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<div class="dashboard-layout">
   <?php include 'admin_sidebar.php'; ?>

   <!-- Main Content Area -->
   <main class="main-content">
      <!-- Header -->
      <header class="content-header">
         <div class="header-left">
            <h1 class="page-title">Dashboard</h1>
         </div>
      </header>

      <!-- Dashboard Content -->
      <div class="content-wrapper">
         <div class="dashboard-stats">
            <!-- Orders Stats -->
            <div class="stat-card">
               <div class="stat-card-header">
                  <i class="fas fa-shopping-cart stat-icon"></i>
                  <h3>Orders</h3>
               </div>
               <div class="stat-card-body">
                  <div class="stat-value"><?php echo $total_orders; ?></div>
                  <div class="stat-label">Total Orders</div>
               </div>
               <div class="stat-card-footer">
                  <div class="stat-detail">
                     <span class="detail-label">Pending:</span>
                     <span class="detail-value"><?php echo $total_pendings; ?></span>
                  </div>
                  <div class="stat-detail">
                     <span class="detail-label">Completed:</span>
                     <span class="detail-value"><?php echo $total_completed; ?></span>
                  </div>
               </div>
            </div>

            <!-- Products Stats -->
            <div class="stat-card">
               <div class="stat-card-header">
                  <i class="fas fa-box stat-icon"></i>
                  <h3>Products</h3>
               </div>
               <div class="stat-card-body">
                  <div class="stat-value"><?php echo $total_products; ?></div>
                  <div class="stat-label">Total Products</div>
               </div>
            </div>

            <!-- Users Stats -->
            <div class="stat-card">
               <div class="stat-card-header">
                  <i class="fas fa-users stat-icon"></i>
                  <h3>Users</h3>
               </div>
               <div class="stat-card-body">
                  <div class="stat-value"><?php echo $total_users; ?></div>
                  <div class="stat-label">Total Users</div>
               </div>
               <div class="stat-card-footer">
                  <div class="stat-detail">
                     <span class="detail-label">Admins:</span>
                     <span class="detail-value"><?php echo $total_admins; ?></span>
                  </div>
               </div>
            </div>

            <!-- Messages Stats -->
            <div class="stat-card">
               <div class="stat-card-header">
                  <i class="fas fa-envelope stat-icon"></i>
                  <h3>Messages</h3>
               </div>
               <div class="stat-card-body">
                  <div class="stat-value"><?php echo $total_messages; ?></div>
                  <div class="stat-label">Total Messages</div>
               </div>
            </div>
         </div>
      </div>
   </main>
</div>

<style>
/* Dashboard Stats Grid */
.dashboard-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    padding: 1rem;
}

/* Stat Card Styles */
.stat-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease;
}

.stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.stat-card-header {
    padding: 1.5rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
    display: flex;
    align-items: center;
    gap: 1rem;
}

.stat-icon {
    font-size: 1.5rem;
    color: #4361ee;
}

.stat-card-header h3 {
    margin: 0;
    font-size: 1.25rem;
    color: #212529;
}

.stat-card-body {
    padding: 1.5rem;
    text-align: center;
}

.stat-value {
    font-size: 2.5rem;
    font-weight: 600;
    color: #4361ee;
    line-height: 1;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6c757d;
    font-size: 0.875rem;
}

.stat-card-footer {
    padding: 1rem 1.5rem;
    background: #f8f9fa;
    border-top: 1px solid #e9ecef;
}

.stat-detail {
    display: flex;
    justify-content: space-between;
    color: #495057;
    font-size: 0.875rem;
    margin-bottom: 0.5rem;
}

.stat-detail:last-child {
    margin-bottom: 0;
}

.detail-label {
    color: #6c757d;
}

.detail-value {
    font-weight: 500;
    color: #212529;
}

/* Responsive Adjustments */
@media (max-width: 768px) {
    .dashboard-stats {
        grid-template-columns: 1fr;
    }
    
    .stat-card-body {
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 2rem;
    }
}
</style>

</body>
</html> 
