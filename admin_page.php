<?php
session_start();
include 'config.php';

$admin_id = $_SESSION['admin_id'] ?? null;

if(!isset($admin_id)){
  header('location:login.php');
   exit();
}

// Update database structure for enhanced functionality
// Check if read_status column exists in message table
$check_column = mysqli_query($conn, "SHOW COLUMNS FROM `message` LIKE 'read_status'");
if(mysqli_num_rows($check_column) == 0) {
  // Add read_status column if it doesn't exist
  mysqli_query($conn, "ALTER TABLE `message` ADD `read_status` TINYINT(1) NOT NULL DEFAULT '0'");
}

// Get stats for dashboard
$pending_amount = 0;
$completed_amount = 0;
$order_count = 0;
$product_count = 0;
$user_count = 0;
$admin_count = 0;
$message_count = 0;

// Get pending orders amount
$select_pending = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM `orders` WHERE payment_status = 'pending'");
if($row = mysqli_fetch_assoc($select_pending)) {
  $pending_amount = $row['total'] ?? 0;
}

// Get completed orders amount
$select_completed = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM `orders` WHERE payment_status = 'completed'");
if($row = mysqli_fetch_assoc($select_completed)) {
  $completed_amount = $row['total'] ?? 0;
}

// Get order count
$select_orders = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `orders`");
if($row = mysqli_fetch_assoc($select_orders)) {
  $order_count = $row['count'];
}

// Get product count
$select_products = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `products`");
if($row = mysqli_fetch_assoc($select_products)) {
  $product_count = $row['count'];
}

// Get user count
$select_users = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `register` WHERE user_type = 'user'");
if($row = mysqli_fetch_assoc($select_users)) {
  $user_count = $row['count'];
}

// Get admin count
$select_admins = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `register` WHERE user_type = 'admin'");
if($row = mysqli_fetch_assoc($select_admins)) {
  $admin_count = $row['count'];
}

// Get message count
$select_messages = mysqli_query($conn, "SELECT COUNT(*) AS count FROM `message`");
if($row = mysqli_fetch_assoc($select_messages)) {
  $message_count = $row['count'];
}

// Get recent orders
$recent_orders = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY id DESC LIMIT 5");

