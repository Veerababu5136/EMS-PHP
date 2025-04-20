<?php
// 
include 'session_validator.php';


if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Delete the event from the database
    $sql = "DELETE FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        // Event deleted successfully, redirect to events list
        header("Location: view_events.php?msg=Event deleted successfully.");
        exit;
    } else {
        // Error deleting event
        $error = "Error deleting event: " . $stmt->error;
    }
} else {
    // Invalid event ID
    $error = "Invalid event ID.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Delete Event</title>
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
    <h2>Delete Event</h2>

    <!-- Error Message -->
    <?php if (isset($error)) : ?>
        <div class="alert alert-danger"><?= $error ?></div>
    <?php endif; ?>

    <!-- Confirmation Message -->
    <div class="alert alert-warning">
        Are you sure you want to delete this event? This action cannot be undone.
    </div>

    <!-- Redirect to events list button -->
    <a href="view_events.php" class="btn btn-secondary">Back to Events</a>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
