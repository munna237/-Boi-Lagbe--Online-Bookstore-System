<?php
include 'config.php';
session_start();

$user_id=$_SESSION['user_id'];

if(!isset($user_id)){
  header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Orders | Boi Lagbe!</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">

  <style>
    .orders-container {
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

    .orders-grid {
      display: grid;
      gap: 1.5rem;
    }

    .order-card {
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
      overflow: hidden;
    }

    .order-header {
      background: #f8f9fa;
      padding: 1.25rem;
      border-bottom: 1px solid #e9ecef;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .order-date {
      display: flex;
      align-items: center;
      gap: 0.5rem;
      color: #6c757d;
      font-size: 0.9rem;
    }

    .order-status {
      padding: 0.5rem 1rem;
      border-radius: 999px;
      font-size: 0.875rem;
      font-weight: 600;
      text-transform: capitalize;
    }

    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }

    .status-completed {
      background-color: #d1e7dd;
      color: #0f5132;
    }

    .order-body {
      padding: 1.5rem;
    }

    .order-section {
      margin-bottom: 1.5rem;
    }

    .order-section:last-child {
      margin-bottom: 0;
    }

    .section-title {
      font-size: 1rem;
      font-weight: 600;
      color: #333;
      margin-bottom: 1rem;
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .info-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 1rem;
    }

    .info-item {
      display: flex;
      flex-direction: column;
      gap: 0.25rem;
    }

    .info-label {
      font-size: 0.875rem;
      color: #6c757d;
    }

    .info-value {
      color: #333;
      font-weight: 500;
    }

    .address-box {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      color: #333;
      line-height: 1.5;
    }

    .products-list {
      background: #f8f9fa;
      padding: 1rem;
      border-radius: 8px;
      color: #333;
      line-height: 1.6;
    }

    .order-total {
      margin-top: 1rem;
      padding-top: 1rem;
      border-top: 1px solid #e9ecef;
      display: flex;
      justify-content: flex-end;
      align-items: center;
      gap: 0.5rem;
      font-weight: 600;
      color: #333;
    }

    .order-total span {
      color: #4361ee;
    }

    .empty-orders {
      text-align: center;
      padding: 3rem;
      background: #fff;
      border-radius: 12px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .empty-orders i {
      font-size: 3rem;
      color: #6c757d;
      margin-bottom: 1rem;
    }

    .empty-orders p {
      color: #6c757d;
      font-size: 1.1rem;
      margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
      .orders-container {
        margin: 1rem auto;
      }

      .page-title {
        font-size: 1.5rem;
        margin-bottom: 1rem;
      }

      .order-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 0.75rem;
      }

      .order-status {
        align-self: flex-start;
      }

      .info-grid {
        grid-template-columns: 1fr;
      }
    }
  </style>
</head>
<body>
  
<?php include 'user_header.php'; ?>

<div class="orders-container">
  <h1 class="page-title">
    <i class="fas fa-box"></i>
    My Orders
  </h1>

  <div class="orders-grid">
    <?php
    $order_query = mysqli_query($conn, "SELECT * FROM `orders` WHERE user_id='$user_id'") or die('query failed');
    if(mysqli_num_rows($order_query) > 0){
      while($fetch_orders = mysqli_fetch_assoc($order_query)){
    ?>
    <div class="order-card">
      <div class="order-header">
        <div class="order-date">
          <i class="far fa-calendar-alt"></i>
          Placed on: <?php echo $fetch_orders['placed_on']; ?>
        </div>
        <div class="order-status <?php echo $fetch_orders['payment_status'] == 'completed' ? 'status-completed' : 'status-pending'; ?>">
          <?php echo $fetch_orders['payment_status']; ?>
        </div>
      </div>

      <div class="order-body">
        <div class="order-section">
          <h2 class="section-title">
            <i class="fas fa-user"></i>
            Customer Information
          </h2>
          <div class="info-grid">
            <div class="info-item">
              <div class="info-label">Name</div>
              <div class="info-value"><?php echo $fetch_orders['name']; ?></div>
            </div>
            <div class="info-item">
              <div class="info-label">Email</div>
              <div class="info-value"><?php echo $fetch_orders['email']; ?></div>
            </div>
            <div class="info-item">
              <div class="info-label">Phone</div>
              <div class="info-value"><?php echo $fetch_orders['number']; ?></div>
            </div>
            <div class="info-item">
              <div class="info-label">Payment Method</div>
              <div class="info-value"><?php echo $fetch_orders['method']; ?></div>
            </div>
          </div>
        </div>

        <div class="order-section">
          <h2 class="section-title">
            <i class="fas fa-map-marker-alt"></i>
            Shipping Address
          </h2>
          <div class="address-box">
            <?php echo $fetch_orders['address']; ?>
          </div>
        </div>

        <div class="order-section">
          <h2 class="section-title">
            <i class="fas fa-shopping-bag"></i>
            Order Summary
          </h2>
          <div class="products-list">
            <?php echo $fetch_orders['total_products']; ?>
          </div>
          <div class="order-total">
            Total Amount: <span>TK. <?php echo $fetch_orders['total_price']; ?>/-</span>
          </div>
        </div>
      </div>
    </div>
    <?php
      }
    } else {
      echo '
      <div class="empty-orders">
        <i class="fas fa-box-open"></i>
        <p>No orders placed yet!</p>
        <a href="shop.php" class="btn btn-primary">Start Shopping</a>
      </div>
      ';
    }
    ?>
  </div>
</div>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>
