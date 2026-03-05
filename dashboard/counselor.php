<?php
// admission-system/dashboard/counselor.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Counselor');
$page_title = "Counselor Dashboard";

// Get all students
$students_result = $conn->query("SELECT * FROM students ORDER BY created_at DESC");

// Handle department assignment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_department'])) {
    $student_id = (int)$_POST['student_id'];
    $department = $conn->real_escape_string($_POST['department']);
    
    $conn->query("UPDATE students SET department = '$department' WHERE id = $student_id");
    $success = "Department updated successfully!";
}

include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> Admission System
        </a>
        <div class="navbar-nav ms-auto">
            <span class="nav-item nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="../actions/logout.php" class="nav-link">Logout</a>
        </div>
    </div>
</nav>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5><i class="fas fa-bars"></i> Menu</h5>
        <ul class="sidebar-menu">
            <li><a href="counselor.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#applications"><i class="fas fa-folder"></i> Applications</a></li>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Counselor Dashboard</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Applications Section -->
        <div id="applications" class="form-card">
            <h4><i class="fas fa-folder"></i> Student Applications</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Current Department</th>
                            <th>Date of Application</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $students_result = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
                        while ($student = $students_result->fetch_assoc()): 
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($student['name']); ?></td>
                                <td><?php echo htmlspecialchars($student['email']); ?></td>
                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($student['created_at'])); ?></td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#departmentModal<?php echo $student['id']; ?>">
                                        <i class="fas fa-edit"></i> Change Dept
                                    </button>
                                </td>
                            </tr>

                            <!-- Department Modal -->
                            <div class="modal fade" id="departmentModal<?php echo $student['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Change Department</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                <div class="mb-3">
                                                    <label for="department" class="form-label">Select New Department</label>
                                                    <select class="form-select" name="department" required>
                                                        <option value="">Select Department</option>
                                                        <option value="Computer Science">Computer Science</option>
                                                        <option value="Electronics">Electronics</option>
                                                        <option value="Mechanical">Mechanical</option>
                                                        <option value="Civil">Civil</option>
                                                        <option value="Electrical">Electrical</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="update_department" class="btn btn-primary">Update</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>