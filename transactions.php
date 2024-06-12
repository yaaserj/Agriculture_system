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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.6.4/css/datepicker.min.css">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
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
    }

    .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        border-radius: 5px;
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

    /* Responsive styles */
    @media (max-width: 768px) {
        .container {
            padding: 10px;
        }

        .header {
            padding: 5px 10px;
        }

        .navigation {
            padding: 5px;
            margin-top: 10px;
        }

        .navigation a {
            padding: 5px;
            font-size: 14px;
        }

        .main-content {
            margin-top: 10px;
        }

        .table-container {
            padding: 5px;
        }

        th,
        td {
            padding: 5px;
            font-size: 14px;
        }

        .actions a {
            padding: 3px 5px;
            font-size: 12px;
        }

        .modal-content {
            padding: 10px;
            width: 90%;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 8px;
            font-size: 14px;
        }

        .close {
            font-size: 24px;
        }
    }

    @media (max-width: 576px) {

        .form-group input,
        .form-group select,
        .form-group textarea {
            padding: 6px;
            font-size: 12px;
        }

        .close {
            font-size: 20px;
        }
    }
    </style>
</head>

<body>
    <div class="container">
        <header class="header">
            <h1>Agricultural Management System</h1>
        </header>
        <div class="navigation">
            <a href="add_transaction.php" data-toggle="modal" data-target="#addTransactionModal">Add Transaction</a>
        </div>
        <div class="main-content">
            <div class="table-container">
                <table id="transactionTable">
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
                    <tbody>
                        <!-- Transaction records will be appended here dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Add Transaction Modal -->
    <div class="modal" id="addTransactionModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add Transaction</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form id="transactionForm" method="POST"
                        action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                        <div class="form-group">
                            <label for="date">Date:</label>
                            <input type="text" id="date" name="date" placeholder="Select date" required>
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <select id="type" name="type" required>
                                <option value="income">Income</option>
                                <option value="expense">Expense</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="amount">Amount:</label>
                            <input type="number" id="amount" name="amount" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" rows="3" required></textarea>
                        </div>
                        <div class="form-group">
                            <label for="linkedItem">Linked Item:</label>
                            <select id="linkedItem" name="linkedItem" class="select2" required>
                                <!-- Options will be populated dynamically using Select2 -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Transaction</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript libraries -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.6.4/js/datepicker.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
    $(document).ready(function() {
        $('#filterDate').datepicker({
            dateFormat: 'yy-mm-dd'
        });

        $('.select2').select2();

        $('#addTransactionModal').on('shown.bs.modal', function() {
            $('#linkedItem').select2({
                width: '100%'
            });
        });

        $('#transactionForm').on('submit', function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: 'add_transaction.php',
                data: $(this).serialize(),
                success: function(response) {
                    alert(response);
                    $('#addTransactionModal').modal('hide');
                    $('#transactionForm')[0].reset();
                    $('.select2').val(null).trigger('change');
                }
            });
        });
    });
    </script>
</body>

</html>