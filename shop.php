<?php
include 'config.php';
session_start();

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if(isset($_POST['add_to_cart'])){
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_quantity = $_POST['product_quantity'];
    $product_image = $_POST['product_image'];

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
        // Regular database cart for logged in users
        $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');

        if(mysqli_num_rows($check_cart_numbers) > 0){
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
    <title>Shop | BoiBD.com</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

    <style>
        .products-container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 1rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.12);
        }

        .product-image {
            width: 100%;
            height: 280px;
            overflow: hidden;
            border-radius: 12px;
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1.25rem;
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

        .product-image.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            color: #71717a;
            font-size: 0.875rem;
        }

        .product-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #18181b;
            margin-bottom: 0.75rem;
            line-height: 1.4;
        }

        .product-price {
            font-size: 1.375rem;
            font-weight: 600;
            color: #2563eb;
            margin-bottom: 1.5rem;
        }

        .quantity-control {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            background: #f4f4f5;
            padding: 0.5rem;
            border-radius: 8px;
            width: fit-content;
            margin-bottom: 1.25rem;
        }

        .quantity-btn {
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: none;
            border-radius: 6px;
            color: #2563eb;
            font-size: 1.25rem;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quantity-btn:hover {
            background: #2563eb;
            color: white;
        }

        .quantity-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: #e5e7eb;
            color: #9ca3af;
        }

        .quantity-input {
            width: 40px;
            text-align: center;
            border: none;
            background: transparent;
            font-size: 1rem;
            font-weight: 500;
            color: #18181b;
            -moz-appearance: textfield;
        }

        .quantity-input::-webkit-outer-spin-button,
        .quantity-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .add-to-cart {
            width: 100%;
            padding: 0.875rem;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-family: inherit;
            font-weight: 500;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .add-to-cart:hover {
            background: #1d4ed8;
        }

        .add-to-cart i {
            font-size: 1.25rem;
        }

        @media (max-width: 640px) {
            .products-container {
                margin: 1rem auto;
            }

            .product-card {
                padding: 1rem;
            }

            .product-image {
                height: 240px;
            }
        }
    </style>
</head>
<body>

<?php include 'user_header.php'; ?>

<div class="products-container">
    <div class="products-grid">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `products` ORDER BY name ASC") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <form action="" method="post" class="product-card">
            <?php if($fetch_products['image']){ ?>
                <div class="product-image">
                    <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" 
                         alt="<?php echo $fetch_products['name']; ?>"
                         onerror="this.outerHTML='<div class=\'product-image placeholder\'>No Image Available</div>'">
                </div>
            <?php } else { ?>
                <div class="product-image placeholder">No Image Available</div>
            <?php } ?>
            
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
            
            <button type="submit" name="add_to_cart" class="add-to-cart" onclick="addToCartAnimation(this)">
                <i class="fas fa-shopping-cart"></i>
                Add to Cart
            </button>
        </form>
        <?php
            }
        } else {
            echo '<p class="empty">No products added yet!</p>';
        }
        ?>
    </div>
</div>

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

function addToCartAnimation(button) {
    // Add visual feedback
    button.style.transform = 'scale(0.95)';
    setTimeout(() => button.style.transform = '', 150);
}
</script>

<?php include 'footer.php'; ?>

</body>
</html>