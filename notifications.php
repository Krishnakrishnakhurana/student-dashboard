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

// Get the student's course and year from the database
$enrollment_number = $_SESSION['enrollment_number'];
$student_sql = "SELECT course, year FROM student_info WHERE enrollment_number = ?";
$stmt = $conn->prepare($student_sql);
$stmt->bind_param("i", $enrollment_number);
$stmt->execute();
$student_result = $stmt->get_result();

// Check if student exists and get course and year
if ($student_result->num_rows > 0) {
    $student = $student_result->fetch_assoc();
    $student_course = $student['course'];
    $student_year = $student['year'];
} else {
    echo "Error: Student not found.";
    exit();
}

// Fetch notifications (both general and specific to the student's course and year)
$notification_sql = "SELECT * FROM notifications 
                     WHERE (course = ? OR course = 'all') 
                     AND (year = ? OR year = 'all') 
                     ORDER BY date_posted DESC";
$stmt = $conn->prepare($notification_sql);
$stmt->bind_param("ss", $student_course, $student_year);
$stmt->execute();
$notifications_result = $stmt->get_result();

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications and Announcements</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f7f9fb;
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        .sidebar {
            width: 225px;
            background-color: #07294d;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            padding: 20px;
        }

        .content-wrapper {
            margin-left: 225px; /* Sidebar width */
            flex-grow: 1; /* Let the content grow to take remaining space */
            padding: 20px;
            background-color: #f7f9fb;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding-top: 50px;
        }

        .notification-header {
            text-align: center;
            color: #07294d;
            margin-bottom: 30px;
            font-weight: 600;
            font-size: 2rem;
        }

        .list-group-item {
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 10px;
            box-shadow: 0px 3px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s ease;
        }

        .list-group-item:hover {
            transform: translateY(-5px);
            background-color: #f5f5f5;
        }

        h5 {
            color: #07294d;
        }

        small {
            color: #6c757d;
        }

        .alert-warning {
            background-color: #ffecb5;
            color: #856404;
        }

        /* Footer Styles */
        .footer {
            background-color: #07294d;
            color: white;
            text-align: center;
            padding: 10px 0;
            position: relative;
            width: 100%;
            bottom: 0;
            flex-shrink: 0; /* Ensure footer is pushed down */
        }

        /* Responsive Design for Smaller Screens */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%; /* Full width for smaller screens */
                position: relative;
            }

            .content-wrapper {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

    <?php include('header.php') ?>

    

    <div class="content-wrapper">
        <div class="container">
            <h2 class="notification-header">Notifications and Announcements</h2>

            <?php if ($notifications_result->num_rows > 0): ?>
                <div class="list-group">
                    <?php while ($row = $notifications_result->fetch_assoc()): ?>
                        <a href="#" class="list-group-item list-group-item-action">
                            <h5 class="mb-1"><?php echo htmlspecialchars($row['title']); ?></h5>
                            <p class="mb-1"><?php echo htmlspecialchars($row['content']); ?></p>
                            <small><?php echo date("F j, Y", strtotime($row['date_posted'])); ?></small>
                        </a>
                    <?php endwhile; ?>
                </div>
            <?php else: ?>
                <div class="alert alert-warning">No notifications available at the moment.</div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Sticky Footer -->
    <div class="footer">
        <p>&copy; 2024 College Dashboard. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
