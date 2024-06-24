<?php
require 'connection.php';

// Check if the transaction ID is provided in the query string
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the record
    try {
        $stmt = $pdo->prepare('DELETE FROM transactions WHERE id = :id');
        $stmt->execute(['id' => $id]);

        // Redirect to the transactions page
        header('Location: transactions.php');
        exit;
    } catch (PDOException $e) {
        $error_message = 'Delete failed: ' . $e->getMessage();
    }
} else {
    // Redirect to the transactions page if no ID is provided
    header('Location: transactions.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Delete Transaction</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <?php if (isset($error_message)): ?>
        <div class="alert alert-danger mt-5" role="alert">
            <?php echo $error_message; ?>
        </div>
        <?php endif; ?>
        <h1 class="mt-5">Delete Transaction</h1>
        <p class="lead">Transaction has been successfully deleted.</p>
        <a href="transactions.php" class="btn btn-primary">Back to Transactions</a>
    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.11.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
</body>

</html>