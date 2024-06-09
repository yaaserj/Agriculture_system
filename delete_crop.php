<?php
// delete_crop.php

// Include database connection
require 'connection.php';

// Check if crop ID is provided
if (isset($_GET['id'])) {
    $cropId = $_GET['id'];

    // Confirm deletion
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Delete the crop from the database
        $stmt = $pdo->prepare("DELETE FROM crop_management WHERE id = :id");
        $stmt->bindParam(':id', $cropId);
        $stmt->execute();

        // Redirect to the crop list page
        header("Location: cropmanagement.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Crop</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Delete Crop</h1>
        <p>Are you sure you want to delete this crop?</p>
        <form method="post">
            <button type="submit" class="btn btn-danger">Delete</button>
            <a href="cropmanagement.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>