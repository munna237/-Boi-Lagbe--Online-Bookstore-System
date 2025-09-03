<?php
include 'config.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Initialize cart total
$grand_total = 0;

// Handle cart updates for guest users
if(!$user_id) {
    if(isset($_POST['update_cart'])) {
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as &$item) {
                $name = $item['name'];
                $qty = $_POST['cart_quantity'][$name];
                if($qty > 0) {
                    $item['quantity'] = $qty;
                }
            }
            $message[] = 'Cart updated successfully!';
        }
    }

    // Handle remove items for guest users
    if(isset($_GET['remove'])) {
        $remove_item = $_GET['remove'];
        if(isset($_SESSION['cart'])) {
            foreach($_SESSION['cart'] as $key => $item) {
                if($item['name'] === $remove_item) {
                    unset($_SESSION['cart'][$key]);
                    $message[] = 'Item removed from cart!';
                    break;
                }
            }
        }
        header('location:cart.php');
    }

    // Handle delete all for guest users
    if(isset($_GET['delete_all'])) {
        unset($_SESSION['cart']);
        header('location:cart.php');
    }
} else {
    // Handle cart updates for logged-in users
    if(isset($_POST['update_cart'])) {
        foreach($_POST['cart_quantity'] as $name => $qty) {
            if($qty > 0) {
                mysqli_query($conn, "UPDATE `cart` SET quantity = '$qty' WHERE name = '$name' AND user_id = '$user_id'") or die('query failed');
            }
        }
        $message[] = 'Cart updated successfully!';
    }

    // Handle remove items for logged-in users
    if(isset($_GET['remove'])) {
        $remove_id = $_GET['remove'];
        mysqli_query($conn, "DELETE FROM `cart` WHERE name = '$remove_id' AND user_id = '$user_id'") or die('query failed');
        header('location:cart.php');
    }

    // Handle delete all for logged-in users
    if(isset($_GET['delete_all'])) {
        mysqli_query($conn, "DELETE FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        header('location:cart.php');
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Shopping Cart | Boi Lagbe!</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">

  <style>
    .shopping-cart {
      max-width: 1200px;
      margin: 2rem auto;
      padding: 0 1rem;
    }

    .page-title {
      font-size: 1.75rem;
      color: #333;
      margin-bottom: 1.5rem;
      display: flex;
      align-items: center;
      gap: 0.75rem;
    }

    .page-title i {
      color: #4361ee;
    }

    .cart-container {
      display: grid;
      gap: 1.5rem;
      margin-bottom: 2rem;
    }

    .cart-item {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      padding: 1.5rem;
      display: grid;
      grid-template-columns: auto 1fr auto;
      gap: 1.5rem;
      align-items: center;
      position: relative;
    }

    .cart-item:hover {
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.12);
    }

    .cart-item img {
      width: 120px;
      height: 160px;
      object-fit: cover;
      border-radius: 8px;
    }

    .item-details {
      display: flex;
      flex-direction: column;
      gap: 0.5rem;
    }

    .item-name {
      font-size: 1.125rem;
      font-weight: 600;
      color: #333;
    }

    .item-price {
      color: #4361ee;
      font-weight: 600;
      font-size: 1.125rem;
    }

    .item-subtotal {
      color: #666;
      font-size: 0.875rem;
    }

    .item-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .quantity-form {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quantity-input {
      width: 80px;
      padding: 0.5rem;
      border: 1px solid #ddd;
      border-radius: 6px;
      text-align: center;
    }

    .update-btn {
      background: #4361ee;
      color: white;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 6px;
      cursor: pointer;
      font-weight: 500;
      transition: background-color 0.2s;
    }

    .update-btn:hover {
      background: #3651d4;
    }

    .delete-btn {
      position: absolute;
      top: 1rem;
      right: 1rem;
      color: #dc3545;
      background: none;
      border: none;
      font-size: 1.25rem;
      cursor: pointer;
      transition: color 0.2s;
    }

    .delete-btn:hover {
      color: #c82333;
    }

    .cart-summary {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      padding: 1.5rem;
    }

    .total-amount {
      font-size: 1.25rem;
      color: #333;
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding-bottom: 1.5rem;
      margin-bottom: 1.5rem;
      border-bottom: 1px solid #eee;
    }

    .total-amount span {
      color: #4361ee;
      font-weight: 600;
    }

    .cart-actions {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 1rem;
    }

    .cart-btn {
      padding: 0.75rem 1rem;
      border-radius: 6px;
      font-weight: 500;
      text-align: center;
      text-decoration: none;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn-delete-all {
      background: #dc3545;
      color: white;
    }

    .btn-delete-all:hover {
      background: #c82333;
    }

    .btn-continue {
      background: #6c757d;
      color: white;
    }

    .btn-continue:hover {
      background: #5a6268;
    }

    .btn-checkout {
      background: #4361ee;
      color: white;
    }

    .btn-checkout:hover {
      background: #3651d4;
    }

    .btn-disabled {
      opacity: 0.6;
      cursor: not-allowed;
    }

    .empty-cart {
      text-align: center;
      padding: 3rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-cart i {
      font-size: 3rem;
      color: #6c757d;
      margin-bottom: 1rem;
    }

    .empty-cart p {
      color: #6c757d;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
      .cart-item {
        grid-template-columns: 1fr;
        text-align: center;
      }

      .cart-item img {
        margin: 0 auto;
      }

      .item-actions {
        justify-content: center;
      }

      .delete-btn {
        top: 0.5rem;
        right: 0.5rem;
      }

      .cart-actions {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  
<?php include 'user_header.php'; ?>

<section class="shopping-cart">
  <h1 class="page-title">
    <i class="fas fa-shopping-cart"></i>
    Shopping Cart
  </h1>

  <div class="cart-container">
    <?php
    $grand_total = 0;
    
    if($user_id) {
      // For logged in users, fetch from database
      $select_cart = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id='$user_id'") or die('query failed');
      if(mysqli_num_rows($select_cart) > 0){
        while($fetch_cart = mysqli_fetch_assoc($select_cart)){
          $sub_total = ($fetch_cart['quantity'] * $fetch_cart['price']);
          $grand_total += $sub_total;
    ?>
    <div class="cart-item">
      <img src="./uploaded_img/<?php echo $fetch_cart['image']; ?>" alt="<?php echo $fetch_cart['name']; ?>">
      <div class="item-details">
        <h3 class="item-name"><?php echo $fetch_cart['name']; ?></h3>
        <p class="item-price">TK. <?php echo $fetch_cart['price']; ?>/-</p>
        <p class="item-subtotal">Subtotal: TK. <?php echo $sub_total; ?>/-</p>
      </div>
      <div class="item-actions">
        <form action="" method="post" class="quantity-form">
          <input type="hidden" name="cart_id" value="<?php echo $fetch_cart['id']; ?>">
          <input type="number" name="cart_quantity" min="1" value="<?php echo $fetch_cart['quantity']; ?>" class="quantity-input">
          <button type="submit" name="update_cart" class="update-btn">
            <i class="fas fa-sync-alt"></i>
            Update
          </button>
        </form>
      </div>
      <a href="cart.php?remove=<?php echo $fetch_cart['name']; ?>" class="delete-btn" onclick="return confirm('Remove this item from cart?');">
        <i class="fas fa-times"></i>
      </a>
    </div>
    <?php
        }
      }
    } else {
      // For guest users, use session cart
      if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0) {
        foreach($_SESSION['cart'] as $item) {
          $sub_total = ($item['quantity'] * $item['price']);
          $grand_total += $sub_total;
    ?>
    <div class="cart-item">
      <img src="./uploaded_img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>">
      <div class="item-details">
        <h3 class="item-name"><?php echo $item['name']; ?></h3>
        <p class="item-price">TK. <?php echo $item['price']; ?>/-</p>
        <p class="item-subtotal">Subtotal: TK. <?php echo $sub_total; ?>/-</p>
      </div>
      <div class="item-actions">
        <form action="" method="post" class="quantity-form">
          <input type="hidden" name="product_name" value="<?php echo $item['name']; ?>">
          <input type="number" name="cart_quantity" min="1" value="<?php echo $item['quantity']; ?>" class="quantity-input">
          <button type="submit" name="update_cart" class="update-btn">
            <i class="fas fa-sync-alt"></i>
            Update
          </button>
        </form>
      </div>
      <a href="cart.php?remove=<?php echo $item['name']; ?>" class="delete-btn" onclick="return confirm('Remove this item from cart?');">
        <i class="fas fa-times"></i>
      </a>
    </div>
    <?php
        }
      }
    }

    if($grand_total > 0) {
    ?>
    <div class="cart-summary">
      <div class="total-amount">
        <div>Total Cart Amount:</div>
        <span>TK. <?php echo $grand_total; ?>/-</span>
      </div>
      <div class="cart-actions">
        <a href="cart.php?delete_all" class="cart-btn btn-delete-all" onclick="return confirm('Delete all items from cart?');">
          <i class="fas fa-trash"></i>
          Delete All
        </a>
        <a href="shop.php" class="cart-btn btn-continue">
          <i class="fas fa-shopping-basket"></i>
          Continue Shopping
        </a>
        <a href="<?php echo $user_id ? 'checkout.php' : 'login.php'; ?>" class="cart-btn btn-checkout">
          <i class="fas fa-credit-card"></i>
          <?php echo $user_id ? 'Checkout' : 'Login to Checkout'; ?>
        </a>
      </div>
    </div>
    <?php
    } else {
      echo '
      <div class="empty-cart">
        <i class="fas fa-shopping-cart"></i>
        <p>Your cart is empty!</p>
        <a href="shop.php" class="cart-btn btn-continue">Start Shopping</a>
      </div>
      ';
    }
    ?>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>
