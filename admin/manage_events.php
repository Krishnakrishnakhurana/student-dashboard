<?php
session_start();
require_once 'db.php';

// Check if faculty is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$message = ''; // Initialize the message variable

// Handle form submission to add an event
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $activity_name = htmlspecialchars($_POST['activity_name']);
    $date = htmlspecialchars($_POST['date']);
    $time_from = htmlspecialchars($_POST['time_from']);
    $time_to = htmlspecialchars($_POST['time_to']);
    $venue = htmlspecialchars($_POST['venue']);
    $supervising_teacher = htmlspecialchars($_POST['supervising_teacher']); // Missing semicolon added here

    // Insert the event into the database
    $sql = "INSERT INTO extracurricular_activities (activity_name, date, time_from, time_to, supervising_teacher, venue) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssss", $activity_name, $date, $time_from, $time_to, $supervising_teacher, $venue);

        // Execute the query and handle success/error message
        if ($stmt->execute()) {
            $message = "Event added successfully!";
        } else {
            $message = "Error adding event: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Database error: " . $conn->error;
    }
}

// Handle deletion of an event
if (isset($_GET['delete_event_id'])) {
    $event_id = intval($_GET['delete_event_id']);
    
    // Delete event from the database
    $sql = "DELETE FROM extracurricular_activities WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $event_id);

    if ($stmt->execute()) {
        $message = "Event deleted successfully!";
    } else {
        $message = "Error deleting event.";
    }

    $stmt->close();
}

// Fetch all events from the database
$sql = "SELECT * FROM extracurricular_activities ORDER BY date DESC";
$events_result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Extracurricular Activities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('header.php') ?>
    <div class="container mt-5">
        <h2>Manage Extracurricular Activities</h2>

        <!-- Event form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="activity_name" class="form-label">Activity Name</label>
                <input type="text" name="activity_name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="date" class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="time_from" class="form-label">Time From</label>
                <input type="time" name="time_from" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="time_to" class="form-label">Time To</label>
                <input type="time" name="time_to" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="venue" class="form-label">Venue</label>
                <input type="text" name="venue" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="supervising_teacher" class="form-label">Supervising Teacher</label>
                <input type="text" name="supervising_teacher" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Add Activity</button>
        </form>

        <!-- Success/Error Message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?php echo $message; ?></div>
        <?php endif; ?>

        <!-- Existing Events -->
        <h3 class="mt-5">Existing Activities</h3>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Activity Name</th>
                    <th>Date</th>
                    <th>Time From</th>
                    <th>Time To</th>
                    <th>Venue</th>
                    <th>Supervising Teacher</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($events_result->num_rows > 0): ?>
                    <?php while ($event = $events_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $event['activity_name']; ?></td>
                            <td><?php echo $event['date']; ?></td>
                            <td><?php echo $event['time_from']; ?></td>
                            <td><?php echo $event['time_to']; ?></td>
                            <td><?php echo $event['venue']; ?></td>
                            <td><?php echo $event['supervising_teacher']; ?></td>
                            <td>
                                <a href="manage_events.php?delete_event_id=<?php echo $event['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this activity?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7">No activities found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
