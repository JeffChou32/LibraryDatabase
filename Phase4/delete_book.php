<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $book_id = intval($_POST['book_id']);
    
    $sql = "DELETE FROM Books WHERE book_id = $book_id";
    
    if ($conn->query($sql)) {
        $success = "Book deleted successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Delete Book</title>
</head>
<body>
    <h2>Delete Book</h2>
    
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="delete_book.php">
        <label>Book ID to Delete:</label>
        <input type="number" name="book_id" required><br>
        <input type="submit" value="Delete Book">
    </form>
    
    <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>