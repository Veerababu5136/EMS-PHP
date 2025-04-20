<?php


include 'session_validator.php';


$error = "";
$success = "";

// Fetch event data if ID is provided
if (isset($_GET['id'])) {
    $event_id = $_GET['id'];

    // Fetch the event details
    $sql = "SELECT * FROM events WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $event = $result->fetch_assoc();
    } else {
        $error = "Event not found.";
    }
} else {
    $error = "Invalid event ID.";
}

// Update event details if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    // Get form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = trim($_POST['location']);
    $image_url = $event['image_url']; // Keep the existing image URL

    // Handle image upload if a new image is selected
    if (isset($_FILES['event_image']) && $_FILES['event_image']['error'] == 0) {
        $image_name = $_FILES['event_image']['name'];
        $image_tmp_name = $_FILES['event_image']['tmp_name'];
        $image_extension = pathinfo($image_name, PATHINFO_EXTENSION);
        
        // Define image folder and name
        $image_folder = "uploads/events/";
        $image_file_name = uniqid() . "." . $image_extension;
        $image_path = $image_folder . $image_file_name;

        // Move the uploaded image to the events folder
        if (move_uploaded_file($image_tmp_name, $image_path)) {
            $image_url = $image_path;
        } else {
            $error = "Error uploading image.";
        }
    }

    // Update event in the database
    if (empty($error)) {
        $sql = "UPDATE events SET title = ?, description = ?, event_date = ?, event_time = ?, location = ?, image_url = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $description, $event_date, $event_time, $location, $image_url, $event_id);

        if ($stmt->execute()) {
            $success = "Event updated successfully!";
        } else {
            $error = "Error updating event: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Event</title>
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
  <h2>Edit Event</h2>

  <!-- Error/Success Messages -->
  <?php if ($error) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <?php if ($success) : ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <!-- Event Edit Form -->
  <form action="edit_event.php?id=<?= $event['id'] ?>" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Event Title</label>
      <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($event['title']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="4" required><?= htmlspecialchars($event['description']) ?></textarea>
    </div>

    <div class="mb-3">
      <label for="event_date" class="form-label">Event Date</label>
      <input type="date" class="form-control" id="event_date" name="event_date" value="<?= $event['event_date'] ?>" required>
    </div>

    <div class="mb-3">
      <label for="event_time" class="form-label">Event Time</label>
      <input type="time" class="form-control" id="event_time" name="event_time" value="<?= $event['event_time'] ?>" required>
    </div>

    <div class="mb-3">
      <label for="location" class="form-label">Location</label>
      <input type="text" class="form-control" id="location" name="location" value="<?= htmlspecialchars($event['location']) ?>" required>
    </div>

    <div class="mb-3">
      <label for="event_image" class="form-label">Event Image (Optional)</label>
      <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*">
      <small class="form-text text-muted">Leave empty to keep the existing image.</small>
      <div class="mt-2">
        <img src="<?= $event['image_url'] ?>" alt="Event Image" style="width: 100px;">
      </div>
    </div>

    <button type="submit" class="btn btn-primary">Update Event</button>
    <a href="view_events.php" class="btn btn-secondary ms-3">Back to Events</a>
  </form>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>