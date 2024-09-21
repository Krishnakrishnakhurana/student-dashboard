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

$message = "";

// Debugging step: Check if faculty_id is set in session
if (!isset($_SESSION['employee_id'])) {
    die("Faculty ID is not set in session.");
}

// Fetch faculty designation and year
$faculty_id = $_SESSION['employee_id'];
$sql_faculty = "SELECT designation, year FROM faculty_info WHERE employee_id = ?";
$stmt_faculty = $conn->prepare($sql_faculty);
$stmt_faculty->bind_param("s", $faculty_id);
$stmt_faculty->execute();
$result_faculty = $stmt_faculty->get_result();
$faculty = $result_faculty->fetch_assoc();

if (!$faculty) {
    die("No faculty data found for the provided faculty ID.");
}

$designation = $faculty['designation'];
$faculty_year = $faculty['year'];

// Initialize filter variables
$filter_course = isset($_GET['course']) ? $_GET['course'] : '';
$filter_year = isset($_GET['year']) ? $_GET['year'] : '';
$filter_section = isset($_GET['section']) ? $_GET['section'] : '';

// Build the query based on filters
$sql_students = "SELECT enrollment_number, name, course, year FROM student_info WHERE 1 = 1";

// Apply course filter (if not disabled)
if (!empty($filter_course) && $designation != 'hod' && $designation != 'coordinator') {
    $sql_students .= " AND course = '$filter_course'";
}

// Apply year filter (if not disabled)
if (!empty($filter_year)) {
    $sql_students .= " AND year = '$filter_year'";
}

// Apply section filter
if (!empty($filter_section)) {
    $sql_students .= " AND section = '$filter_section'";
}

$result_students = $conn->query($sql_students);

// Handle form submission to add marks
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $marks_data = $_POST['marks'];

    foreach ($marks_data as $enrollment_number => $marks) {
        foreach ($marks as $subject => $mark) {
            // Check if marks entry already exists
            $check_sql = "SELECT * FROM marks WHERE enrollment_number = ? AND subject = ?";
            $check_stmt = $conn->prepare($check_sql);
            $check_stmt->bind_param("ss", $enrollment_number, $subject);
            $check_stmt->execute();
            $result = $check_stmt->get_result();

            if ($result->num_rows > 0) {
                // Update existing marks
                $update_sql = "UPDATE marks SET marks = ? WHERE enrollment_number = ? AND subject = ?";
                $update_stmt = $conn->prepare($update_sql);
                $update_stmt->bind_param("iss", $mark, $enrollment_number, $subject);
                $update_stmt->execute();
            } else {
                // Insert new marks
                $insert_sql = "INSERT INTO marks (enrollment_number, subject, marks, total_marks) VALUES (?, ?, ?, 100)";
                $insert_stmt = $conn->prepare($insert_sql);
                $insert_stmt->bind_param("ssi", $enrollment_number, $subject, $mark);
                $insert_stmt->execute();
            }
        }
    }

    $message = "Marks updated successfully!";
}

// Close the connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Add Marks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .sidebar {
            width: 225px;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            background-color: #07294d;
        }

        .main-content {
            margin-left: 225px;
            padding: 20px;
        }
    </style>
</head>
<body>
    <?php include('header.php') ?>

    <div class="main-content">
        <h2 class="text-center mt-4">Add Student Marks</h2>

        <?php if (!empty($message)): ?>
            <div class="alert alert-success text-center">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Filter buttons for course, year, and section -->
        <div class="filter-buttons text-center mb-4">
            <form method="GET" action="admin_add_marks.php">
                <?php if ($designation != 'coordinator'): ?>
                    <select name="course" class="form-select" <?php if ($designation == 'hod') echo 'disabled'; ?>>
                        <option value="">Select Course</option>
                        <!-- Add options for courses -->
                        <option value="BCA" <?php if ($filter_course == 'BCA') echo 'selected'; ?>>BCA</option>
                        <option value="BBA" <?php if ($filter_course == 'BBA') echo 'selected'; ?>>BBA</option>
                    </select>
                <?php endif; ?>

                <select name="year" class="form-select" <?php if ($designation == 'coordinator') echo 'disabled'; ?>>
                    <option value="">Select Year</option>
                    <option value="<?php echo $faculty_year; ?>" <?php if ($designation == 'coordinator') echo 'selected'; ?>>
                        <?php echo $faculty_year; ?>
                    </option>
                    <!-- Add other year options if needed -->
                    <option value="1st Year" <?php if ($filter_year == '1st Year') echo 'selected'; ?>>1st Year</option>
                    <option value="2nd Year" <?php if ($filter_year == '2nd Year') echo 'selected'; ?>>2nd Year</option>
                    <option value="3rd Year" <?php if ($filter_year == '3rd Year') echo 'selected'; ?>>3rd Year</option>
                </select>

                <select name="section" class="form-select">
                    <option value="">Select Section</option>
                    <!-- Add options for sections -->
                    <option value="A" <?php if ($filter_section == 'A') echo 'selected'; ?>>A</option>
                    <option value="B" <?php if ($filter_section == 'B') echo 'selected'; ?>>B</option>
                </select>

                <button type="submit" class="btn btn-primary mt-3">Filter</button>
            </form>
        </div>

        <form method="POST" action="admin_add_marks.php">
            <table class="table table-bordered mt-4">
                <thead>
                    <tr>
                        <th>Enrollment Number</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Year</th>
                        <th>Subject 1 Marks</th>
                        <th>Subject 2 Marks</th>
                        <th>Subject 3 Marks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result_students->num_rows > 0): ?>
                        <?php while ($row = $result_students->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['enrollment_number']); ?></td>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['course']); ?></td>
                                <td><?php echo htmlspecialchars($row['year']); ?></td>
                                <td><input type="number" name="marks[<?php echo $row['enrollment_number']; ?>][subject1]" class="form-control" placeholder="Enter marks"></td>
                                <td><input type="number" name="marks[<?php echo $row['enrollment_number']; ?>][subject2]" class="form-control" placeholder="Enter marks"></td>
                                <td><input type="number" name="marks[<?php echo $row['enrollment_number']; ?>][subject3]" class="form-control" placeholder="Enter marks"></td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="7" class="text-center">No students found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>

            <div class="text-center">
                <button type="submit" class="btn btn-primary">Submit Marks</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
