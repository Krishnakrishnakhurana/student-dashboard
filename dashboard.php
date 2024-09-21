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

// Check if enrollment number exists in session
if (!isset($_SESSION['enrollment_number'])) {
    header("Location: login.php"); // Redirect to login if session isn't set
    exit();
}

$enrollment_number = $_SESSION['enrollment_number']; 

// Fetch student information
$sql = "SELECT * FROM student_info WHERE enrollment_number='$enrollment_number'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Fetch student details
    $student = $result->fetch_assoc();
} else {
    echo "No student found with this enrollment number.";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }
.navbar{
    background-color:#07294d;
}
        .sidebar {
            height: 100vh;
            background-color: #07294d;
            padding-top: 20px;
            position: fixed;
            width: 250px;
            transition: 0.3s;
        }

        .sidebar h4 {
            color: white;
            margin-bottom: 40px;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .sidebar a:hover {
            background-color: red;
        }

        .main-content {
            margin-left: 250px;
            padding: 20px;
        }

        .card {
            margin-bottom: 20px;
        }

        h2 {
            color: red;
            margin-bottom: 30px;
        }

        .card-title {
            color: #07294d;
            font-weight: bold;
        }

        .btn-primary {
            background-color: #07294d;
            border: none;
        }

        .btn-primary:hover {
            background-color: red;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #07294d;
            color: white;
            position: auto;
            bottom: 0;
            width: 100%;
        }

        /* Sidebar collapse for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar a {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar for desktop -->
<div class="sidebar d-none d-lg-block">
    <div class="container-fluid">
        <h4 class="text-center">Dashboard</h4>
        <a href="#">Home</a>
        <a href="class_schedule.php">Class Schedule</a>
        <a href="attendance_records.php">Attendance Records</a>
        <a href="extracurricular_activities.php">Extracurricular Activities</a>
        <a href="notifications.php">Notifications</a>
        <a href="marks.php">Marks</a>
        <a href="contact_support.php">Contact Support</a>
        <a href="settings.php">Settings</a>
    </div>
</div>

<!-- Sidebar for mobile -->
<nav class="navbar navbar-expand-lg navbar-dark  d-lg-none">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Dashboard</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="class_schedule.php">Class Schedule</a></li>
                <li class="nav-item"><a class="nav-link" href="attendance_records.php">Attendance Records</a></li>
                <li class="nav-item"><a class="nav-link" href="extracurricular_activities.php">Extracurricular Activities</a></li>
                <li class="nav-item"><a class="nav-link" href="notifications.php">Notifications</a></li>
                <li class="nav-item"><a class="nav-link" href="contact_support.php">Contact Support</a></li>
                <li class="nav-item"><a class="nav-link" href="settings.php">Settings</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="main-content">
    <div class="container">
        <h2 class="text-center">Welcome, <?php echo $student['name']; ?></h2>

        <!-- Student Profile -->
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Student Profile</h4>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Name:</strong> <?php echo $student['name']; ?></li>
                    <li class="list-group-item"><strong>Enrollment Number:</strong> <?php echo $student['enrollment_number']; ?></li>
                    <li class="list-group-item"><strong>Course:</strong> <?php echo $student['course']; ?></li>
                    <li class="list-group-item"><strong>Year:</strong> <?php echo $student['year']; ?></li>
                    <li class="list-group-item"><strong>Section:</strong> <?php echo $student['section']; ?></li>
                    <li class="list-group-item"><strong>Date of Birth:</strong> <?php echo $student['dob']; ?></li>
                    <li class="list-group-item"><strong>Phone Number:</strong> <?php echo $student['phone_number']; ?></li>
                    <li class="list-group-item"><strong>Address:</strong> <?php echo $student['address']; ?></li>
                </ul>
            </div>
        </div>

        <!-- Class Schedule -->
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Class Schedule</h4>
                <a href="class_schedule.php" class="btn btn-primary">View Schedule</a>
            </div>
        </div>

        <!-- Notifications & Announcements -->
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Notifications & Announcements</h4>
                <?php
                // Database connection for notifications
                $conn = new mysqli($servername, $username, $password, $dbname);

                // Fetch notifications (course and year-specific or general)
                $notifications_sql = "SELECT * FROM notifications 
                                      WHERE (course = ? OR course = 'all') 
                                      AND (year = ? OR year = 'all') 
                                      ORDER BY date_posted DESC LIMIT 5";
                $stmt = $conn->prepare($notifications_sql);
                $stmt->bind_param("ss", $student['course'], $student['year']);
                $stmt->execute();
                $notifications_result = $stmt->get_result();
                ?>

                <?php if ($notifications_result->num_rows > 0): ?>
                    <ul class="list-group">
                        <?php while ($row = $notifications_result->fetch_assoc()): ?>
                            <li class="list-group-item">
                                <strong><?php echo htmlspecialchars($row['title']); ?></strong><br>
                                <small class="text-muted"><?php echo date("F j, Y", strtotime($row['date_posted'])); ?></small>
                                <p><?php echo htmlspecialchars($row['content']); ?></p>
                            </li>
                        <?php endwhile; ?>
                    </ul>
                <?php else: ?>
                    <div class="alert alert-info">No new notifications or announcements.</div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Extracurricular Activities -->
        <div class="card shadow">
            <div class="card-body">
                <h4 class="card-title">Upcoming Extracurricular Activities</h4>
                <a href="extracurricular_activities.php" class="btn btn-primary">View Activities</a>
            </div>
        </div>
    </div>
</div>

<footer>
    <p>&copy; 2024 College Dashboard. All rights reserved.</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
