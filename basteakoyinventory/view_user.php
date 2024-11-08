<?php
session_start();
include_once("connections/connection.php");
$con = connection();

$userId = $_GET['id'];

// Fetch user activity
$stmt = $con->prepare("SELECT login_time, logout_time FROM user_activity WHERE user_id=?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$stmt->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Activity</title>
</head>
<body>
    <div class="activity-container">
        <h2>User Activity</h2>
        <table>
            <thead>
                <tr>
                    <th>Login Time</th>
                    <th>Logout Time</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['login_time']); ?></td>
                            <td><?php echo htmlspecialchars($row['logout_time']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="2">No activity found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
