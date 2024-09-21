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

$error = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enrollment_number = $conn->real_escape_string($_POST['enrollment_number']);
    $password = $conn->real_escape_string($_POST['password']);

    // Fetch the hashed password from the database
    $sql = "SELECT password FROM students WHERE enrollment_number = '$enrollment_number'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        $hashed_password = $row['password'];

        // Verify the entered password with the hashed password
        if (password_verify($password, $hashed_password)) {
            // Login successful, store session variables and redirect to dashboard
            $_SESSION['enrollment_number'] = $enrollment_number;
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid Enrollment Number or Password.";
        }
    } else {
        $error = "Invalid Enrollment Number or Password.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }
        .login-container h2 {
            font-family: 'Poppins', sans-serif;
            font-weight: 600;
            color: #343a40;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #4e73df;
            border: none;
        }
        .btn-primary:hover {
            background-color: #375a7f;
        }
        .alert-danger {
            font-size: 0.9rem;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Student Login</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST" class="mt-3">
            <div class="mb-3">
                <label for="enrollment_number" class="form-label">Enrollment Number</label>
                <input type="text" name="enrollment_number" class="form-control" id="enrollment_number" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" name="password" class="form-control" id="password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
