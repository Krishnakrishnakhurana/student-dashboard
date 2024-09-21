CREATE TABLE marks (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_number VARCHAR(255),
    subject VARCHAR(100),
    marks INT,
    total_marks INT,
    FOREIGN KEY (enrollment_number) REFERENCES student_info(enrollment_number) ON DELETE CASCADE
);
