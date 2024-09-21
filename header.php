
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    background-color: #f0f0f0;
}

.navbar {
    background-color: #07294d;
    margin: 0;
}

.sidebar {
    width: 225px;
    position: fixed;
    height: 100vh;
    top: 0;
    left: 0;
    background-color: #07294d;
    padding-top: 20px;
    transition: 0.3s;
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

.sidebar h4 {
    color: white;
    margin: 20px 0 40px 0; /* Optional, if you want space for the heading */
}

.main-content {
    margin-left: 250px;
    padding-top: 20px; /* Adjust as necessary */
    min-height: 100vh;
    background-color: #f0f0f0;
}

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }

            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>

<!-- Sidebar for desktop -->
<div class="sidebar d-none d-lg-block">
    <h4 class="text-center">Dashboard</h4>
    <a href="dashboard.php">Home</a>
    <a href="class_schedule.php">Class Schedule</a>
    <a href="attendance_records.php">Attendance Records</a>
    <a href="extracurricular_activities.php">Extracurricular Activities</a>
    <a href="notifications.php">Notifications</a>
    <a href="marks.php">Marks</a>
    <a href="contact_support.php">Contact Support</a>
    <a href="settings.php">Settings</a>
</div>

<!-- Navbar for mobile -->
<nav class="navbar navbar-expand-lg navbar-dark d-lg-none">
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
