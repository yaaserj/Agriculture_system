<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require 'connection.php';

// Check if user_id is set in the session
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page or handle the error
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Default profile image URL
$defaultProfileImage = 'default.png';

try {
    // Fetch the profile image URL from the database
    $stmt = $pdo->prepare('SELECT profile_picture FROM users WHERE user_id = :user_id');
    $stmt->execute(['user_id' => $userId]);
    $user = $stmt->fetch();

    // Check if the user has a profile image
    if (empty($user['profile_picture'])) {
        // Update the user's profile picture to the default image
        $stmt = $pdo->prepare('UPDATE users SET profile_picture = :profile_picture WHERE user_id = :user_id');
        $stmt->execute(['profile_picture' => $defaultProfileImage, 'user_id' => $userId]);
        $profileImage = 'images/' . $defaultProfileImage;
    } else {
        $profileImage = 'images/' . $user['profile_picture'];
    }

    // Fetch key metrics
    $stmt = $pdo->prepare('SELECT COUNT(*) AS total_users FROM users');
    $stmt->execute();
    $totalUsers = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) AS total_transactions FROM transactions');
    $stmt->execute();
    $totalTransactions = $stmt->fetchColumn();

    // $stmt = $pdo->prepare('SELECT COUNT(*) AS total_inventory FROM inventory');
    // $stmt->execute();
    // $totalInventory = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT SUM(amount) AS total_revenue FROM transactions');
    $stmt->execute();
    $totalRevenue = $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) AS total_crops FROM users');
    $stmt->execute();
    $totalCrops = $stmt->fetchColumn();

} catch (PDOException $e) {
    echo 'Query failed: ' . $e->getMessage();
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agrify - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css">

    <style>
        /* Add custom styles for branding, hover effects, etc. */
        .sidebar {
            padding: 20px;
            background-color: #f8f9fa;
            height: 100vh;
            position: fixed;
            top: 70px;
            /* Adjust based on header height */
            bottom: 0;
            left: 0;
            width: 250px;
            /* Fixed width for sidebar */
            z-index: 1000;
            overflow-y: auto;
        }

        .sidebar .nav-link {
            color: #333;
            font-weight: bold;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }

        .sidebar .nav-link.active {
            background-color: #007bff;
            color: white;
        }

        .header {
            padding: 10px 20px;
            background-color: #f8f9fa;
            border-bottom: 1px solid #ddd;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            height: 70px;
            /* Adjust header height */
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .profile-image {
            width: 50px;
            /* Set a specific width */
            height: 50px;
            /* Set a specific height */
            object-fit: cover;
            /* Ensure the image covers the container without stretching */
            border-radius: 50%;
            /* Optional: make it a circle */
        }

        .main-content {
            padding: 20px;
            margin-left: 250px;
            /* Adjust based on sidebar width */
            margin-top: 70px;
            /* Adjust based on header height */
            background-color: #F0FFF0;
        }

        .metric-card {
            padding: 20px;
            border-radius: 5px;
            background-color: rgb(255, 255, 255);
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
            text-align: center;
            transition: transform 0.3s, background-color 0.3s;
        }

        .metric-card:hover {
            transform: translateY(-5px);
            background-color: #f0f8ff;
            cursor: pointer;
        }

        .metric-card h3 {
            color: #007bff;
            font-weight: bold;
            font-size: 15px;
        }

        .metric-card p {
            color: #333;
            font-size: 1.5em;
        }

        #chart-container {
            width: 350px;
            /* Set your desired width */
            height: 350px;
            /* Set your desired height */
            background-color: rgb(255, 255, 255);
            border-radius: 10px;
        }

        /* Media query for small devices */
        @media (max-width: 768px) {
            .sidebar {
                width: 80%;
                max-width: 280px;
                transform: translateX(-100%);
                transition: transform 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <?php
    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
    ?>

    <header class="header d-flex justify-content-between align-items-center">
        <div>
            <h1>Agricultural Management System</h1>
        </div>
        <div class="text-end">
            <div class="dropdown d-inline-block ms-3">
                <a href="#" id="profileDropdown" class="dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <img src="<?php echo htmlspecialchars($profileImage); ?>" alt="Profile Image" class="profile-image">
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">
                    <li><a class="dropdown-item" href="profilemanagement.php">Profile Management</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </div>
            <!-- Add a menu toggle button for smaller screens -->
            <button class="navbar-toggler d-block d-md-none" type="button" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>

    <div class="sidebar">
        <div class="nav flex-column">
            <a href="adminpanel.php" class="nav-link active">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
            <a href="#" class="nav-link" onclick="loadContent('users.php')">
                <i class="bi bi-people"></i> Users
            </a>
            <a href="#" class="nav-link" onclick="loadContent('reports.php')">
                <i class="bi bi-file-earmark-bar-graph"></i> Reports
            </a>
            <a href="#" class="nav-link" onclick="loadContent('settings.php')">
                <i class="bi bi-gear"></i> Settings
            </a>
            <hr> <!-- Add a horizontal line for visual separation -->
            <a href="#" class="nav-link" onclick="loadContent('transactions.php')">
                <i class="bi bi-cash"></i> Record Transaction
            </a>
            <a href="#" class="nav-link" onclick="loadContent('inventorymanagement.php')">
                <i class="bi bi-box-seam"></i> View Inventory
            </a>
            <a href="#" class="nav-link" onclick="loadContent('reports.php')">
                <i class="bi bi-bar-chart"></i> Generate Reports
            </a>
            <a href="#" class="nav-link" onclick="loadContent('landmanagement.php')">
                <i class="bi bi-map"></i> Land Management
            </a>
            <a href="#" class="nav-link" onclick="loadContent('cropmanagement.php')">
                <i class="bi bi-tree"></i> Crop Management
            </a>
        </div>
    </div>

    <main class="main-content" id="main-content">
        <h2><?php echo "Welcome to the admin dashboard, " . htmlspecialchars($_SESSION['username']) . "!"; ?></h2>
        <div class="row">
            <div class="col-md-3 reduce-height">
                <div class="metric-card">
                    <i class="bi bi-person-fill"></i>
                    <h3>Total Users</h3>
                    <p><?php echo $totalUsers; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-credit-card"></i>
                    <h3>Total Transactions</h3>
                    <p><?php echo $totalTransactions; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-box"></i>
                    <h3>Total Inventory Items</h3>
                    <p><?php //echo $totalInventoryItems; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-bar-chart"></i>
                    <h3>Generated Reports</h3>
                    <p><?php //echo $generatedReports; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-map"></i>
                    <h3>Total Land</h3>
                    <p><?php //echo $totalLand; ?></p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="metric-card">
                    <i class="bi bi-tree"></i>
                    <h3>Total Crops</h3>
                    <p><?php echo $totalCrops; ?></p>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12" id="chart-container">
                    <canvas id="pieChart" width="400" height="400"></canvas>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
    <script>
        // Toggle sidebar for small devices
        function toggleSidebar() {
            document.querySelector('.sidebar').classList.toggle('show');
        }

        // Function to load content dynamically
        function loadContent(url) {
            fetch(url)
                .then(response => response.text())
                .then(data => {
                    document.getElementById('main-content').innerHTML = data;
                })
                .catch(error => console.error('Error loading content:', error));
        }
        document.addEventListener("DOMContentLoaded", function () {
            fetch('fetch_data.php')
                .then(response => response.json())
                .then(data => {
                    // Prepare data for the pie chart
                    const labels = data.map(item => item.crop_type);
                    const counts = data.map(item => item.count);

                    // Create pie chart
                    const ctx = document.getElementById('pieChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Crop Types',
                                data: counts,
                                backgroundColor: [
                                    "#51EAEA", "#FCDDB0",
                                    "#FF9D76", "#FB3569", "#82CD47",
                                    'rgba(255, 159, 64, 0.2)'
                                ],
                                borderColor: [
                                    'rgba(255, 99, 132, 1)',
                                    'rgba(54, 162, 235, 1)',
                                    'rgba(255, 206, 86, 1)',
                                    'rgba(75, 192, 192, 1)',
                                    'rgba(153, 102, 255, 1)',
                                    'rgba(255, 159, 64, 1)'
                                ],
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'top',
                                },
                                title: {
                                    display: true,
                                    text: 'Distribution of Crop Types'
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error fetching data:', error));
        });
    </script>
</body>

</html>