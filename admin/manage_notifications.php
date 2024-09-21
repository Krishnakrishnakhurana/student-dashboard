<?php
session_start();
require_once 'db.php'; // Ensure this file defines the $conn variable for database connection

// Check if faculty is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

$message = ''; // Initialize the message variable

// Handle form submission to add a notification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $title = htmlspecialchars($_POST['title']);
    $content = htmlspecialchars($_POST['content']);
    $course = htmlspecialchars($_POST['course']);
    $year = htmlspecialchars($_POST['year']);

    // Prepare the SQL statement
    $sql = "INSERT INTO notifications (title, content, course, year, date_posted) VALUES (?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssss", $title, $content, $course, $year);

        // Execute the query and handle success/error message
        if ($stmt->execute()) {
            $message = "Notification added successfully!";
        } else {
            $message = "Error adding notification: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $message = "Database error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Notifications</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include('header.php') ?>
    <div class="container mt-5">
        <h2>Manage Notifications</h2>

        <!-- Notification form -->
        <form method="POST" action="">
            <div class="mb-3">
                <label for="title" class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Content</label>
                <textarea name="content" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select name="course" class="form-select">
                    <option value="all">All</option>
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <select name="year" class="form-select">
                    <option value="all">All</option>
                    <option value="1st Year">1st Year</option>
                    <option value="2nd Year">2nd Year</option>
                    <option value="3rd Year">3rd Year</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Notification</button>
        </form>

        <!-- Success/Error Message -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-info mt-3"><?php echo $message; ?></div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
