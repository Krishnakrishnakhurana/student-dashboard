<?php
session_start();
require_once 'db.php';
include('header.php'); 
// Check if faculty is logged in
if (!isset($_SESSION['employee_id'])) {
    header("Location: login.php");
    exit();
}

// Get the faculty details
$employee_id = $_SESSION['employee_id'];
$sql = "SELECT designation, course, year FROM faculty_info WHERE employee_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $employee_id);
$stmt->execute();
$result = $stmt->get_result();
$faculty = $result->fetch_assoc();
$designation = $faculty['designation'];
$course = $faculty['course'];
$year = $faculty['year'];

$stmt->close(); // Close the previous prepared statement

// SQL query to fetch students based on role
if ($designation == 'director' || $designation == 'dean' || $designation == 'chairman') {
    // Director, Dean, Chairman can view all students
    $sql = "SELECT * FROM student_info ORDER BY enrollment_number ASC";
    $stmt = $conn->prepare($sql);
} elseif ($designation == 'HOD') {
    // HOD can view students from their course
    $sql = "SELECT * FROM student_info WHERE course = ? ORDER BY enrollment_number ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $course);
} elseif ($designation == 'coordinator') {
    // Coordinator can view students from their course and year
    $sql = "SELECT * FROM student_info WHERE course = ? AND year = ? ORDER BY enrollment_number ASC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $course, $year);
}

// Execute the query and fetch students
$stmt->execute();
$students_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Students</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h2>Student List</h2>
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Course</th>
                    <th>Year</th>
                    <th>Enrollment Number</th>
                    <th>Section</th>
                    <th>ABC ID</th>
                    <th>Gmail</th>
                    <th>Phone Number</th>
                    <th>Aadhaar Number</th>
                    <th>Guardian's Nmber</th>

                </tr>
            </thead>
            <tbody>
                <?php if ($students_result->num_rows > 0): ?>
                    <?php while ($student = $students_result->fetch_assoc()): ?>
                        <tr>
                            
                            <td><?php echo $student['name']; ?></td>
                            <td><?php echo $student['course']; ?></td>
                            <td><?php echo $student['year']; ?></td>
                            <td><?php echo $student['enrollment_number']; ?></td>
                            <td><?php echo $student['section']; ?></td>
                            <td><?php echo $student['abc_id']; ?></td>
                            <td><?php echo $student['gmail']; ?></td>
                            <td><?php echo $student['phone_number']; ?></td>
                            <td><?php echo $student['aadhaar_number']; ?></td>
                            <td><?php echo $student['guardian_phone_number']; ?></td>
                            

                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5">No students found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$stmt->close();
$conn->close();
?>
