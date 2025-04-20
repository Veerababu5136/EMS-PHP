<?php

include 'session_validator.php';



// Fetch counts from database
$event_count = $student_count = $admin_count = 0;

$event_sql = "SELECT COUNT(*) AS total FROM events";
$student_sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'user'";
$admin_sql = "SELECT COUNT(*) AS total FROM users WHERE role = 'admin'";

$event_result = $conn->query($event_sql);
$student_result = $conn->query($student_sql);
$admin_result = $conn->query($admin_sql);

if ($event_result) {
    $row = $event_result->fetch_assoc();
    $event_count = $row['total'];
}
if ($student_result) {
    $row = $student_result->fetch_assoc();
    $student_count = $row['total'];
}
if ($admin_result) {
    $row = $admin_result->fetch_assoc();
    $admin_count = $row['total'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Admin Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Welcome, <?= $_SESSION['admin_name'] ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Main Content -->
<div class="container my-5">
  <div class="row g-4">
    <div class="col-md-4">
      <div class="card text-bg-primary shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Total Events</h5>
          <p class="display-5"><?= $event_count ?></p>
          <a href="view_events.php" class="btn btn-light">View Events</a>
          <a href="add_event.php" class="btn btn-success">Add Event</a>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-success shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Total Students</h5>
          <p class="display-5"><?= $student_count ?></p>
          <a href="view_students.php" class="btn btn-light">View Students</a>
          <a href="add_student.php" class="btn btn-warning">Add Student</a>

        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card text-bg-warning shadow-sm">
        <div class="card-body">
          <h5 class="card-title">Total Admins</h5>
          <p class="display-5"><?= $admin_count ?></p>
          <a href="view_admins.php" class="btn btn-light">View Admins</a>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
