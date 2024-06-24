<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'connection.php'; // Include your database connection file

try {

    $id = $_GET['id'];

    $stmt = $pdo->prepare('DELETE FROM users WHERE user_id = ?');
    $stmt->execute([$id]);

    header('Location: users.php');
    exit;
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>