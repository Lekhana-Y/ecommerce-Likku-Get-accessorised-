<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit();
}

include '../includes/db.php';

// Check if product ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Product ID is missing.");
}

$product_id = $_GET['id'];

// Fetch the product to delete its image as well
$stmt = $conn->prepare("SELECT image FROM products WHERE id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    die("Product not found.");
}

// Delete product from database
$delete_stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
$delete_stmt->execute([$product_id]);

// Delete associated image if exists
if (!empty($product['image']) && file_exists("../images/" . $product['image'])) {
    unlink("../images/" . $product['image']);
}

// Redirect back with success message
header("Location: manage_products.php?success=Product deleted successfully");
exit();
?>
