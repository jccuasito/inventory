<?php
session_start();
include_once("connections/connection.php");
$con = connection();

// Check if the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Add User
if (isset($_POST['add_user'])) {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $type = $_POST['type'];

    $query = "INSERT INTO Users (first_name, last_name, contact_number, email, password, type) 
              VALUES ('$firstName', '$lastName', '$contactNumber', '$email', '$password', '$type')";

    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Add user successful";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($con);
    }
}

// Update User (including password check)
if (isset($_POST['edit_user'])) {
    $userId = $_POST['users_id'];
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $contactNumber = $_POST['contact_number'];
    $email = $_POST['email'];
    $type = $_POST['type'];

    // Check if password is provided
    if (!empty($_POST['password'])) {
        // Hash the new password
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = "UPDATE Users SET first_name='$firstName', last_name='$lastName', contact_number='$contactNumber', email='$email', password='$password', type='$type' WHERE users_id='$userId'";
    } else {
        // If no password, update without changing it
        $query = "UPDATE Users SET first_name='$firstName', last_name='$lastName', contact_number='$contactNumber', email='$email', type='$type' WHERE users_id='$userId'";
    }

    // Execute query and check for success
    if (mysqli_query($con, $query)) {
        $_SESSION['message'] = "Updated user successfully";
    } else {
        $_SESSION['message'] = "Error: " . mysqli_error($con);
    }
}


// Fetch Users
$query = "SELECT * FROM Users";
$users_result = mysqli_query($con, $query);

// Fetch User Activity
$activity_query = "SELECT * FROM User_Activity"; // Assuming you have a User_Activity table
$activity_result = mysqli_query($con, $activity_query);

// Fetch Products Data with User Names
$query = "
    SELECT 
        p.product_id, 
        u1.first_name AS edit_by_first_name, 
        u1.last_name AS edit_by_last_name, 
        u2.first_name AS last_edit_by_first_name, 
        u2.last_name AS last_edit_by_last_name, 
        p.product_last_edited 
    FROM products p 
    LEFT JOIN Users u1 ON p.product_edit_by = u1.users_id 
    LEFT JOIN Users u2 ON p.product_last_edit_by = u2.users_id";
$products_result = mysqli_query($con, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Utilities</title>
    <link rel="stylesheet" href="css/utilities.css">
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
        </aside>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Utilities</h1>
            </header>
            
            <!-- User List -->
            <section id="userList">
                <h2>BasTeakoy Employee Activity</h2>
                <button id="addUserBtn">Add User</button>
                <table>
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Contact Number</th>
                            <th>Email</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($users_result)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['first_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['contact_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['type']); ?></td>
                                <td>
                                    <!-- Action buttons in a flex container -->
                                    <button class="edit-btn"
                                            data-id="<?php echo $row['users_id']; ?>"
                                            data-first-name="<?php echo htmlspecialchars($row['first_name']); ?>"
                                            data-last-name="<?php echo htmlspecialchars($row['last_name']); ?>"
                                            data-contact-number="<?php echo htmlspecialchars($row['contact_number']); ?>"
                                            data-email="<?php echo htmlspecialchars($row['email']); ?>"
                                            data-type="<?php echo htmlspecialchars($row['type']); ?>"
                                            data-password="<?php echo htmlspecialchars($row['password']); ?>">
                                        Edit
                                    </button>

                                        
                                        <!-- Delete form with confirmation -->
                                        <form method="POST" action="delete_user.php" onsubmit="return confirm('Are you sure you want to delete this user?');" style="display:inline-block;">
                                            <input type="hidden" name="users_id" value="<?php echo $row['users_id']; ?>">
                                            <button type="submit" class="delete-btn">Delete</button>
                                        </form>

                                        <button class="view-activity-btn" data-id="<?php echo $row['users_id']; ?>">View Activity</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>

            <!-- Modal for Add/Edit User -->
            <div id="userModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2 id="modalTitle">Add User</h2>
                    <form id="userForm" method="POST" action="utilities.php">
                        <input type="hidden" id="users_id" name="users_id">

                        <label for="first_name">First Name:</label>
                        <input type="text" id="first_name" name="first_name" required>

                        <label for="last_name">Last Name:</label>
                        <input type="text" id="last_name" name="last_name" required>

                        <label for="contact_number">Contact Number:</label>
                        <input type="text" id="contact_number" name="contact_number" required>

                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>

                        <label for="password" id="passwordLabel">Password:</label>
                        <input type="password" id="password" name="password">

                        <label for="type">Type:</label>
                        <select id="type" name="type" required>
                            <option value="cashier">Cashier</option>
                            <option value="admin">Admin</option>
                        </select>

                        <button type="submit" name="add_user" id="saveBtn">Save</button>
                    </form>
                </div>
            </div>

            <!-- Modal for View Activity -->
            <div id="activityModal" class="modal" style="display:none;">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>User Activity</h2>
                    
                    <!-- Table to display login/logout activity -->
                    <table id="activityTable">
                        <thead>
                            <tr>
                                <th>Login Time</th>
                                <th>Logout Time</th>
                            </tr>
                        </thead>
                        <tbody id="activityBody">
                            <!-- Data will be injected here by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Product List -->
            <section id="productList">
                <h2>Product Editing Audit</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Edited By</th>
                            <th>Product Last Edited By</th>
                            <th>Product Last Edited Time</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($products_result)) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['product_id']); ?></td>
                                <td><?php echo htmlspecialchars($row['edit_by_first_name'] . ' ' . $row['edit_by_last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['last_edit_by_first_name'] . ' ' . $row['last_edit_by_last_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['product_last_edited']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </section>


        </main>
    </div>

    <script src="js/utilities.js"></script> <!-- Include JavaScript at the end of the body -->
    <script>
    // Check if there's a message stored in the session
    <?php if (isset($_SESSION['message'])): ?>
        alert("<?php echo $_SESSION['message']; ?>");
        <?php unset($_SESSION['message']); ?> // Clear the session message after showing it
    <?php endif; ?>
    </script>
</body>
</html>
