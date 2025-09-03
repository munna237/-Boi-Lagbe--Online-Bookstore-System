<?php
include 'config.php';
session_start();

$admin_id=$_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:login.php');
}

if(isset($_GET['delete'])){
  $delete_id=$_GET['delete'];
  mysqli_query($conn,"DELETE FROM `register` WHERE id='$delete_id'");
  $message[]='1 user has been deleted';
  header("location:admin_users.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Users | Admin Dashboard</title>
  
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
        <h1 class="page-title">Users</h1>
      </div>
      <div class="header-right">
        <div class="search-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" id="userSearch" class="search-input" placeholder="Search users..." onkeyup="searchUsers()">
        </div>
      </div>
    </header>

    <!-- Users Content -->
    <div class="content-wrapper">
      <div class="users-grid" id="usersGrid">
        <?php
        $select_users = mysqli_query($conn, "SELECT * FROM `register`") or die('query failed');
        if(mysqli_num_rows($select_users) > 0){
          while($fetch_users = mysqli_fetch_assoc($select_users)){
        ?>
        <div class="user-card">
          <div class="user-card-header">
            <div class="user-avatar">
              <i class="fas fa-user"></i>
            </div>
            <div class="user-status <?php echo $fetch_users['user_type'] == 'admin' ? 'status-admin' : 'status-user'; ?>">
              <?php echo $fetch_users['user_type']; ?>
            </div>
          </div>
          
          <div class="user-card-body">
            <div class="user-info-group">
              <label class="info-label">Username</label>
              <div class="info-value"><?php echo $fetch_users['name']; ?></div>
            </div>
            
            <div class="user-info-group">
              <label class="info-label">Email</label>
              <div class="info-value"><?php echo $fetch_users['email']; ?></div>
            </div>
            
            <div class="user-info-group">
              <label class="info-label">Role</label>
              <div class="info-value"><?php echo ucfirst($fetch_users['user_type']); ?></div>
            </div>
          </div>
          
          <div class="user-card-footer">
            <a href="admin_users.php?delete=<?php echo $fetch_users['id']; ?>" 
               class="btn btn-danger btn-sm delete-btn"
               onclick="return confirm('Delete this user?');">
              <i class="fas fa-trash"></i> Delete
            </a>
          </div>
        </div>
        <?php
          }
        } else {
          echo '<div class="empty-state">
                  <i class="fas fa-users"></i>
                  <h3>No Users Found</h3>
                  <p>There are no users registered in the system.</p>
                </div>';
        }
        ?>
      </div>
    </div>
  </main>
</div>

<script src="admin.js"></script>
<script>
function searchUsers() {
    const searchInput = document.getElementById('userSearch');
    const filter = searchInput.value.toLowerCase();
    const grid = document.getElementById('usersGrid');
    const cards = grid.getElementsByClassName('user-card');

    for (let card of cards) {
        const username = card.querySelector('.user-info-group:nth-child(1) .info-value').textContent;
        const email = card.querySelector('.user-info-group:nth-child(2) .info-value').textContent;
        const role = card.querySelector('.user-info-group:nth-child(3) .info-value').textContent;

        const matchesSearch = 
            username.toLowerCase().includes(filter) ||
            email.toLowerCase().includes(filter) ||
            role.toLowerCase().includes(filter);

        card.style.display = matchesSearch ? '' : 'none';
    }
}
</script>
</body>
</html>
