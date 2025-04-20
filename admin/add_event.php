<?php

include 'session_validator.php';


$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get form data
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $event_date = $_POST['event_date'];
    $event_time = $_POST['event_time'];
    $location = trim($_POST['location']);
    
    // Image upload handling
    $image_url = "";
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

    // Insert event into database
    if (empty($error)) {
        $sql = "INSERT INTO events (title, description, event_date, event_time, location, image_url, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssi", $title, $description, $event_date, $event_time, $location, $image_url, $_SESSION['admin_id']);

        if ($stmt->execute()) {
            $success = "Event added successfully!";
        } else {
            $error = "Error adding event: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Add Event</title>
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
  <h2>Add New Event</h2>

  <!-- Error/Success Messages -->
  <?php if ($error) : ?>
    <div class="alert alert-danger"><?= $error ?></div>
  <?php endif; ?>
  <?php if ($success) : ?>
    <div class="alert alert-success"><?= $success ?></div>
  <?php endif; ?>

  <!-- Event Form -->
  <form action="add_event.php" method="POST" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="title" class="form-label">Event Title</label>
      <input type="text" class="form-control" id="title" name="title" required>
    </div>

    <div class="mb-3">
      <label for="description" class="form-label">Description</label>
      <textarea class="form-control" id="description" name="description" rows="4" required></textarea>
    </div>

    <div class="mb-3">
      <label for="event_date" class="form-label">Event Date</label>
      <input type="date" class="form-control" id="event_date" name="event_date" required>
    </div>

    <div class="mb-3">
      <label for="event_time" class="form-label">Event Time</label>
      <input type="time" class="form-control" id="event_time" name="event_time" required>
    </div>

    <div class="mb-3">
      <label for="location" class="form-label">Location</label>
      <input type="text" class="form-control" id="location" name="location" required>
    </div>

    <div class="mb-3">
      <label for="event_image" class="form-label">Event Image</label>
      <input type="file" class="form-control" id="event_image" name="event_image" accept="image/*" required>
    </div>

    <button type="submit" class="btn btn-success">Add Event</button>
    <a href="view_events.php" class="btn btn-secondary ms-3">Back to Events</a>
  </form>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
