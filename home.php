<?php
include 'config.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_POST['add_to_cart'])) {
  $pro_name = $_POST['product_name'];
  $pro_price = $_POST['product_price'];
  $pro_quantity = $_POST['product_quantity'];
  $pro_image = $_POST['product_image'];

  if(!$user_id) {
    // Initialize cart session if not exists
    if(!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    
    // Check if product already in cart
    $product_exists = false;
    foreach($_SESSION['cart'] as &$item) {
      if($item['name'] === $pro_name) {
        $product_exists = true;
        break;
      }
    }

    if($product_exists) {
      $message[] = 'Already added to cart!';
    } else {
      // Add to session cart
      $_SESSION['cart'][] = array(
        'name' => $pro_name,
        'price' => $pro_price,
        'quantity' => $pro_quantity,
        'image' => $pro_image
      );
      $message[] = 'Product added to cart!';
    }
  } else {
    $check = mysqli_query($conn, "SELECT * FROM `cart` WHERE name='$pro_name' AND user_id='$user_id'") or die('query failed');

    if (mysqli_num_rows($check) > 0) {
      $message[] = 'Already added to cart!';
    } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id,name,price,quantity,image) VALUES ('$user_id','$pro_name','$pro_price','$pro_quantity','$pro_image')") or die('query2 failed');
      $message[] = 'Product added to cart!';
    }
  }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>BoiLagbe!.com - Your Online Bookstore</title>

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

  <!-- Custom CSS -->
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="home.css">
</head>

<body>

<?php include 'user_header.php'; ?>

<!-- Hero Section -->
  <section class="home_cont">
    <div class="main_descrip">
    <h1>Where Bookworms Belong.</h1>
    <p>Thousands of titles. One perfect read. Dive into your next literary escape with Boi Lagbe!.com.</p>
    <button onclick="window.location.href='#featured'">Browse Books</button>
  </div>
</section>

<!-- Categories Section -->
<section class="categories">
  <div class="section-header">
    <h2>Popular Categories</h2>
    <p>Find your favorite genre</p>
  </div>
  <div class="category-grid">
    <div class="category-card" onclick="window.location.href='shop.php?category=fiction'">
      <i class="fas fa-book"></i>
      <h3>Fiction</h3>
    </div>
    <div class="category-card" onclick="window.location.href='shop.php?category=non-fiction'">
      <i class="fas fa-graduation-cap"></i>
      <h3>Non-Fiction</h3>
    </div>
    <div class="category-card" onclick="window.location.href='shop.php?category=children'">
      <i class="fas fa-child"></i>
      <h3>Children</h3>
    </div>
    <div class="category-card" onclick="window.location.href='shop.php?category=academic'">
      <i class="fas fa-university"></i>
      <h3>Academic</h3>
    </div>
    </div>
  </section>

<!-- Featured Books -->
<section id="featured" class="products_cont">
  <div class="title">
    <h2>All Books</h2>
    <p>Browse our complete collection</p>
  </div>
    <div class="pro_box_cont">
      <?php
      $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY name ASC") or die('query failed');
      if (mysqli_num_rows($select_products) > 0) {
        while ($fetch_products = mysqli_fetch_assoc($select_products)) {
      ?>
          <form action="" method="post" class="pro_box">
            <div class="product-image">
              <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                   alt="<?php echo $fetch_products['name']; ?>"
                   onerror="this.onerror=null; this.src='images/no-image.png';">
            </div>
            <div class="product-details">
                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="price">TK.<?php echo $fetch_products['price']; ?>/-</div>
                <div class="quantity-input">
                    <button type="button" onclick="decrementQuantity(this)" aria-label="Decrease quantity">
                        <i class="fas fa-minus"></i>
                    </button>
                    <input type="number" name="product_quantity" min="1" value="1" aria-label="Product quantity">
                    <button type="button" onclick="incrementQuantity(this)" aria-label="Increase quantity">
                        <i class="fas fa-plus"></i>
                    </button>
                </div>
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">
                <button type="submit" name="add_to_cart" class="btn">
                    <i class="fas fa-shopping-cart"></i>
                    Add to Cart
                </button>
            </div>
          </form>
      <?php
        }
      } else {
        echo '<p class="empty">No products added yet!</p>';
      }
      ?>
    </div>

    <div id="load-more" class="load-more">
      <button onclick="loadMore()" class="btn-load-more">Load More</button>
    </div>
</section>

<!-- About Section -->
  <section class="about_cont">
    <div class="about_descript">
    <h2>About Boi Lagbe!.com</h2>
    <p>Your premier destination for books of all genres. We believe in the power of reading to transform lives and inspire minds.</p>
    <ul class="features-list">
      <li><i class="fas fa-check"></i> Wide Selection of Books</li>
      <li><i class="fas fa-check"></i> Competitive Prices</li>
      <li><i class="fas fa-check"></i> Fast Delivery</li>
      <li><i class="fas fa-check"></i> Expert Staff</li>
    </ul>
    <button onclick="window.location.href='about.php'">Learn More</button>
    </div>
    
  </section>

<!-- Newsletter Section -->
  <section class="questions_cont">
    <div class="questions">
    <h2>Stay Updated</h2>
    <p>Subscribe to our newsletter for the latest books and exclusive offers</p>
    <form class="newsletter-form">
      <input type="email" placeholder="Enter your email">
      <button type="submit">Subscribe</button>
    </form>
    </div>
  </section>

