<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password
    $type = $_POST['type'];

    // Insert new user into the database
    $stmt = $con->prepare("INSERT INTO users (username, password, type) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $username, $password, $type);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('User added successfully!'); window.location.href='utilities.php';</script>";
    exit;
}
?>
