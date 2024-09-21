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

// Assuming the student's enrollment number is stored in the session
$enrollment_number = $_SESSION['enrollment_number'];

// Fetch the student information from the database
$sql = "SELECT name, gmail, phone_number, address FROM student_info WHERE enrollment_number = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $enrollment_number);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

$message = "";

// Update student information on form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_name = $_POST['name'];
    $new_gmail = $_POST['gmail'];
    $new_phone_number = $_POST['phone_number'];
    $new_address = $_POST['address'];
    $new_password = password_hash($_POST['password'], PASSWORD_BCRYPT); // Hash the new password

    // Update query
    $update_sql = "UPDATE student_info SET name = ?, gmail = ?, phone_number = ?, address = ?, password = ? WHERE enrollment_number = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssss", $new_name, $new_gmail, $new_phone_number, $new_address, $new_password, $enrollment_number);

    if ($update_stmt->execute()) {
        $message = "Profile updated successfully!";
    } else {
        $message = "Error updating profile. Please try again.";
    }
    
    $update_stmt->close();
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Set the body layout */
        body {
            background-color: #f7f9fb; /* Match the background color */
            margin: 0;
            padding: 0;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
        }

        /* Sidebar */
        .sidebar {
            width: 225px;
            background-color: #07294d;
            color: white;
            position: fixed;
            top: 0;
            bottom: 0;
            padding: 20px;
            height: 100vh; /* Make sidebar take full height */
        }

        /* Main content wrapper */
        .content-wrapper {
            margin-left: 225px; /* Ensure content doesn't go under the sidebar */
            flex-grow: 1; /* Let content grow to take available space */
            padding: 20px;
            background-color: #f7f9fb;
        }

        /* Footer styling */
        .footer {
            background-color: #07294d;
            color: white;
            text-align: center;
            padding: 10px 0;
            width: 100%;
            position: relative;
            bottom: 0;
            flex-shrink: 0; /* Ensure footer stays at the bottom */
        }

        /* Adjust form styles */
        .container {
            max-width: 600px;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            color: #07294d;
            font-weight: 600;
        }

        label {
            color: #07294d;
        }

        .btn-primary {
            background-color: #07294d;
            border: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%; /* Full width for smaller screens */
                position: relative;
            }

            .content-wrapper {
                margin-left: 0; /* No margin when sidebar takes full width */
            }
        }
    </style>
</head>
<body>

    <?php include('header.php') ?>

    

    <!-- Main Content -->
    <div class="content-wrapper">
        <div class="container mt-5">
            <h2 class="text-center">Account Settings</h2>

            <!-- Display a message if the profile is updated -->
            <?php if (!empty($message)): ?>
                <div class="alert alert-success">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <!-- Settings Form -->
            <form method="POST" action="settings.php">
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="<?php echo htmlspecialchars($student['name']); ?>" required>
                </div>

                <!-- Gmail -->
                <div class="mb-3">
                    <label for="gmail" class="form-label">Email</label>
                    <input type="email" name="gmail" class="form-control" id="gmail" value="<?php echo htmlspecialchars($student['gmail']); ?>" required>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" name="phone_number" class="form-control" id="phone_number" value="<?php echo htmlspecialchars($student['phone_number']); ?>" required>
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea name="address" class="form-control" id="address" required><?php echo htmlspecialchars($student['address']); ?></textarea>
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input type="password" name="password" class="form-control" id="password" required>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </form>

            <!-- Logout Button -->
            <div class="mt-4">
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; 2024 College Dashboard. All rights reserved.</p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
