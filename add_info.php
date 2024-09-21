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
    header("Location: login.php"); // Redirect to login if not registered
    exit();
}

$error = "";
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $enrollment_number = $_SESSION['enrollment_number']; // Use session variable
    $course = $conn->real_escape_string($_POST['course']);
    $year = $conn->real_escape_string($_POST['year']);
    $section = $conn->real_escape_string($_POST['section']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $aadhaar_number = $conn->real_escape_string($_POST['aadhaar_number']);
    $gmail = $conn->real_escape_string($_POST['gmail']);
    $abc_id = $conn->real_escape_string($_POST['abc_id']);
    $phone_number = $conn->real_escape_string($_POST['phone_number']);
    $guardian_phone_number = $conn->real_escape_string($_POST['guardian_phone_number']);
    $address = $conn->real_escape_string($_POST['address']);

    // Insert additional info into the 'student_info' table
    $sql = "INSERT INTO student_info (enrollment_number, name, course, year, section, dob, aadhaar_number, gmail, abc_id, phone_number, address, guardian_phone_number)
            VALUES ('$enrollment_number', '$name', '$course', '$year', '$section', '$dob', '$aadhaar_number', '$gmail', '$abc_id', '$phone_number', '$address', '$guardian_phone_number')
            ON DUPLICATE KEY UPDATE 
            name='$name', course='$course', year='$year', section='$section', dob='$dob', 
            aadhaar_number='$aadhaar_number', gmail='$gmail', abc_id='$abc_id', 
            phone_number='$phone_number', address='$address', guardian_phone_number=$guardian_phone_number";

    if ($conn->query($sql) === TRUE) {
        // If successful, redirect to the dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        $error = "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Your Information</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>
        <form action="add_info.php" method="POST" class="mt-3">
            <!-- Form Fields for student info -->
            <div class="mb-3">
                <label for="enrollment_number" class="form-label">Enrollment Number</label>
                <input type="text" name="enrollment_number" class="form-control" id="enrollment_number" value="<?php echo $_SESSION['enrollment_number']; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="course" class="form-label">Course</label>
                <select name="course" id="course" class="form-control" required>
                    <option value="BCA">BCA</option>
                    <option value="BBA">BBA</option>
                    <option value="BBA">BCOM H</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="year" class="form-label">Year</label>
                <select name="year" id="year" class="form-control" required>
                    <option value="1st">1st Year</option>
                    <option value="2nd">2nd Year</option>
                    <option value="3rd">3rd Year</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section (for BCA 1st Year and BBA)</label>
                <input type="text" name="section" class="form-control" id="section" >
            </div>
            <div class="mb-3">
                <label for="dob" class="form-label">Date of Birth</label>
                <input type="date" name="dob" class="form-control" id="dob" required>
            </div>
            <div class="mb-3">
                <label for="aadhaar_number" class="form-label">Aadhaar Number</label>
                <input type="number" name="aadhaar_number" class="form-control" id="aadhaar_number" required>
            </div>
            <div class="mb-3">
                <label for="gmail" class="form-label">Gmail</label>
                <input type="email" name="gmail" class="form-control" id="gmail" required>
            </div>
            <div class="mb-3">
                <label for="abc_id" class="form-label">ABC ID</label>
                <input type="text" name="abc_id" class="form-control" id="abc_id" required>
            </div>
            <div class="mb-3">
                <label for="phone_number" class="form-label">Phone Number</label>
                <input type="tel" name="phone_number" class="form-control" id="phone_number" required>
            </div>
            <div class="mb-3">
                <label for="guardian_phone_number" class="form-label">Guardian's Phone Number</label>
                <input type="tel" name="guardian_phone_number" class="form-control" id="guardian_phone_number" required>
            </div>
            <div class="mb-3">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" class="form-control" id="address" required>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
    <?php include('footer.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
