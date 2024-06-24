<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit;
}

require_once 'connection.php'; // Include your database connection file

try {
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $id = $_POST['id'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $user_type = $_POST['user_type'];

        $stmt = $pdo->prepare('UPDATE users SET username = ?, email = ?, user_type = ? WHERE user_id = ?');
        $stmt->execute([$username, $email, $user_type, $id]);

        header('Location: users.php');
        exit;
    } else {
        $id = $_GET['id'];
        $stmt = $pdo->prepare('SELECT * FROM users WHERE user_id = ?');
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
</head>

<body>
    <div class="container">
        <h2 class="my-4">Edit User</h2>
        <form method="POST" action="edit_user.php">
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($user['user_id']); ?>">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username"
                    value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="mb-3">
                <label for="user_type" class="form-label">User Type</label>
                <select class="form-select" id="user_type" name="user_type" required>
                    <option value="admin" <?php if ($user['user_type'] == 'admin')
                        echo 'selected'; ?>>Admin</option>
                    <option value="user" <?php if ($user['user_type'] == 'user')
                        echo 'selected'; ?>>User</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Save Changes</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>

</html>