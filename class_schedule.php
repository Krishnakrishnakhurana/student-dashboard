<?php
session_start();

// Database connection
$servername = "localhost"; 
$username = "root"; 
$password = ""; 
$dbname = "college_dashboard"; 

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if student is logged in
if (!isset($_SESSION['enrollment_number'])) {
    header("Location: login.php");
    exit();
}

// Get the student's enrollment number from session
$enrollment_number = $_SESSION['enrollment_number'];

// Fetch the student's course from the database
$student_sql = "SELECT course FROM student_info WHERE enrollment_number = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("i", $enrollment_number);
$stmt->execute();
$student_result = $stmt->get_result();

// Check if student exists and get the course
if ($student_result->num_rows > 0) {
    $student = $student_result->fetch_assoc();
    $student_course = $student['course'];
} else {
    echo "Error: Student not found.";
    exit();
}

// Fetch class schedules for the student's course
$schedule_sql = "SELECT * FROM class_schedule 
                 WHERE course = ? 
                 ORDER BY date ASC, time_from ASC";
$stmt = $conn->prepare($schedule_sql);
$stmt->bind_param("s", $student_course);
$stmt->execute();
$schedule_result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedule</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .schedule-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }

        .sidebar {
            height: auto;
            background-color: #343a40;
            position: fixed;
            padding-top: 20px;
        }

        .sidebar .nav-link {
            color: white;
        }

        .sidebar .nav-link:hover {
            background-color: #007bff;
            color: white;
        }

        .content {
            margin-left: 220px;
            padding: 20px;
        }

        @media (max-width: 768px) {
            .sidebar {
                position: relative;
                height: auto;
                margin-bottom: 20px;
            }

            .content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <?php include('header.php') ?>

        <!-- Main Content -->
        <div class="content">
            <div class="schedule-box">
                <h2 class="text-center">Class Schedule for <?php echo htmlspecialchars($student_course); ?></h2>
            </div>
            <?php if ($schedule_result->num_rows > 0): ?>
                <table class="table table-bordered mt-4">
                    <thead class="table-dark">
                        <tr>
                            <th>Subject</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Teacher</th>
                            <th>Venue</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $schedule_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                <td><?php echo date("d-m-Y", strtotime($row['date'])); ?></td>
                                <td><?php echo date("h:i A", strtotime($row['time_from'])) . " - " . date("h:i A", strtotime($row['time_to'])); ?></td>
                                <td><?php echo htmlspecialchars($row['teacher']); ?></td>
                                <td><?php echo htmlspecialchars($row['venue']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-warning mt-3">No class schedule available for your course.</div>
            <?php endif; ?>
        </div>
    </div>
    <?php include('footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
