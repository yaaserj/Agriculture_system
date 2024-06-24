<?php
require 'connection.php';

session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$message = "";

// Fetch user details
$sql = "SELECT username, email FROM users WHERE user_id = :user_id";
$stmt = $pdo->prepare($sql); // Ensure $pdo is your correct PDO connection variable
$stmt->execute([':user_id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Update profile picture if a new one is uploaded
    if (!empty($_FILES['profile_picture']['name'])) {
        $target_dir = "images/images/";
        $target_file = $target_dir . basename($_FILES['profile_picture']['name']);
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check file size
        if ($_FILES['profile_picture']['size'] > 2000000) {
            $message = "Sorry, your file is too large.";
        } else {
            // Allow certain file formats
            if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                $message = "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
            } else {
                // Upload file
                if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $target_file)) {
                    $profile_picture = $target_file;
                } else {
                    $message = "Sorry, there was an error uploading your file.";
                }
            }
        }
    } else {
        $profile_picture = $user['profile_picture'] ?? null; // Handle the case where profile_picture might be missing
    }

    // Update user details in the database
    if (empty($message)) {
        $sql = "UPDATE users SET username = :username, email = :email, profile_picture = :profile_picture WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql); // Ensure $pdo is your correct PDO connection variable
        if ($stmt->execute([':username' => $username, ':email' => $email, ':profile_picture' => $profile_picture, ':user_id' => $user_id])) {
            $message = "Profile updated successfully!";
            //redirect to dashboard
            header('Location: adminpanel.php');
            // Fetch updated details
            $stmt = $pdo->prepare("SELECT username, email, profile_picture FROM users WHERE user_id = :user_id");
            $stmt->execute([':user_id' => $user_id]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
        } else {
            $message = "Error updating profile.";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Management</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Profile Management</h2>
        <?php if (!empty($message)): ?>
            <div class="alert alert-info"><?php echo $message; ?></div>
        <?php endif; ?>
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="profile_picture">Profile Picture:</label>
                <?php if (!empty($user['profile_picture'])): ?>
                    <div class="mb-2">
                        <img src="<?php echo htmlspecialchars($user['profile_picture']); ?>" alt="Profile Picture"
                            width="100" height="100">
                    </div>
                <?php endif; ?>
                <input type="file" class="form-control-file" id="profile_picture" name="profile_picture">
            </div>
            <button type="submit" class="btn btn-primary">Update Profile</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>