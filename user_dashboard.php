<?php
include 'session_validator.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Event Portal</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Dashboard -->
<div class="container mt-5">
    <h3>User Dashboard</h3>
    <div class="row mt-4">
        <!-- Example Card -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Your Events</h5>
                    <p class="card-text">Check your registered events here.</p>
                    <a href="my_events.php" class="btn btn-primary btn-sm">View</a>
                </div>
            </div>
        </div>

        <!-- Add more cards as needed -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body text-center">
                    <h5 class="card-title">Profile</h5>
                    <p class="card-text">Manage your profile and details.</p>
                    <a href="user_profile.php" class="btn btn-primary btn-sm">Go</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
