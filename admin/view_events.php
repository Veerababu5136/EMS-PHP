<?php
include 'session_validator.php';

// Fetch events from database
$sql = "SELECT * FROM events";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Events</title>
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
  <h2>Manage Events</h2>
  <div class="mb-3">
    <a href="add_event.php" class="btn btn-success">Add New Event</a>
  </div>

  <!-- Events Table -->
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Title</th>
        <th>Description</th>
        <th>Date</th>
        <th>Time</th>
        <th>Location</th>
        <th>Image</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      if ($result->num_rows > 0) {
          while ($event = $result->fetch_assoc()) {
              echo "<tr>";
              echo "<td>" . $event['id'] . "</td>";
              echo "<td>" . htmlspecialchars($event['title']) . "</td>";
              echo "<td>" . htmlspecialchars($event['description']) . "</td>";
              echo "<td>" . $event['event_date'] . "</td>";
              echo "<td>" . $event['event_time'] . "</td>";
              echo "<td>" . htmlspecialchars($event['location']) . "</td>";
              echo "<td><img src='" . $event['image_url'] . "' alt='Event Image' style='width: 100px;'></td>";
              echo "<td>
                      <a href='edit_event.php?id=" . $event['id'] . "' class='btn btn-warning btn-sm'>Edit</a>
                      <a href='delete_event.php?id=" . $event['id'] . "' class='btn btn-danger btn-sm'>Delete</a>
                      <a href='view_registrations.php?event_id=" . $event['id'] . "' class='btn btn-info btn-sm'>View Registrations</a>
                    </td>";
              echo "</tr>";
          }
      } else {
          echo "<tr><td colspan='8' class='text-center'>No events found.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
