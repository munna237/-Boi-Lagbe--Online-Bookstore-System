<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Card</title>
    <style>
        .product-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 2px 4px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            max-width: 320px;
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 20px rgba(0,0,0,0.12);
        }

        .product-image {
            width: 100%;
            height: 280px;
            object-fit: contain;
            border-radius: 12px;
            background: #f8f9fa;
            padding: 1rem;
            margin-bottom: 1.25rem;
            transition: transform 0.3s ease;
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
            .product-card {
                max-width: 100%;
                padding: 1rem;
            }

            .product-image {
                height: 240px;
            }
        }
    </style>
</head>
<body>

<div class="product-card">
    <div class="product-image placeholder">No Image Available</div>
    <h3 class="product-title">SHATTERED</h3>
    <div class="product-price">TK. 600/-</div>
    
    <div class="quantity-control">
        <button class="quantity-btn minus" onclick="updateQuantity(this, -1)" disabled>
            <i class="fas fa-minus"></i>
        </button>
        <input type="number" class="quantity-input" value="1" min="1" readonly>
        <button class="quantity-btn plus" onclick="updateQuantity(this, 1)">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    
    <button class="add-to-cart" onclick="addToCart(this)">
        <i class="fas fa-shopping-cart"></i>
        Add to Cart
    </button>
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

function addToCart(button) {
    const card = button.closest('.product-card');
    const quantity = card.querySelector('.quantity-input').value;
    const title = card.querySelector('.product-title').textContent;
    
    // Here you can add your cart logic
    console.log(`Added ${quantity} x ${title} to cart`);
    
    // Visual feedback
    button.style.transform = 'scale(0.95)';
    setTimeout(() => button.style.transform = '', 150);
}

// To use this in a loop, you can create cards dynamically like this:
function createProductCard(product) {
    const template = document.querySelector('.product-card').cloneNode(true);
    
    // Update product details
    template.querySelector('.product-title').textContent = product.name;
    template.querySelector('.product-price').textContent = `TK. ${product.price}/-`;
    
    // If there's an image
    if (product.image) {
        const img = document.createElement('img');
        img.src = product.image;
        img.alt = product.name;
        img.className = 'product-image';
        img.onerror = function() {
            this.outerHTML = '<div class="product-image placeholder">No Image Available</div>';
        };
        template.querySelector('.product-image').replaceWith(img);
    }
    
    return template;
}
</script>

</body>
</html> 