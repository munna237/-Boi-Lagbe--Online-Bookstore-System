<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

$update_id = $_GET['update'];
$select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE id = '$update_id'") or die('query failed');
if(mysqli_num_rows($select_products) > 0){
   $fetch_products = mysqli_fetch_assoc($select_products);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Update Product | Admin Dashboard</title>
   
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
   <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<div class="dashboard-layout">
   <!-- Copy the sidebar from admin_products.php -->
   
   <main class="main-content">
      <header class="content-header">
         <div class="header-left">
            <h1 class="page-title">Update Product</h1>
            <nav class="breadcrumb">
               <a href="admin_dashboard.php">Dashboard</a> / 
               <a href="admin_products.php">Products</a> / 
               Update Product
            </nav>
         </div>
      </header>

      <div class="content-wrapper">
         <div class="form-container">
            <form action="admin_products.php" method="post" enctype="multipart/form-data">
               <input type="hidden" name="update_p_id" value="<?php echo $fetch_products['id']; ?>">
               <input type="hidden" name="update_old_image" value="<?php echo $fetch_products['image']; ?>">
               
               <div class="form-group">
                  <label for="update_name">Product Name</label>
                  <input type="text" name="update_name" value="<?php echo $fetch_products['name']; ?>" class="form-control" required placeholder="Enter product name">
               </div>
               
               <div class="form-group">
                  <label for="update_price">Price</label>
                  <input type="number" name="update_price" value="<?php echo $fetch_products['price']; ?>" min="0" class="form-control" required placeholder="Enter product price">
               </div>
               
               <div class="form-group">
                  <label for="update_image">Update Image</label>
                  <input type="file" class="form-control" name="update_image" accept="image/jpg, image/jpeg, image/png">
                  <div class="current-image">
                     <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="Current Image" style="max-width: 200px; margin-top: 10px;">
                  </div>
               </div>
               
               <div class="form-actions">
                  <button type="submit" name="update_product" class="btn btn-primary">
                     <i class="fas fa-save"></i> Update Product
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