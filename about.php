<?php
include 'config.php';
session_start();

// Remove login check and user_id requirement
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About BookExpress</title>

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">
</head>
<body>
  
<?php include 'user_header.php'; ?>

<!-- Hero Section -->
<section class="about-hero">
  <div class="hero-content">
    <h1>Our Story</h1>
    <p>Discover the journey of BoiBD and our commitment to spreading the joy of reading</p>
  </div>
</section>

<!-- Mission Section -->
<section class="mission-section">
  <div class="container">
    <div class="mission-content">
      <div class="mission-text">
        <h2>Our Mission</h2>
        <p>At <strong>BoiBD</strong>, we are dedicated to bringing the magic of books to every reader in Bangladesh. Our mission is to create a vibrant community of book lovers by providing affordable, accessible, and diverse reading materials that celebrate both local and international literature.</p>
        <div class="mission-stats">
          <div class="stat-item">
            <i class="fas fa-book"></i>
            <h3>10,000+</h3>
            <p>Books Available</p>
          </div>
          <div class="stat-item">
            <i class="fas fa-users"></i>
            <h3>5,000+</h3>
            <p>Happy Customers</p>
          </div>
          <div class="stat-item">
            <i class="fas fa-globe"></i>
            <h3>50+</h3>
            <p>Categories</p>
          </div>
        </div>
      </div>
      <div class="mission-image">
        <img src="aboutph.jpg" alt="BookExpress Mission">
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="features-section">
  <div class="container">
    <h2>Why Choose BoiBD?</h2>
    <div class="features-grid">
      <div class="feature-card">
        <i class="fas fa-truck"></i>
        <h3>Fast Delivery</h3>
        <p>Quick and reliable delivery to your doorstep</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-tags"></i>
        <h3>Best Prices</h3>
        <p>Competitive prices and regular discounts</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-book-open"></i>
        <h3>Wide Selection</h3>
        <p>Extensive collection of books across genres</p>
      </div>
      <div class="feature-card">
        <i class="fas fa-headset"></i>
        <h3>24/7 Support</h3>
        <p>Always here to help you</p>
      </div>
    </div>
    </div>
  </section>

<!-- Contact CTA -->
<section class="contact-cta">
  <div class="container">
    <h2>Have Questions?</h2>
    <p>We're here to help you find your next favorite book</p>
    <button class="btn-primary" onclick="window.location.href='contact.php'">Contact Us</button>
  </div>
  </section>

<?php include 'footer.php'; ?>

<script src="script.js"></script>

</body>
</html>