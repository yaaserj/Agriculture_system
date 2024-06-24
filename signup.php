<?php
require 'connection.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Set default user type
    $user_type = 'user';

    // Validate email (example, you can use a more thorough validation method)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit; // Stop execution if email is invalid
    }

    // Prepare SQL statement
    $sql = "INSERT INTO users (username, email, password, user_type) VALUES (:username, :email, :password, :user_type)";
    $stmt = $pdo->prepare($sql);

    // Bind parameters and execute the statement
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':user_type', $user_type, PDO::PARAM_STR);

    try {
        $stmt->execute();
        // Redirect to login page
        header('Location: login.php');
        exit;
        // echo "User registered successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agricultural Management System - Signup</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: linear-gradient(45deg, #83a4d4, #b6fbff);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }

        h1,
        h2 {
            margin: 0;
            color: #333;
        }

        form {
            margin-top: 20px;
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 5px;
            text-align: left;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            width: calc(100% - 20px);
        }

        button[type="submit"] {
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #45a049;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            justify-content: center;
        }

        .remember-me input {
            margin-right: 5px;
        }

        .forgot-password {
            color: #007bff;
            text-decoration: none;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Agricultural Management System</h1>
        <h2>Sign Up</h2>
        <form id="signup-form" onsubmit="return validateForm()" method="post">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" required>
            <label for="email">Email</label>
            <input type="text" name="email" id="email" required>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
            <div class="remember-me">
                <input type="checkbox" id="remember-me">
                <label for="remember-me">Remember me</label>
            </div>
            <button type="submit">Signup</button>
            <p>Already have an account? <a href="./login.php">Login</a></p>
        </form>
    </div>

    <script>
        function validateForm() {
            var username = document.getElementById('username').value;
            var password = document.getElementById('password').value;

            if (username.trim() === '' || password.trim() === '') {
                alert('Please fill out all fields.');
                return false;
            }

            return true;
        }
    </script>
</body>

</html>