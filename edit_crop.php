<?php
// edit_crop.php

// Include database connection
require 'connection.php';

// Check if crop ID is provided
if (isset($_GET['id'])) {
    $cropId = $_GET['id'];

    // Fetch crop details from the database
    $stmt = $pdo->prepare("SELECT * FROM crop_management WHERE id = :id");
    $stmt->bindParam(':id', $cropId);
    $stmt->execute();
    $crop = $stmt->fetch(PDO::FETCH_ASSOC);

    // Display edit form
    if ($crop) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Get form data
            $cropType = $_POST['cropType'];
            $plantingDate = $_POST['plantingDate'];
            $harvestDate = $_POST['harvestDate'];
            $landArea = $_POST['landArea'];

            // Update crop details in the database
            $stmt = $pdo->prepare("UPDATE crop_management SET crop_type = :cropType, planting_date = :plantingDate, harvest_date_estimated = :harvestDate, land_area_ha = :landArea WHERE id = :id");
            $stmt->bindParam(':cropType', $cropType);
            $stmt->bindParam(':plantingDate', $plantingDate);
            $stmt->bindParam(':harvestDate', $harvestDate);
            $stmt->bindParam(':landArea', $landArea);
            $stmt->bindParam(':id', $cropId);
            $stmt->execute();

            // Redirect to the crop list page
            header("Location: cropmanagement.php");
            exit();
        }
    } else {
        echo "Crop not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Crop</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container">
        <h1>Edit Crop</h1>
        <form method="post">
            <div class="form-group">
                <label for="cropType">Crop Type:</label>
                <input type="text" class="form-control" id="cropType" name="cropType"
                    value="<?php echo htmlspecialchars($crop['crop_type']); ?>" required>
            </div>
            <div class="form-group">
                <label for="plantingDate">Planting Date:</label>
                <input type="date" class="form-control" id="plantingDate" name="plantingDate"
                    value="<?php echo htmlspecialchars($crop['planting_date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="harvestDate">Harvest Date (Estimated):</label>
                <input type="date" class="form-control" id="harvestDate" name="harvestDate"
                    value="<?php echo htmlspecialchars($crop['harvest_date_estimated']); ?>" required>
            </div>
            <div class="form-group">
                <label for="landArea">Land Area (ha):</label>
                <input type="number" step="0.01" class="form-control" id="landArea" name="landArea"
                    value="<?php echo htmlspecialchars($crop['land_area_ha']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
            <a href="cropmanagement.php" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
</body>

</html>