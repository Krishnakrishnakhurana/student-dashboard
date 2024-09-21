<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "college_dashboard";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Assuming the student's enrollment number is stored in the session
$enrollment_number = $_SESSION['enrollment_number'];

// Fetch student info
$sql_student = "SELECT name,enrollment_number, course, year FROM student_info WHERE enrollment_number = ?";
$stmt_student = $conn->prepare($sql_student);
$stmt_student->bind_param("s", $enrollment_number);
$stmt_student->execute();
$result_student = $stmt_student->get_result();
$student = $result_student->fetch_assoc();

// Fetch marks for the student
$sql_marks = "SELECT subject, marks, total_marks FROM marks WHERE enrollment_number = ?";
$stmt_marks = $conn->prepare($sql_marks);
$stmt_marks->bind_param("s", $enrollment_number);
$stmt_marks->execute();
$result_marks = $stmt_marks->get_result();

$stmt_student->close();
$stmt_marks->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Marks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f7f9fb;
            margin: 0;
            padding: 0;
        }

        .sidebar {
            width: 225px;
            background-color: #07294d;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 20px;
            height: 100vh;
            z-index: 1000;
        }

        .sidebar h4 {
            color: white;
            margin-bottom: 20px;
        }

        .sidebar a {
            color: white;
            display: block;
            padding: 10px 0;
            text-decoration: none;
            font-weight: 500;
        }

        .sidebar a:hover {
            background-color: #FF1A09;
            padding-left: 10px;
            transition: 0.3s;
        }

        .main-content {
            margin-left: 240px; /* Adjusted to avoid sidebar overlap */
            padding: 20px;
            transition: margin-left 0.3s;
        }

        .container {
            max-width: 100%;
        }

        /* Responsive design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }

            .container {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include('header.php') ?>
    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">
            <h2 class="text-center">Internal Marks</h2>

            <!-- Display Student Information -->
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Student Details</h5>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($student['name']); ?></p>
                    <p><strong>Enrollment Number:</strong> <?php echo htmlspecialchars($student['enrollment_number']); ?></p>
                    <p><strong>Course:</strong> <?php echo htmlspecialchars($student['course']); ?></p>
                    <p><strong>Year:</strong> <?php echo htmlspecialchars($student['year']); ?></p>
                </div>
            </div>

            <!-- Display Marks -->
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Marks</h5>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Subject</th>
                                <th>Marks Obtained</th>
                                <th>Total Marks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result_marks->num_rows > 0): ?>
                                <?php while ($row = $result_marks->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                                        <td><?php echo htmlspecialchars($row['marks']); ?></td>
                                        <td><?php echo htmlspecialchars($row['total_marks']); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="3" class="text-center">No marks available</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
<?php include('footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

