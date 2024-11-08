<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete user from the database
    $stmt = $con->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('User deleted successfully!'); window.location.href='utilities.php';</script>";
    exit;
} else {
    echo "<script>alert('No user ID specified.'); window.location.href='utilities.php';</script>";
    exit;
}
?>
