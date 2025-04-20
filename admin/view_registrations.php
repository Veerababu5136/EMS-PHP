<?php
include 'session_validator.php';

if (!isset($_GET['event_id'])) {
    echo "Event ID is missing!";
    exit;
}

$event_id = intval($_GET['event_id']);

// Get Event Name
$event_sql = "SELECT title FROM events WHERE id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();
$event = $event_result->fetch_assoc();

// Get registrations
$sql = "SELECT users.name, users.email, registrations.registered_at 
        FROM registrations 
        JOIN users ON registrations.user_id = users.id 
        WHERE registrations.event_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $event_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>View Registrations</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="admin_dashboard.php">Admin Dashboard</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Welcome, <?= $_SESSION['admin_name'] ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<div class="container my-5">
  <h3>Registrations for: <span class="text-primary"><?= htmlspecialchars($event['title']) ?></span></h3>
  <a href="view_events.php" class="btn btn-secondary mb-3">Back to Events</a>

  <table class="table table-bordered">
    <thead>
      <tr>
        <th>#</th>
        <th>Name</th>
        <th>Email</th>
        <th>Registered At</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $i = 1;
      if ($result->num_rows > 0) {
          while ($row = $result->fetch_assoc()) {
              echo "<tr>
                      <td>$i</td>
                      <td>{$row['name']}</td>
                      <td>{$row['email']}</td>
                      <td>{$row['registered_at']}</td>
                    </tr>";
              $i++;
          }
      } else {
          echo "<tr><td colspan='4' class='text-center'>No registrations found for this event.</td></tr>";
      }
      ?>
    </tbody>
  </table>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
