<?php
session_start();
require_once 'config.php';

if (isset($_SESSION['user'])) {
    header("Location: " . ($_SESSION['user']['role'] == 'admin' ? 'admin_dashboard.php' : 'search_books.php'));
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Library Home</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            margin: 0;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }
        h1 {
            color: #2c3e50;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        nav {
            margin: 20px 0;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        nav a {
            margin-right: 15px;
            text-decoration: none;
            color: #2980b9;
            font-weight: bold;
        }
        nav a:hover {
            color: #e74c3c;
        }
        .welcome-message {
            background: #e8f4fc;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Welcome to the Library Management System</h1>
    
    <?php if (isset($_GET['logout'])): ?>
        <div class="welcome-message">
            You have been successfully logged out.
        </div>
    <?php endif; ?>
    
    <nav>
        <a href="login.php">Login</a> | 
        <a href="search_books.php">Search Books</a>
    </nav>
    
    
</body>
</html>