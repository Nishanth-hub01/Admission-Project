<?php
// admission-system/dashboard/admin.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Admin');
$page_title = "Admin Dashboard";

// Get statistics
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$totalUsers = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$totalPayments = $conn->query("SELECT SUM(paid_amount) as total FROM payments WHERE payment_status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$pendingPayments = $conn->query("SELECT SUM(total_fee - paid_amount) as total FROM payments WHERE payment_status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$totalApproved = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Confirmed'")->fetch_assoc()['count'];
$totalPending = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Enquiry'")->fetch_assoc()['count'];

// Get data for Department Chart
$departmentData = $conn->query("
    SELECT course_department, COUNT(*) as count 
    FROM students 
    WHERE course_department IS NOT NULL
    GROUP BY course_department 
    ORDER BY count DESC
");

$departments = [];
$departmentCounts = [];
while ($row = $departmentData->fetch_assoc()) {
    $departments[] = $row['course_department'];
    $departmentCounts[] = $row['count'];
}

// Get data for Payment Status Chart
$paymentStats = $conn->query("
    SELECT payment_status, COUNT(*) as count 
    FROM payments 
    GROUP BY payment_status
");

$paymentLabels = [];
$paymentCounts = [];
while ($row = $paymentStats->fetch_assoc()) {
    $paymentLabels[] = $row['payment_status'];
    $paymentCounts[] = $row['count'];
}

// Get data for Monthly Applications Chart
$monthlyData = $conn->query("
    SELECT DATE_FORMAT(created_at, '%b') as month, COUNT(*) as count 
    FROM students 
    GROUP BY MONTH(created_at)
    ORDER BY created_at ASC
    LIMIT 12
");

$months = [];
$monthlyCounts = [];
while ($row = $monthlyData->fetch_assoc()) {
    $months[] = $row['month'];
    $monthlyCounts[] = $row['count'];
}

// Get data for Payment Collection Chart
$paymentCollection = $conn->query("
    SELECT DATE_FORMAT(payment_date, '%b') as month, SUM(paid_amount) as amount 
    FROM payments 
    WHERE payment_date IS NOT NULL
    GROUP BY MONTH(payment_date)
    ORDER BY payment_date ASC
    LIMIT 12
");

$paymentMonths = [];
$paymentAmounts = [];
while ($row = $paymentCollection->fetch_assoc()) {
    $paymentMonths[] = $row['month'];
    $paymentAmounts[] = (int)$row['amount'];
}

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
        $check = $conn->query("SELECT id FROM users WHERE username = '$username'");
        if ($check->num_rows == 0) {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);
            $sql = "INSERT INTO users (username, password, role) VALUES ('$username', '$hashed_password', '$role')";
            if ($conn->query($sql)) {
                $success = "✅ User created successfully!";
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

// Handle user edit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit_user'])) {
    $user_id = (int)$_POST['user_id'];
    $username = $conn->real_escape_string($_POST['username']);
    $role = $conn->real_escape_string($_POST['role']);
    
    $sql = "UPDATE users SET username = '$username', role = '$role' WHERE id = $user_id";
    if ($conn->query($sql)) {
        $success = "✅ User updated successfully!";
    } else {
        $error = "❌ Error updating user!";
    }
}

// Calculate collection rate safely
$totalFees = $totalPayments + $pendingPayments;
$collectionRate = ($totalFees > 0) ? round(($totalPayments / $totalFees) * 100, 1) : 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/style.css">
    
    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 30px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .chart-title {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.1rem;
        }
    </style>
</head>
<body>

<!-- NAVBAR START -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> <strong>Admission System</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <span class="nav-link"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../actions/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>
<!-- NAVBAR END -->

<div style="display: flex; min-height: calc(100vh - 60px);">

    <!-- SIDEBAR START -->
    <div class="sidebar" style="width: 250px; background: #2c3e50; color: white; padding: 20px 0; position: fixed; height: calc(100vh - 60px); overflow-y: auto; top: 60px; left: 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
        <h5 style="padding: 15px 20px; color: #667eea; border-bottom: 1px solid #34495e;"><i class="fas fa-bars"></i> Menu</h5>
        <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0;">
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="admin.php" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="#statistics-charts" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-chart-bar"></i> Statistics & Charts</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="#users-section" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-users"></i> Manage Users</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="counselor_dashboard.php" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-graduation-cap"></i> Counselor Dashboard</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="../actions/logout.php" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>
    <!-- SIDEBAR END -->

    <!-- MAIN CONTENT START -->
    <div style="flex: 1; padding: 30px; margin-left: 250px; overflow-y: auto;">

        <h2 style="color: #667eea; margin-bottom: 25px;"><i class="fas fa-tachometer-alt"></i> Admin Dashboard</h2>

        <!-- ALERTS START -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert" style="border-left: 4px solid #2ecc71;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert" style="border-left: 4px solid #e74c3c;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <!-- ALERTS END -->

        <!-- STATISTICS CARDS START -->
        <div class="row mb-4">
            <!-- Card 1: Total Students -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #2ecc71; transition: all 0.3s;">
                    <div style="float: right; font-size: 3rem; color: #2ecc71; opacity: 0.15;">
                        <i class="fas fa-users"></i>
                    </div>
                    <h5 style="color: #2ecc71; margin: 0 0 10px 0; font-weight: 600;"><i class="fas fa-graduation-cap"></i> Total Students</h5>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;"><?php echo $totalStudents; ?></div>
                    <small style="color: #999;">Registered in system</small>
                </div>
            </div>

            <!-- Card 2: Approved Admissions -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #3498db; transition: all 0.3s;">
                    <div style="float: right; font-size: 3rem; color: #3498db; opacity: 0.15;">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h5 style="color: #3498db; margin: 0 0 10px 0; font-weight: 600;"><i class="fas fa-check"></i> Approved</h5>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;"><?php echo $totalApproved; ?></div>
                    <small style="color: #999;"><?php echo $totalStudents > 0 ? round(($totalApproved/$totalStudents)*100, 1) : 0; ?>% of total</small>
                </div>
            </div>

            <!-- Card 3: Total Collected -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #f39c12; transition: all 0.3s;">
                    <div style="float: right; font-size: 3rem; color: #f39c12; opacity: 0.15;">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h5 style="color: #f39c12; margin: 0 0 10px 0; font-weight: 600;"><i class="fas fa-check-circle"></i> Collected</h5>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">₹<?php echo number_format($totalPayments, 0); ?></div>
                    <small style="color: #999;">Payment received</small>
                </div>
            </div>

            <!-- Card 4: Pending Amount -->
            <div class="col-lg-3 col-md-6 mb-3">
                <div style="background: white; border-radius: 12px; padding: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); border-left: 5px solid #e74c3c; transition: all 0.3s;">
                    <div style="float: right; font-size: 3rem; color: #e74c3c; opacity: 0.15;">
                        <i class="fas fa-hourglass-end"></i>
                    </div>
                    <h5 style="color: #e74c3c; margin: 0 0 10px 0; font-weight: 600;"><i class="fas fa-clock"></i> Pending</h5>
                    <div style="font-size: 2.5rem; font-weight: bold; color: #333;">₹<?php echo number_format($pendingPayments, 0); ?></div>
                    <small style="color: #999;">Outstanding</small>
                </div>
            </div>
        </div>
        <!-- STATISTICS CARDS END -->

        <!-- CHARTS SECTION START -->
        <h3 id="statistics-charts" style="color: #667eea; margin-top: 40px; margin-bottom: 25px; font-weight: 700;"><i class="fas fa-chart-bar"></i> Statistics & Analytics</h3>

        <div class="row">
            <!-- Chart 1: Department-wise Distribution -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-graduation-cap"></i> Students by Department</div>
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>

            <!-- Chart 2: Payment Status Distribution -->
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-credit-card"></i> Payment Status Distribution</div>
                    <canvas id="paymentStatusChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart 3: Monthly Applications -->
            <div class="col-lg-12">
                <div class="chart-container" style="height: 400px;">
                    <div class="chart-title"><i class="fas fa-calendar-alt"></i> Monthly Applications Trend</div>
                    <canvas id="monthlyApplicationsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Chart 4: Monthly Payment Collection -->
            <div class="col-lg-12">
                <div class="chart-container" style="height: 400px;">
                    <div class="chart-title"><i class="fas fa-money-bill"></i> Monthly Fee Collection Trend</div>
                    <canvas id="paymentCollectionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Approval Status Chart -->
        <div class="row">
            <div class="col-lg-6">
                <div class="chart-container">
                    <div class="chart-title"><i class="fas fa-tasks"></i> Application Status</div>
                    <canvas id="approvalStatusChart"></canvas>
                </div>
            </div>

            <!-- Summary Card -->
            <div class="col-lg-6">
                <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: 350px; display: flex; flex-direction: column; justify-content: center;">
                    <h4 style="color: #667eea; margin-bottom: 20px; font-weight: 700;"><i class="fas fa-info-circle"></i> Quick Summary</h4>
                    
                    <div style="margin-bottom: 20px;">
                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <span style="color: #666; font-weight: 600;"><i class="fas fa-users"></i> Total Applications:</span>
                            <span style="font-size: 1.5rem; color: #667eea; font-weight: bold;"><?php echo $totalStudents; ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <span style="color: #666; font-weight: 600;"><i class="fas fa-check-circle"></i> Approved:</span>
                            <span style="font-size: 1.5rem; color: #27ae60; font-weight: bold;"><?php echo $totalApproved; ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <span style="color: #666; font-weight: 600;"><i class="fas fa-hourglass-half"></i> Pending:</span>
                            <span style="font-size: 1.5rem; color: #f39c12; font-weight: bold;"><?php echo $totalPending; ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px; margin-bottom: 10px;">
                            <span style="color: #666; font-weight: 600;"><i class="fas fa-money-bill"></i> Total Fees:</span>
                            <span style="font-size: 1.5rem; color: #3498db; font-weight: bold;">₹<?php echo number_format($totalFees, 0); ?></span>
                        </div>

                        <div style="display: flex; justify-content: space-between; align-items: center; padding: 15px; background: #f8f9fa; border-radius: 8px;">
                            <span style="color: #666; font-weight: 600;"><i class="fas fa-percentage"></i> Collection Rate:</span>
                            <span style="font-size: 1.5rem; color: #667eea; font-weight: bold;"><?php echo $collectionRate; ?>%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- CHARTS SECTION END -->

        <!-- CREATE USER FORM START -->
        <div style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px; margin-top: 40px;">
            <h4 style="color: #667eea; margin-bottom: 20px; font-weight: 700;"><i class="fas fa-user-plus"></i> Create New User</h4>
            
            <form method="POST">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="username" class="form-label" style="font-weight: 600;">Username *</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Enter username" required style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px 15px;">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="password" class="form-label" style="font-weight: 600;">Password *</label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Min 6 characters" minlength="6" required style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px 15px;">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="role" class="form-label" style="font-weight: 600;">Select Role *</label>
                        <select class="form-select" id="role" name="role" required style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px 15px;">
                            <option value="">-- Select Role --</option>
                            <option value="Support Staff">Support Staff</option>
                            <option value="Counselor">Counselor</option>
                            <option value="Cashier">Cashier</option>
                            <option value="Management">Management</option>
                        </select>
                    </div>
                </div>

                <button type="submit" name="create_user" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 10px 25px; border: none; border-radius: 8px; font-weight: 600; cursor: pointer;">
                    <i class="fas fa-plus"></i> Create User
                </button>
            </form>
        </div>
        <!-- CREATE USER FORM END -->

        <!-- USERS TABLE START -->
        <div id="users-section" style="background: white; border-radius: 12px; padding: 30px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
            <h4 style="color: #667eea; margin-bottom: 20px; font-weight: 700;"><i class="fas fa-list"></i> All Users</h4>

            <div style="overflow-x: auto;">
                <table class="table table-hover" style="margin: 0;">
                    <thead style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                        <tr>
                            <th>ID</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Created Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $users_result = $conn->query("SELECT * FROM users ORDER BY created_at DESC");
                        if ($users_result->num_rows > 0):
                            while ($user = $users_result->fetch_assoc()): 
                        ?>
                            <tr style="border-bottom: 1px solid #ecf0f1;">
                                <td><?php echo $user['id']; ?></td>
                                <td><strong><?php echo htmlspecialchars($user['username']); ?></strong></td>
                                <td>
                                    <?php
                                    $badgeColor = '';
                                    if ($user['role'] == 'Admin') $badgeColor = 'danger';
                                    else if ($user['role'] == 'Support Staff') $badgeColor = 'info';
                                    else if ($user['role'] == 'Counselor') $badgeColor = 'primary';
                                    else if ($user['role'] == 'Cashier') $badgeColor = 'success';
                                    else if ($user['role'] == 'Management') $badgeColor = 'warning';
                                    ?>
                                    <span class="badge bg-<?php echo $badgeColor; ?>"><?php echo $user['role']; ?></span>
                                </td>
                                <td><?php echo date('d-m-Y', strtotime($user['created_at'])); ?></td>
                                <td>
                                    <?php if ($user['role'] != 'Admin'): ?>
                                        <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?php echo $user['id']; ?>" style="border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer;">
                                            <i class="fas fa-edit"></i> Edit
                                        </button>
                                        <a href="admin.php?delete_user=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger" style="border: none; padding: 6px 12px; border-radius: 6px; cursor: pointer; text-decoration: none;">
                                            <i class="fas fa-trash"></i> Delete
                                        </a>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Admin</span>
                                    <?php endif; ?>
                                </td>
                            </tr>

                            <!-- EDIT MODAL -->
                            <div class="modal fade" id="editModal<?php echo $user['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content" style="border: none; border-radius: 12px;">
                                        <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">
                                            <h5 class="modal-title"><i class="fas fa-edit"></i> Edit User</h5>
                                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body" style="padding: 25px;">
                                                <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                                
                                                <div class="mb-3">
                                                    <label for="edit_username" class="form-label" style="font-weight: 600;">Username</label>
                                                    <input type="text" class="form-control" id="edit_username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px 15px;">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="edit_role" class="form-label" style="font-weight: 600;">Role</label>
                                                    <select class="form-select" id="edit_role" name="role" required style="border: 2px solid #ecf0f1; border-radius: 8px; padding: 10px 15px;">
                                                        <option value="Support Staff" <?php echo $user['role'] == 'Support Staff' ? 'selected' : ''; ?>>Support Staff</option>
                                                        <option value="Counselor" <?php echo $user['role'] == 'Counselor' ? 'selected' : ''; ?>>Counselor</option>
                                                        <option value="Cashier" <?php echo $user['role'] == 'Cashier' ? 'selected' : ''; ?>>Cashier</option>
                                                        <option value="Management" <?php echo $user['role'] == 'Management' ? 'selected' : ''; ?>>Management</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer" style="border-top: 1px solid #ecf0f1;">
                                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="edit_user" class="btn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none;">Save Changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <!-- EDIT MODAL END -->
                        <?php 
                            endwhile;
                        else:
                        ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted" style="padding: 20px;">No users found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <!-- USERS TABLE END -->

    </div>
    <!-- MAIN CONTENT END -->

</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CHART.JS INITIALIZATION -->
<script>
    // Color palette
    const colors = {
        primary: '#667eea',
        secondary: '#764ba2',
        success: '#2ecc71',
        danger: '#e74c3c',
        warning: '#f39c12',
        info: '#3498db',
        lightBlue: 'rgba(52, 152, 219, 0.5)',
        lightGreen: 'rgba(46, 204, 113, 0.5)',
        lightOrange: 'rgba(243, 156, 18, 0.5)',
    };

    // Chart 1: Department-wise Distribution
    const departments = <?php echo json_encode($departments); ?>;
    const departmentCounts = <?php echo json_encode($departmentCounts); ?>;

    if (departments.length > 0) {
        const departmentCtx = document.getElementById('departmentChart').getContext('2d');
        const departmentChart = new Chart(departmentCtx, {
            type: 'doughnut',
            data: {
                labels: departments,
                datasets: [{
                    data: departmentCounts,
                    backgroundColor: [
                        '#667eea',
                        '#764ba2',
                        '#f39c12',
                        '#e74c3c',
                        '#3498db',
                        '#2ecc71',
                        '#9b59b6'
                    ],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Chart 2: Payment Status Distribution
    const paymentLabels = <?php echo json_encode($paymentLabels); ?>;
    const paymentCounts = <?php echo json_encode($paymentCounts); ?>;

    if (paymentLabels.length > 0) {
        const paymentStatusCtx = document.getElementById('paymentStatusChart').getContext('2d');
        const paymentStatusChart = new Chart(paymentStatusCtx, {
            type: 'pie',
            data: {
                labels: paymentLabels,
                datasets: [{
                    data: paymentCounts,
                    backgroundColor: [
                        '#2ecc71',
                        '#e74c3c'
                    ],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            font: { size: 12, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    // Chart 3: Monthly Applications
    const months = <?php echo json_encode($months); ?>;
    const monthlyCounts = <?php echo json_encode($monthlyCounts); ?>;

    if (months.length > 0) {
        const monthlyCtx = document.getElementById('monthlyApplicationsChart').getContext('2d');
        const monthlyChart = new Chart(monthlyCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Applications',
                    data: monthlyCounts,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#667eea',
                    pointBorderColor: 'white',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 12, weight: 'bold' } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                }
            }
        });
    }

    // Chart 4: Monthly Payment Collection
    const paymentMonths = <?php echo json_encode($paymentMonths); ?>;
    const paymentAmounts = <?php echo json_encode($paymentAmounts); ?>;

    if (paymentMonths.length > 0) {
        const paymentCtx = document.getElementById('paymentCollectionChart').getContext('2d');
        const paymentChart = new Chart(paymentCtx, {
            type: 'bar',
            data: {
                labels: paymentMonths,
                datasets: [{
                    label: 'Amount Collected (₹)',
                    data: paymentAmounts,
                    backgroundColor: '#f39c12',
                    borderColor: '#e67e22',
                    borderWidth: 2,
                    borderRadius: 5,
                    hoverBackgroundColor: '#e67e22'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: { font: { size: 12, weight: 'bold' } }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0, 0, 0, 0.05)' }
                    }
                }
            }
        });
    }

    // Chart 5: Application Status
    const approvalStatusCtx = document.getElementById('approvalStatusChart').getContext('2d');
    const approvalStatusChart = new Chart(approvalStatusCtx, {
        type: 'doughnut',
        data: {
            labels: ['Confirmed', 'Enquiry'],
            datasets: [{
                data: [<?php echo $totalApproved; ?>, <?php echo $totalPending; ?>],
                backgroundColor: [
                    '#2ecc71',
                    '#f39c12'
                ],
                borderColor: 'white',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 12, weight: 'bold' }
                    }
                }
            }
        }
    });

    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    });
</script>

</body>
</html>