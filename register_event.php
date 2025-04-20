<?php
session_start();
include 'connection.php';

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: user_login.php");
    exit;
}

// Get user and event ID from URL
$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['event_id']) ? intval($_GET['event_id']) : 0;
$message = "";

// Check if event exists
$event_sql = "SELECT * FROM events WHERE id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param("i", $event_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();

if ($event_result->num_rows === 0) {
    $message = "Event not found.";
} else {
    // Check if already registered
    $check_sql = "SELECT * FROM registrations WHERE user_id = ? AND event_id = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("ii", $user_id, $event_id);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        $message = "You are already registered for this event.";
    } else {
        // Register user
        $reg_sql = "INSERT INTO registrations (user_id, event_id) VALUES (?, ?)";
        $reg_stmt = $conn->prepare($reg_sql);
        $reg_stmt->bind_param("ii", $user_id, $event_id);

        if ($reg_stmt->execute()) {
            $message = "You have successfully registered for the event!";
        } else {
            $message = "Something went wrong. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register Event</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="user_dashboard.php">Event Portal</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">Hi, <?= htmlspecialchars($_SESSION['user_name']); ?></span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- Message -->
<div class="container mt-5">
    <div class="card p-4 shadow-sm">
        <h4>Event Registration</h4>
        <p><?= $message; ?></p>
        <a href="my_events.php" class="btn btn-secondary">Back to Events</a>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
v