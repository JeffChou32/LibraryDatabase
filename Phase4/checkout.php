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
    $user_id = $_SESSION['user']['user_id'];
    $book_id = intval($_POST['book_id']);
    $due_date = date('Y-m-d', strtotime('+14 days')); 

    
    $check_sql = "SELECT copies_available FROM Books WHERE book_id = $book_id";
    $available = $conn->query($check_sql)->fetch_assoc()['copies_available'];

    if ($available > 0) {
        
        $checkout_sql = "INSERT INTO Checkout (user_id, book_id, action, transaction_date, due_date)
                        VALUES ($user_id, $book_id, 'checkout', NOW(), '$due_date')";
        
        if ($conn->query($checkout_sql)) {
            $transaction_id = $conn->insert_id; 
            
           
            $update_sql = "UPDATE Books SET copies_available = copies_available - 1 
                          WHERE book_id = $book_id";
            $conn->query($update_sql);
            
           
            $success = "Book checked out successfully!<br>
                       Transaction ID: <strong>$transaction_id</strong><br>
                       Due Date: $due_date";
        } else {
            $error = "Checkout failed: " . $conn->error;
        }
    } else {
        $error = "No copies available!";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout Book</title>
    <style>
        .success { color: green; font-weight: bold; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Checkout Book</h2>
    
    <?php if ($error): ?>
        <p class="error"><?php echo $error; ?></p>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="success"><?php echo $success; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="checkout.php">
        <label>Book ID:</label>
        <input type="number" name="book_id" required><br>
        <input type="submit" value="Checkout">
    </form>
    
    <p><a href="search_books.php">← Back to Search</a></p>
</body>
</html>