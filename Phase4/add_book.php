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
    $title = $conn->real_escape_string($_POST['title']);
    $author = $conn->real_escape_string($_POST['author']);
    $genre = $conn->real_escape_string($_POST['genre']);
    $isbn = $conn->real_escape_string($_POST['isbn']);
    $year = intval($_POST['publication_year']);
    $copies = intval($_POST['copies']);
    $location = $conn->real_escape_string($_POST['shelf_location']);

    $sql = "INSERT INTO Books (title, author, genre, isbn, publication_year, copies_available, shelf_location)
            VALUES ('$title', '$author', '$genre', '$isbn', $year, $copies, '$location')";

    if ($conn->query($sql)) {
        $success = "Book added successfully!";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
</head>
<body>
    <h2>Add New Book</h2>
    
    <?php if ($error): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p style="color:green;"><?php echo $success; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="add_book.php">
        <label>Title:</label>
        <input type="text" name="title" required><br>
        
        <label>Author:</label>
        <input type="text" name="author" required><br>
        
        <label>Genre:</label>
        <input type="text" name="genre"><br>
        
        <label>ISBN:</label>
        <input type="text" name="isbn" required><br>
        
        <label>Publication Year:</label>
        <input type="number" name="publication_year" required><br>
        
        <label>Copies:</label>
        <input type="number" name="copies" value="1"><br>
        
        <label>Shelf Location:</label>
        <input type="text" name="shelf_location"><br>
        
        <input type="submit" value="Add Book">
    </form>
    
    <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>