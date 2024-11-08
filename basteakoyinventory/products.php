<?php
include_once("connections/connection.php");
$con = connection();
$sql = "SELECT * FROM products";
$products = $con->query($sql) or die($con->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Management</title>
    <link rel="stylesheet" href="css/products.css">
    <script src="js/confirmationdialog.js"></script>
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="logo">
                <img src="img/logo.png" alt="BasTeakoy Logo">
                <h2>Menu</h2>
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="utilities.php">Utilities</a></li>
                    <li><a href="products.php">Products</a></li>
                    <li><a href="sales.php">Sales</a></li>
                    <li><a href="attendance.html">Attendance</a></li>
                </ul>
                <div class="logout">
                    <a href="logout.php">Log out</a>
                </div>
            </nav>
        </aside>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Product Management</h1>
            </header>
            <section class="dashboard-content">
                <div class="product-management">
                    <h2>Manage Products</h2>
                    <button id="addProductBtn" class="btn-add">Add Product</button>
                    <table id="productTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Product Name</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Stock Quantity</th>
                                <th>Size</th>
                                <th>Cups</th> <!-- New Cups Column -->
                                <th>Status</th>
                                <th>Actions</th> <!-- Ensure Actions is the last column -->
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $products->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['id']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo htmlspecialchars($row['price']); ?></td>
                                <td><?php echo htmlspecialchars($row['stock']); ?></td>
                                <td><?php echo htmlspecialchars($row['size']); ?></td>
                                <td><?php echo htmlspecialchars($row['cups']); ?></td> <!-- Display Cups -->
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                                <td>
                                    <button class="edit-btn" 
                                        data-id="<?php echo $row['id']; ?>"
                                        data-name="<?php echo htmlspecialchars($row['name']); ?>"
                                        data-category="<?php echo htmlspecialchars($row['category']); ?>"
                                        data-price="<?php echo htmlspecialchars($row['price']); ?>"
                                        data-stock="<?php echo htmlspecialchars($row['stock']); ?>"
                                        data-status="<?php echo htmlspecialchars($row['status']); ?>"
                                        data-size="<?php echo htmlspecialchars($row['size']); ?>"
                                        data-cups="<?php echo htmlspecialchars($row['cups']); ?>">Edit</button>
                                    <button class="delete-btn" data-id="<?php echo $row['id']; ?>">Delete</button>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <div id="productModal" class="modal">
                        <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2 id="modalTitle">Add Product</h2>
                            <form id="productForm" method="POST" action="update_product.php">
                                <label for="productName">Product Name</label>
                                <input type="text" id="productName" name="name" required>

                                <label for="category">Category</label>
                                <input type="text" id="category" name="category" required>

                                <label for="price">Price</label>
                                <input type="number" id="price" name="price" required>

                                <label for="stock">Stock Quantity</label>
                                <input type="number" id="stock" name="stock" required>

                                <label for="size">Size</label>
                                <input type="text" id="size" name="size" required>

                                <label for="cups">Cups</label> <!-- New Cups Input -->
                                <input type="number" id="cups" name="cups" required>

                                <label for="status">Status</label>
                                <select id="status" name="status" required>
                                    <option value="Available">Available</option>
                                    <option value="Not Available">Not Available</option>
                                </select>

                                <input type="hidden" id="productId" name="id">
                                <button type="submit" class="btn-save">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </main>
    </div>
    <script src="js/products.js"></script>
</body>
</html>
