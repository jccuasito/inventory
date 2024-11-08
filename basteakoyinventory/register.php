<?php
include_once("connections/connection.php");
$con = connection();

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

// Example registration script
$username = 'staff_test';
$password = 'test_password';
$type = 'staff'; // Should match the enum values in your database
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$stmt = $con->prepare("INSERT INTO users (username, password, type) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $username, $hashed_password, $type);
$stmt->execute();
$stmt->close();

echo "User registered with hashed password and type.";
$con->close();
?>
