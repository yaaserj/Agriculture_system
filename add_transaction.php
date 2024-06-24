<?php
require 'connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST['date'];
    $type = $_POST['type'];
    $amount = $_POST['amount'];
    $description = $_POST['description'];
    // $linkedItem = $_POST['linkedItem'];

    $sql = "INSERT INTO transactions (date, type, amount, description) VALUES (:date, :type, :amount, :description)";
    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':date' => $date,
        ':type' => $type,
        ':amount' => $amount,
        ':description' => $description,
    ]);

    // echo "New record created successfully";
    header('Location: transactions.php');
}