<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:login.php');
}

if(isset($_POST['update_order'])){
  $order_update_id = $_POST['order_id'];
  $update_payment = $_POST['update_payment'];
  
  mysqli_query($conn, "UPDATE `orders` SET payment_status = '$update_payment' WHERE id = '$order_update_id'") or die('query failed');
  $message[] = 'Payment status has been updated!';
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `orders` WHERE id = '$delete_id'") or die('query failed');
  header('location:admin_orders.php');
}

if(isset($_POST['toggle_status'])) {
    $order_id = $_POST['order_id'];
    $new_status = $_POST['new_status'];
    mysqli_query($conn, "UPDATE `orders` SET payment_status = '$new_status' WHERE id = '$order_id'") or die('query failed');
    // Return JSON response for AJAX
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'new_status' => $new_status]);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Orders | Admin Dashboard</title>
  
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link rel="stylesheet" href="admin_styles.css">
  <style>
    /* Add these new styles */
    .status-toggle {
      padding: 8px 16px;
      border-radius: 20px;
      border: none;
      cursor: pointer;
      font-weight: 500;
      transition: all 0.3s ease;
    }

    .status-pending {
      background-color: #ffd700;
      color: #000;
    }

    .status-completed {
      background-color: #4CAF50;
      color: white;
    }

    /* Remove filter buttons except All Orders */
    .filter-buttons {
      margin-bottom: 20px;
    }

    .filter-btn {
      padding: 8px 16px;
      border-radius: 4px;
      border: 1px solid #ddd;
      background: white;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .filter-btn.active {
      background: #4361ee;
      color: white;
      border-color: #4361ee;
    }
  </style>
</head>
<body>

<div class="dashboard-layout">
   <?php include 'admin_sidebar.php'; ?>
   
   <!-- Main Content Area -->
  <main class="main-content">
    <!-- Header -->
    <header class="content-header">
      <div class="header-left">
        <h1 class="page-title">Orders</h1>
      </div>
      <div class="header-right">
        <div class="search-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" id="orderSearch" class="search-input" placeholder="Search orders..." onkeyup="searchOrders()">
        </div>
      </div>
    </header>

    <!-- Orders Content -->
    <div class="content-wrapper">
      <!-- Modified filter buttons - only All Orders -->
      <div class="filter-buttons">
        <button class="filter-btn active">All Orders</button>
      </div>

      <!-- Orders Table -->
      <div class="table-container">
        <table class="orders-table">
          <thead>
            <tr>
              <th>Order ID</th>
              <th>Customer</th>
              <th>Products</th>
              <th>Total</th>
              <th>Date</th>
              <th>Status</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="ordersTableBody">
            <?php
            $select_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY placed_on DESC") or die('query failed');
            if(mysqli_num_rows($select_orders) > 0){
              while($fetch_orders = mysqli_fetch_assoc($select_orders)){
            ?>
            <tr>
              <td>#<?php echo $fetch_orders['id']; ?></td>
              <td>
                <div class="customer-info">
                  <span class="customer-name"><?php echo $fetch_orders['name']; ?></span>
                  <span class="customer-email"><?php echo $fetch_orders['email']; ?></span>
                </div>
              </td>
              <td>
                <div class="order-products">
                  <?php echo $fetch_orders['total_products']; ?>
                </div>
              </td>
              <td>
                <div class="order-total">
                  TK. <?php echo $fetch_orders['total_price']; ?>/-
                </div>
              </td>
              <td>
                <div class="order-date">
                  <?php echo $fetch_orders['placed_on']; ?>
                </div>
              </td>
              <td>
                <button 
                  class="status-toggle <?php echo $fetch_orders['payment_status'] === 'completed' ? 'status-completed' : 'status-pending'; ?>"
                  data-order-id="<?php echo $fetch_orders['id']; ?>"
                  data-current-status="<?php echo $fetch_orders['payment_status']; ?>"
                >
                  <?php echo ucfirst($fetch_orders['payment_status']); ?>
                </button>
              </td>
              <td>
                <div class="action-buttons">
                  <a href="admin_orders.php?delete=<?php echo $fetch_orders['id']; ?>" 
                     class="btn btn-danger btn-sm"
                     onclick="return confirm('Delete this order?');">
                    <i class="fas fa-trash"></i>
                  </a>
                </div>
              </td>
            </tr>
            <?php
              }
            } else {
              echo '<tr><td colspan="7" class="empty-table">No orders found</td></tr>';
            }
            ?>
          </tbody>
        </table>
      </div>
    </div>
  </main>
</div>

<script src="admin.js"></script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Handle status toggle
    document.querySelectorAll('.status-toggle').forEach(button => {
      button.addEventListener('click', function() {
        const orderId = this.dataset.orderId;
        const currentStatus = this.dataset.currentStatus;
        const newStatus = currentStatus === 'completed' ? 'pending' : 'completed';

        // Send AJAX request to update status
        fetch('admin_orders.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: `toggle_status=1&order_id=${orderId}&new_status=${newStatus}`
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Update button appearance
            this.textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
            this.classList.toggle('status-completed');
            this.classList.toggle('status-pending');
            this.dataset.currentStatus = newStatus;
          }
        })
        .catch(error => console.error('Error:', error));
      });
    });
  });

function searchOrders() {
    const searchInput = document.getElementById('orderSearch');
    const filter = searchInput.value.toLowerCase();
    const tbody = document.getElementById('ordersTableBody');
    const rows = tbody.getElementsByTagName('tr');

    for (let row of rows) {
        const orderIdCell = row.cells[0].textContent;
        const customerCell = row.cells[1].textContent;
        const productsCell = row.cells[2].textContent;
        const totalCell = row.cells[3].textContent;
        const dateCell = row.cells[4].textContent;
        const statusCell = row.cells[5].textContent;

        const matchesSearch = 
            orderIdCell.toLowerCase().includes(filter) ||
            customerCell.toLowerCase().includes(filter) ||
            productsCell.toLowerCase().includes(filter) ||
            totalCell.toLowerCase().includes(filter) ||
            dateCell.toLowerCase().includes(filter) ||
            statusCell.toLowerCase().includes(filter);

        row.style.display = matchesSearch ? '' : 'none';
    }
}
</script>
</body>
</html>