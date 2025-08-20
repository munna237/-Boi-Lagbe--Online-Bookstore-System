<?php
include 'config.php';
session_start();

// Make user_id optional
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_POST['send'])) {
  $name = mysqli_real_escape_string($conn, $_POST['name']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $number = $_POST['number'];
  $msg = mysqli_real_escape_string($conn, $_POST['message']);
  
  $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg'") or die('query failed');
  
  if (mysqli_num_rows($select_message) > 0) {
     $message[] = 'message sent already!';
  } else {
     // Insert message with or without user_id
     mysqli_query($conn, "INSERT INTO `message`(user_id, name, email, number, message) VALUES(" . ($user_id ? "'$user_id'" : "NULL") . ", '$name', '$email', '$number', '$msg')") or die('query failed');
     $message[] = 'message sent successfully!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact BookExpress</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">
</head>
<body>
  
<?php include 'user_header.php'; ?>

<!-- Contact Hero -->
<section class="contact-hero">
  <div class="hero-content">
    <h1>Get in Touch</h1>
    <p>We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
  </div>
</section>

<!-- Contact Information -->
<section class="contact-info">
  <div class="container">
    <div class="info-grid">
      <div class="info-card">
        <i class="fas fa-map-marker-alt"></i>
        <h3>Visit Us</h3>
        <p>123 Book Street<br>Dhaka, Bangladesh</p>
      </div>
      <div class="info-card">
        <i class="fas fa-phone"></i>
        <h3>Call Us</h3>
        <p>+880 1787831313<br>Mon-Sat, 9am-6pm</p>
      </div>
      <div class="info-card">
        <i class="fas fa-envelope"></i>
        <h3>Email Us</h3>
        <p>support@boibd.com<br>info@boibd.com</p>
      </div>
    </div>
  </div>
</section>

<!-- Contact Form -->
<section class="contact-form">
  <div class="container">
    <div class="form-content">
      <h2>Send Us a Message</h2>
      <p>Fill out the form below and we'll get back to you shortly.</p>
      <form action="" method="post">
        <div class="form-group">
          <label for="name">Your Name</label>
          <input type="text" id="name" name="name" required placeholder="Enter your name">
        </div>
        <div class="form-group">
          <label for="email">Email Address</label>
          <input type="email" id="email" name="email" required placeholder="Enter your email">
        </div>
        <div class="form-group">
          <label for="number">Phone Number</label>
          <input type="tel" id="number" name="number" required placeholder="Enter your phone number">
        </div>
        <div class="form-group">
          <label for="message">Message</label>
          <textarea id="message" name="message" rows="5" required placeholder="How can we help you?"></textarea>
        </div>
        <button type="submit" name="send" class="btn-primary">Send Message</button>
      </form>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="faq-section">
  <div class="container">
    <h2>Frequently Asked Questions</h2>
    <div class="faq-grid">
      <div class="faq-item">
        <h3>How long does delivery take?</h3>
        <p>We typically deliver within 2-3 business days within Dhaka, and 3-5 business days for other locations.</p>
      </div>
      <div class="faq-item">
        <h3>Do you offer international shipping?</h3>
        <p>Yes, we ship to selected international locations. Shipping times and costs vary by destination.</p>
      </div>
      <div class="faq-item">
        <h3>What is your return policy?</h3>
        <p>We accept returns within 7 days of delivery. Books must be in original condition.</p>
      </div>
      <div class="faq-item">
        <h3>Can I track my order?</h3>
        <p>Yes, you'll receive a tracking number once your order ships.</p>
      </div>
    </div>
  </div>
</section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>