<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

date_default_timezone_set('Asia/Manila'); // Set timezone

$username = $_SESSION['username'];

$stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $user_id = $user['id'];
    $logout_time = date("Y-m-d H:i:s");
    $stmt = $con->prepare("UPDATE user_activity SET logout_time = ? WHERE user_id = ? AND logout_time IS NULL");
    $stmt->bind_param("si", $logout_time, $user_id);
    $stmt->execute();
    $stmt->close();
}

session_unset();
session_destroy();
header("Location: login.php");
exit();
?>
