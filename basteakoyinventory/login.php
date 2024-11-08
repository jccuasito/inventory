<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if ($con->connect_error) {
    die("Connection failed: " . $con->connect_error);
}

date_default_timezone_set('Asia/Manila'); // Set timezone
$error = '';

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query the database for the user by username
    $stmt = $con->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify the password using password_verify
        if (password_verify($password, $user['password'])) {
            // Store user data in session
            $_SESSION['username'] = $username;
            $_SESSION['user_id'] = $user['id']; // Store user_id in session
            $_SESSION['user_type'] = $user['type']; // Store user_type (admin or staff)

            $user_id = $user['id'];
            $login_time = date("Y-m-d H:i:s");

            // Log the login activity
            $stmt = $con->prepare("INSERT INTO user_activity (user_id, login_time) VALUES (?, ?)");
            $stmt->bind_param("is", $user_id, $login_time);
            $stmt->execute();
            $stmt->close();

            // Redirect based on user type
            if ($_SESSION['user_type'] == 'admin') {
                header("Location: index.php"); // Redirect admin to the admin dashboard
            } else {
                header("Location: staffindex.php"); // Redirect staff to the staff dashboard
            }
            exit(); // Stop further script execution
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "User not found.";
    }
    $stmt->close();
}

$con->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Basteakoy Inventory Management System</title>
    <link rel="stylesheet" href="css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <div class="overlay">
            <h1 class="title">BASTEAKOY</h1>
            <h2 class="subtitle">INVENTORY MANAGEMENT SYSTEM</h2>
            <div class="login-box">
                <form action="" method="POST">
                    <label for="username">USERNAME</label>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                    <label for="password">PASSWORD</label>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <button type="submit" name="login" class="login-btn">LOGIN</button>
                    <?php if ($error): ?>
                        <p style="color: red;"><?php echo $error; ?></p>
                    <?php endif; ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
