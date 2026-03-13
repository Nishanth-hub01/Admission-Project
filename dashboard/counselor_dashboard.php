<?php
// admission-system/dashboard/counselor_dashboard.php
// Counselor Dashboard - Integrated with existing project

include('../includes/db.php');
include('../includes/auth.php');

// Check if user is logged in and has counselor role
if (!isset($_SESSION['user_id'])) {
    header("Location: ../pages/login.php");
    exit();
}

// Verify counselor role
$result = $conn->query("SELECT role FROM users WHERE id = {$_SESSION['user_id']}");
$user = $result->fetch_assoc();

if ($user['role'] != 'Counselor') {
    header("Location: ../index.php");
    exit();
}

$page_title = "Counselor Dashboard";
$success = '';
$error = '';
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$selectedStudent = null;

// ============================================
// CONFIRM ADMISSION
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_admission'])) {
    $student_id = (int)$_POST['student_id'];
    
    $stmt = $conn->prepare("UPDATE students SET application_status = 'Approved', 
                           admission_confirmed_date = NOW() WHERE id = ?");
    $stmt->bind_param("i", $student_id);
    
    if ($stmt->execute()) {
        $success = "✅ Admission confirmed successfully!";
    } else {
        $error = "❌ Error confirming admission: " . $conn->error;
    }
    $stmt->close();
}

// ============================================
// UPDATE DEPARTMENT
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_department'])) {
    $student_id = (int)$_POST['student_id'];
    $new_department = $conn->real_escape_string(trim($_POST['new_department']));
    
    $stmt = $conn->prepare("UPDATE students SET course_department = ? WHERE id = ?");
    $stmt->bind_param("si", $new_department, $student_id);
    
    if ($stmt->execute()) {
        $success = "✅ Department updated successfully!";
    } else {
        $error = "❌ Error updating department: " . $conn->error;
    }
    $stmt->close();
}

// ============================================
// VIEW STUDENT DETAILS
// ============================================
if (isset($_GET['view_student'])) {
    $student_id = (int)$_GET['view_student'];
    $result = $conn->query("SELECT * FROM students WHERE id = $student_id");
    if ($result && $result->num_rows > 0) {
        $selectedStudent = $result->fetch_assoc();
        $currentTab = 'view_student';
    } else {
        $error = "❌ Student not found!";
    }
}

// ============================================
// GET STATISTICS
// ============================================
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$pendingAdmissions = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Submitted'")->fetch_assoc()['count'];
$approvedAdmissions = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Approved'")->fetch_assoc()['count'];

// Fetch all students
$students = [];
$sql = "SELECT id, admission_id, full_name, email_id, mobile_number, course_department, 
        class_12_percentage, entrance_exam_score, application_status, created_at FROM students 
        ORDER BY created_at DESC";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $students[] = $row;
    }
}

// Fetch departments
$departments = [];
$deptResult = $conn->query("SELECT DISTINCT course_department FROM students WHERE course_department IS NOT NULL ORDER BY course_department");
if ($deptResult && $deptResult->num_rows > 0) {
    while ($row = $deptResult->fetch_assoc()) {
        $departments[] = $row['course_department'];
    }
}

