<?php
include_once("connections/connection.php");
$con = connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $size = $_POST['size'];
    $cups = $_POST['cups'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $status = $_POST['status'];

    if (empty($id)) {
        // Insert statement
        $sql = "INSERT INTO products (name, category, size, cups, price, stock, status) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssdiss", $name, $category, $size, $cups, $price, $stock, $status);
    } else {
        // Update statement
        $sql = "UPDATE products SET name=?, category=?, size=?, cups=?, price=?, stock=?, status=? WHERE id=?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sssdissi", $name, $category, $size, $cups, $price, $stock, $status, $id);
    }
    
    $stmt->execute();
    header("Location: products.php");
    exit();
}
