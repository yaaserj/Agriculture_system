<?php
require 'connection.php';

try {
    // Establish database connection
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Query to fetch count of each crop type
    $stmt = $pdo->query("SELECT crop_type, COUNT(*) as count FROM crop_management GROUP BY crop_type");
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Return data as JSON
    echo json_encode($data);
} catch (PDOException $e) {
    // Return error message if connection fails
    echo 'Connection failed: ' . $e->getMessage();
}

?>