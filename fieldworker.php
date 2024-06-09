<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Field Worker Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    .header {
      padding: 20px;
      background-color: #4CAF50;
      color: white;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }

    .header h1 {
      margin: 0;
    }

    .navigation {
      list-style: none;
      margin: 0;
      padding: 0;
      display: flex;
    }

    .navigation a {
      text-decoration: none;
      padding: 10px 15px;
      color: white;
      transition: background-color 0.3s;
    }

    .navigation a:hover {
      background-color: #45a049;
    }

    .main-content {
      padding: 20px;
    }

    .activity-recording {
      margin-bottom: 20px;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 3px;
      background: white;
    }

    .activity-recording h3 {
      margin-top: 0;
    }

    .activity-recording form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 10px;
    }

    .activity-recording label {
      font-weight: bold;
    }

    .activity-recording select,
    .activity-recording input {
      padding: 8px;
      border-radius: 3px;
      border: 1px solid #ddd;
    }

    .activity-recording button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 3px;
      transition: background-color 0.3s;
    }

    .activity-recording button:hover {
      background-color: #45a049;
    }

    .assigned-tasks,
    .activity-history {
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 3px;
      background: white;
      margin-bottom: 20px;
    }

    .assigned-tasks h3,
    .activity-history h3 {
      margin-top: 0;
    }

    .assigned-tasks ul {
      padding-left: 20px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    th {
      background-color: #f9f9f9;
      font-weight: bold;
    }

    .close-modal-btn {
      float: right;
      cursor: pointer;
      color: #aaa;
      font-weight: bold;
    }

    .close-modal-btn:hover,
    .close-modal-btn:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }
  </style>
</head>

<body>
  <header class="header">
    <h1>Agricultural Management System</h1>
    <nav class="navigation">
      <a href="#">Dashboard</a>
      <a href="#">Logout</a>
    </nav>
  </header>

  <main class="main-content">
    <h2>Field Worker Dashboard</h2>

    <section class="activity-recording">
      <h3>Record Activity</h3>
      <form id="activity-form">
        <label for="date">Date:</label>
        <input type="date" id="date" name="date" required>
        <label for="activity">Activity:</label>
        <select id="activity" name="activity" required>
          <option value="">Select Activity</option>
          <option value="planting">Planting</option>
          <option value="harvesting">Harvesting</option>
          <option value="pest_control">Pest Control</option>
          <option value="fertilization">Fertilization</option>
          <option value="irrigation">Irrigation</option>
        </select>
        <label for="crop">Crop:</label>
        <select id="crop" name="crop" required>
          <option value="">Select Crop</option>
          <!-- Crop options will be populated dynamically -->
        </select>
        <label for="output">Output (Quantity):</label>
        <input type="number" id="output" name="output" required min="0">
        <button type="submit">Record Activity</button>
      </form>
    </section>

    <section class="assigned-tasks">
      <h3>Assigned Tasks</h3>
      <ul id="assigned-tasks-list">
        <!-- Assigned tasks will be populated dynamically -->
      </ul>
    </section>

    <section class="activity-history">
      <h3>Activity History</h3>
      <table id="activity-history-table">
        <thead>
          <tr>
            <th>Date</th>
            <th>Activity</th>
            <th>Crop</th>
            <th>Output</th>
          </tr>
        </thead>
        <tbody>
          <!-- Activity history will be populated dynamically -->
        </tbody>
      </table>
    </section>
  </main>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    $(document).ready(function() {
      // Function to fetch crop data (replace with actual implementation)
      function fetchCrops() {
        // Simulated crop data (replace with actual API call)
        const crops = [
          { id: 1, name: "Corn" },
          { id: 2, name: "Wheat" },
          { id: 3, name: "Rice" },
          { id: 4, name: "Barley" },
        ];
        return crops;
      }

      // Populate crop dropdown options
      function populateCropDropdown() {
        const cropSelect = $('#crop');
        cropSelect.empty(); // Clear existing options
        const crops = fetchCrops(); // Fetch crop data
        crops.forEach(crop => {
          const option = `<option value="${crop.id}">${crop.name}</option>`;
          cropSelect.append(option);
        });
      }

      // Function to populate assigned tasks list
      function populateAssignedTasks() {
        const taskList = $('#assigned-      tasks-list');
        taskList.empty(); // Clear existing tasks
        // Simulated assigned tasks data (replace with actual API call)
        const assignedTasks = [
          { task: 'Planting (Corn)', dueDate: '2024-06-10' },
          { task: 'Pest Control', dueDate: '2024-06-15' },
        ];
        assignedTasks.forEach(task => {
          const listItem = `<li><b>${task.task}</b> (Due: ${task.dueDate})</li>`;
          taskList.append(listItem);
        });
      }

      // Function to populate activity history table
      function populateActivityHistory() {
        const historyTable = $('#activity-history-table tbody');
        historyTable.empty(); // Clear existing entries
        // Simulated activity history data (replace with actual API call)
        const activityHistory = [
          { date: '2024-06-01', activity: 'Planting', crop: 'Corn', output: 100 },
          { date: '2024-06-05', activity: 'Harvesting', crop: 'Wheat', output: 150 },
          { date: '2024-06-10', activity: 'Pest Control', crop: 'Rice', output: 10 },
        ];
        activityHistory.forEach(activity => {
          const row = `<tr>
            <td>${activity.date}</td>
            <td>${activity.activity}</td>
            <td>${activity.crop}</td>
            <td>${activity.output}</td>
          </tr>`;
          historyTable.append(row);
        });
      }

      // Call functions to populate dropdown, assigned tasks, and activity history
      populateCropDropdown();
      populateAssignedTasks();
      populateActivityHistory();

      // Submit activity form
      $('#activity-form').submit(function(event) {
        event.preventDefault();
        // Get form data
        const date = $('#date').val();
        const activity = $('#activity').val();
        const crop = $('#crop').val();
        const output = $('#output').val();
        // Simulate recording activity (replace with actual API call)
        console.log('Recording activity:', { date, activity, crop, output });
        // Clear form fields
        $('#date').val('');
        $('#activity').val('');
        $('#crop').val('');
        $('#output').val('');
      });
    });
  </script>
</body>

</html>
