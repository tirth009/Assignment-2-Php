<?php
include 'config.php';

// Fetch all students from database
$sql = "SELECT * FROM students ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Student Records Management System - View All Student Records">
    <meta name="author" content="Student Records Portal">
    <meta name="keywords" content="student, records, view, database, management">
    <title>View Students - Student Records Portal</title>
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
                            <a class="nav-link" href="add-student.php">
                                <i class="fas fa-plus me-1"></i>Add Student
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="view-students.php">
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
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h2 class="mb-0">
                    <i class="fas fa-list me-2"></i>
                    Student Records Database
                </h2>
            </div>
            <div class="card-body p-0">
                <?php if ($result->num_rows > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-dark">
                                <tr>
                                    <th>Student ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Course</th>
                                    <th>Grade</th>
                                    <th>GPA</th>
                                    <th>Enrollment Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><strong><?php echo htmlspecialchars($row['student_id']); ?></strong></td>
                                    <td><?php echo htmlspecialchars($row['first_name'] . ' ' . $row['last_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['course']); ?></td>
                                    <td>
                                        <span class="badge grade-<?php echo strtolower(substr($row['grade'], 0, 1)); ?>">
                                            <?php echo htmlspecialchars($row['grade']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo $row['gpa'] ? number_format($row['gpa'], 2) : 'N/A'; ?></td>
                                    <td><?php echo date('M d, Y', strtotime($row['enrollment_date'])); ?></td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No Student Records Found</h4>
                        <p class="text-muted">Start by <a href="add-student.php">adding a new student</a>.</p>
                    </div>
                <?php endif; ?>
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

<?php
$conn->close();
?>