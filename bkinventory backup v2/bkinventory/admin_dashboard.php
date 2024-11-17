<?php
session_start();
include_once("connections/connection.php");
$con = connection();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch total users
$total_users_query = "SELECT COUNT(*) AS total_users FROM Users";
$total_users_result = $con->query($total_users_query);
$total_users = $total_users_result->fetch_assoc()['total_users'];

// Fetch total products
$total_products_query = "SELECT COUNT(*) AS total_products FROM Products";
$total_products_result = $con->query($total_products_query);
$total_products = $total_products_result->fetch_assoc()['total_products'];

// Fetch total sales
$total_sales_query = "SELECT COUNT(*) AS total_sales FROM Sales";
$total_sales_result = $con->query($total_sales_query);
$total_sales = $total_sales_result->fetch_assoc()['total_sales'];

// Fetch recent checkout transactions
$recent_checkouts_query = "SELECT * FROM Checkout_Transactions ORDER BY transaction_date DESC LIMIT 5";
$recent_checkouts_result = $con->query($recent_checkouts_query);

// Fetch best-selling products
$best_sellers_query = "SELECT t.product_id, p.product_name, SUM(t.quantity_count) AS quantity_sold 
                       FROM Transactions t
                       JOIN Products p ON t.product_id = p.product_id
                       GROUP BY t.product_id, p.product_name
                       ORDER BY quantity_sold DESC
                       LIMIT 5";
$best_sellers_result = $con->query($best_sellers_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/admin_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <!-- Sidebar content here -->
            <div class="logo">
                <img src="img/logo.png" alt="Logo">
            </div>
            <h2>Menu</h2>
            <nav>
                <ul>
                    <li><a href="admin_dashboard.php" onclick="showSection('dashboard')">Dashboard</a></li>
                    <li><a href="admin_pos.php" onclick="showSection('home')">Pos</a></li>
                    <li><a href="utilities.php" onclick="showSection('utilities')">Utilities</a></li>
                    <li><a href="products.php" onclick="showSection('products')">Products</a></li>
                    <li><a href="sales.php" onclick="showSection('sales')">Sales</a></li>
                </ul>
            </nav>
            <div class="logout">
                <a href="logout.php" onclick="return confirm('Do you want to logout from this page?');">Log out</a>
            </div>
        </div>
        <div class="main-content">
            <div class="dashboard-header">
                <h1>Dashboard</h1>
            </div>
            <div class="dashboard-content home-background">
                <div class="stats-box">
                    <div>Total Users: <?php echo $total_users; ?></div>
                    <div>Total Products: <?php echo $total_products; ?></div>
                    <div>Total Sales: <?php echo $total_sales; ?></div>
                </div>

                <div class="best-seller">
                    <h3>Best Sellers</h3>
                    <table>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity Sold</th>
                        </tr>
                        <?php while($row = $best_sellers_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity_sold']; ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>

                <div class="recent-history">
                    <h3>Recent History</h3>
                    <table>
                        <tr>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Transaction Date</th>
                        </tr>
                        <?php while($row = $recent_checkouts_result->fetch_assoc()) { ?>
                        <tr>
                            <td><?php echo $row['product_name']; ?></td>
                            <td><?php echo $row['quantity']; ?></td>
                            <td><?php echo $row['transaction_date']; ?></td>
                        </tr>
                        <?php } ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
