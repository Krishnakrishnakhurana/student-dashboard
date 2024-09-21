<?php
// Start session
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

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = $conn->real_escape_string($_POST['name']);
    $employee_id = $conn->real_escape_string($_POST['employee_id']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm_password']);

    // Check if passwords match
    if ($password != $confirm_password) {
        $error = "Passwords do not match!";
    } else {
        // Check if enrollment number already exists
        $enrollmentCheckQuery = "SELECT employee_id FROM faculty WHERE employee_id='$employee_id'";
        $result = $conn->query($enrollmentCheckQuery);

        if ($result->num_rows > 0) {
            $error = "Employee ID already registered!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert new user into database
            $sql = "INSERT INTO faculty (employee_id, name, password) 
                    VALUES ('$employee_id', '$name', '$hashed_password')";

            if ($conn->query($sql) === TRUE) {
                // Set session to store enrollment number
                $_SESSION['employee_id'] = $employee_id;
                header("Location: add_faculty_info.php");
                exit();
            } else {
                $error = "Error: " . $sql . "<br>" . $conn->error;
            }
        }
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
    <title>Student Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f9;
        }
        .registration-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .form-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            color: #07294d;
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #07294d;
            border: none;
        }
        .btn-primary:hover {
            background-color: #004080;
        }
        .form-label {
            font-weight: bold;
            color: #07294d;
        }
        .alert-danger {
            text-align: center;
            font-size: 0.9rem;
        }
        .card {
            border: none;
            padding: 20px;
            border-radius: 12px;
        }
        .form-control {
            border-radius: 0.4rem;
            border: 1px solid #ddd;
        }
        .form-control:focus {
            box-shadow: none;
            border-color: #07294d;
        }
        .login-link {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2 class="form-title">Faculty Registration</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="register.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>
            <div class="mb-3">
                <label for="employee_id" class="form-label">Employee ID</label>
                <input type="number" name="employee_id" class="form-control" id="enrollment_number" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <div class="mb-3">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <input type="password" name="confirm_password" class="form-control" id="confirm_password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
        </form>
        <p class="login-link mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
