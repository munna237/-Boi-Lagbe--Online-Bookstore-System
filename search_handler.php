<?php
include 'config.php';

if(isset($_POST['search'])) {
    $search_term = mysqli_real_escape_string($conn, $_POST['search']);
    $query = "SELECT * FROM `products` WHERE name LIKE '%{$search_term}%' ORDER BY name ASC LIMIT 5";
    $result = mysqli_query($conn, $query) or die('query failed');
    
    $output = '';
    
    if(mysqli_num_rows($result) > 0) {
        while($row = mysqli_fetch_assoc($result)) {
            $output .= '
            <div class="search-result-item" onclick="window.location.href=\'search_page.php?search=' . urlencode($row['name']) . '\'">
                <div class="search-result-image">
                    <img src="uploaded_img/' . $row['image'] . '" alt="' . $row['name'] . '" onerror="this.src=\'images/no-image.png\'">
                </div>
                <div class="search-result-info">
                    <div class="search-result-name">' . $row['name'] . '</div>
                    <div class="search-result-price">TK. ' . $row['price'] . '/-</div>
                </div>
            </div>';
        }
    } else {
        $output .= '<div class="no-results">No matching books found</div>';
    }
    
    echo $output;
}
?> 