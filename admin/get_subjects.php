<?php
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

// Get course and year from AJAX request
$course = $_POST['course'];
$year = $_POST['year'];

$subjects = [];

// Define subjects based on course and year
if ($course == 'BCA' && $year == '1') {
    $subjects = ['Mathematics', 'Programming Concepts', 'Digital Logic', 'Communication Skills'];
} elseif ($course == 'BCA' && $year == '2') {
    $subjects = ['Management', 'C++', 'Computer Networks', 'Computer Organization'];
} elseif ($course == 'BCA' && $year == '3') {
    $subjects = ['Java', 'Database Management Systems', 'Operating Systems', 'Web Technologies'];
} elseif ($course == 'BBA' && $year == '1') {
    $subjects = ['Business Organization', 'Accounting', 'Micro Economics', 'Business Communication'];
} elseif ($course == 'BBA' && $year == '2') {
    $subjects = ['Marketing Management', 'Human Resource Management', 'Business Law', 'Operations Management'];
} elseif ($course == 'BBA' && $year == '3') {
    $subjects = ['Financial Management', 'Entrepreneurship', 'International Business', 'Business Ethics'];
} elseif ($course == 'BCOM H' && $year == '1') {
    $subjects = ['Financial Accounting', 'Business Law', 'Micro Economics', 'Business Communication'];
} elseif ($course == 'BCOM H' && $year == '2') {
    $subjects = ['Corporate Accounting', 'Cost Accounting', 'Macroeconomics', 'Financial Management'];
} elseif ($course == 'BCOM H' && $year == '3') {
    $subjects = ['Auditing', 'Income Tax', 'Business Statistics', 'Business Environment'];
}

// Return subjects as JSON
echo json_encode($subjects);

$conn->close();
?>