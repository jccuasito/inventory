<?php
include_once("connections/connection.php");
$con = connection();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $sql = "DELETE FROM products WHERE id=?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) {
        echo "Product deleted successfully";
    } else {
        echo "Error deleting product";
    }
    $stmt->close();
}
?>
