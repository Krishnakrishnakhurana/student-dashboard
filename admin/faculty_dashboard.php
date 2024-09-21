<?php
session_start();

// Check if faculty is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php"); // Redirect to login if not authenticated
    exit();
}

// Database connection setup
$servername = "localhost"; // Change if your database server is different
$username = "root"; // Change if your MySQL username is different
$password = ""; // Change if your MySQL password is set
$dbname = "college_dashboard"; // Change to your actual database name

// Create the connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch faculty details
$employee_id = $_SESSION['employee_id']; // Get the employee_id from session
$sql = "SELECT * FROM faculty_info WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id); // Bind the employee_id to the query
$stmt->execute();
$result = $stmt->get_result();

// Check if faculty data was found
if ($result->num_rows > 0) {
    $faculty = $result->fetch_assoc(); // Assign faculty data to $faculty variable
} else {
    echo "No faculty found with this ID.";
    exit();
}

$conn->close(); // Close the connection
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }

        .sidebar {
            height: 100vh;
            background-color: #07294d;
            padding-top: 20px;
            position: fixed;
            width: 250px;
        }

        .sidebar a {
            color: white;
            padding: 10px;
            display: block;
            text-decoration: none;
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
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h4 class="text-center text-white">Faculty Dashboard</h4>
        <a href="faculty_dashboard.php">Dashboard</a>
        <a href="view_students.php">View Students</a>
        <a href="manage_notifications.php">Manage Notifications</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="add_marks.php">Add marks</a>
        <a href="logout.php">Logout</a>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2>Welcome, <?php echo htmlspecialchars($faculty['name']); ?></h2>

        <!-- Manage Notifications -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Notifications</h4>
                <a href="manage_notifications.php" class="btn btn-primary">Manage Notifications</a>
            </div>
        </div>

        <!-- Manage Events -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">Manage Events</h4>
                <a href="manage_events.php" class="btn btn-primary">Manage Events</a>
            </div>
        </div>
        
        <!-- View Students -->
        <div class="card">
            <div class="card-body">
                <h4 class="card-title">View Student Information</h4>
                <a href="view_students.php" class="btn btn-primary">View Students</a>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
