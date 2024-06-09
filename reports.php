<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Agricultural Management System - Reports</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
  <style>
    body {
      font-family: 'Arial', sans-serif;
      margin: 0;
      padding: 0;
      background-color: #f9f9f9;
    }

    .container {
      max-width: 1200px;
      margin: auto;
      padding: 20px;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      background-color: #fff;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      animation: fadeInDown 0.5s;
    }

    .header a {
      text-decoration: none;
      color: #007bff;
      font-weight: bold;
      transition: color 0.3s;
    }

    .header a:hover {
      color: #0056b3;
    }

    h1 {
      margin: 0;
      font-size: 24px;
      color: #333;
    }

    .navigation {
      display: flex;
      gap: 10px;
      margin-top: 20px;
      background-color: #fff;
      padding: 10px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      animation: fadeInLeft 0.5s;
    }

    .navigation a {
      text-decoration: none;
      color: #333;
      padding: 10px;
      border-bottom: 2px solid transparent;
      transition: border-bottom 0.3s;
    }

    .navigation a:hover {
      border-bottom: 2px solid #007bff;
    }

    .main-content {
      background-color: #fff;
      padding: 20px;
      margin-top: 20px;
      box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
      border-radius: 5px;
      animation: fadeInRight 0.5s;
    }

    .main-content h2 {
      margin-top: 0;
    }

    .report-filters {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
      align-items: center;
      margin-bottom: 20px;
    }

    .report-filters label {
      font-weight: bold;
      margin-right: 5px;
    }

    .report-filters input,
    .report-filters select,
    .report-filters button {
      padding: 5px;
      border: 1px solid #ddd;
      border-radius: 3px;
      flex: 1;
      min-width: 150px;
      transition: box-shadow 0.3s;
    }

    .report-filters button {
      background-color: #28a745;
      color: #fff;
      cursor: pointer;
      border: none;
    }

    .report-filters button:hover {
      background-color: #218838;
    }

    #report-container {
      animation: fadeIn 0.5s;
    }

    .chart-container {
      position: relative;
      margin: auto;
      height: 400px;
      width: 80%;
    }

    @keyframes fadeInDown {
      from {
        opacity: 0;
        transform: translateY(-10px);
      }

      to {
        opacity: 1;
        transform: translateY(0);
      }
    }

    @keyframes fadeInLeft {
      from {
        opacity: 0;
        transform: translateX(-10px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(10px);
      }

      to {
        opacity: 1;
        transform: translateX(0);
      }
    }

    @keyframes fadeIn {
      from {
        opacity: 0;
      }

      to {
        opacity: 1;
      }
    }
  </style>
</head>

<body>
  <div class="container">
    <header class="header">
      <a href="dashboard.html">Dashboard</a>
      <h1>Agricultural Management System - Reports</h1>
    </header>

    <nav class="navigation">
      <a href="inventory-management.html">Inventory Management</a>
      <a href="transactions.html">Transactions</a>
      <a href="#">Reports</a>
    </nav>

    <main class="main-content">
      <h2>Reports</h2>

      <div class="report-filters">
        <label for="report-type">Report Type:</label>
        <select id="report-type" required>
          <option value="">Select Report</option>
          <option value="financial">Financial Report</option>
          <option value="crop-yield">Crop Yield Report</option>
          <option value="inventory-usage">Inventory Usage Report</option>
        </select>
        <label for="from-date">From Date:</label>
        <input type="text" id="from-date" placeholder="Select Start Date">
        <label for="to-date">To Date:</label>
        <input type="text" id="to-date" placeholder="Select End Date">
        <button id="generate-report-btn">Generate Report</button>
      </div>

      <div id="report-container"></div>
    </main>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.6.4/js/datepicker.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
  <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>
  <script>
    $(document).ready(function () {
      // Initialize date pickers
      $('#from-date').datepicker({
        dateFormat: 'yy-mm-dd'
      });
      $('#to-date').datepicker({
        dateFormat: 'yy-mm-dd'
      });

      // Initialize select2 for report type dropdown
      $('#report-type').select2();

      // Function to generate report based on selected type and filters
      function generateReport() {
        const reportType = $('#report-type').val();
        const fromDate = $('#from-date').val();
        const toDate = $('#to-date').val();

        // Simulate data fetching from server (replace with actual API calls)
        let reportData;
        switch (reportType) {
          case 'financial':
            reportData = generateFinancialReport(fromDate, toDate);
            break;
          case 'crop-yield':
            reportData = generateCropYieldReport(fromDate, toDate);
            break;
          case 'inventory-usage':
            reportData = generateInventoryUsageReport(fromDate, toDate);
            break;
          default:
            reportData = null;
        }

        // Clear existing report content
        $('#report-container').empty();

        if (reportData) {
          // Display report based on type (e.g., chart, table)
          if (reportType === 'financial') {
            displayFinancialReport(reportData);
          } else if (reportType === 'crop-yield') {
            displayCropYieldReport(reportData);
          } else if (reportType === 'inventory-usage') {
            displayInventoryUsageReport(reportData);
          }
        } else {
          $('#report-container').html('<p>No data available for selected report.</p>');
        }
      }

      // Function to generate financial report data (replace with actual logic)
      function generateFinancialReport(fromDate, toDate) {
        const transactions = [
          { date: '2024-01-01', type: 'income', amount: 1000 },
          { date: '2024-01-02', type: 'expense', amount: 500 },
          { date: '2024-01-03', type: 'income', amount: 2000 },
          { date: '2024-01-04', type: 'expense', amount: 1000 },
        ]; // Simulate fetching transactions from Transactions page

        const income = transactions.filter(transaction => transaction.type === 'income')
          .reduce((acc, transaction) => acc + transaction.amount, 0);
        const expense = transactions.filter(transaction => transaction.type === 'expense')
          .reduce((acc, transaction) => acc + transaction.amount, 0);
        const reportData = {
          income: income,
          expense: expense,
          profit: income - expense
        };
        return reportData;
      }

      // Placeholder functions for other report data generation (replace with actual implementations)
      function generateCropYieldReport(fromDate, toDate) {
        // Simulate fetching and processing crop yield data
        return {
          labels: ['Corn', 'Wheat', 'Soy'],
          data: [150, 200, 100]
        };
      }

      function generateInventoryUsageReport(fromDate, toDate) {
        // Simulate fetching and processing inventory usage data
        return {
          labels: ['Fertilizer', 'Seeds', 'Pesticides'],
          data: [30, 50, 20]
        };
      }

      // Function to display financial report as a chart
      function displayFinancialReport(data) {
        const ctx = document.createElement('canvas');
        $('#report-container').append(ctx);
        const chart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: ['Income', 'Expense', 'Profit'],
            datasets: [{
              label: 'Amount',
              data: [data.income, data.expense, data.profit],
              backgroundColor: ['green', 'red', 'blue']
            }]
          },
          options: {
            scales: {
              y: {
                beginAtZero: true
              }
            },
            plugins: {
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return ` ${context.label}: $${context.raw}`;
                  }
                }
              }
            }
          }
        });
      }

      // Functions to display other report types
      function displayCropYieldReport(data) {
        const ctx = document.createElement('canvas');
        $('#report-container').append(ctx);
        const chart = new Chart(ctx, {
          type: 'pie',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Yield',
              data: data.data,
              backgroundColor: ['#ff6384', '#36a2eb', '#ffce56']
            }]
          },
          options: {
            plugins: {
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return `${context.label}: ${context.raw} tons`;
                  }
                }
              }
            }
          }
        });
      }

      function displayInventoryUsageReport(data) {
        const ctx = document.createElement('canvas');
        $('#report-container').append(ctx);
        const chart = new Chart(ctx, {
          type: 'doughnut',
          data: {
            labels: data.labels,
            datasets: [{
              label: 'Usage',
              data: data.data,
              backgroundColor: ['#4bc0c0', '#ff9f40', '#ffcd56']
            }]
          },
          options: {
            plugins: {
              tooltip: {
                callbacks: {
                  label: function (context) {
                    return `${context.label}: ${context.raw} units`;
                  }
                }
              }
            }
          }
        });
      }

      // Generate report on button click
      $('#generate-report-btn').click(generateReport);
    });
  </script>
</body>

</html>
