<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Add Product | Admin Dashboard</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<div class="dashboard-layout">
   <!-- Copy the sidebar from admin_products.php -->
   
   <main class="main-content">
      <header class="content-header">
         <div class="header-left">
            <h1 class="page-title">Add New Product</h1>
            <nav class="breadcrumb">
               <a href="admin_dashboard.php">Dashboard</a> / 
               <a href="admin_products.php">Products</a> / 
               Add Product
            </nav>
         </div>
      </header>

      <div class="content-wrapper">
         <div class="form-container">
            <form action="admin_products.php" method="post" enctype="multipart/form-data">
               <div class="form-group">
                  <label for="name">Product Name</label>
                  <input type="text" name="name" class="form-control" required placeholder="Enter product name">
               </div>
               
               <div class="form-group">
                  <label for="price">Price</label>
                  <input type="number" min="0" name="price" class="form-control" required placeholder="Enter product price">
               </div>
               
               <div class="form-group">
                  <label for="image">Product Image</label>
                  <input type="file" name="image" accept="image/jpg, image/jpeg, image/png" class="form-control" required>
               </div>
               
               <div class="form-actions">
                  <button type="submit" name="add_product" class="btn btn-primary">
                     <i class="fas fa-plus"></i> Add Product
                  </button>
                  <a href="admin_products.php" class="btn btn-secondary">Cancel</a>
               </div>
            </form>
         </div>
      </div>
   </main>
</div>

</body>
</html> 
