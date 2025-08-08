<?php
session_start();
include 'config.php';

$message = '';
$message_type = '';

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate and sanitize input
    $student_id = mysqli_real_escape_string($conn, trim($_POST['student_id']));
    $first_name = mysqli_real_escape_string($conn, trim($_POST['first_name']));
    $last_name = mysqli_real_escape_string($conn, trim($_POST['last_name']));
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $course = mysqli_real_escape_string($conn, trim($_POST['course']));
    $grade = mysqli_real_escape_string($conn, trim($_POST['grade']));
    $gpa = !empty($_POST['gpa']) ? floatval($_POST['gpa']) : NULL;
    $enrollment_date = mysqli_real_escape_string($conn, $_POST['enrollment_date']);
    
    // Basic validation
    if (!empty($student_id) && !empty($first_name) && !empty($last_name) && 
        !empty($email) && !empty($course) && !empty($grade) && !empty($enrollment_date)) {
        
        // Insert into database
        $sql = "INSERT INTO students (student_id, first_name, last_name, email, course, grade, gpa, enrollment_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssds", $student_id, $first_name, $last_name, $email, $course, $grade, $gpa, $enrollment_date);
        
        if ($stmt->execute()) {
            $message = "Student record added successfully!";
            $message_type = "success";
        } else {
            $message = "Error: " . $stmt->error;
            $message_type = "danger";
        }
        
        $stmt->close();
    } else {
        $message = "Please fill in all required fields.";
        $message_type = "danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Records Management System - Add Student Information">
    <meta name="author" content="Student Records Portal">
    <meta name="keywords" content="student, records, management, education, database">
    <title>Add Student - Student Records Portal</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="styles.css" rel="stylesheet">
</head>
<body>
    <!-- Header with Navigation -->
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
            <div class="container">
                <a class="navbar-brand" href="#">
                    <i class="fas fa-graduation-cap me-2"></i>
                    Student Records Portal
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link active" href="add-student.php">
                                <i class="fas fa-plus me-1"></i>Add Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="view-students.php">
                                <i class="fas fa-list me-1"></i>View Records
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>

    <!-- Main Content -->
    <main class="container my-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h2 class="mb-0">
                            <i class="fas fa-user-plus me-2"></i>
                            Add New Student Record
                        </h2>
                    </div>
                    <div class="card-body">
                        <!-- Display messages -->
                        <?php if (!empty($message)): ?>
                            <div class="alert alert-<?php echo $message_type; ?> alert-dismissible fade show" role="alert">
                                <?php echo htmlspecialchars($message); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Student Form -->
                        <form method="POST" action="add-student.php" novalidate>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="first_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>First Name *
                                    </label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="last_name" class="form-label">
                                        <i class="fas fa-user me-1"></i>Last Name *
                                    </label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" required>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="student_id" class="form-label">
                                        <i class="fas fa-id-card me-1"></i>Student ID *
                                    </label>
                                    <input type="text" class="form-control" id="student_id" name="student_id" 
                                           pattern="[0-9]{6,}" title="Student ID must be at least 6 digits" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">
                                        <i class="fas fa-envelope me-1"></i>Email Address *
                                    </label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="course" class="form-label">
                                        <i class="fas fa-book me-1"></i>Course *
                                    </label>
                                    <select class="form-select" id="course" name="course" required>
                                        <option value="">Select Course</option>
                                        <option value="Computer Science">Computer Science</option>
                                        <option value="Business Administration">Business Administration</option>
                                        <option value="Engineering">Engineering</option>
                                        <option value="Mathematics">Mathematics</option>
                                        <option value="English Literature">English Literature</option>
                                        <option value="Biology">Biology</option>
                                        <option value="Chemistry">Chemistry</option>
                                        <option value="Physics">Physics</option>
                                    </select>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="grade" class="form-label">
                                        <i class="fas fa-star me-1"></i>Current Grade *
                                    </label>
                                    <select class="form-select" id="grade" name="grade" required>
                                        <option value="">Select Grade</option>
                                        <option value="A+">A+</option>
                                        <option value="A">A</option>
                                        <option value="A-">A-</option>
                                        <option value="B+">B+</option>
                                        <option value="B">B</option>
                                        <option value="B-">B-</option>
                                        <option value="C+">C+</option>
                                        <option value="C">C</option>
                                        <option value="C-">C-</option>
                                        <option value="D">D</option>
                                        <option value="F">F</option>
                                    </select>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="enrollment_date" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Enrollment Date *
                                    </label>
                                    <input type="date" class="form-control" id="enrollment_date" name="enrollment_date" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="gpa" class="form-label">
                                        <i class="fas fa-chart-line me-1"></i>GPA
                                    </label>
                                    <input type="number" class="form-control" id="gpa" name="gpa" 
                                           min="0" max="4" step="0.01" placeholder="0.00 - 4.00">
                                </div>
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-save me-2"></i>Save Student Record
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="fas fa-graduation-cap me-2"></i>Student Records Portal</h5>
                    <p class="mb-0">Efficiently managing student information and academic records.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">© 2024 Student Records System</p>
                    <small>Built with PHP & MySQL</small>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>