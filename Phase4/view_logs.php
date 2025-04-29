<?php
require_once 'config.php';
session_start();

if (!isset($_SESSION['user']) || !in_array($_SESSION['user']['role'], ['admin', 'faculty'])) {
    header("Location: login.php");
    exit();
}

$logs = $conn->query("SELECT * FROM Checkout ORDER BY transaction_date DESC");
?>
<!DOCTYPE html>
<html>
<head>
    <title>View Logs</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Borrowing Logs</h2>
    
    <table>
        <tr>
            <th>Transaction ID</th>
            <th>User ID</th>
            <th>Book ID</th>
            <th>Action</th>
            <th>Date</th>
            <th>Due Date</th>
            <th>Return Date</th>
        </tr>
        <?php while ($log = $logs->fetch_assoc()): ?>
        <tr>
            <td><?php echo $log['transaction_id']; ?></td>
            <td><?php echo $log['user_id']; ?></td>
            <td><?php echo $log['book_id']; ?></td>
            <td><?php echo $log['action']; ?></td>
            <td><?php echo $log['transaction_date']; ?></td>
            <td><?php echo $log['due_date']; ?></td>
            <td><?php echo $log['return_date'] ?: 'Not returned'; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    
    <p><a href="admin_dashboard.php">← Back to Dashboard</a></p>
</body>
</html>