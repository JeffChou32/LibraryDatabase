<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $transaction_id = intval($_POST['transaction_id']);
    
    $check_sql = "SELECT book_id FROM Checkout 
                 WHERE transaction_id = ? 
                 AND return_date IS NULL";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("i", $transaction_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $book_id = $result->fetch_assoc()['book_id'];
        
        $return_sql = "UPDATE Checkout SET 
                      action = 'return',
                      return_date = NOW() 
                      WHERE transaction_id = ?";
        $stmt = $conn->prepare($return_sql);
        $stmt->bind_param("i", $transaction_id);
        
        if ($stmt->execute()) {
            $update_sql = "UPDATE Books 
                          SET copies_available = copies_available + 1 
                          WHERE book_id = ?";
            $stmt = $conn->prepare($update_sql);
            $stmt->bind_param("i", $book_id);
            $stmt->execute();
            
            $success = "Book returned successfully!";
        } else {
            $error = "Error processing return: " . $conn->error;
        }
    } else {
        $error = "Invalid or already returned transaction ID!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Return Book</title>
    <style>
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Return Book</h2>
    
    <?php if ($error): ?>
        <p class="error"><?= $error ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <p class="success"><?= $success ?></p>
    <?php endif; ?>
    
    <form method="POST" action="return_book.php">
        <label>Transaction ID:</label>
        <input type="number" name="transaction_id" required><br>
        <input type="submit" value="Return Book">
    </form>
    
    <p><a href="search_books.php">← Back to Search</a></p>
</body>
</html>