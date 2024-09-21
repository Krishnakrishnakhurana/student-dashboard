<?php
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

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $enrollment_number = $_POST['enrollment_number'];
    $name = $_POST['name'];
    $course = $_POST['course'];
    $year = $_POST['year'];
    $subject_complaint = $_POST['subject_complaint'];
    $subject_suggestion = $_POST['subject_suggestion'] ?? '';
    $description = $_POST['description'];

    $sql = "INSERT INTO feedback (enrollment_number, name, course, year, subject_complaint, subject_suggestion, description)
            VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $enrollment_number, $name, $course, $year, $subject_complaint, $subject_suggestion, $description);
    
    if ($stmt->execute()) {
        $message = "Feedback submitted successfully!";
    } else {
        $message = "Error: Could not submit feedback. Please try again.";
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Support / Feedback Form</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f0f0;
        }
        .navbar {
            background-color: #07294d;
        }
        .navbar a:hover {
            background-color: #FF1A0985;
            border-radius: 20px;
        }
        .sidebar {
            height: 100vh;
            background-color: #07294d;
            padding-top: 20px;
            position: fixed;
            transition: 0.3s;
        }
        .sidebar h4 {
            color: white;
            margin-bottom: 40px;
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
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        .card {
            margin-bottom: 20px;
        }
        .btn-primary {
            background-color: #07294d;
            border: none;
        }
        .btn-primary:hover {
            background-color: red;
        }
        footer {
            text-align: center;
            padding: 20px;
            background-color: #07294d;
            color: white;
            position: auto;
            bottom: 0;
            width: 100%;
        }
        /* Ensure the form scrolls when there's too much content */
        .container {
            overflow-y: auto;
            height: 80vh;
            margin-bottom: 80px; /* Ensure space for footer */
        }
        /* Sidebar collapse for mobile */
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .main-content {
                margin-left: 0;
            }
            .sidebar a {
                padding: 10px;
                font-size: 14px;
            }
        }
    </style>
    <script>
        function toggleSubjectSuggestions() {
            var subjectComplaintYes = document.getElementById('subject_complaint_yes').checked;
            var suggestionsDiv = document.getElementById('subject_suggestions');
            suggestionsDiv.style.display = subjectComplaintYes ? 'block' : 'none';
        }

        function loadSubjectSuggestions(course) {
            var bcaSubjects = ['Data Structures', 'Operating Systems', 'DBMS', 'Computer Networks'];
            var bbaSubjects = ['Accounting', 'Marketing', 'Management', 'Economics'];

            var suggestionsDropdown = document.getElementById('subject_suggestion');
            suggestionsDropdown.innerHTML = '';

            var subjects = course === 'BCA' ? bcaSubjects : bbaSubjects;

            subjects.forEach(function(subject) {
                var option = document.createElement('option');
                option.value = subject;
                option.text = subject;
                suggestionsDropdown.appendChild(option);
            });
        }

        window.onload = function() {
            <?php if (!empty($message)): ?>
                var feedbackModal = new bootstrap.Modal(document.getElementById('feedbackModal'), {});
                feedbackModal.show();
            <?php endif; ?>
        }
    </script>
</head>
<body>
    <?php include('header.php')?>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container mt-5">
            <h2 class="text-center">Contact Support / Feedback Form</h2>
            
            <form method="POST" action="contact_support.php" class="mt-4">
                <div class="mb-3">
                    <label for="enrollment_number" class="form-label">Enrollment Number</label>
                    <input type="text" name="enrollment_number" class="form-control" id="enrollment_number" required>
                </div>
                
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                </div>
                
                <div class="mb-3">
                    <label for="course" class="form-label">Course</label>
                    <select name="course" class="form-select" id="course" onchange="loadSubjectSuggestions(this.value);" required>
                        <option value="BCA">BCA</option>
                        <option value="BBA">BBA</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="year" class="form-label">Year</label>
                    <select name="year" class="form-select" id="year" required>
                        <option value="1st Year">1st Year</option>
                        <option value="2nd Year">2nd Year</option>
                        <option value="3rd Year">3rd Year</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label class="form-label">Is this a subject-related complaint?</label>
                    <div>
                        <input type="radio" id="subject_complaint_yes" name="subject_complaint" value="yes" onclick="toggleSubjectSuggestions();" required>
                        <label for="subject_complaint_yes">Yes</label>
                    </div>
                    <div>
                        <input type="radio" id="subject_complaint_no" name="subject_complaint" value="no" onclick="toggleSubjectSuggestions();" required>
                        <label for="subject_complaint_no">No</label>
                    </div>
                </div>

                <div id="subject_suggestions" style="display: none;" class="mb-3">
                    <label for="subject_suggestion" class="form-label">Subject</label>
                    <select name="subject_suggestion" class="form-select" id="subject_suggestion">
                    </select>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea name="description" class="form-control" id="description" rows="4" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 College Dashboard. All rights reserved.</p>
    </footer>

    <!-- Modal for Feedback Submission Result -->
    <div class="modal fade" id="feedbackModal" tabindex="-1" aria-labelledby="feedbackModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="feedbackModalLabel">Feedback Status</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php echo htmlspecialchars($message); ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">OK</button>
                </div>
            </div>
        </div>
    </div>

   

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
