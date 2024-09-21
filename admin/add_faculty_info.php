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
$success = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data and check if fields are set
    $name = isset($_POST['name']) ? $conn->real_escape_string($_POST['name']) : null;
    $employee_id = isset($_SESSION['employee_id']) ? $_SESSION['employee_id'] : null;
    $course = isset($_POST['course']) ? $conn->real_escape_string($_POST['course']) : null;
    $year = isset($_POST['year']) ? $conn->real_escape_string($_POST['year']) : null;
    $designation = isset($_POST['designation']) ? $conn->real_escape_string($_POST['designation']) : null;
    $is_subject_teacher = isset($_POST['is_subject_teacher']) ? 1 : 0;

    // Insert into faculty_info table
    if ($name && $employee_id && $designation) {
        $sql = "INSERT INTO faculty_info (employee_id, name, course, year, designation, is_subject_teacher)
                VALUES ('$employee_id', '$name', '$course', '$year', '$designation', '$is_subject_teacher')
                ON DUPLICATE KEY UPDATE 
                name='$name', course='$course', year='$year', designation='$designation', is_subject_teacher='$is_subject_teacher'";

        if ($conn->query($sql) === TRUE) {
            $success = "Faculty information added successfully!";
        } else {
            $error = "Error: " . $sql . "<br>" . $conn->error;
        }

        // If subject teacher, insert into subject_teacher table
        if ($is_subject_teacher && isset($_POST['subject_assignments'])) {
            foreach ($_POST['subject_assignments'] as $assignment) {
                $course = isset($assignment['course']) ? $conn->real_escape_string($assignment['course']) : null;
                $year = isset($assignment['year']) ? $conn->real_escape_string($assignment['year']) : null;
                $subject = isset($assignment['subject']) ? $conn->real_escape_string($assignment['subject']) : null;
                $section = isset($assignment['section']) ? implode(",", $assignment['section']) : '';

                if ($course && $year && $subject) {
                    $insert_subject_teacher = "INSERT INTO subject_teacher (employee_id, course, year, subject, section)
                                               VALUES ('$employee_id', '$course', '$year', '$subject', '$section')";
                    $conn->query($insert_subject_teacher);
                }
            }
        }
    } else {
        $error = "Please ensure all required fields are filled in.";
    }
}

$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Faculty Info</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Include jQuery for AJAX -->

    <script>
        // Fetch subjects dynamically from get_subjects.php
        function fetchSubjects(course, year, subjectDropdownId) {
            $.ajax({
                url: 'get_subjects.php',
                method: 'POST',
                data: { course: course, year: year },
                success: function(response) {
                    let subjects = JSON.parse(response);
                    let subjectDropdown = document.getElementById(subjectDropdownId);
                    subjectDropdown.innerHTML = ''; // Clear the existing subjects

                    // Populate the dropdown with subjects
                    subjects.forEach(function(subject) {
                        let option = document.createElement("option");
                        option.value = subject;
                        option.text = subject;
                        subjectDropdown.appendChild(option);
                    });
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching subjects:', error);
                }
            });
        }

        // Handle course and year selection changes
        function onCourseOrYearChange(courseSelectId, yearSelectId, subjectDropdownId) {
            let course = document.getElementById(courseSelectId).value;
            let year = document.getElementById(yearSelectId).value;

            if (course && year) {
                fetchSubjects(course, year, subjectDropdownId);
            }
        }

        // Add another subject assignment dynamically
        function addSubjectAssignment() {
            let subjectTeacherSection = document.getElementById("subject_teacher_section_template").cloneNode(true);
            subjectTeacherSection.style.display = "block"; // Make it visible

            let uniqueId = Math.random().toString(36).substr(2, 9);
            subjectTeacherSection.querySelector(".courseSelect").id = "course_" + uniqueId;
            subjectTeacherSection.querySelector(".yearSelect").id = "year_" + uniqueId;
            subjectTeacherSection.querySelector(".subjectSelect").id = "subject_" + uniqueId;

            subjectTeacherSection.querySelector(".courseSelect").onchange = function() {
                onCourseOrYearChange("course_" + uniqueId, "year_" + uniqueId, "subject_" + uniqueId);
            };
            subjectTeacherSection.querySelector(".yearSelect").onchange = function() {
                onCourseOrYearChange("course_" + uniqueId, "year_" + uniqueId, "subject_" + uniqueId);
            };

            document.getElementById("subject_teacher_section_container").appendChild(subjectTeacherSection);
        }
    </script>
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center">Add Faculty Information</h2>
        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php elseif (!empty($success)): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <!-- Faculty Info -->
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" name="name" class="form-control" id="name" required>
            </div>

            <div class="mb-3">
                <label for="designation" class="form-label">Designation</label>
                <select name="designation" id="designation" class="form-control">
                    <option value="director">Director</option>
                    <option value="dean">Dean</option>
                    <option value="chairman">Chairman</option>
                    <option value="HOD">HOD</option>
                    <option value="associate_professor">Associate Professor</option>
                    <option value="assistant_professor">Assistant Professor</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="is_subject_teacher" class="form-label">Are you a subject teacher?</label>
                <input type="radio" name="is_subject_teacher" value="1" id="subject_teacher_yes" onclick="document.getElementById('subject_teacher_section').style.display = 'block';">
                <label for="subject_teacher_yes">Yes</label>
                <input type="radio" name="is_subject_teacher" value="0" id="subject_teacher_no" onclick="document.getElementById('subject_teacher_section').style.display = 'none';">
                <label for="subject_teacher_no">No</label>
            </div>

            <!-- Subject Teacher Section -->
            <div id="subject_teacher_section" style="display: none;">
                <div id="subject_teacher_section_container"></div>
                
                <!-- Template (hidden, will be cloned) -->
                <div id="subject_teacher_section_template" style="display: none;">
                    <div class="mb-3">
                        <label for="course" class="form-label">Select Course</label>
                        <select name="subject_assignments[][course]" class="form-control courseSelect">
                            <option value="">Select Course</option>
                            <option value="BCA">BCA</option>
                            <option value="BBA">BBA</option>
                            <option value="MCA">MCA</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="year" class="form-label">Select Year</label>
                        <select name="subject_assignments[][year]" class="form-control yearSelect">
                            <option value="">Select Year</option>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Select Subject</label>
                        <select name="subject_assignments[][subject]" class="form-control subjectSelect"></select>
                    </div>

                    <div class="mb-3">
                        <label for="section" class="form-label">Section</label>
                        <select name="subject_assignments[][section][]" class="form-control" multiple>
                            <option value="A">A</option>
                            <option value="B">B</option>
                            <option value="C">C</option>
                        </select>
                    </div>
                </div>

                <button type="button" class="btn btn-success" onclick="addSubjectAssignment()">Add Subject Assignment</button>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>

    <!-- Include Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
