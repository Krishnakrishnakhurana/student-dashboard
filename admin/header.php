<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
    margin: 0;
    padding: 0;
    width: 100%;
    height: 100%;
    background-color: #f0f0f0;
}

.sidebar {
    height: 100vh;
    background-color: #07294d;
    padding-top: 0; /* Remove padding */
    position: fixed;
    top: 0; /* Aligns the sidebar to the top */
    width: 250px;
    transition: 0.3s;
}
.sidebar h4 {
    color: white;
    margin: 20px 0 40px 0; /* Optional, if you want space for the heading */
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
        </style>
<!-- Sidebar -->
<div class="sidebar">
        <h4 class="text-center text-white">Faculty Dashboard</h4>
        <a href="faculty_dashboard.php">Dashboard</a>
        <a href="view_students.php">View Students</a>
        <a href="manage_notifications.php">Manage Notifications</a>
        <a href="manage_events.php">Manage Events</a>
        <a href="add_marks.php">add marks</a>
        <a href="logout.php">Logout</a>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>