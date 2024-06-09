<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Inventory Management</title>
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

    th, td {
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
      background-color: rgba(0,0,0,0.4);
      animation: fadeIn 0.5s;
    }

    .modal-content {
      background-color: #fff;
      margin: 15% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      border-radius: 5px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
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
      bottom: 125%; /* Position above the tooltip */
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
      from {opacity: 0;}
      to {opacity: 1;}
    }

    @keyframes fadeInUp {
      from {transform: translateY(10px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }

    @keyframes fadeInDown {
      from {transform: translateY(-10px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }

    @keyframes fadeInLeft {
      from {transform: translateX(-10px); opacity: 0;}
      to {transform: translateX(0); opacity: 1;}
    }

    @keyframes fadeInRight {
      from {transform: translateX(10px); opacity: 0;}
      to {transform: translateX(0); opacity: 1;}
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
</head>
<body>
  <div class="container">
    <header class="header">
      <a href="dashboard.html">Dashboard</a>
      <h1>Agricultural Management System - Inventory Management</h1>
    </header>

    <nav class="navigation">
      <a href="#">Land Management</a>
      <a href="#">Inventory Management</a>
      <a href="#" class="tooltip" id="open-add-inventory-modal">Add Inventory<span class="tooltiptext">Click to add new item</span></a>
    </nav>

    <main class="main-content">
      <h2>Inventory</h2>
      <div class="table-container">
        <table>
          <thead>
            <tr>
              <th>Item Name</th>
              <th>Description</th>
              <th>Category</th>
              <th>Unit Type</th>
              <th>Current Quantity</th>
              <th>Reorder Level</th>
              <th>Supplier</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="inventory-data">
          </tbody>
        </table>
      </div>

      <canvas id="inventory-chart" width="400" height="200"></canvas>

      <div id="add-inventory-modal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <h2>Add New Inventory Item</h2>
          <form id="add-inventory-form">
            <div class="form-group">
              <label for="item-name">Item Name:</label>
              <input type="text" id="item-name" name="itemName" required>
            </div>
            <div class="form-group">
              <label for="description">Description:</label>
              <textarea id="description" name="description" rows="4"></textarea>
            </div>
            <div class="form-group">
              <label for="category">Category:</label>
              <select id="category" name="category" class="select2" required>
                <option value="seeds">Seeds</option>
                <option value="fertilizers">Fertilizers</option>
                <option value="tools">Tools</option>
              </select>
            </div>
            <div class="form-group">
              <label for="unit-type">Unit Type:</label>
              <select id="unit-type" name="unitType" required>
                <option value="kg">Kg</option>
                <option value="liters">Liters</option>
                <option value="units">Units</option>
              </select>
            </div>
            <div class="form-group">
              <label for="current-quantity">Current Quantity:</label>
              <input type="number" id="current-quantity" name="currentQuantity" required>
            </div>
            <div class="form-group">
              <label for="reorder-level">Reorder Level:</label>
              <input type="number" id="reorder-level" name="reorderLevel" required>
            </div>
            <div class="form-group">
              <label for="supplier">Supplier:</label>
              <input type="text" id="supplier" name="supplier">
            </div>
            <button type="submit">Add Inventory</button>
          </form>
        </div>
      </div>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
      $('.select2').select2();

      var modal = document.getElementById('add-inventory-modal');
      var btn = document.getElementById('open-add-inventory-modal');
      var span = document.getElementsByClassName('close')[0];

      btn.onclick = function() {
        modal.style.display = 'block';
      }

      span.onclick = function() {
        modal.style.display = 'none';
      }

      window.onclick = function(event) {
        if (event.target == modal) {
          modal.style.display = 'none';
        }
      }

      const ctx = document.getElementById('inventory-chart').getContext('2d');
      const inventoryChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: ['Seeds', 'Fertilizers', 'Tools'],
          datasets: [{
            label: 'Inventory Count',
            data: [10, 20, 30],
            backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
          }]
        },
        options: {
          responsive: true,
          plugins: {
            legend: {
              position: 'top',
            },
            title: {
              display: true,
              text: 'Inventory Distribution'
            }
          }
        }
      });

      document.getElementById('add-inventory-form').addEventListener('submit', function(event) {
        event.preventDefault();
        const newItem = {
          itemName: this.itemName.value,
          description: this.description.value,
          category: this.category.value,
          unitType: this.unitType.value,
          currentQuantity: this.currentQuantity.value,
          reorderLevel: this.reorderLevel.value,
          supplier: this.supplier.value,
        };
        addItemToTable(newItem);
        this.reset();
        modal.style.display = 'none';
      });

      function addItemToTable(item) {
        const tbody = document.getElementById('inventory-data');
        const tr = document.createElement('tr');
        tr.innerHTML = `
          <td>${item.itemName}</td>
          <td>${item.description}</td>
          <td>${item.category}</td>
          <td>${item.unitType}</td>
          <td>${item.currentQuantity}</td>
          <td>${item.reorderLevel}</td>
          <td>${item.supplier}</td>
          <td class="actions">
            <a href="#" class="edit">Edit</a>
            <a href="#" class="delete">Delete</a>
          </td>
        `;
        tbody.appendChild(tr);
      }
    });
  </script>
</body>
</html>
