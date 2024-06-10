<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Management System - Crop Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- <link rel="stylesheet" href="bootstrap.css"> -->
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f5f5f5;
        }

        .container-fluid {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin: 0;
            font-size: 20px;
            color: #333;
        }

        .navigation {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding: 10px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .navigation a {
            text-decoration: none;
            color: #333;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .navigation a:hover {
            background-color: #eee;
        }

        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 20px;
            padding: 20px;
            /* width: 100%; */
        }

        .table-container {
            overflow-x: auto;
            background-color: #fff;
            /* padding: 20px; */
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        table {
            border-collapse: collapse;
            width: 100%;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #fff;
        }

        th,
        td {
            padding: 15px;
            border-bottom: 1px solid #ddd;
            transition: background-color 0.3s;
        }

        th {
            text-align: left;
            background-color: #f8f8f8;
            cursor: pointer;
        }

        th:hover {
            background-color: #f1f1f1;
        }

        tr:hover td {
            background-color: #f1f1f1;
        }

        .actions {
            display: flex;
            gap: 5px;
        }

        .actions a {
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 3px;
            transition: background-color 0.3s, color 0.3s;
        }

        .actions a.edit {
            background-color: #ffc107;
            color: #333;
        }

        .actions a.edit:hover {
            background-color: #ffa800;
        }

        .actions a.delete {
            background-color: #dc3545;
            color: #fff;
        }

        .actions a.delete:hover {
            background-color: #c8233c;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.4);
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        .modal-content {
            background-color: #fff;
            margin: 10% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 8px;
            animation: slideIn 0.3s;
        }

        @keyframes slideIn {
            from {
                transform: translateY(-50px);
            }

            to {
                transform: translateY(0);
            }
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
        }

        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltiptext {
            visibility: hidden;
            width: 120px;
            background-color: black;
            color: #fff;
            text-align: center;
            border-radius: 6px;
            padding: 5px;
            position: absolute;
            z-index: 1;
            bottom: 125%;
            left: 50%;
            margin-left: -60px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .tooltip:hover .tooltiptext {
            visibility: visible;
            opacity: 1;
        }

        .chart-container {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        @media (max-width: 768px) {

            .header,
            .navigation,
            .main-content {
                padding: 10px;
            }

            .modal-content {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <header class="header">
            <a href="#" onclick="loadContent('dashboard.php')">Dashboard</a>
            <h1>Agricultural Management System - Crop Management</h1>
        </header>

        <nav class="navigation">
            <a href="#" id="add-crop-btn">Add New Crop</a>
        </nav>

        <main class="main-content">
            <h2>Crop List</h2>
            <div class="table-container">
                <table id="crop-table">
                    <thead>
                        <tr>
                            <th>Crop Type</th>
                            <th>Planting Date</th>
                            <th>Harvest Date (Estimated)</th>
                            <th>Land Area (ha)</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="crop-data">
                        <?php
                        // Database connection
                        require 'connection.php';

                        // Fetch crop data
                        try {
                            $sql = "SELECT * FROM crop_management";
                            $stmt = $pdo->query($sql);

                            // Check if there are records
                            if ($stmt->rowCount() > 0) {
                                // Fetch each row as an associative array
                                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>" . htmlspecialchars($row['crop_type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['planting_date']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['harvest_date_estimated']) . "</td>";
                                    echo "<td>" . htmlspecialchars($row['land_area_ha']) . "</td>";
                                    echo "<td>";
                                    echo '<a class="btn btn-success" href="edit_crop.php?id=' . htmlspecialchars($row['id']) . '">Edit</a>';
                                    echo '<a class="btn btn-danger" href="delete_crop.php?id=' . htmlspecialchars($row['id']) . '" onclick="return confirm(\'Are you sure you want to delete this crop?\');">Delete</a>';
                                    echo "</td>";
                                    echo "</tr>";
                                }
                            } else {
                                echo "<tr><td colspan='5'>No crops found.</td></tr>";
                            }
                        } catch (PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <div id="edit-modal" class="modal">
                <div class="modal-content">
                    <span class="close">&times;</span>
                    <h2>Edit Crop Information</h2>
                    <form id="edit-crop-form">
                        <div class="form-group">
                            <label for="crop-type">Crop Type:</label>
                            <input type="text" id="crop-type" name="cropType" required>
                        </div>
                        <div class="form-group">
                            <label for="planting-date">Planting Date:</label>
                            <input type="date" id="planting-date" name="plantingDate" required>
                        </div>
                        <div class="form-group">
                            <label for="harvest-date-estimated">Harvest Date (Estimated):</label>
                            <input type="date" id="harvest-date-estimated" name="harvestDateEstimated" required>
                        </div>
                        <div class="form-group">
                            <label for="land-area">Land Area (ha):</label>
                            <input type="number" id="land-area" name="landArea" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="chart-container">
                <canvas id="crop-chart"></canvas>
            </div>
        </main>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var ctx = document.getElementById('crop-chart').getContext('2d');
            var cropChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Crop 1', 'Crop 2', 'Crop 3', 'Crop 4', 'Crop 5'],
                    datasets: [{
                        label: 'Harvest Amount (kg)',
                        data: [120, 150, 180, 100, 200],
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            var modal = document.getElementById("edit-modal");
            var closeModal = document.getElementsByClassName("close")[0];

            closeModal.onclick = function () {
                modal.style.display = "none";
            }

            window.onclick = function (event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            }

            document.getElementById('add-crop-btn').onclick = function () {
                modal.style.display = "block";
            }
        });
    </script>
</body>

</html>