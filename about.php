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
  <title>About Us - Boi Lagbe!</title>

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
    <p>Discover the journey of Boi Lagbe! and our commitment to spreading the joy of reading</p>
  </div>
</section>

<!-- Mission Section -->
<section class="mission-section">
  <div class="container">
    <div class="mission-content">
      <div class="mission-text">
        <h2>Our Mission</h2>
        <p>At <strong>Boi Lagbe!</strong>, we are dedicated to bringing the magic of books to every reader in Bangladesh. Our mission is to create a vibrant community of book lovers by providing affordable, accessible, and diverse reading materials that celebrate both local and international literature.</p>
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

<!-- Meet Our Team Section -->
    <section class="team-section">
        <h2>Meet Our Passionate Team</h2>
        <p>A group of dedicated book lovers curating the best reads just for you.</p>
        
        <div class="team-grid">
            <!-- Team Member 1 -->
            <div class="team-member">
                <div class="team-member-img-wrapper">
                    <img src="Teammates_Pictures\Munna.png" alt="Mohammad Mehedi Hasan Munna">
                </div>
                <h3>Mohammad Mehedi Hasan Munna</h3>
                <p class="role">Founder & Chief Curator</p>
                <p>With over 1.5 years in the publishing world, Munna founded BoiLagbe! to share his love for literature and help readers discover hidden gems and timeless classics.</p>
            </div>

            <!-- Team Member 2 -->
            <div class="team-member">
                <div class="team-member-img-wrapper">
                    <img src="Teammates_Pictures\Aditya.png" alt="Aditya Debnath (Tirtha)">
                </div>
                <h3>Aditya Debnath (Tirtha)</h3>
                <p class="role">Developer & Book Specialist</p>
                <p>Aditya is a passionate Developer who excels at finding the most compelling Solution. He is known for his thoughtful recommendations and deep knowledge of contemporary fiction.</p>
            </div>

            <!-- Team Member 3 -->
            <div class="team-member">
                <div class="team-member-img-wrapper">
                    <img src="Teammates_Pictures\Wadud_Priyo.png" alt="Abdul Wadud (Priyo)">
                </div>
                <h3>Abdul Wadud (Priyo)</h3>
                <p class="role">Head of Operations</p>
                <p>Priyo is an expert in logistics, ensuring that your books are packed with care and delivered to your doorstep quickly and efficiently. He keeps our virtual shelves running smoothly.</p>
            </div>
            
            <!-- Team Member 4 -->
            <div class="team-member">
                 <div class="team-member-img-wrapper">
                    <img src="Teammates_Pictures\Akash.png" alt="Akash Khan">
                </div>
                <h3>Akash Khan</h3>
                <p class="role">Children's & YA Specialist</p>
                <p>Akash leads our efforts to inspire the next generation of readers. He carefully selects engaging and educational books for children and young adults of all ages.</p>
            </div>
        </div>
    </section>

<!-- Features Section -->
<section class="features-section">
  <div class="container">
    <h2>Why Choose Boi Lagbe!?</h2>
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
