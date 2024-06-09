<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Admin Dashboard</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
      background: #f5f5f5;
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

    .farmer-management,
    .user-roles,
    .activity-management {
      margin-bottom: 20px;
      border: 1px solid #ddd;
      padding: 10px;
      border-radius: 3px;
      background: white;
    }

    .farmer-management h3,
    .user-roles h3,
    .activity-management h3 {
      margin-top: 0;
    }

    .farmer-management table {
      width: 100%;
      border-collapse: collapse;
    }

    .farmer-management th,
    .farmer-management td {
      padding: 10px;
      border: 1px solid #ddd;
    }

    .farmer-management th {
      background-color: #f9f9f9;
    }

    .user-roles ul {
      padding-left: 20px;
    }

    .user-roles li {
      margin-bottom: 5px;
    }

    .modal {
      display: none;
      position: fixed;
      z-index: 1;
      left: 0;
      top: 0;
      width: 100%;
      height: 100vh;
      overflow: auto;
      background-color: rgba(0, 0, 0, 0.5);
      animation: fadeIn 0.5s;
    }

    .modal-content {
      background-color: white;
      margin: auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 500px;
      border-radius: 5px;
      animation: slideIn 0.5s;
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

    @keyframes fadeIn {
      from { opacity: 0; }
      to { opacity: 1; }
    }

    @keyframes slideIn {
      from { transform: translateY(-50px); opacity: 0; }
      to { transform: translateY(0); opacity: 1; }
    }

    button {
      background-color: #4CAF50;
      color: white;
      border: none;
      padding: 10px 15px;
      cursor: pointer;
      border-radius: 3px;
      transition: background-color 0.3s;
    }

    button:hover {
      background-color: #45a049;
    }
  </style>
</head>

<body>
  <header class="header">
    <h1>Agricultural Management System</h1>
    <nav class="navigation">
      <a href="#">Dashboard</a>
      <a href="#">Reports</a>
      <a href="#">Farmers</a>
      <a href="#">Logout</a>
    </nav>
  </header>

  <main class="main-content">
    <h2>Admin Dashboard</h2>

    <section class="farmer-management">
      <h3>Farmer Management</h3>
      <button id="add-farmer-btn"><i class="fas fa-plus"></i> Add Farmer</button>
      <table id="farmers-table" class="display">
        <thead>
          <tr>
            <th>Name</th>
            <th>Email</th>
            <th>Farm Name</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </section>

    <section class="user-roles">
      <h3>User Roles</h3>
      <ul>
        <li>Admin (Full Access)</li>
        <li>Farm Manager (View Reports, Manage Inventory)</li>
        <li>Field Worker (Record Crop Data, Track Tasks)</li>
        <li>Farmer (View Reports, Manage Profile)</li>
      </ul>
    </section>

    <section class="activity-management">
      <h3>Activity Management</h3>
      <button id="manage-activity-btn"><i class="fas fa-wrench"></i> Manage Activity</button>
      <table id="activity-table" class="display">
        <thead>
          <tr>
            <th>Farmer</th>
            <th>Activity</th>
            <th>Timestamp</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td>John Doe</td>
            <td>Planted Corn</td>
            <td>2024-06-10 08:00:00</td>
            <td><button class="delete-activity-btn"><i class="fas fa-trash"></i></button></td>
          </tr>
          <tr>
            <td>Jane Smith</td>
            <td>Harvested Wheat</td>
            <td>2024-06-11 10:00:00</td>
            <td><button class="delete-activity-btn"><i class="fas fa-trash"></i></button></td>
          </tr>
        </tbody>
      </table>
    </section>
  </main>

  <div class="modal" id="add-farmer-modal">
    <div class="modal-content">
      <span class="close-modal-btn">&times;</span>
      <h2>Add Farmer</h2>
      <form id="add-farmer-form">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <label for="farm-name">Farm Name:</label>
        <input type="text" id="farm-name" name="farm-name">
        <button type="submit">Add Farmer</button>
      </form>
    </div>
  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function() {
      // Initialize DataTables
      const farmersTable = $('#farmers-table').DataTable({
        columns: [
          { data: 'name' },
          { data: 'email' },
          { data: 'farmName' },
          {
            data: null,
            className: 'dt-center',
            defaultContent: '<button class="delete-farmer-btn"><i class="fas fa-trash"></i></button>',
            orderable: false
          }
        ]
      });

      // Initialize DataTables for activity table
      const activityTable = $('#activity-table').DataTable();

      // Add Farmer Button Click
      $('#add-farmer-btn').click(function() {
        $('#add-farmer-modal').fadeIn();
      });

      // Close Modal Button Click
      $('.close-modal-btn').click(function() {
        $('.modal').fadeOut();
      });

      // Add Farmer Form Submission
      $('#add-farmer-form').submit(function(event) {
        event.preventDefault();

        const name = $('#name').val();
        const email = $('#email').val();
        const farmName = $('#farm-name').val();

        // Simulate adding farmer to database
        farmersTable.row.add({ name, email, farmName }).draw();

        // Clear form fields
        $('#add-farmer-form').trigger('reset');

        // Close the modal
        $('.modal').fadeOut();
      });

      // Delete Farmer Button Click
      $('#farmers-table tbody').on('click', '.delete-farmer-btn', function() {
        farmersTable.row($(this).parents('tr')).remove().draw();
      });

      // Delete Activity Button Click
      $('#activity-table tbody').on('click', '.delete-activity-btn', function() {
        activityTable.row($(this).parents('tr')).remove().draw();
      });
    });
  </script>
</body>

</html>
