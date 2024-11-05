<?php

session_start();

$selectedCategory = $_COOKIE['category'] ?? 'all';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['category'])) {
    $selectedCategory = $_POST['category'];
    setcookie("category", $selectedCategory, time() + 3, "/"); 
    header("Location: index.php"); 
    exit();
}

if (isset($_GET['product_name'])) {
    $recentProducts = isset($_COOKIE['recent_products']) ? explode(',', $_COOKIE['recent_products']) : [];
    $productName = $_GET['product_name'];
    if (!in_array($productName, $recentProducts)) {
        $recentProducts[] = $productName;
        setcookie("recent_products", implode(',', $recentProducts), time() + (86400 * 30), "/"); // 30 days
    }
}

if (!isset($_COOKIE['last_visit'])) {
    $lastVisitMessage = "Welcome! This is your first visit.";
} else {
    $lastVisitMessage = "Welcome back! Your last visit was on " . $_COOKIE['last_visit'];
}
setcookie("last_visit", date("Y-m-d H:i:s"), time() + (86400 * 30), "/"); // 30 days
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-commerce Store</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <!-- Header -->
    <header>
        <h1>E-commerce Store</h1>
        <p><?php echo $lastVisitMessage; ?></p> <!-- Display last visit message -->
        <input type="text" id="search-bar" placeholder="Search products..." oninput="searchProducts()">
        <button onclick="viewCart()">View Cart (<span id="cart-count"><?php echo count($_SESSION['cart'] ?? []); ?></span>)</button>
    </header>

    <!-- Filters -->
    <section class="filters">
        <h2>Products</h2>
        <form method="POST" action="index.php">
            <select name="category" id="category-filter" onchange="this.form.submit()">
                <option value="all" <?php if ($selectedCategory == 'all') echo 'selected'; ?>>All</option>
                <option value="electronics" <?php if ($selectedCategory == 'electronics') echo 'selected'; ?>>Electronics</option>
                <option value="clothing" <?php if ($selectedCategory == 'clothing') echo 'selected'; ?>>Clothing</option>
                <option value="books" <?php if ($selectedCategory == 'books') echo 'selected'; ?>>Books</option>
            </select>
        </form>
    </section>

    <!-- Recently Viewed Products -->
    <section class="recently-viewed">
        <h2>Recently Viewed Products</h2>
        <ul>
            <?php
            if (isset($_COOKIE['recent_products'])) {
                $recentProducts = explode(',', $_COOKIE['recent_products']);
                foreach ($recentProducts as $recentProduct) {
                    echo "<li>$recentProduct</li>";
                }
            } else {
                echo "<li>No recent products viewed.</li>";
            }
            ?>
        </ul>
    </section>

    <!-- Product List -->
    <section id="products" class="product-list">
        <?php
        // Define products
        $products = [
        [
            'category' => 'electronics', 
            'name' => 'Smartphone', 
            'price' => 500,
            'image' => 'https://via.placeholder.com/150x150.png?text=Smartphone'
        ],
        [
            'category' => 'clothing', 
            'name' => 'T-Shirt', 
            'price' => 150,
            'image' => 'https://via.placeholder.com/150x150.png?text=T-Shirt'
        ],
        [
            'category' => 'books', 
            'name' => 'Book', 
            'price' => 100,
            'image' => 'https://via.placeholder.com/150x150.png?text=Book'
        ]
    ];


        // Display products based on selected category
        foreach ($products as $product) {
            if ($selectedCategory == 'all' || $product['category'] == $selectedCategory) {
                echo "<div class='product' data-category='{$product['category']}'>
                        <img src='https://via.placeholder.com/150x150.png?text={$product['name']}' alt='{$product['name']}'>
                        <h3><a href='index.php?product_name={$product['name']}'>{$product['name']}</a></h3>
                        <p>Price: ₱" . number_format($product['price'], 2) . "</p> 
                        <button onclick=\"addToCart('{$product['name']}', {$product['price']})\">Add to Cart</button>
                      </div>";
            }
        }
        ?>
    </section>

    <!-- Cart Modal -->
    <div id="cart-modal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeCart()">&times;</span>
            <h2>Your Cart</h2>
            <div id="cart-items">
                <?php

                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        echo "<p>{$item['name']} - ₱" . number_format($item['price'], 2) . "</p>"; 
                    }
                } else {
                    echo "<p>Your cart is empty!</p>";
                }
                ?>
            </div>
            <button onclick="checkout()">Checkout</button>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 E-commerce Store. All rights reserved.</p>
    </footer>

    <script src="script.js"></script>
</body>
</html>
