<?php
include 'config.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (isset($_POST['add_to_cart'])) {
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $product_image = $_POST['product_image'];
  $product_quantity = $_POST['product_quantity'];

  if(!$user_id) {
    // Initialize cart session if not exists
    if(!isset($_SESSION['cart'])) {
      $_SESSION['cart'] = array();
    }
    
    // Check if product already in cart
    $product_exists = false;
    foreach($_SESSION['cart'] as &$item) {
      if($item['name'] === $product_name) {
        $product_exists = true;
        break;
      }
    }

    if($product_exists) {
      $message[] = 'Already added to cart!';
    } else {
      // Add to session cart
      $_SESSION['cart'][] = array(
        'name' => $product_name,
        'price' => $product_price,
        'quantity' => $product_quantity,
        'image' => $product_image
      );
      $message[] = 'Product added to cart!';
    }
  } else {
    $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

    if (mysqli_num_rows($check_cart_numbers) > 0) {
      $message[] = 'Already added to cart!';
    } else {
      mysqli_query($conn, "INSERT INTO `cart`(user_id, name, price, quantity, image) VALUES('$user_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
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
  <title>Search Results | Boi Lagbe!</title>

  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="style.css">
</head>

<body>
  <?php include 'user_header.php'; ?>

  <div class="search-page">
    <div class="products-container">
      <div class="products-grid" id="productsGrid">
        <?php
        if (isset($_POST['submit']) || isset($_POST['search'])) {
          $search_term = mysqli_real_escape_string($conn, $_POST['search']);
          $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_term}%' ORDER BY name ASC") or die('query failed');
          
          if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_products = mysqli_fetch_assoc($select_products)) {
        ?>
            <form action="" method="post" class="product-card">
              <div class="product-image">
                <?php if($fetch_products['image']){ ?>
                  <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                       alt="<?php echo $fetch_products['name']; ?>"
                       onerror="this.outerHTML='<div class=\'image-placeholder\'><i class=\'fas fa-book\'></i></div>'">
                <?php } else { ?>
                  <div class="image-placeholder">
                    <i class="fas fa-book"></i>
                  </div>
                <?php } ?>
              </div>
              
              <div class="product-details">
                <h3 class="product-title"><?php echo $fetch_products['name']; ?></h3>
                <div class="product-price">TK. <?php echo $fetch_products['price']; ?>/-</div>
                
                <div class="quantity-control">
                  <button type="button" class="quantity-btn minus" onclick="updateQuantity(this, -1)" disabled>
                    <i class="fas fa-minus"></i>
                  </button>
                  <input type="number" name="product_quantity" class="quantity-input" value="1" min="1" readonly>
                  <button type="button" class="quantity-btn plus" onclick="updateQuantity(this, 1)">
                    <i class="fas fa-plus"></i>
                  </button>
                </div>

                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_price" value="<?php echo $fetch_products['price']; ?>">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                <button type="submit" name="add_to_cart" class="add-to-cart">
                  <i class="fas fa-shopping-cart"></i>
                  Add to Cart
                </button>
              </div>
            </form>
        <?php
            }
          } else {
            echo '<div class="no-results">
                    <i class="fas fa-search"></i>
                    <p>No books found matching "'.$search_term.'"</p>
                  </div>';
          }
        } else {
          echo '<div class="search-prompt">
                  <i class="fas fa-search"></i>
                  <p>Use the search bar above to find books</p>
                </div>';
        }
        ?>
      </div>
    </div>
  </div>

  <?php include 'footer.php'; ?>

  <script>
    function updateQuantity(button, change) {
      const container = button.closest('.quantity-control');
      const input = container.querySelector('.quantity-input');
      const minusBtn = container.querySelector('.minus');
      let value = parseInt(input.value) + change;
      
      // Ensure minimum value is 1
      value = Math.max(1, value);
      input.value = value;
      
      // Disable minus button when quantity is 1
      minusBtn.disabled = value === 1;
    }
  </script>

  <style>
    .search-page {
      max-width: 1200px;
      margin: 0 auto;
      padding: 1rem;
    }

    .products-grid {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 2rem;
    }

    .product-card {
      background: white;
      border-radius: 12px;
      overflow: hidden;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
      transition: transform 0.2s, box-shadow 0.2s;
      display: flex;
      flex-direction: column;
    }

    .product-card:hover {
      transform: translateY(-4px);
      box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    }

    .product-image {
      width: 100%;
      height: 280px;
      overflow: hidden;
      background: #f8f9fa;
      padding: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .product-image img {
      width: 100%;
      height: 100%;
      object-fit: contain;
      transition: transform 0.3s ease;
    }

    .product-image:hover img {
      transform: scale(1.05);
    }

    .image-placeholder {
      width: 100%;
      height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
      background: #f4f4f5;
      color: #9ca3af;
    }

    .image-placeholder i {
      font-size: 3rem;
    }

    .product-details {
      padding: 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1rem;
    }

    .product-title {
      font-size: 1.125rem;
      font-weight: 600;
      color: #1f2937;
      margin: 0;
    }

    .product-price {
      color: #2563eb;
      font-weight: 600;
      font-size: 1.25rem;
    }

    .quantity-control {
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .quantity-btn {
      background: #f3f4f6;
      border: none;
      border-radius: 6px;
      width: 36px;
      height: 36px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1rem;
      color: #4b5563;
      cursor: pointer;
      transition: all 0.2s;
    }

    .quantity-btn:hover:not(:disabled) {
      background: #e5e7eb;
      color: #1f2937;
    }

    .quantity-btn:disabled {
      opacity: 0.5;
      cursor: not-allowed;
    }

    .quantity-input {
      width: 60px;
      padding: 0.5rem;
      text-align: center;
      border: 1px solid #e5e7eb;
      border-radius: 6px;
      font-size: 1rem;
    }

    .add-to-cart {
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
      margin-top: auto;
    }

    .add-to-cart:hover {
      background: #1d4ed8;
    }

    .no-results,
    .search-prompt {
      text-align: center;
      padding: 3rem 1rem;
      color: #6b7280;
    }

    .no-results i,
    .search-prompt i {
      font-size: 3rem;
      margin-bottom: 1rem;
    }

    .no-results p,
    .search-prompt p {
      color: #6b7280;
      font-size: 1.1rem;
    }

    @media (max-width: 640px) {
      .products-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
      }

      .product-image {
        height: 240px;
      }

      .product-details {
        padding: 1rem;
      }
    }
  </style>
</body>
</html>
