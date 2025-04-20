<?php
session_start();
include 'connection.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$success = "";
$error = "";

// Fetch user data
$sql = "SELECT * FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Update user info
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password && $new_password !== $confirm_password) {
        $error = "Passwords do not match.";
    } else {
        $update_sql = "UPDATE users SET name = ?";
        $params = [$name];
        $types = "s";

        if ($new_password) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $update_sql .= ", password = ?";
            $params[] = $hashed_password;
            $types .= "s";
        }

        $update_sql .= " WHERE id = ?";
        $params[] = $user_id;
        $types .= "i";

        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bind_param($types, ...$params);

        if ($update_stmt->execute()) {
            $_SESSION['user_name'] = $name;
            $success = "Profile updated successfully!";
        } else {
            $error = "Something went wrong.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="user_dashboard.php">Event Portal</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Profile Form -->
<div class="container mt-5">
    <h3>User Profile</h3>

    <?php if ($success): ?>
        <div class="alert alert-success"><?= $success; ?></div>
    <?php elseif ($error): ?>
        <div class="alert alert-danger"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" class="card p-4 shadow-sm col-md-6">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']); ?>" required>
        </div>

        <div class="mb-3">
            <label>Email (read-only)</label>
            <input type="email" class="form-control" value="<?= htmlspecialchars($user['email']); ?>" readonly>
        </div>

        <div class="mb-3">
            <label>New Password (leave blank to keep current)</label>
            <input type="password" name="new_password" class="form-control">
        </div>

        <div class="mb-3">
            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Update Profile</button>
    </form>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
