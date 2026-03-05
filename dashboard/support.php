<?php
// admission-system/dashboard/support.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Support Staff');
$page_title = "Support Staff Dashboard";

// Get all students
$students_result = $conn->query("SELECT * FROM students ORDER BY created_at DESC");
$totalStudents = $students_result->num_rows;

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
            <li><a href="support.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#add-student"><i class="fas fa-user-plus"></i> Add Student</a></li>
            <li><a href="#students-list"><i class="fas fa-list"></i> View Students</a></li>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Support Staff Dashboard</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <h5>Total Students</h5>
                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                </div>
            </div>
        </div>

        <!-- Add Student Form -->
        <div id="add-student" class="form-card">
            <h4><i class="fas fa-user-plus"></i> Add New Student</h4>
            <form method="POST" action="../actions/add_student.php" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="name" class="form-label">Student Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="dob" class="form-label">Date of Birth</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label">Phone Number</label>
                        <input type="tel" class="form-control" id="phone" name="phone" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                </div>

                <div class="mb-3">
                    <label for="department" class="form-label">Department/Course</label>
                    <select class="form-select" id="department" name="department" required>
                        <option value="">Select Department</option>
                        <option value="Computer Science">Computer Science</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Mechanical">Mechanical</option>
                        <option value="Civil">Civil</option>
                        <option value="Electrical">Electrical</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="documents" class="form-label">Upload Documents (PDF/JPG)</label>
                    <input type="file" class="form-control" id="documents" name="documents" accept=".pdf,.jpg,.jpeg">
                    <small class="form-text text-muted">Accepted: PDF, JPG (Max 5MB)</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Add Student
                </button>
            </form>
        </div>

        <!-- Students List -->
        <div id="students-list" class="form-card">
            <h4><i class="fas fa-list"></i> Student Applications</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Department</th>
                            <th>Applied Date</th>
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
                                <td><?php echo htmlspecialchars($student['phone']); ?></td>
                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                                <td><?php echo date('d-m-Y', strtotime($student['created_at'])); ?></td>
                                <td>
                                    <a href="#" class="btn btn-sm btn-info btn-action">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                </td>
                            </tr>
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