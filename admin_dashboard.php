<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

echo "Welcome to the admin dashboard, " . $_SESSION['username'] . "!";
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Management System - Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
        }

        .container {
            padding: 20px;
        }

        .header {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 10px 20px;
        }

        .navigation {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        .card {
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-bottom: 20px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            border-radius: 10px;
            width: 80%;
            max-width: 500px;
        }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>Agricultural Management System</h1>
            <a href="profilemanagement.php">
                <img src="logo.png" alt="Logo" width="50" height="50">
            </a>
            <a href="#">Dashboard</a>
            <a href="logout.php">Logout</a>
            <!-- Add a menu toggle button for smaller screens -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </header>

        <!-- Navigation -->
        <nav class="navigation collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" href="./transactions.php">Record Transaction</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./inventorymanagement.php">View Inventory</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./reports.php">Generate Reports</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./landmanagement.php">Land Management</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./cropmanagement.php">Crop Management</a>
                </li>
            </ul>
        </nav>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Content will be loaded here based on the selected menu item -->
        </main>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Your custom JavaScript -->
        <script>
            // Your existing JavaScript code here
        </script>
    </div>
</body>

</html>