<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $date = $_POST['date'];
  $type = $_POST['type'];
  $amount = $_POST['amount'];
  $description = $_POST['description'];
  $linkedItem = $_POST['linkedItem'];

  $sql = "INSERT INTO transactions (date, type, amount, description, linked_item) VALUES (:date, :type, :amount, :description, :linkedItem)";
  $stmt = $conn->prepare($sql);

  $stmt->execute([
    ':date' => $date,
    ':type' => $type,
    ':amount' => $amount,
    ':description' => $description,
    ':linkedItem' => $linkedItem,
  ]);

  echo "New record created successfully";
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Transactions</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.6.4/css/datepicker.min.css">
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
      animation: fadeInDown 0.5s;
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
      animation: fadeInLeft 0.5s;
    }

    .navigation a {
      text-decoration: none;
      color: #333;
      padding: 10px;
      border-bottom: 1px solid #ddd;
      transition: background-color 0.3s;
    }

    .navigation a:hover {
      background-color: #eee;
    }

    .main-content {
      flex: 1;
      display: flex;
      flex-direction: column;
      gap: 20px;
      margin-top: 20px;
      animation: fadeInRight 0.5s;
    }

    .filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      background-color: #fff;
      padding: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
    }

    .filters label {
      margin-right: 5px;
      font-weight: bold;
    }

    .filters input[type="text"],
    .filters select {
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 3px;
    }

    .filters button {
      padding: 5px 10px;
      border: none;
      background-color: #28a745;
      color: #fff;
      border-radius: 3px;
      cursor: pointer;
      transition: background-color 0.3s;
    }

    .filters button:hover {
      background-color: #218838;
    }

    .table-container {
      overflow-x: auto;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      background-color: #fff;
      padding: 10px;
      border-radius: 5px;
    }

    table {
      border-collapse: collapse;
      width: 100%;
    }

    th,
    td {
      padding: 10px;
      border: 1px solid #ddd;
      text-align: left;
    }

    th {
      background-color: #f8f8f8;
    }

    .actions {
      display: flex;
      gap: 5px;
    }

    .actions a {
      text-decoration: none;
      padding: 5px 10px;
      border-radius: 3px;
      transition: background-color 0.3s;
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
      animation: fadeIn 0.5s;
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      border-radius: 5px;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
      animation: fadeInUp 0.5s;
    }

    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      transition: color 0.3s;
    }

    .close:hover,
    .close:focus {
      color: black;
      text-decoration: none;
      cursor: pointer;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 3px;
      box-sizing: border-box;
    }

    .form-group input[type="number"] {
      -moz-appearance: textfield;
    }

    .form-group input[type="number"]::-webkit-outer-spin-button,
    .form-group input[type="number"]::-webkit-inner-spin-button {
      -webkit-appearance: none;
      margin: 0;
    }

    .select2 {
      width: 100%;
    }

    .tooltip {
      position: relative;
      display: inline-block;
      cursor: pointer;
    }

    .tooltip .tooltiptext {
      visibility: hidden;
      width: 120px;
      background-color: #555;
      color: #fff;
      text-align: center;
      border-radius: 6px;
      padding: 5px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      /* Position above the tooltip */
      left: 50%;
      margin-left: -60px;
      opacity: 0;
      transition: opacity 0.3s;
    }

    .tooltip:hover .tooltiptext {
      visibility: visible;
      opacity: 1;
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }

    @keyframes fadeInUp {
      from {
        transform: translateY(10px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeInDown {
      from {
        transform: translateY(-10px);
        opacity: 0;
      }

      to {
        transform: translateY(0);
        opacity: 1;
      }
    }

    @keyframes fadeInLeft {
      from {
        transform: translateX(-10px);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }

    @keyframes fadeInRight {
      from {
        transform: translateX(10px);
        opacity: 0;
      }

      to {
        transform: translateX(0);
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <div class="header">
      <h1>Agricultural Management System - Transactions</h1>
      <button id="openModalBtn">Add Transaction</button>
    </div>
    <div class="navigation">
      <a href="#">Dashboard</a>
      <a href="#">Transactions</a>
      <a href="#">Reports</a>
      <a href="#">Settings</a>
    </div>
    <div class="main-content">
      <div class="filters">
        <label for="filter-type">Type:</label>
        <select id="filter-type">
          <option value="all">All</option>
          <option value="expense">Expense</option>
          <option value="income">Income</option>
        </select>
        <label for="filter-date">Date:</label>
        <input type="text" id="filter-date" placeholder="YYYY-MM-DD">
        <button id="filterBtn">Filter</button>
      </div>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Date</th>
              <th>Type</th>
              <th>Amount</th>
              <th>Description</th>
              <th>Linked Item</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="transaction-table-body">
            <!-- Transactions will be dynamically inserted here -->
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div id="myModal" class="modal">
    <div class="modal-content">
      <span class="close">&times;</span>
      <form method="post" id="transaction-form">
        <div class="form-group">
          <label for="date">Date:</label>
          <input type="text" id="date" name="date" placeholder="YYYY-MM-DD" required>
        </div>
        <div class="form-group">
          <label for="type">Type:</label>
          <select id="type" name="type" required>
            <option value="expense">Expense</option>
            <option value="income">Income</option>
          </select>
        </div>
        <div class="form-group">
          <label for="amount">Amount:</label>
          <input type="number" id="amount" name="amount" required>
        </div>
        <div class="form-group">
          <label for="description">Description:</label>
          <textarea id="description" name="description" required></textarea>
        </div>
        <div class="form-group">
          <label for="linkedItem">Linked Item:</label>
          <select id="linkedItem" name="linkedItem" class="select2" required>
            <!-- Dynamically populate this select field with items -->
          </select>
        </div>
        <button type="submit">Add Transaction</button>
      </form>
    </div>
  </div>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.6.4/js/datepicker.min.js"></script>
  <script>
    $(document).ready(function () {
      $('.select2').select2();
      $('#date').datepicker({
        format: 'yyyy-mm-dd',
        autoclose: true
      });

      $('#openModalBtn').click(function () {
        $('#myModal').show();
      });

      $('.close').click(function () {
        $('#myModal').hide();
      });

      $(window).click(function (event) {
        if (event.target == document.getElementById('myModal')) {
          $('#myModal').hide();
        }
      });

      // Load linked items dynamically (Example: fetch items from server)
      // For now, we will use static options
      const linkedItems = [{
        id: 1,
        text: 'Item 1'
      },
      {
        id: 2,
        text: 'Item 2'
      },
      {
        id: 3,
        text: 'Item 3'
      }
      ];
      linkedItems.forEach(item => {
        $('#linkedItem').append(new Option(item.text, item.id));
      });

      // Filter transactions (Example: AJAX call to fetch filtered transactions)
      $('#filterBtn').click(function () {
        // Use AJAX to fetch filtered transactions
        const type = $('#filter-type').val();
        const date = $('#filter-date').val();
        // Example AJAX request (adapt to your needs)
        $.ajax({
          url: 'fetch_transactions.php',
          method: 'GET',
          data: {
            type,
            date
          },
          success: function (response) {
            // Populate table with response data
            $('#transaction-table-body').html(response);
          }
        });
      });

      // Example transaction data (this should be fetched from the server)
      const transactions = [{
        date: '2023-06-01',
        type: 'expense',
        amount: 100,
        description: 'Purchase of seeds',
        linked_item: 'Item 1'
      },
      {
        date: '2023-06-05',
        type: 'income',
        amount: 200,
        description: 'Sale of produce',
        linked_item: 'Item 2'
      }
      ];

      // Populate table with example data
      transactions.forEach(transaction => {
        $('#transaction-table-body').append(`
          <tr>
            <td>${transaction.date}</td>
            <td>${transaction.type}</td>
            <td>${transaction.amount}</td>
            <td>${transaction.description}</td>
            <td>${transaction.linked_item}</td>
            <td class="actions">
              <a href="#" class="edit">Edit</a>
              <a href="#" class="delete">Delete</a>
            </td>
          </tr>
        `);
      });
    });
  </script>
</body>

</html>