<!-- Navigation Menu -->
<nav class="navbar">
    <a href="home.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'home.php' ? 'active' : ''; ?>">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="about.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'about.php' ? 'active' : ''; ?>">
        <i class="fas fa-info-circle"></i>
        <span>About</span>
    </a>
    <a href="shop.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'shop.php' ? 'active' : ''; ?>">
        <i class="fas fa-shopping-bag"></i>
        <span>Shop</span>
    </a>
    <a href="contact.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'contact.php' ? 'active' : ''; ?>">
        <i class="fas fa-envelope"></i>
        <span>Contact</span>
    </a>
    <?php if(isset($_SESSION['user_id'])): ?>
    <a href="orders.php" class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'orders.php' ? 'active' : ''; ?>">
        <i class="fas fa-gift"></i>
        <span>Orders</span>
    </a>
    <?php endif; ?>
    <div class="search-container">
        <form action="search_page.php" method="post" class="search-form" id="searchForm">
            <input type="text" 
                   name="search" 
                   id="instant-search" 
                   class="search-input" 
                   placeholder="Search books..." 
                   autocomplete="off" />
            <button type="submit" name="submit" class="search-button">
                <i class="fas fa-search"></i>
            </button>
        </form>
        <div class="search-results" id="searchResults"></div>
    </div>
</nav>

<style>
.navbar {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0 1rem;
}

.nav-link {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    color: #374151;
    text-decoration: none;
    font-weight: 500;
    border-radius: 8px;
    transition: all 0.2s ease;
}

.nav-link:hover {
    background: #f3f4f6;
    color: #2563eb;
}

.nav-link.active {
    background: #2563eb;
    color: white;
}

.nav-link i {
    font-size: 1.1rem;
}

.search-container {
    margin-left: auto;
    position: relative;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-form {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.search-input {
    padding: 0.75rem 1rem;
    padding-right: 2.5rem;
    border: 1px solid #e5e7eb;
    border-radius: 8px;
    font-size: 0.95rem;
    width: 250px;
    background: white;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
}

.search-button {
    background: none;
    border: none;
    color: #6b7280;
    cursor: pointer;
    padding: 0.5rem;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.search-button:hover {
    color: #2563eb;
    background: #f3f4f6;
}

.search-results {
    display: none;
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border-radius: 8px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin-top: 0.5rem;
    max-height: 400px;
    overflow-y: auto;
    z-index: 1000;
}

.search-result-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 0.75rem;
    cursor: pointer;
    transition: background-color 0.2s;
}

.search-result-item:hover {
    background-color: #f3f4f6;
}

.search-result-image {
    width: 50px;
    height: 50px;
    flex-shrink: 0;
}

.search-result-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 4px;
}

.search-result-info {
    flex-grow: 1;
}

.search-result-name {
    font-weight: 500;
    color: #1f2937;
    margin-bottom: 0.25rem;
}

.search-result-price {
    color: #2563eb;
    font-weight: 600;
    font-size: 0.875rem;
}

.no-results {
    padding: 1rem;
    text-align: center;
    color: #6b7280;
}

@media (max-width: 1024px) {
    .navbar {
        gap: 0.5rem;
    }
    
    .nav-link {
        padding: 0.5rem 0.75rem;
    }
    
    .search-input {
        width: 200px;
    }
}

@media (max-width: 768px) {
    .navbar {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .search-container {
        width: 100%;
        margin: 0.5rem 0;
    }
    
    .search-form {
        width: 100%;
    }
    
    .search-input {
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('instant-search');
    const searchResults = document.getElementById('searchResults');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;

    // Handle input changes
    searchInput.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const searchTerm = this.value.trim();
        
        if (searchTerm.length > 0) {
            searchTimeout = setTimeout(() => {
                fetchSearchResults(searchTerm);
            }, 300);
        } else {
            searchResults.style.display = 'none';
        }
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!searchResults.contains(e.target) && e.target !== searchInput) {
            searchResults.style.display = 'none';
        }
    });

    // Show results when focusing on search input
    searchInput.addEventListener('focus', function() {
        if (this.value.trim().length > 0) {
            searchResults.style.display = 'block';
        }
    });

    // Handle form submission
    searchForm.addEventListener('submit', function(e) {
        const searchTerm = searchInput.value.trim();
        if (searchTerm.length === 0) {
            e.preventDefault();
        }
    });

    // Fetch search results using AJAX
    function fetchSearchResults(searchTerm) {
        const formData = new FormData();
        formData.append('search', searchTerm);

        fetch('search_handler.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            searchResults.innerHTML = data;
            searchResults.style.display = 'block';
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }
});
</script> 