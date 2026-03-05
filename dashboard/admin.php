<?php
// admission-system/dashboard/admin_enhanced.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Admin');
$page_title = "Admin Dashboard";

// Get statistics
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalPayments = $conn->query("SELECT SUM(paid_amount) as total FROM payments WHERE payment_status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$pendingPayments = $conn->query("SELECT SUM(total_fee - paid_amount) as total FROM payments WHERE payment_status = 'Pending'")->fetch_assoc()['total'] ?? 0;

// Get all users
$users_result = $conn->query("SELECT * FROM users WHERE role != 'Admin' ORDER BY created_at DESC");

$success = '';
$error = '';

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['create_user'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $role = $conn->real_escape_string($_POST['role']);
    
    if (strlen($password) < 6) {
        $error = "Password must be at least 6 characters!";
    } else {
        // Check if username exists
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows == 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
            if ($conn->query($sql)) {
                $success = "✅ User created successfully!";
                $users_result = $conn->query("SELECT * FROM users WHERE role != 'Admin' ORDER BY created_at DESC");
            } else {
                $error = "❌ Error creating user!";
            }
        } else {
            $error = "❌ Username already exists!";
        }
    }
}

// Handle user deletion
if (isset($_GET['delete_user'])) {
    $user_id = (int)$_GET['delete_user'];
    $conn->query("DELETE FROM users WHERE id = $user_id AND role != 'Admin'");
    header("Location: admin.php?success=User deleted successfully!");
    exit();
}

include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> Admission Management System
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <span class="nav-item nav-link"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../actions/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5><i class="fas fa-cog"></i> Admin Menu</h5>
        <ul class="sidebar-menu">
            <li><a href="#dashboard" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#users-section"><i class="fas fa-users-cog"></i> Manage Users</a></li>
            <li><a href="#statistics"><i class="fas fa-chart-bar"></i> Statistics</a></li>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 id="dashboard"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics Section -->
        <div id="statistics" class="row mb-4">
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-success">
                    <div class="stat-icon"><i class="fas fa-users"></i></div>
                    <h5><i class="fas fa-graduation-cap"></i> Total Students</h5>
                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-info">
                    <div class="stat-icon"><i class="fas fa-user-tie"></i></div>
                    <h5><i class="fas fa-users-cog"></i> System Users</h5>
                    <div class="stat-number"><?php echo $totalUsers; ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-success">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h5><i class="fas fa-check-circle"></i> Total Collected</h5>
                    <div class="stat-number">₹<?php echo number_format($totalPayments, 0); ?></div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6">
                <div class="stat-card card-warning">
                    <div class="stat-icon"><i class="fas fa-clock"></i></div>
                    <h5><i class="fas fa-hourglass-half"></i> Pending Amount</h5>
                    <div class="stat-number">₹<?php echo number_format($pendingPayments, 0); ?></div>
                </div>
            </div>
        </div>

        <!-- Create User Section -->
        <div class="form-card">
            <h4><i class="fas fa-user-plus"></i> Create New System User</h4>
            <form method="POST">
                <div class="row">
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="username" class="form-label">Username <span class="required">*</span></label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="password" class="form-label">Password <span class="required">*</span></label>
                            <input type="password" class="form-control" id="password" name="password" placeholder="Min 6 characters" minlength="6" required>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-6">
                        <div class="form-group">
                            <label for="role" class="form-label">Select Role <span class="required">*</span></label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="">-- Select Role --</option>
                                <option value="Support Staff"><i class="fas fa-headset"></i> Support Staff</option>
                                <option value="Counselor"><i class="fas fa-user-tie"></i> Counselor</option>
                                <option value="Cashier"><i class="fas fa-cash-register"></i> Cashier</option>
                                <option value="Management"><i class="fas fa-chart-line"></i> Management</option>
                            </select>
                        </div>
                    </div>
                </div>
                <button type="submit" name="create_user" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Create User Account
                </button>
            </form>
        </div>

        <!-- Users List Section -->
        <div id="users-section" class="form-card">
            <h4><i class="fas fa-list"></i> System Users (<?php echo $users_result->num_rows; ?> Total)</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th><i class="fas fa-hashtag"></i> ID</th>
                            <th><i class="fas fa-user"></i> Username</th>
                            <th><i class="fas fa-tag"></i> Role</th>
                            <th><i class="fas fa-calendar"></i> Created</th>
                            <th><i class="fas fa-tools"></i> Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                        if ($users_result->num_rows > 0):
                            while ($user = $users_result->fetch_assoc()): 
                                $roleIcon = '';
                                $roleClass = 'badge-secondary';
                                
                                switch($user['role']) {
                                    case 'Support Staff':
                                        $roleIcon = 'fas fa-headset';
                                        $roleClass = 'badge-info';
                                        break;
                                    case 'Counselor':
                                        $roleIcon = 'fas fa-user-tie';
                                        $roleClass = 'badge-primary';
                                        break;
                                    case 'Cashier':
                                        $roleIcon = 'fas fa-cash-register';
                                        $roleClass = 'badge-success';
                                        break;
                                    case 'Management':
                                        $roleIcon = 'fas fa-chart-line';
                                        $roleClass = 'badge-warning';
                                        break;
                                    case 'Admin':
                                        $roleIcon = 'fas fa-user-shield';
                                        $roleClass = 'badge-danger';
                                        break;
                                }
                        ?>
                            <tr>
                                <td><strong>#<?php echo $user['id']; ?></strong></td>
                                <td>
                                    <i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($user['username']); ?>
                                </td>
                                <td>
                                    <span class="badge <?php echo $roleClass; ?>">
                                        <i class="<?php echo $roleIcon; ?>"></i> <?php echo $user['role']; ?>
                                    </span>
                                </td>
                                <td><?php echo date('d M Y, H:i', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['role'] != 'Admin'): ?>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="admin.php?delete_user=<?php echo $user['id']; ?>" 
                                           onclick="return confirm('Delete this user?')" 
                                           class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    <?php else: ?>
                                        <span class="badge badge-danger"><i class="fas fa-lock"></i> System Admin</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title"><i class="fas fa-edit"></i> Edit User - <?php echo htmlspecialchars($user['username']); ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label"><i class="fas fa-user"></i> Username</label>
                                                    <input type="text" class="form-control" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label"><i class="fas fa-tag"></i> Role</label>
                                                    <select class="form-select" name="role" required>
                                                        <option value="Support Staff" <?php echo $user['role'] == 'Support Staff' ? 'selected' : ''; ?>>Support Staff</option>
                                                        <option value="Counselor" <?php echo $user['role'] == 'Counselor' ? 'selected' : ''; ?>>Counselor</option>
                                                        <option value="Cashier" <?php echo $user['role'] == 'Cashier' ? 'selected' : ''; ?>>Cashier</option>
                                                        <option value="Management" <?php echo $user['role'] == 'Management' ? 'selected' : ''; ?>>Management</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="edit_user" class="btn btn-primary">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    <i class="fas fa-inbox"></i> No users found
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/script.js"></script>
</body>
</html>