<?php include 'footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('instant-search');
    const productCards = document.querySelectorAll('.pro_box');
    const searchResultsMessage = document.getElementById('search-results-message');
    const productsContainer = document.querySelector('.pro_box_cont');
    const loadMoreBtn = document.querySelector('.btn-load-more');
    let visibleProducts = 12;

    // Load More functionality
    function loadMore() {
        const products = document.querySelectorAll('.pro_box');
        const productsArray = Array.from(products);
        const hiddenProducts = productsArray.filter(product => product.style.display !== 'none');

        for (let i = visibleProducts; i < visibleProducts + 12 && i < hiddenProducts.length; i++) {
            if (hiddenProducts[i]) {
                hiddenProducts[i].style.display = 'flex';
            }
        }

        visibleProducts += 12;

        // Hide load more button if no more products to show
        if (visibleProducts >= hiddenProducts.length) {
            loadMoreBtn.classList.add('hidden');
        }
    }

    // Search functionality
    searchInput.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase().trim();
        let matchCount = 0;
        visibleProducts = 12; // Reset visible products count
        
        productCards.forEach((card, index) => {
            const bookTitle = card.querySelector('.name').textContent.toLowerCase();
            const matches = bookTitle.includes(searchTerm);
            
            if (matches) {
                matchCount++;
                if (matchCount <= visibleProducts) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            } else {
                card.style.display = 'none';
            }
        });

        // Show/hide load more button
        if (matchCount > visibleProducts) {
            loadMoreBtn.classList.remove('hidden');
        } else {
            loadMoreBtn.classList.add('hidden');
        }

        // Update search results message
        if (searchTerm === '') {
            searchResultsMessage.classList.remove('show');
            productCards.forEach((card, index) => {
                if (index < visibleProducts) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
            if (productCards.length > visibleProducts) {
                loadMoreBtn.classList.remove('hidden');
            }
        } else {
            searchResultsMessage.classList.add('show');
            if (matchCount === 0) {
                searchResultsMessage.textContent = `No results found for '${searchTerm}'`;
            } else {
                searchResultsMessage.textContent = `Found ${matchCount} book${matchCount === 1 ? '' : 's'} matching '${searchTerm}'`;
            }
        }

        // Add fade effect
        productsContainer.style.opacity = '0.6';
        setTimeout(() => {
            productsContainer.style.opacity = '1';
        }, 150);
    });

    // Initialize load more button visibility
    if (productCards.length <= visibleProducts) {
        loadMoreBtn.classList.add('hidden');
    }
});

function incrementQuantity(button) {
    const input = button.parentElement.querySelector('input[type="number"]');
    input.value = parseInt(input.value) + 1;
}

function decrementQuantity(button) {
    const input = button.parentElement.querySelector('input[type="number"]');
    if (parseInt(input.value) > 1) {
        input.value = parseInt(input.value) - 1;
    }
}

// Make loadMore function global
function loadMore() {
    const event = new Event('loadMore');
    document.dispatchEvent(event);
}
</script>

<script src="script.js"></script>

<style>
.pro_box_cont {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 2rem;
    padding: 1rem;
}

.pro_box {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
    display: flex;
    flex-direction: column;
    border: 1px solid #e5e7eb;
}

.pro_box:hover {
    transform: translateY(-4px);
    box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
}

.pro_box .product-image {
    width: 100%;
    height: 320px;
    overflow: hidden;
    border-radius: 12px;
    background: #f8f9fa;
    padding: 1rem;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-bottom: 1rem;
}

.pro_box .product-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    transition: transform 0.3s ease;
}

.pro_box .product-image:hover img {
    transform: scale(1.05);
}

.pro_box .product-details {
    padding: 1.5rem;
    display: flex;
    flex-direction: column;
    gap: 1rem;
    flex-grow: 1;
}

.pro_box .name {
    font-size: 1.125rem;
    font-weight: 600;
    color: #1f2937;
    margin-bottom: 0.5rem;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.pro_box .price {
    color: #2563eb;
    font-weight: 600;
    font-size: 1.25rem;
}

.quantity-input {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    margin: 1rem 0;
}

.quantity-input button {
    background: #f3f4f6;
    border: none;
    border-radius: 6px;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    color: #4b5563;
    cursor: pointer;
    transition: all 0.2s;
}

.quantity-input button:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.quantity-input input[type="number"] {
    width: 60px;
    padding: 0.5rem;
    text-align: center;
    border: 1px solid #e5e7eb;
    border-radius: 6px;
    font-size: 1rem;
}

.quantity-input input[type="number"]::-webkit-inner-spin-button,
.quantity-input input[type="number"]::-webkit-outer-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.btn {
    background: #2563eb;
    color: white;
    border: none;
    padding: 0.875rem;
    border-radius: 8px;
    font-weight: 500;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    cursor: pointer;
    transition: background-color 0.2s;
    width: 100%;
    margin-top: auto;
}

.btn:hover {
    background: #1d4ed8;
}

.btn i {
    font-size: 1.1rem;
}

@media (max-width: 640px) {
    .pro_box_cont {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
    }

    .pro_box .product-image {
        height: 280px;
    }

    .pro_box .product-details {
        padding: 1rem;
    }
}
</style>

</body>
</html>
