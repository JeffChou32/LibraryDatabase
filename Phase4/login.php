<?php
// login.php - Add this at the VERY TOP (line 1)

// Start PHP session and include database config
session_start();
require_once 'config.php';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password']; // Note: In production, hash this password
    
    // Query database
    $sql = "SELECT * FROM Users WHERE email = '$email'";
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        // Login successful
        $_SESSION['user'] = $result->fetch_assoc();
        
        // Redirect based on role
        if ($_SESSION['user']['role'] == 'admin') {
            header("Location: admin_dashboard.php");
        } else {
            header("Location: search_books.php");
        }
        exit();
    } else {
        $error = "Invalid email or password!";
    }
}
?>


<html>
<head>
    <title>Login</title>
    <style>
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Login</h2>
    
    <?php if (isset($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="login.php">
        <label>Email:</label>
        <input type="email" name="email" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
    
    <p><strong>Simulated:</strong> Continue as:</p>
    <ul>
        <li><a href="admin_dashboard.php">Admin</a></li>
        <li><a href="search_books.php">User</a></li>
    </ul>
</body>
</html>