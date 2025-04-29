<?php

require_once 'config.php';
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$books = [];
if ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)) {
    $conditions = [];
    $params = [];
    
    if (!empty($_GET['title'])) {
        $conditions[] = "title LIKE ?";
        $params[] = '%' . $_GET['title'] . '%';
    }
    
    if (!empty($_GET['author'])) {
        $conditions[] = "author LIKE ?";
        $params[] = '%' . $_GET['author'] . '%';
    }
    
    if (!empty($_GET['genre'])) {
        $conditions[] = "genre LIKE ?";
        $params[] = '%' . $_GET['genre'] . '%';
    }
    
    if (!empty($_GET['isbn'])) {
        $conditions[] = "isbn = ?";
        $params[] = $_GET['isbn'];
    }

    $sql = "SELECT * FROM Books";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        if (!empty($params)) {
            $types = str_repeat('s', count($params));
            $stmt->bind_param($types, ...$params);
        }
        
        $stmt->execute();
        $result = $stmt->get_result();
        $books = $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Books</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <h2>Search Books</h2>
    <form method="GET" action="search_books.php">
        <label>Title:</label>
        <input type="text" name="title" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>"><br>
        
        <label>Author:</label>
        <input type="text" name="author" value="<?= htmlspecialchars($_GET['author'] ?? '') ?>"><br>
        
        <label>Genre:</label>
        <input type="text" name="genre" value="<?= htmlspecialchars($_GET['genre'] ?? '') ?>"><br>
        
        <label>ISBN:</label>
        <input type="text" name="isbn" value="<?= htmlspecialchars($_GET['isbn'] ?? '') ?>"><br>
        
        <input type="submit" value="Search">
    </form>
    
    <hr>
    
    <?php if (!empty($books)): ?>
        <h3>Search Results</h3>
        <table>
            <thead>
                <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Author</th>
                <th>Genre</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Available</th>
                <th>Location</th>
                <th>Action</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($books as $book): ?>
            <tr>
                <td><?= htmlspecialchars($book['book_id']) ?></td>
                <td><?= htmlspecialchars($book['title']) ?></td>
                <td><?= htmlspecialchars($book['author']) ?></td>
                <td><?= htmlspecialchars($book['genre']) ?></td>
                <td><?= htmlspecialchars($book['isbn']) ?></td>
                <td><?= htmlspecialchars($book['publication_year']) ?></td>
                <td><?= htmlspecialchars($book['copies_available']) ?></td>
                <td><?= htmlspecialchars($book['shelf_location']) ?></td>
                <td>
                    <?php if ($book['copies_available'] > 0): ?>
                        <a href="checkout.php?book_id=<?= $book['book_id'] ?>">Checkout</a>
                    <?php else: ?>
                        <span style="color:red;">Unavailable</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php elseif ($_SERVER["REQUEST_METHOD"] == "GET" && !empty($_GET)): ?>
        <p>No books found matching your criteria.</p>
    <?php endif; ?>
    
    <hr>
    <p><strong>Quick Links:</strong></p>
    <ul>
        <li><a href="checkout.php">Checkout a Book</a></li>
        <li><a href="return_book.php">Return a Book</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</body>
</html>           