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

// Fetch all extracurricular activities
$activities_sql = "SELECT * FROM extracurricular_activities ORDER BY date ASC, time_from ASC";
$activities_result = $conn->query($activities_sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extracurricular Activities</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar styling */
        .sidebar {
            width: 225px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            background-color: #07294d;
            color: white;
            padding: 20px;
            box-shadow: 2px 0px 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 0;
            text-decoration: none;
            font-size: 16px;
        }

        .sidebar a:hover {
            background-color: #1c3e69;
            border-radius: 4px;
        }

        /* Content wrapper with margin to respect sidebar width */
        .content-wrapper {
            margin-left: 225px; /* Add left margin to account for sidebar */
            padding: 20px;
        }

        /* Responsive design for smaller screens */
        @media (max-width: 768px) {
            .content-wrapper {
                margin-left: 0; /* Remove sidebar margin on small screens */
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar included -->
    <?php include('header.php')?>

    <div class="content-wrapper mt-5">
        <h2 class="text-center">Extracurricular Activities</h2>
        <?php if ($activities_result->num_rows > 0): ?>
            <table class="table table-bordered mt-3">
                <thead>
                    <tr>
                        <th>Activity Name</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Supervising Teacher</th>
                        <th>Venue</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $activities_result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['activity_name']; ?></td>
                            <td><?php echo date("d-m-Y", strtotime($row['date'])); ?></td>
                            <td><?php echo date("h:i A", strtotime($row['time_from'])) . " - " . date("h:i A", strtotime($row['time_to'])); ?></td>
                            <td><?php echo $row['supervising_teacher']; ?></td>
                            <td><?php echo $row['venue']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-warning">No extracurricular activities available at the moment.</div>
        <?php endif; ?>
    </div>

    <!-- Footer included -->
    <?php include('footer.php') ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