// Get monthly sales data for chart
$monthly_sales = [];
$sales_query = mysqli_query($conn, "SELECT 
    MONTH(STR_TO_DATE(placed_on, '%d-%b-%Y')) AS month,
    SUM(total_price) AS amount
    FROM `orders` 
    WHERE payment_status = 'completed'
    AND placed_on >= DATE_FORMAT(DATE_SUB(NOW(), INTERVAL 6 MONTH), '%d-%b-%Y')
    GROUP BY month
    ORDER BY month ASC");

while($row = mysqli_fetch_assoc($sales_query)) {
    $month_name = date("M", mktime(0, 0, 0, $row['month'], 10));
    $monthly_sales[$month_name] = $row['amount'];
}

// Fill in missing months with zero
$months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
$current_month = date('n');
$chart_months = [];
$chart_data = [];

for($i = 5; $i >= 0; $i--) {
    $month_idx = ($current_month - $i - 1 + 12) % 12;
    $month = $months[$month_idx];
    $chart_months[] = $month;
    $chart_data[] = $monthly_sales[$month] ?? 0;
}

// Format chart data for JavaScript
$chart_months_json = json_encode($chart_months);
$chart_data_json = json_encode($chart_data);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Admin Dashboard | BookExpress</title>
  
  <!-- Google Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  
  <!-- Custom CSS -->
  <link rel="stylesheet" href="admin_styles.css">
</head>
<body>

<?php include 'admin_header.php'; ?>

<!-- Stats Grid -->
<div class="content-wrapper">
  <div class="grid grid-cols-4 gap-4">
    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between mb-4">
          <h3 class="card-title">Pending Orders</h3>
          <div class="text-primary">
            <i class="fas fa-shopping-cart fa-2x"></i>
          </div>
        </div>
        <div class="text-2xl font-bold mb-2">TK. <?php echo number_format($pending_amount); ?></div>
        <p class="text-secondary">Total payments pending</p>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between mb-4">
          <h3 class="card-title">Completed Orders</h3>
          <div class="text-success">
            <i class="fas fa-check-circle fa-2x"></i>
          </div>
  </div>
        <div class="text-2xl font-bold mb-2">TK. <?php echo number_format($completed_amount); ?></div>
        <p class="text-secondary">Total payments completed</p>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between mb-4">
          <h3 class="card-title">Total Users</h3>
          <div class="text-warning">
            <i class="fas fa-users fa-2x"></i>
          </div>
  </div>
        <div class="text-2xl font-bold mb-2"><?php echo $user_count; ?></div>
        <p class="text-secondary">Registered customers</p>
      </div>
    </div>
    
    <div class="card">
      <div class="card-body">
        <div class="flex items-center justify-between mb-4">
          <h3 class="card-title">Products</h3>
          <div class="text-danger">
            <i class="fas fa-book fa-2x"></i>
          </div>
  </div>
        <div class="text-2xl font-bold mb-2"><?php echo $product_count; ?></div>
        <p class="text-secondary">Products in inventory</p>
      </div>
  </div>
</div>

<!-- Charts Section -->
  <div class="grid grid-cols-3 gap-4 mt-6">
    <div class="card col-span-2">
    <div class="card-header">
      <h2 class="card-title">Sales Overview</h2>
      <div class="card-actions">
          <select class="form-control">
          <option value="6months">Last 6 Months</option>
          <option value="year">Last Year</option>
          <option value="all">All Time</option>
        </select>
      </div>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
        <canvas id="salesChart"></canvas>
      </div>
    </div>
  </div>
  
  <div class="card">
    <div class="card-header">
      <h2 class="card-title">Category Distribution</h2>
    </div>
    <div class="card-body">
        <div style="height: 300px;">
        <canvas id="categoryChart"></canvas>
      </div>
    </div>
  </div>
</div>

<!-- Recent Orders -->
  <div class="card mt-6">
  <div class="card-header">
    <h2 class="card-title">Recent Orders</h2>
    <div class="card-actions">
        <a href="admin_orders.php" class="btn btn-primary">View All</a>
    </div>
  </div>
  <div class="card-body">
      <div class="table-container">
      <table class="data-table">
        <thead>
          <tr>
            <th>Order ID</th>
            <th>Customer</th>
            <th>Date</th>
            <th>Amount</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php
            if(mysqli_num_rows($recent_orders) > 0){
              while($fetch_orders = mysqli_fetch_assoc($recent_orders)){
          ?>
          <tr>
            <td>#<?php echo $fetch_orders['id']; ?></td>
            <td><?php echo $fetch_orders['name']; ?></td>
            <td><?php echo $fetch_orders['placed_on']; ?></td>
            <td>TK. <?php echo number_format($fetch_orders['total_price']); ?></td>
            <td>
                <span class="badge <?php echo $fetch_orders['payment_status'] == 'pending' ? 'badge-warning' : 'badge-success'; ?>">
                  <?php echo ucfirst($fetch_orders['payment_status']); ?>
                </span>
            </td>
            <td>
                <a href="admin_orders.php?order=<?php echo $fetch_orders['id']; ?>" class="btn btn-sm btn-primary">
                  View Details
              </a>
            </td>
          </tr>
          <?php
              }
            } else {
                echo '<tr><td colspan="6" class="text-center">No orders found</td></tr>';
            }
          ?>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>

<script>
  // Sales Chart
const salesCtx = document.getElementById('salesChart').getContext('2d');
new Chart(salesCtx, {
      type: 'line',
      data: {
        labels: <?php echo $chart_months_json; ?>,
        datasets: [{
      label: 'Monthly Sales',
          data: <?php echo $chart_data_json; ?>,
      borderColor: '#4070FF',
      backgroundColor: 'rgba(64, 112, 255, 0.1)',
      borderWidth: 2,
      fill: true,
      tension: 0.4
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            display: false
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            grid: {
          color: 'rgba(0, 0, 0, 0.05)'
            }
          },
          x: {
            grid: {
              display: false
            }
          }
        }
      }
    });
  
// Category Chart
const categoryCtx = document.getElementById('categoryChart').getContext('2d');
new Chart(categoryCtx, {
      type: 'doughnut',
      data: {
    labels: ['Fiction', 'Non-Fiction', 'Academic', 'Children'],
        datasets: [{
      data: [30, 25, 25, 20],
          backgroundColor: [
        '#4070FF',
        '#50C878',
        '#FFB347',
        '#FF6B6B'
      ]
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
        position: 'bottom'
      }
    }
  }
});
</script>

</body>
</html>