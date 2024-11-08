<?php
session_start();
include_once("connections/connection.php");
$con = connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $userId = $_POST['id'];
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null; // Hash the password only if provided
    $type = $_POST['type'];

    // Update user information
    if ($password) {
        // If password is provided, update username and password
        $stmt = $con->prepare("UPDATE users SET username=?, password=?, type=? WHERE id=?");
        $stmt->bind_param("sssi", $username, $password, $type, $userId);
    } else {
        // If password is not provided, update only username and type
        $stmt = $con->prepare("UPDATE users SET username=?, type=? WHERE id=?");
        $stmt->bind_param("ssi", $username, $type, $userId);
    }
    
    $stmt->execute();
    $stmt->close();

    echo "<script>alert('User updated successfully!');</script>";
    exit;
}

$userId = $_GET['id'];
$stmt = $con->prepare("SELECT username, type FROM users WHERE id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://fonts.googleapis.com/css2?family=General+Sans:wght@400;500;600&display=swap" rel="stylesheet">
</head>
<body>
    <div class="form-container">
        <h2>Edit User</h2>
        <form method="POST" action="edit_user.php">
            <input type="hidden" name="id" value="<?php echo $userId; ?>">
            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            
            <label for="password">New Password (leave blank if not changing):</label>
            <input type="password" name="password">
            
            <label for="type">User Type:</label>
            <select name="type" required>
                <option value="admin" <?php echo $user['type'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
                <option value="staff" <?php echo $user['type'] == 'staff' ? 'selected' : ''; ?>>Staff</option>
            </select>
            
            <button type="submit">Update User</button>
        </form>
    </div>
</body>
</html>
