<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$username = $_SESSION['username'];
$user_type = $_SESSION['user_type'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Management System - Dashboard</title>
    <style>
    /* Global styles */
    body {
        font-family: sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        min-height: 100vh;
        background-color: #f5f5f5;
    }

    .container {
        flex: 1;
        display: flex;
        flex-direction: column;
        padding: 20px;
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
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    .card {
        padding: 20px;
        border-radius: 5px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card h2 {
        margin: 0;
        font-size: 18px;
        color: #333;
        margin-bottom: 10px;
    }

    .metrics {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }

    .metrics p {
        margin: 0;
        font-weight: bold;
    }

    #crop-yield-list li {
        list-style: none;
        padding: 5px 0;
    }

    #crop-yield-list li:nth-child(odd) {
        background-color: #f9f9f9;
    }

    /* Search bar styles */
    .search-bar {
        display: flex;
        justify-content: center;
        margin-bottom: 20px;
    }

    .search-bar input {
        padding: 10px;
        width: 80%;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0, 0, 0);
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 60px;
    }

    .modal-content {
        background-color: #fefefe;
        margin: 5% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 500px;
        border-radius: 10px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    .modal input,
    .modal button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    /* Responsive navigation menu */
    .menu-toggle {
        display: none;
    }

    @media (max-width: 768px) {
        .navigation {
            display: none;
            flex-direction: column;
            gap: 0;
        }

        .navigation a {
            border-bottom: none;
            padding: 15px;
            text-align: center;
        }

        .menu-toggle {
            display: block;
            cursor: pointer;
        }

        .menu-toggle.active+.navigation {
            display: flex;
        }
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
            <span class="menu-toggle">☰</span>
        </header>

        <nav class="navigation">
            <a href="#" id="add-crop-btn">Add New Crop</a>
            <a href="./transactions.php">Record Transaction</a>
            <a href="./inventorymanagement.php">View Inventory</a>
            <a href="./reports.php">Generate Reports</a>
            <a href="./landmanagement.php">Land Management</a>
        </nav>

        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search crops...">
        </div>

        <main class="main-content">
            <div class="card">
                <h2>Key Metrics</h2>
                <div class="metrics">
                    <p>Total Crops</p>
                    <p id="total-crops">100</p>
                </div>
                <div class="metrics">
                    <p>Upcoming Tasks</p>
                    <p id="upcoming-tasks">5</p>
                </div>
                <div class="metrics">
                    <p>Recent Transactions</p>
                    <p id="recent-transactions">3</p>
                </div>
            </div>

            <div class="card">
                <h2>Crop Yield Summary (Sample Data)</h2>
                <ul id="crop-yield-list">
                    <li>Wheat | 500</li>
                    <li>Corn | 750</li>
                    <li>Tomatoes | 200</li>
                </ul>
            </div>
        </main>

        <!-- The Modal -->
        <div id="myModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Add New Crop</h2>
                <input type="text" id="crop-name" placeholder="Crop Name">
                <input type="date" id="planting-date" placeholder="Planting Date">
                <input type="date" id="harvest-date" placeholder="Harvest Date">
                <button id="save-crop-btn">Save</button>
            </div>
        </div>

        <script>
        // Simulate fetching data from a server (replace with your actual data fetching logic)
        const metricsData = {
            totalCrops: 125,
            upcomingTasks: 3,
            recentTransactions: 7,
        };

        // Update metrics data on page load
        document.getElementById('total-crops').textContent = metricsData.totalCrops;
        document.getElementById('upcoming-tasks').textContent = metricsData.upcomingTasks;
        document.getElementById('recent-transactions').textContent = metricsData.recentTransactions;

        // Sample crop yield data (replace with your actual data)
        const cropYieldData = [{
                name: 'Wheat',
                yield: 500
            },
            {
                name: 'Corn',
                yield: 750
            },
            {
                name: 'Tomatoes',
                yield: 200
            },
        ];

        // Update crop yield list on page load
        const cropYieldList = document.getElementById('crop-yield-list');
        cropYieldData.forEach(crop => {
            const listItem = document.createElement('li');
            listItem.textContent = `${crop.name} | ${crop.yield}`;
            cropYieldList.appendChild(listItem);
        });

        // Search functionality
        document.getElementById('search-input').addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const cropItems = cropYieldList.getElementsByTagName('li');
            Array.from(cropItems).forEach(item => {
                const text = item.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Modal functionality
        const modal = document.getElementById("myModal");
        const btn = document.getElementById("add-crop-btn");
        const span = document.getElementsByClassName("close")[0];

        btn.onclick = function() {
            modal.style.display = "block";
        }

        span.onclick = function() {
            modal.style.display = "none";
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        document.getElementById('save-crop-btn').onclick = function() {
            const cropName = document.getElementById('crop-name').value;
            const plantingDate = document.getElementById('planting-date').value;
            const harvestDate = document.getElementById('harvest-date').value;

            // Validate inputs (add more validation as needed)
            if (cropName === '' || plantingDate === '' || harvestDate === '') {
                alert('Please fill in all fields.');
                return;
            }

            // Simulate saving the crop data (replace with your actual data saving logic)
            alert(`Crop "${cropName}" saved successfully!`);

            // Clear the input fields
            document.getElementById('crop-name').value = '';
            document.getElementById('planting-date').value = '';
            document.getElementById('harvest-date').value = '';

            // Close the modal
            modal.style.display = 'none';
        };

        // Menu toggle functionality
        const menuToggle = document.querySelector('.menu-toggle');
        const navigation = document.querySelector('.navigation');
        menuToggle.addEventListener('click', () => {
            menuToggle.classList.toggle('active');
            navigation.classList.toggle('active');
        });
        </script>
    </div>
</body>

</html>