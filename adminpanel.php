<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agrify - Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
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

    .main-content {
        padding: 20px;
        margin-left: 250px;
        /* Adjust based on sidebar width */
        margin-top: 70px;
        /* Adjust based on header height */
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
    session_start();

    if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
        header('Location: login.php');
        exit;
    }
    ?>

    <header class="header">
        <div>
            <h1>Agricultural Management System</h1>
        </div>
        <div class="text-end">
            <a href="profilemanagement.php">
                <img src="logo.png" alt="Logo" width="50" height="50">
            </a>
            <a href="#" class="ms-3">Dashboard</a>
            <a href="logout.php" class="ms-3">Logout</a>
            <!-- Add a menu toggle button for smaller screens -->
            <button class="navbar-toggler d-block d-md-none" type="button" onclick="toggleSidebar()">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </header>

    <div class="sidebar">
        <div class="nav flex-column">
            <a href="#" class="nav-link active" onclick="loadContent('dashboard.php')">
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
                Record Transaction
            </a>
            <a href="#" class="nav-link" onclick="loadContent('inventorymanagement.php')">
                View Inventory
            </a>
            <a href="#" class="nav-link" onclick="loadContent('reports.php')">
                Generate Reports
            </a>
            <a href="#" class="nav-link" onclick="loadContent('landmanagement.php')">
                Land Management
            </a>
            <a href="#" class="nav-link" onclick="loadContent('cropmanagement.php')">
                Crop Management
            </a>
        </div>
    </div>

    <main class="main-content" id="main-content">
        <h2><?php echo "Welcome to the admin dashboard, " . $_SESSION['username'] . "!"; ?></h2>
        <p>Select an option from the sidebar to get started.</p>
    </main>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous">
    </script>
    <script>
    function loadContent(page) {
        fetch(page)
            .then(response => response.text())
            .then(data => {
                document.getElementById('main-content').innerHTML = data;
            });
    }

    function toggleSidebar() {
        document.querySelector('.sidebar').classList.toggle('show');
    }
    </script>
</body>

</html>