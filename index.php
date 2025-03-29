<?php
session_start();
include 'includes/db.php'; // Include the database connection

// Fetch products from the database
$stmt = $conn->query("SELECT * FROM products");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Likku - Get Accessorized</title>
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@700&family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* General Styles */
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        /* Header Styling */
        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
            background-color: #222;
            color: white;
        }

        /* Branding */
        .branding {
            display: flex;
            align-items: center;
        }

        .branding img {
            height: 60px;
            width: 60px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid white;
        }

        .branding h1 {
            font-size: 28px;
            font-family: 'Lora', serif;
            font-weight: bold;
            margin: 0;
            margin-left: 10px;
            color: #FFD700;
        }

        .branding sub {
            font-size: 14px;
            font-family: 'Montserrat', sans-serif;
            color: #FFD700;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* Navigation Bar Styling */
        nav {
            display: flex;
            align-items: center;
        }

        nav a, .logout-button {
            font-family: 'Montserrat', sans-serif;
            color: white;
            text-decoration: none;
            margin-left: 15px;
            font-size: 16px;
            padding: 8px 15px;
            border-radius: 5px;
            transition: 0.3s;
        }

        nav a {
            background-color: #444;
        }

        nav a:hover {
            background-color: #FFD700;
            color: #222;
        }

        .logout-button {
            background-color: #ff4d4d;
            border: none;
            cursor: pointer;
        }

        .logout-button:hover {
            background-color: #d42f2f;
        }

        .cart-link {
            display: flex;
            align-items: center;
            background-color: #FFD700;
            color: #222;
            font-weight: bold;
        }

        .cart-icon {
            height: 20px;
            margin-right: 5px;
        }

        /* Products Section */
        .product-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            padding: 20px;
        }

        .product {
            background: white;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .product h3 {
            font-size: 20px;
            color: #333;
        }

        .product p {
            font-size: 16px;
            color: #777;
        }

        .add-to-cart-button {
            background-color: #222;
            color: white;
            padding: 8px 12px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .add-to-cart-button:hover {
            background-color: #FFD700;
            color: #222;
        }

        /* Footer */
        footer {
            text-align: center;
            padding: 10px;
            background-color: #222;
            color: white;
            font-size: 14px;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .header-container {
                flex-direction: column;
                align-items: center;
                text-align: center;
            }

            nav {
                flex-direction: column;
                margin-top: 10px;
            }

            nav a, .logout-button {
                display: block;
                margin: 5px 0;
            }

            .branding h1 {
                font-size: 24px;
            }

            .branding img {
                height: 50px;
                width: 50px;
            }
        }

        @media (max-width: 480px) {
            .product-list {
                grid-template-columns: 1fr;
            }

            .branding h1 {
                font-size: 22px;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <div class="branding">
                <img src="images/store-logo.png" alt="Store Logo">
                <h1>Likku <sub>Get Accessorized</sub></h1>
            </div>
            <nav>
                <a href="pages/login.php">Login</a>
                <a href="pages/register.php">Register</a>
                <a href="pages/cart.php" class="cart-link">
                    <img src="images/cart-icon.png" alt="Cart" class="cart-icon">
                    Cart
                </a>
                <form method="POST" style="display: inline;">
                    <button type="submit" name="logout" class="logout-button">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    
    <div class="main-container">
        <main>
            <h2 style="text-align:center; font-family:'Lora', serif;">Our Collection</h2>
            <div class="product-list">
                <?php if (empty($products)) : ?>
                    <p style="text-align:center;">No products available.</p>
                <?php else : ?>
                    <?php foreach ($products as $product) : ?>
                        <div class="product">
                            <h3><?= htmlspecialchars($product['name']); ?></h3>
                            <p>Price: $<?= number_format($product['price'], 2); ?></p>
                            <p><?= htmlspecialchars($product['description']); ?></p>
                            <?php if (!empty($product['image'])) : ?>
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" class="product-image">
                            <?php endif; ?>
                            <form method="POST" action="pages/cart.php">
                                <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                <button type="submit" name="add_to_cart" class="add-to-cart-button">Add to Cart</button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <footer>
        <p>&copy; <?= date('Y'); ?> Likku Accessories. All rights reserved.</p>
    </footer>
</body>
</html>
