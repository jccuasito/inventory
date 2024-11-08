<?php
session_start(); // Start the session
include_once("connections/connection.php");
$con = connection();

// Fetch users from the database
$sql = "SELECT id, username, type FROM users";
$users = $con->query($sql) or die($con->error);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Utilities</title>
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/dashboard.css">
    <style>
        /* Simple modal styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
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
                    <li><a href="index.php" onclick="showSection('home')">Home</a></li>
                    <li><a href="utilities.php" onclick="showSection('utilities')">Utilities</a></li>
                    <li><a href="products.php" onclick="showSection('products')">Products</a></li>
                    <li><a href="sales.php" onclick="showSection('sales')">Sales</a></li>
                    <li><a href="attendance.html" onclick="showSection('attendance')">Attendance</a></li>
                </ul>
            </nav>
            <div class="logout">
                <a href="logout.php">Log out</a>
            </div>
        </aside>
        <main class="main-content">
            <header class="dashboard-header">
                <h1>Admin Dashboard</h1>
            </header>
            <section id="dashboardContent" class="dashboard-content home-background">
                <div id="home" class="section active">
                    <h2>User Management</h2>
                    <button onclick="openModal('addUserModal')">Add User</button>
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($users->num_rows > 0): ?>
                                <?php while ($row = $users->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['id']); ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo htmlspecialchars($row['type']); ?></td>
                                        <td>
                                            <button onclick="openModal('editUserModal', 'edit_user.php?id=<?php echo $row['id']; ?>')">Edit</button> |
                                            <button onclick="openModal('viewUserModal', 'view_user.php?id=<?php echo $row['id']; ?>')">View Activity</button> |
                                            <button onclick="confirmDelete(<?php echo $row['id']; ?>)">Delete</button>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4">No users found.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </main>
    </div>

    <!-- Add User Modal -->
    <div id="addUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('addUserModal')">&times;</span>
            <h2>Add User</h2>
            <form method="POST" action="add_user.php">
                <label for="username">Username:</label>
                <input type="text" name="username" required>
                <label for="password">Password:</label>
                <input type="password" name="password" required>
                <label for="type">User Type:</label>
                <select name="type" required>
                    <option value="admin">Admin</option>
                    <option value="staff">Staff</option>
                </select>
                <button type="submit">Add User</button>
            </form>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('editUserModal')">&times;</span>
            <iframe id="editUserFrame" src="" width="100%" height="400px" style="border:none;"></iframe>
        </div>
    </div>

    <!-- View User Activity Modal -->
    <div id="viewUserModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal('viewUserModal')">&times;</span>
            <iframe id="viewUserFrame" src="" width="100%" height="400px" style="border:none;"></iframe>
        </div>
    </div>

    <script>
    function openModal(modalId, src) {
        const modal = document.getElementById(modalId);
        const frame = document.getElementById(modalId === 'editUserModal' ? 'editUserFrame' : 'viewUserFrame');
        if (src) {
            frame.src = src; // Set the iframe source if applicable
        }
        modal.style.display = "block"; // Show the modal
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        modal.style.display = "none"; // Hide the modal
        const frame = document.getElementById(modalId === 'editUserModal' ? 'editUserFrame' : 'viewUserFrame');
        frame.src = ""; // Clear the iframe source
    }

    function confirmDelete(userId) {
        if (confirm("Are you sure you want to delete this user?")) {
            window.location.href = 'delete_user.php?id=' + userId; // Redirect to delete script
        }
    }

    // Close modal when clicking outside of the modal
    window.onclick = function(event) {
        const editModal = document.getElementById('editUserModal');
        const viewModal = document.getElementById('viewUserModal');
        const addModal = document.getElementById('addUserModal');
        if (event.target === editModal) {
            closeModal('editUserModal');
        }
        if (event.target === viewModal) {
            closeModal('viewUserModal');
        }
        if (event.target === addModal) {
            closeModal('addUserModal');
        }
    }
    </script>
</body>
</html>
