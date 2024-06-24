<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <?php
        require 'connection.php';

        // Check if the form is submitted
        if (isset($_POST['update'])) {
            $id = $_POST['id'];
            $date = $_POST['date'];
            $type = $_POST['type'];
            $amount = $_POST['amount'];
            $description = $_POST['description'];

            // Update the record
            try {
                $stmt = $pdo->prepare('UPDATE transactions SET type = :type, amount = :amount, description = :description, date = :date WHERE id = :id');
                $stmt->execute(['type' => $type, 'amount' => $amount, 'description' => $description, 'date' => $date, 'id' => $id]);
                header('Location: transactions.php');
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger" role="alert">Update failed: ' . $e->getMessage() . '</div>';
            }
        } else {
            // Get the transaction ID from the query string
            $id = $_GET['id'];

            // Fetch the transaction details
            try {
                $stmt = $pdo->prepare('SELECT id, type, amount, description, date FROM transactions WHERE id = :id');
                $stmt->execute(['id' => $id]);
                $transaction = $stmt->fetch();
            } catch (PDOException $e) {
                echo '<div class="alert alert-danger" role="alert">Query failed: ' . $e->getMessage() . '</div>';
                exit;
            }
        }
        ?>

        <h1 class="mt-5">Edit Transaction</h1>
        <form method="POST" class="mt-4">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($transaction['id']); ?>">
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date"
                    value="<?php echo htmlspecialchars($transaction['date']); ?>" required>
            </div>
            <div class="form-group">
                <label for="type">Type:</label>
                <select class="form-control" id="type" name="type" required>
                    <option value="income" <?php echo $transaction['type'] == 'income' ? 'selected' : ''; ?>>Income
                    </option>
                    <option value="expense" <?php echo $transaction['type'] == 'expense' ? 'selected' : ''; ?>>Expense
                    </option>
                </select>
            </div>
            <div class="form-group">
                <label for="amount">Amount:</label>
                <input type="number" class="form-control" id="amount" name="amount"
                    value="<?php echo htmlspecialchars($transaction['amount']); ?>" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="3"
                    required><?php echo htmlspecialchars($transaction['description']); ?></textarea>
            </div>
            <button type="submit" name="update" class="btn btn-primary">Update</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>