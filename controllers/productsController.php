<?php
session_start();
include '../components/config.php';

    // Handle Add Product
    if(isset($_POST['addProduct'])) {
        $name = $_POST['name'];
        $category = $_POST['category'];
        $unit = $_POST['unit'];
        $price = $_POST['price'];
        $expiration = $_POST['expiration'];
        $user_id = $_SESSION['user_id'];
        $created_at = date('Y-m-d H:i:s');
        
        $stmt = $con->prepare("INSERT INTO products (user_id, product_name, category, unit, price, expiration_date, created_at) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("isssdss", $user_id, $name, $category, $unit, $price, $expiration, $created_at);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>location.href='../admin/products.php';</script>";
    }

    // Handle Update Product
    if(isset($_POST['updateProduct'])) {
        $id = $_POST['edit_id'];
        $name = $_POST['edit_name'];
        $category = $_POST['edit_category'];
        $unit = $_POST['edit_unit'];
        $price = $_POST['edit_price'];
        $expiration = $_POST['edit_expiration'];
        
        $stmt = $con->prepare("UPDATE products SET product_name=?, category=?, unit=?, price=?, expiration_date=? WHERE product_id=?");
        $stmt->bind_param("sssdsi", $name, $category, $unit, $price, $expiration, $id);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>location.href='../admin/products.php';</script>";
    }

    // Handle Delete Product
    if(isset($_POST['deleteProduct'])) {
        $id = $_POST['delete_id'];
        
        $stmt = $con->prepare("DELETE FROM products WHERE product_id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $stmt->close();
        
        echo "<script>location.href='products.php';</script>";
    }
?>