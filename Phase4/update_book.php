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
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $year = intval($_POST['publication_year']);
    
    $sql = "UPDATE Books SET 
            title = '$title',
            author = '$author',
            genre = '$genre',
            isbn = '$isbn',
            publication_year = $year
            WHERE book_id = $book_id";
    
    if ($conn->query($sql)) {
        $success = "Book updated successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Update Book</title>
</head>
<body>
    <h2>Update Book</h2>
    
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="update_book.php">
        <label>Book ID:</label>
        <input type="number" name="book_id" required><br>
        <label>New Title:</label>
        <input type="text" name="title"><br>
        <label>New Author:</label>
        <input type="text" name="author"><br>
        <label>New Genre:</label>
        <input type="text" name="genre"><br>
        <label>New ISBN:</label>
        <input type="text" name="isbn"><br>
        <label>New Publication Year:</label>
        <input type="number" name="publication_year"><br>
        <input type="submit" value="Update Book">
    </form>
    
    <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>