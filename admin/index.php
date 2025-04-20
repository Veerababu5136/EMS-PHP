<?php
session_start();
include 'connection.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ? AND role = 'admin'";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);  
    
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $admin = $result->fetch_assoc();

        if ($password===$admin['password'])
        {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header("Location: admin_dashboard.php");
            exit;
        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found or wrong email.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="#">Event Management</a>
  </div>
</nav>

<!-- Login Card -->
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header text-center bg-primary text-white">
                    <h4>Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label>Email address</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter email" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
                <div class="card-footer text-center small text-muted">
                    Only authorized admins can login here.
                </div>
            </div>
        </div>
    </div>
</div><br><br><br><br>


<!-- Footer -->
<footer class="bg-dark text-white mt-5">
  <div class="container py-4">
    <div class="row">
      <div class="col-md-6">
        <h5>Event Management System</h5>
        <p class="small">Manage your events efficiently with our platform.</p>
      </div>
      <div class="col-md-3">
        <h6>Quick Links</h6>
        <ul class="list-unstyled">
          <li><a href="index.php" class="text-white text-decoration-none">Home</a></li>
          <li><a href="admin_login.php" class="text-white text-decoration-none">Admin Login</a></li>
        </ul>
      </div>
      <div class="col-md-3">
        <h6>Contact</h6>
        <p class="small mb-0">Email: support@example.com</p>
        <p class="small">Phone: +91-9876543210</p>
      </div>
    </div>
  </div>
  <div class="text-center bg-secondary py-2 small">
    &copy; <?= date('Y') ?> Event Management System. All rights reserved.
  </div>
</footer>


</body>
</html>
