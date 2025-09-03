<?php
include 'config.php';
session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
  header('location:login.php');
}

if(isset($_GET['delete'])){
  $delete_id = $_GET['delete'];
  mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
  $message[] = 'Message has been deleted successfully!';
  header('location:admin_messages.php');
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Messages | Admin Dashboard</title>
  
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
        <h1 class="page-title">Messages</h1>
      </div>
      <div class="header-right">
        <div class="search-bar">
          <i class="fas fa-search search-icon"></i>
          <input type="text" id="messageSearch" class="search-input" placeholder="Search messages..." onkeyup="searchMessages()">
        </div>
      </div>
    </header>

    <!-- Messages Content -->
    <div class="content-wrapper">
      <div class="messages-container" id="messagesContainer">
        <?php
        $select_message = mysqli_query($conn, "SELECT * FROM `message` ORDER BY id DESC") or die('query failed');
        if(mysqli_num_rows($select_message) > 0){
          while($fetch_message = mysqli_fetch_assoc($select_message)){
        ?>
        <div class="message-card">
          <div class="message-header">
            <div class="user-info">
              <div class="user-avatar">
                <i class="fas fa-user"></i>
              </div>
              <div class="user-details">
                <h4><?php echo $fetch_message['name']; ?></h4>
                <div class="contact-info">
                  <span><i class="fas fa-envelope"></i> <?php echo $fetch_message['email']; ?></span>
                  <span><i class="fas fa-phone"></i> <?php echo $fetch_message['number']; ?></span>
                </div>
              </div>
            </div>
            <div class="message-actions">
              <a href="admin_messages.php?delete=<?php echo $fetch_message['id']; ?>" 
                class="btn btn-danger btn-sm" 
                onclick="return confirm('Delete this message?');">
                <i class="fas fa-trash"></i> Delete
              </a>
            </div>
          </div>
          <div class="message-body">
            <p><?php echo $fetch_message['message']; ?></p>
          </div>
        </div>
        <?php
          }
        } else {
          echo '<div class="empty-state">
                  <i class="fas fa-inbox"></i>
                  <h3>No Messages</h3>
                  <p>There are no messages to display.</p>
                </div>';
        }
        ?>
      </div>
    </div>
  </main>
</div>

<script src="admin.js"></script>
<script>
function searchMessages() {
    const searchInput = document.getElementById('messageSearch');
    const filter = searchInput.value.toLowerCase();
    const container = document.getElementById('messagesContainer');
    const cards = container.getElementsByClassName('message-card');

    for (let card of cards) {
        const name = card.querySelector('.user-details h4').textContent;
        const email = card.querySelector('.contact-info span:nth-child(1)').textContent;
        const phone = card.querySelector('.contact-info span:nth-child(2)').textContent;
        const message = card.querySelector('.message-body p').textContent;

        const matchesSearch = 
            name.toLowerCase().includes(filter) ||
            email.toLowerCase().includes(filter) ||
            phone.toLowerCase().includes(filter) ||
            message.toLowerCase().includes(filter);

        card.style.display = matchesSearch ? '' : 'none';
    }
}
</script>
</body>
</html>
