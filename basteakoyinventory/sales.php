<?php
include_once("connections/connection.php");
$con = connection();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="css/sales.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo">
                <img src="img/logo.png" alt="BasTeakoy">
                <h2>Menu</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="utilities.php">Utilities</a></li>
                    <li><a href="products.html">Products</a></li>
                    <li><a href="sales.php">Sales</a></li>
                    <li><a href="attendance.html">Attendance</a></li>
                </ul>
            </nav>
            <div class="logout">
                <a href="index.html">Log out</a>
            </div>
        </aside>

        <!-- Main Content -->
        <section class="main-content">
            <div class="dashboard-header">
                <h1>ADMIN DASHBOARD</h1>
            </div>

            <div class="sales-report">
                <h2>Sales Report</h2>
                <div class="report-buttons">
                    <button class="report-btn" id="btnToday">Today</button>
                    <button class="report-btn" id="btnThisWeek">This Week</button>
                    <button class="report-btn" id="btnThisMonth">This Month</button>
                    <button class="report-btn" id="btnThisYear">This Year</button>
                </div>

                <div class="sales-summary">
                    <div class="sales-box">
                        <h3>Amount</h3>
                        <p id="amountTotal">₱0.00</p>
                    </div>
                    <div class="sales-box">
                        <h3>Expense</h3>
                        <p id="expenseTotal">₱0.00</p>
                    </div>
                    <div class="sales-box">
                        <h3>Total Product Sold</h3>
                        <p id="productTotal">0</p>
                    </div>
                </div>

                <div class="history-sales">
                    <h3>History Sales</h3>
                    <table id="salesTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Products</th>
                                <th>Size</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>04/18/2024</td>
                                <td>2 pcs Mango Graham, 3 pcs Strawberry</td>
                                <td>22oz, 16oz</td>
                            </tr>
                            <tr>
                                <td>04/18/2024</td>
                                <td>2 pcs Mango Graham, 3 pcs Strawberry</td>
                                <td>22oz, 16oz</td>
                            </tr>
                            <!-- Add more history sales as needed -->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
    <script src="sales.js"></script>
</body>
</html>
