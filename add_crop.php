<?php
// add_crop.php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Database connection details
    require 'connection.php';

    // Get form data
    $cropType = htmlspecialchars($_POST['cropType']);
    $plantingDate = htmlspecialchars($_POST['plantingDate']);
    $harvestDate = htmlspecialchars($_POST['harvestDateEstimated']);
    $landArea = htmlspecialchars($_POST['landArea']);

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO crop_management (crop_type, planting_date, harvest_date_estimated, land_area_ha) VALUES (:cropType, :plantingDate, :harvestDate, :landArea)";
        $stmt = $pdo->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':cropType', $cropType);
        $stmt->bindParam(':plantingDate', $plantingDate);
        $stmt->bindParam(':harvestDate', $harvestDate);
        $stmt->bindParam(':landArea', $landArea);

        // Execute the statement
        $stmt->execute();

        // Redirect to the main page
        header("Location: cropmanagement.php");
        exit();
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>