<?php
require_once 'config.php';
session_start();

$allowed_roles = ['admin'];
if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], $allowed_roles)) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['user']['name']); ?></p>
    
    <ul>
        <li><a href="add_book.php">Add Book</a></li>
        <li><a href="update_book.php">Update Book</a></li>
        <li><a href="delete_book.php">Delete Book</a></li>
        <li><a href="view_logs.php">View Logs</a></li>
    </ul>
    
    <p><a href="logout.php">Logout</a></p>
</body>
</html>