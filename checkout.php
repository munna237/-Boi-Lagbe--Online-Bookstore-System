<?php
include 'config.php';
session_start();

// If user is not logged in, store current page in session and redirect to login
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = 'checkout.php';
    header('location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];

// Transfer any items from session cart to database cart if they exist
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $name = $item['name'];
        $price = $item['price'];
        $quantity = $item['quantity'];
        $image = $item['image'];
        
        // Check if item already exists in user's cart
        $check_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$name' AND user_id = '$user_id'");
        
        if (mysqli_num_rows($check_cart) > 0) {
            // Update quantity if item exists
            $cart_item = mysqli_fetch_assoc($check_cart);
            $new_quantity = $cart_item['quantity'] + $quantity;
            mysqli_query($conn, "UPDATE `cart` SET quantity = '$new_quantity' WHERE name = '$name' AND user_id = '$user_id'");
        } else {
            // Insert new item if it doesn't exist
            mysqli_query($conn, "INSERT INTO `cart` (user_id, name, price, quantity, image) VALUES ('$user_id', '$name', '$price', '$quantity', '$image')");
        }
    }
    
    // Clear session cart after transfer
    unset($_SESSION['cart']);
}

if(isset($_POST['order_btn'])){
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $number = $_POST['number'];
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $method = mysqli_real_escape_string($conn, $_POST['method']);
  $address = mysqli_real_escape_string($conn, $_POST['address']);
  $placed_on = date('d-M-Y');

  $cart_total = 0;
  $cart_products[] = '';

  $cart_query = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
  if(mysqli_num_rows($cart_query) > 0){
    while($cart_item = mysqli_fetch_assoc($cart_query)){
      $cart_products[] = $cart_item['name'].' ('.$cart_item['quantity'].') ';
      $sub_total = ($cart_item['price'] * $cart_item['quantity']);
      $cart_total += $sub_total;
    }
  }

  $total_products = implode(' ',$cart_products);

  $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE name = '$name' AND number = '$number' AND email = '$email' AND method = '$method' AND address = '$address' AND total_products = '$total_products' AND total_price = '$cart_total'") or die('query failed');

  if($cart_total == 0){
    $message[] = 'your cart is empty';
  }else{
    if(mysqli_num_rows($order_query) > 0){
      $message[] = 'order already placed!'; 
    }else{
      mysqli_query($conn, "INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price, placed_on) VALUES('$user_id', '$name', '$number', '$email', '$method', '$address', '$total_products', '$cart_total', '$placed_on')") or die('query failed');
      $message[] = 'order placed successfully!';
      mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Checkout | BookExpress</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">

  <style>
    .checkout-container {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 2rem;
    }

    @media (max-width: 768px) {
      .checkout-container {
        grid-template-columns: 1fr;
      }
    }

    .order-summary, .checkout-form {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      padding: 2rem;
    }

    .section-title {
      font-size: 1.5rem;
      color: #333;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .section-title i {
      color: #4361ee;
    }

    .order-product {
      display: flex;
      align-items: center;
      gap: 1rem;
      padding: 1rem;
      border: 1px solid #eee;
      border-radius: 8px;
      margin-bottom: 1rem;
    }

    .order-product img {
      width: 80px;
      height: 100px;
      object-fit: cover;
      border-radius: 4px;
    }

    .product-details h3 {
      font-size: 1rem;
      color: #333;
      margin-bottom: 0.25rem;
    }

    .product-details p {
      color: #666;
      font-size: 0.9rem;
    }

    .grand-total {
      margin-top: 1.5rem;
      padding-top: 1.5rem;
      border-top: 2px solid #eee;
      font-size: 1.25rem;
      color: #333;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .grand-total span {
      color: #4361ee;
      font-weight: 600;
    }

    .checkout-form form {
      display: flex;
      flex-direction: column;
      gap: 1.25rem;
    }

    .form-group {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .form-group label {
      font-size: 0.9rem;
      color: #555;
      font-weight: 500;
    }

    .form-control {
      width: 100%;
      padding: 0.75rem 1rem;
      border: 1px solid #ddd;
      border-radius: 6px;
      font-size: 1rem;
      transition: border-color 0.2s;
    }

    .form-control:focus {
      outline: none;
      border-color: #4361ee;
    }

    select.form-control {
      background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23666' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
      background-repeat: no-repeat;
      background-position: right 1rem center;
      background-size: 1em;
      padding-right: 2.5rem;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
    }

    textarea.form-control {
      min-height: 120px;
      resize: vertical;
    }

    .place-order-btn {
      background: #4361ee;
      color: white;
      border: none;
      padding: 1rem;
      border-radius: 6px;
      font-size: 1rem;
      font-weight: 500;
      cursor: pointer;
      transition: background-color 0.2s;
      margin-top: 1rem;
    }

    .place-order-btn:hover {
      background: #3651d4;
    }

    .empty-cart-message {
      text-align: center;
      padding: 2rem;
      color: #666;
      font-size: 1.1rem;
    }
  </style>
</head>
<body>
  
<?php include 'user_header.php'; ?>

<div class="checkout-container">
  <!-- Order Summary Section -->
  <div class="order-summary">
    <h2 class="section-title">
      <i class="fas fa-shopping-bag"></i>
      Ordered Products
    </h2>
    
    <?php
    $grand_total = 0;
    $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');

    if(mysqli_num_rows($select_cart) > 0){
      while($fetch_cart = mysqli_fetch_assoc($select_cart)){
        $total_price = ($fetch_cart['price'] * $fetch_cart['quantity']);
        $grand_total += $total_price;
    ?>
    <div class="order-product">
      <img src="./uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
      <div class="product-details">
        <h3><?php echo $fetch_cart['name']; ?></h3>
        <p>Price: TK. <?php echo $fetch_cart['price']; ?>/-</p>
        <p>Quantity: <?php echo $fetch_cart['quantity']; ?></p>
        <p>Subtotal: TK. <?php echo $total_price; ?>/-</p>
      </div>
    </div>
    <?php
      }
    } else {
      echo '<p class="empty-cart-message">Your cart is empty!</p>';
    }
    ?>

    <div class="grand-total">
      <div>Total Amount:</div>
      <span>TK. <?php echo $grand_total; ?>/-</span>
    </div>
  </div>

  <!-- Checkout Form Section -->
  <div class="checkout-form">
    <h2 class="section-title">
      <i class="fas fa-user-circle"></i>
      Add Your Details
    </h2>

    <form action="" method="post">
      <div class="form-group">
        <label for="name">Full Name</label>
        <input type="text" id="name" name="name" class="form-control" required placeholder="Enter your full name">
      </div>

      <div class="form-group">
        <label for="number">Phone Number</label>
        <input type="tel" id="number" name="number" class="form-control" required placeholder="Enter your phone number">
      </div>

      <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email" class="form-control" required placeholder="Enter your email address">
      </div>

      <div class="form-group">
        <label for="method">Payment Method</label>
        <select name="method" id="method" class="form-control" required>
          <option value="cash on delivery">Cash on Delivery</option>
          <option value="BKash">Bkash</option>
        </select>
      </div>

      <div class="form-group">
        <label for="address">Delivery Address</label>
        <textarea name="address" id="address" class="form-control" required placeholder="Enter your complete delivery address"></textarea>
      </div>

      <button type="submit" name="order_btn" class="place-order-btn">
        <i class="fas fa-shopping-cart"></i> Place Your Order
      </button>
    </form>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>