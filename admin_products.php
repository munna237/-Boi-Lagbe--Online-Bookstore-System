<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:login.php');
}

if(isset($_POST['add_product'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $price = $_POST['price'];
  $image = $_FILES['image']['name'];
  $image_size = $_FILES['image']['size'];
  $image_tmp_name = $_FILES['image']['tmp_name'];
  $image_folder = "uploaded_img/".$image;

  $select_product_name = mysqli_query($conn, "SELECT name FROM `products` WHERE name='$name'") or die('query failed');

  if(mysqli_num_rows($select_product_name) > 0){
    $message[] = 'Product already exists';
  } else {
    $add_product_query = mysqli_query($conn, "INSERT INTO `products`(name, price, image) VALUES('$name', '$price', '$image')") or die('query failed');
    
    if($add_product_query){
      if($image_size > 2000000){
        $message[] = 'Image size is too large';
      } else {
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'Product added successfully!';
      }
    } else {
      $message[] = 'Product could not be added!';
    }
  }
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  $delete_image_query = mysqli_query($conn, "SELECT image FROM `products` WHERE id = '$delete_id'") or die('query failed');
  $fetch_delete_image = mysqli_fetch_assoc($delete_image_query);
  unlink('uploaded_img/'.$fetch_delete_image['image']);
  mysqli_query($conn, "DELETE FROM `products` WHERE id = '$delete_id'") or die('query failed');
  header('location:admin_products.php');
}

if(isset($_POST['update_product'])){
  $update_p_id = $_POST['update_p_id'];
  $update_name = $_POST['update_name'];
  $update_price = $_POST['update_price'];

  mysqli_query($conn, "UPDATE `products` SET name = '$update_name', price = '$update_price' WHERE id = '$update_p_id'") or die('query failed');

  $update_image = $_FILES['update_image']['name'];
  $update_image_tmp_name = $_FILES['update_image']['tmp_name'];
  $update_image_size = $_FILES['update_image']['size'];
  $update_folder = 'uploaded_img/'.$update_image;
  $update_old_image = $_POST['update_old_image'];

  if(!empty($update_image)){
    if($update_image_size > 2000000){
      $message[] = 'Image file size is too large';
    } else {
      mysqli_query($conn, "UPDATE `products` SET image = '$update_image' WHERE id = '$update_p_id'") or die('query failed');
      move_uploaded_file($update_image_tmp_name, $update_folder);
      unlink('uploaded_img/'.$update_old_image);
    }
  }

  header('location:admin_products.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products | Admin Dashboard</title>
  
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
        <h1 class="page-title">Products</h1>
      </div>
      <div class="header-right">
        <a href="admin_add_product.php" class="btn btn-primary">
          <i class="fas fa-plus"></i> Add Product
        </a>
      </div>
    </header>

    <!-- Products Content -->
    <div class="content-wrapper">
      <div class="products-grid">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products`") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
          while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="product-card">
          <div class="product-card-header">
            <div class="product-image">
              <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="<?php echo $fetch_products['name']; ?>">
            </div>
          </div>
          
          <div class="product-card-body">
            <div class="product-info-group">
              <label class="info-label">Product Name</label>
              <div class="info-value product-name"><?php echo $fetch_products['name']; ?></div>
            </div>
            
            <div class="product-info-group">
              <label class="info-label">Price</label>
              <div class="info-value product-price">TK. <?php echo $fetch_products['price']; ?>/-</div>
            </div>
          </div>
          
          <div class="product-card-footer">
            <div class="action-buttons">
              <a href="admin_update_product.php?update=<?php echo $fetch_products['id']; ?>" 
                 class="btn btn-primary btn-sm">
                <i class="fas fa-edit"></i> Edit
              </a>
              <a href="admin_products.php?delete=<?php echo $fetch_products['id']; ?>"
                 class="btn btn-danger btn-sm"
                 onclick="return confirm('Delete this product?');">
                <i class="fas fa-trash"></i> Delete
              </a>
            </div>
          </div>
        </div>
        <?php
          }
        } else {
          echo '<div class="empty-state">
                  <i class="fas fa-box"></i>
                  <h3>No Products Found</h3>
                  <p>There are no products available in the system.</p>
                </div>';
        }
        ?>
      </div>
    </div>
  </main>
</div>

<style>
/* Products Grid Layout */
.products-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1.5rem;
    padding: 1rem;
    max-width: 1400px;
    margin: 0 auto;
}

/* Product Card Styles */
.product-card {
    background: #ffffff;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s ease;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.product-card-header {
    padding: 1rem;
    background: #f8f9fa;
    border-bottom: 1px solid #e9ecef;
}

.product-image {
    width: 100%;
    height: 250px;
    overflow: hidden;
    border-radius: 4px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f9fa;
    padding: 1rem;
}

.product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.product-image:hover img {
    transform: scale(1.05);
}

.product-card-body {
    padding: 1.5rem;
    flex-grow: 1;
}

.product-info-group {
    margin-bottom: 1rem;
}

.product-info-group:last-child {
    margin-bottom: 0;
}

.info-label {
    font-size: 0.875rem;
    color: #6c757d;
    margin-bottom: 0.25rem;
    display: block;
}

.info-value {
    font-size: 1rem;
    color: #212529;
    font-weight: 500;
}

.product-name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #212529;
    margin-bottom: 0.5rem;
}

.product-price {
    color: #4361ee;
    font-weight: 600;
}

.product-card-footer {
    padding: 1rem 1.5rem;
    border-top: 1px solid #e9ecef;
    background: #f8f9fa;
    margin-top: auto;
}

.action-buttons {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

/* Button Styles */
.btn-sm {
    padding: 0.5rem 1rem;
    font-size: 0.875rem;
    border-radius: 4px;
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    transition: all 0.2s ease;
    text-decoration: none;
}

.btn-primary {
    background-color: #4361ee;
    color: white;
    border: none;
}

.btn-primary:hover {
    background-color: #3651d4;
}

.btn-danger {
    background-color: #dc3545;
    color: white;
    border: none;
}

.btn-danger:hover {
    background-color: #c82333;
}

/* Empty State Styles */
.empty-state {
    text-align: center;
    padding: 3rem;
    color: #6c757d;
    grid-column: 1 / -1;
}

.empty-state i {
    font-size: 3rem;
    margin-bottom: 1rem;
    color: #6c757d;
}

.empty-state h3 {
    margin-bottom: 0.5rem;
    font-weight: 600;
}

.empty-state p {
    font-size: 0.875rem;
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .products-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .products-grid {
        grid-template-columns: 1fr;
    }
    
    .product-image {
        height: 200px;
    }
    
    .product-card-body {
        padding: 1rem;
    }
}
</style>

<script src="admin.js"></script>
</body>
</html>