// Get department fees
$fees = [];
$feeResult = $conn->query("SELECT * FROM department_fees ORDER BY degree_type, department_name");
if ($feeResult && $feeResult->num_rows > 0) {
    while ($row = $feeResult->fetch_assoc()) {
        $fees[] = $row;
    }
}
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
    
    <style>
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
            transition: all 0.3s;
        }
        
        .section-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .section-title {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            font-size: 1.2rem;
        }
        
        .field-group {
            margin-bottom: 20px;
        }
        
        .field-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }
        
        .field-value {
            color: #555;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #667eea;
            font-weight: 500;
        }
        
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: bold;
            color: #667eea;
            margin: 10px 0;
        }
        
        .stat-label {
            color: #666;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .table {
            background: white;
            border-radius: 8px;
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
        }
        
        .student-row {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 12px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
            border-left: 4px solid #667eea;
        }
        
        .modal-content {
            border-radius: 12px;
        }
        
        .modal-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-radius: 12px 12px 0 0;
        }
        
        .modal-title {
            font-weight: 700;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> <strong>Counselor Dashboard</strong>
        </a>
        <div class="navbar-nav ms-auto">
            <span class="nav-link"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
            <a href="../actions/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
        </div>
    </div>
</nav>

<div style="display: flex; min-height: calc(100vh - 60px);">

    <!-- SIDEBAR -->
    <div class="sidebar" style="width: 250px; background: #2c3e50; color: white; padding: 20px 0; position: fixed; height: calc(100vh - 60px); overflow-y: auto; top: 60px; left: 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1); z-index: 999;">
        <h5 style="padding: 15px 20px; color: #667eea; border-bottom: 1px solid #34495e; margin: 0;">
            <i class="fas fa-bars"></i> Navigation
        </h5>
        <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0;">
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <a href="?tab=dashboard" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'dashboard' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <a href="?tab=students" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'students' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>">
                    <i class="fas fa-users"></i> View Students
                </a>
            </li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <a href="?tab=fees" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'fees' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>">
                    <i class="fas fa-money-bill"></i> Department Fees
                </a>
            </li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);">
                <a href="../actions/logout.php" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div style="flex: 1; padding: 30px; margin-left: 250px; overflow-y: auto;">

        <h2 style="color: #667eea; margin-bottom: 25px; font-weight: 700;">
            <i class="fas fa-graduation-cap"></i> Counselor Dashboard
        </h2>

        <!-- ALERTS -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-left: 4px solid #2ecc71;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="border-left: 4px solid #e74c3c;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- STATISTICS -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-graduation-cap"></i> Total Students</div>
                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #f39c12;">
                    <div class="stat-label" style="color: #f39c12;"><i class="fas fa-hourglass-half"></i> Pending</div>
                    <div class="stat-number" style="color: #f39c12;"><?php echo $pendingAdmissions; ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #2ecc71;">
                    <div class="stat-label" style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Approved</div>
                    <div class="stat-number" style="color: #2ecc71;"><?php echo $approvedAdmissions; ?></div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD TAB -->
        <?php if ($currentTab == 'dashboard'): ?>
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-chart-bar"></i> Welcome to Counselor Dashboard</h3>
                <p style="color: #666; line-height: 1.8; margin: 15px 0;">
                    As a counselor, you can view all student applications submitted by support staff, 
                    manage departments, and confirm admissions.
                </p>
                <ul style="color: #666; line-height: 1.8; padding-left: 20px; margin-top: 15px;">
                    <li><strong>👥 View Students:</strong> Browse all registered students and their details</li>
                    <li><strong>✏️ Change Department:</strong> Update student's department/course selection</li>
                    <li><strong>✅ Confirm Admission:</strong> Approve student admissions</li>
                    <li><strong>💰 View Fees:</strong> Check department fee structures</li>
                </ul>
            </div>

            <!-- QUICK ACTIONS -->
            <div class="row">
                <div class="col-md-6">
                    <div class="section-card">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">
                            <i class="fas fa-lightning-bolt"></i> Quick Actions
                        </h5>
                        <a href="?tab=students" class="btn btn-primary w-100 mb-2">
                            <i class="fas fa-users"></i> View All Students
                        </a>
                        <a href="?tab=fees" class="btn btn-primary w-100">
                            <i class="fas fa-money-bill"></i> View Department Fees
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-card">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">
                            <i class="fas fa-info-circle"></i> Summary
                        </h5>
                        <p style="margin: 10px 0;"><strong>Total Registrations:</strong> <span style="color: #667eea; font-size: 1.2rem; font-weight: bold;"><?php echo $totalStudents; ?></span></p>
                        <p style="margin: 10px 0;"><strong>Pending Review:</strong> <span style="color: #f39c12; font-size: 1.2rem; font-weight: bold;"><?php echo $pendingAdmissions; ?></span></p>
                        <p style="margin: 10px 0;"><strong>Confirmed:</strong> <span style="color: #2ecc71; font-size: 1.2rem; font-weight: bold;"><?php echo $approvedAdmissions; ?></span></p>
                    </div>
                </div>
            </div>

        <!-- STUDENTS TAB -->
        <?php elseif ($currentTab == 'students'): ?>
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-users"></i> All Students</h3>
                
                <?php if (!empty($students)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Admission ID</th>
                                    <th>Student Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Department</th>
                                    <th>12th %</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td>
                                            <code style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px;">
                                                <?php echo htmlspecialchars($student['admission_id']); ?>
                                            </code>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($student['full_name']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($student['email_id']); ?></td>
                                        <td><?php echo htmlspecialchars($student['mobile_number']); ?></td>
                                        <td><?php echo htmlspecialchars($student['course_department'] ?? 'N/A'); ?></td>
                                        <td><?php echo !empty($student['class_12_percentage']) ? $student['class_12_percentage'] . '%' : 'N/A'; ?></td>
                                        <td>
                                            <?php 
                                            $statusColor = 'secondary';
                                            if ($student['application_status'] == 'Approved') $statusColor = 'success';
                                            elseif ($student['application_status'] == 'Rejected') $statusColor = 'danger';
                                            elseif ($student['application_status'] == 'Submitted') $statusColor = 'info';
                                            ?>
                                            <span class="badge bg-<?php echo $statusColor; ?>">
                                                <?php echo htmlspecialchars($student['application_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?tab=view_student&view_student=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View
                                            </a>
                                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                                    data-bs-target="#editDeptModal<?php echo $student['id']; ?>">
                                                <i class="fas fa-edit"></i> Edit Dept
                                            </button>
                                            <?php if ($student['application_status'] == 'Submitted'): ?>
                                                <form method="POST" action="" style="display: inline;">
                                                    <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                    <button type="submit" name="confirm_admission" class="btn btn-sm btn-success" onclick="return confirm('Confirm admission for this student?');">
                                                        <i class="fas fa-check"></i> Confirm
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                    <!-- EDIT DEPARTMENT MODAL -->
                                    <div class="modal fade" id="editDeptModal<?php echo $student['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="fas fa-edit"></i> Change Department</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                    <div class="modal-body">
                                                        <p style="margin-bottom: 15px;">
                                                            <strong>Student:</strong> <?php echo htmlspecialchars($student['full_name']); ?><br>
                                                            <strong>Admission ID:</strong> <?php echo htmlspecialchars($student['admission_id']); ?>
                                                        </p>
                                                        <div class="form-group">
                                                            <label class="form-label" style="font-weight: 600;">Select New Department</label>
                                                            <select name="new_department" class="form-select" required>
                                                                <option value="">-- Choose Department --</option>
                                                                <?php foreach ($departments as $dept): ?>
                                                                    <option value="<?php echo htmlspecialchars($dept); ?>" 
                                                                            <?php echo $dept == $student['course_department'] ? 'selected' : ''; ?>>
                                                                        <?php echo htmlspecialchars($dept); ?>
                                                                    </option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <button type="submit" name="update_department" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Update Department
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px; color: #999;">
                        <i class="fas fa-inbox" style="font-size: 3rem; margin-bottom: 15px; display: block;"></i>
                        <p>No students registered yet</p>
                    </div>
                <?php endif; ?>
            </div>

        <!-- FEES TAB -->
        <?php elseif ($currentTab == 'fees'): ?>
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-money-bill"></i> Department Fee Structure</h3>
                
                <?php if (!empty($fees)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Degree Type</th>
                                    <th>Department Name</th>
                                    <th>Tuition Fee</th>
                                    <th>Hostel Fee</th>
                                    <th>Transport Fee</th>
                                    <th>Admission Fee</th>
                                    <th>Total Fee</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($fees as $fee): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($fee['degree_type']); ?></strong></td>
                                        <td><?php echo htmlspecialchars($fee['department_name']); ?></td>
                                        <td>₹<?php echo number_format($fee['tuition_fee']); ?></td>
                                        <td>₹<?php echo number_format($fee['hostel_fee']); ?></td>
                                        <td>₹<?php echo number_format($fee['transport_fee']); ?></td>
                                        <td>₹<?php echo number_format($fee['admission_fee']); ?></td>
                                        <td><strong style="color: #667eea;">₹<?php echo number_format($fee['total_fee']); ?></strong></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No fee structure found
                    </div>
                <?php endif; ?>
            </div>

        <!-- VIEW STUDENT DETAILS TAB -->
        <?php elseif ($currentTab == 'view_student' && $selectedStudent): ?>
            <div style="margin-bottom: 25px;">
                <a href="?tab=students" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Students
                </a>
                <button onclick="window.print()" class="btn btn-info">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>

            <!-- ADMISSION ID DISPLAY -->
            <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; border-radius: 12px; text-align: center; margin-bottom: 30px; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);">
                <h4 style="margin: 0 0 10px 0;"><i class="fas fa-id-card"></i> ADMISSION ID</h4>
                <div style="font-size: 2.5rem; font-weight: bold; letter-spacing: 2px; margin: 15px 0; font-family: 'Courier New', monospace;">
                    <?php echo htmlspecialchars($selectedStudent['admission_id']); ?>
                </div>
                <p style="color: rgba(255,255,255,0.9); margin: 0; font-size: 0.9rem;">
                    Registered on: <?php echo date('d M Y, H:i', strtotime($selectedStudent['created_at'])); ?>
                </p>
            </div>

            <!-- SECTION 1: PERSONAL DETAILS -->
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-user"></i> Personal Details</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Full Name</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['full_name']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Date of Birth</label>
                            <div class="field-value"><?php echo !empty($selectedStudent['date_of_birth']) ? date('d M Y', strtotime($selectedStudent['date_of_birth'])) : 'N/A'; ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Gender</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['gender'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Nationality</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['nationality'] ?? 'Indian'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Community/Category</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['community'] ?? 'General'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Aadhaar Number</label>
                            <div class="field-value"><?php echo !empty($selectedStudent['aadhaar_number']) ? htmlspecialchars($selectedStudent['aadhaar_number']) : 'Not Provided'; ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 2: CONTACT DETAILS -->
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-phone"></i> Contact Details</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Mobile Number</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['mobile_number']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Email ID</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['email_id']); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">City</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['city'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">State</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['state'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="field-group">
                            <label class="field-label">Permanent Address</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['permanent_address'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 3: ACADEMIC DETAILS -->
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-book"></i> Academic Details</h3>
                <div class="row">
                    <div class="col-md-6">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">📚 12th Class</h5>
                        <div class="field-group">
                            <label class="field-label">School Name</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_school'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Board</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_board'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Percentage</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['class_12_percentage'] ?? 'N/A'); ?>%</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">🎯 Entrance Exam</h5>
                        <div class="field-group">
                            <label class="field-label">Exam Type</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['entrance_exam_type'] ?? 'N/A'); ?></div>
                        </div>
                        <div class="field-group">
                            <label class="field-label">Score</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['entrance_exam_score'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SECTION 4: COURSE SELECTION -->
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-graduation-cap"></i> Course Selection</h3>
                <div class="row">
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Degree Type</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['degree_type'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Department</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['course_department'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Specialization</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['preferred_specialization'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="field-group">
                            <label class="field-label">Admission Type</label>
                            <div class="field-value"><?php echo htmlspecialchars($selectedStudent['admission_type'] ?? 'N/A'); ?></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- APPLICATION STATUS -->
            <div class="section-card" style="border-left-color: #2ecc71;">
                <h3 class="section-title" style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Application Status</h3>
                <div style="padding: 15px; text-align: center; background: linear-gradient(135deg, rgba(46, 204, 113, 0.05) 0%, rgba(39, 174, 96, 0.05) 100%); border-radius: 8px;">
                    <div style="font-size: 1.3rem; font-weight: bold; color: #2ecc71; margin-bottom: 10px;">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($selectedStudent['application_status']); ?>
                    </div>
                    <small style="color: #666;">
                        Submitted on: <?php echo date('d M Y, H:i A', strtotime($selectedStudent['created_at'])); ?>
                    </small>
                </div>
            </div>

            <!-- ACTION BUTTONS -->
            <div style="text-align: center; margin-top: 25px;">
                <button class="btn btn-warning btn-lg" data-bs-toggle="modal" data-bs-target="#editDeptModalFull" style="margin-right: 10px;">
                    <i class="fas fa-edit"></i> Change Department
                </button>
                <?php if ($selectedStudent['application_status'] == 'Submitted'): ?>
                    <form method="POST" action="" style="display: inline;">
                        <input type="hidden" name="student_id" value="<?php echo $selectedStudent['id']; ?>">
                        <button type="submit" name="confirm_admission" class="btn btn-success btn-lg" onclick="return confirm('Confirm admission for this student?');">
                            <i class="fas fa-check"></i> Confirm Admission
                        </button>
                    </form>
                <?php else: ?>
                    <div class="btn btn-success btn-lg disabled">
                        <i class="fas fa-check-circle"></i> Already Confirmed
                    </div>
                <?php endif; ?>
            </div>

            <!-- EDIT DEPARTMENT MODAL -->
            <div class="modal fade" id="editDeptModalFull" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title"><i class="fas fa-edit"></i> Change Department</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form method="POST" action="">
                            <div class="modal-body">
                                <p style="margin-bottom: 15px;">
                                    <strong>Student:</strong> <?php echo htmlspecialchars($selectedStudent['full_name']); ?><br>
                                    <strong>Current Department:</strong> <?php echo htmlspecialchars($selectedStudent['course_department'] ?? 'N/A'); ?>
                                </p>
                                <div class="form-group">
                                    <label class="form-label" style="font-weight: 600;">Select New Department</label>
                                    <select name="new_department" class="form-select" required>
                                        <option value="">-- Choose Department --</option>
                                        <?php foreach ($departments as $dept): ?>
                                            <option value="<?php echo htmlspecialchars($dept); ?>" 
                                                    <?php echo $dept == $selectedStudent['course_department'] ? 'selected' : ''; ?>>
                                                <?php echo htmlspecialchars($dept); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <input type="hidden" name="student_id" value="<?php echo $selectedStudent['id']; ?>">
                                <button type="submit" name="update_department" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Department
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        <?php endif; ?>

    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }, 5000);
        });
    });
</script>

</body>
</html>