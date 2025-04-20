<?php
include 'session_validator.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Events</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
  <div class="container-fluid">
    <a class="navbar-brand" href="user_dashboard.php">Event Portal</a>
    <div class="d-flex">
      <span class="navbar-text text-white me-3">
        Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>
      </span>
      <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
  </div>
</nav>

<!-- My Events -->
<div class="container mt-5">
    <h3 class="mb-4">Available Events</h3>
    
    <div class="row">
        <?php
        $sql = "SELECT * FROM events ORDER BY event_date ASC";
        $result = $conn->query($sql);

        if ($result->num_rows > 0):
            while ($row = $result->fetch_assoc()):
        ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100 shadow-sm">
                    <?php if (!empty($row['image_url'])): ?>
                        <!-- <?php echo $row['image_url']; ?> -->
                        <img src="<?= 'admin/' . $row['image_url']; ?>" class="card-img-top" style="height: 200px; object-fit: cover;">
                        <?php endif; ?>
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($row['title']); ?></h5>
                        <p class="card-text"><?= htmlspecialchars($row['description']); ?></p>
                        <p><strong>Date:</strong> <?= $row['event_date']; ?></p>
                        <p><strong>Time:</strong> <?= $row['event_time']; ?></p>
                        <p><strong>Location:</strong> <?= htmlspecialchars($row['location']); ?></p>
                        <!-- Optional: register button -->
                        <a href="register_event.php?event_id=<?= $row['id']; ?>" class="btn btn-success btn-sm">Register</a>
                        </div>
                </div>
            </div>
        <?php
            endwhile;
        else:
            echo "<p>No events found.</p>";
        endif;
        ?>
    </div>
</div>

<!-- Footer -->
<?php include 'footer.php'; ?>

</body>
</html>
