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
// UPDATE FULL STUDENT DETAILS
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_full_student'])) {
    $student_id = (int)$_POST['student_id'];
    
    // Personal Details
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $nationality = $conn->real_escape_string($_POST['nationality'] ?? 'Indian');
    $religion = $conn->real_escape_string($_POST['religion'] ?? '');
    $community = $conn->real_escape_string($_POST['community']);
    $community_other = ($community === 'Other' && !empty($_POST['community_other'])) ? $conn->real_escape_string($_POST['community_other']) : '';
    $aadhaar_number = $conn->real_escape_string($_POST['aadhaar_number'] ?? '');
    
    // Contact Details
    $mobile_number = $conn->real_escape_string($_POST['mobile_number']);
    $email_id = $conn->real_escape_string($_POST['email_id']);
    $city = $conn->real_escape_string($_POST['city'] ?? '');
    $state = $conn->real_escape_string($_POST['state'] ?? '');
    $pincode = $conn->real_escape_string($_POST['pincode'] ?? '');
    $permanent_address = $conn->real_escape_string($_POST['permanent_address'] ?? '');
    
    // Academic Details
    $class_12_school = $conn->real_escape_string($_POST['class_12_school'] ?? '');
    $class_12_board = $conn->real_escape_string($_POST['class_12_board'] ?? 'State Board');
    $class_12_percentage = !empty($_POST['class_12_percentage']) ? (float)$_POST['class_12_percentage'] : 0;
    $entrance_exam_type = $conn->real_escape_string($_POST['entrance_exam_type'] ?? '');
    $entrance_exam_score = $conn->real_escape_string($_POST['entrance_exam_score'] ?? '');
    
    // Course Details
    $degree_type = $conn->real_escape_string($_POST['degree_type'] ?? '');
    $course_department = $conn->real_escape_string($_POST['course_department']);
    $preferred_specialization = $conn->real_escape_string($_POST['preferred_specialization'] ?? '');
    $admission_type = $conn->real_escape_string($_POST['admission_type'] ?? '');
    $application_status = $conn->real_escape_string($_POST['application_status']);
    
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $first_graduate = $conn->real_escape_string($_POST['first_graduate'] ?? '');
    $alternate_mobile = $conn->real_escape_string($_POST['alternate_mobile'] ?? '');
    $current_address = $conn->real_escape_string($_POST['current_address'] ?? '');
    $father_name = $conn->real_escape_string($_POST['father_name'] ?? '');
    $mother_name = $conn->real_escape_string($_POST['mother_name'] ?? '');
    $guardian_name = $conn->real_escape_string($_POST['guardian_name'] ?? '');
    $parent_occupation = $conn->real_escape_string($_POST['parent_occupation'] ?? '');
    $parent_mobile = $conn->real_escape_string($_POST['parent_mobile'] ?? '');
    $parent_email = $conn->real_escape_string($_POST['parent_email'] ?? '');
    $annual_family_income = isset($_POST['annual_family_income']) ? (int)$_POST['annual_family_income'] : 0;
    
    $class_10_school = $conn->real_escape_string($_POST['class_10_school'] ?? '');
    $class_10_board = $conn->real_escape_string($_POST['class_10_board'] ?? '');
    $class_10_register_number = $conn->real_escape_string($_POST['class_10_register_number'] ?? '');
    $class_10_percentage = isset($_POST['class_10_percentage']) ? (float)$_POST['class_10_percentage'] : 0;
    
    $class_12_register_number = $conn->real_escape_string($_POST['class_12_register_number'] ?? '');
    $class_12_subject_1_marks = isset($_POST['class_12_subject_1_marks']) && $_POST['class_12_subject_1_marks'] !== '' ? (float)$_POST['class_12_subject_1_marks'] : 'NULL';
    $class_12_subject_2_marks = isset($_POST['class_12_subject_2_marks']) && $_POST['class_12_subject_2_marks'] !== '' ? (float)$_POST['class_12_subject_2_marks'] : 'NULL';
    $class_12_subject_3_marks = isset($_POST['class_12_subject_3_marks']) && $_POST['class_12_subject_3_marks'] !== '' ? (float)$_POST['class_12_subject_3_marks'] : 'NULL';
    $class_12_subject_4_marks = isset($_POST['class_12_subject_4_marks']) && $_POST['class_12_subject_4_marks'] !== '' ? (float)$_POST['class_12_subject_4_marks'] : 'NULL';
    $class_12_subject_5_marks = isset($_POST['class_12_subject_5_marks']) && $_POST['class_12_subject_5_marks'] !== '' ? (float)$_POST['class_12_subject_5_marks'] : 'NULL';
    $class_12_subjects = $conn->real_escape_string($_POST['class_12_subjects'] ?? '');
    $programme_choice = $conn->real_escape_string($_POST['programme_choice'] ?? '');
    
    $hostel_requirement = $conn->real_escape_string($_POST['hostel_requirement'] ?? 'No');
    $transport_requirement = $conn->real_escape_string($_POST['transport_requirement'] ?? 'No');
    $scholarship_details = $conn->real_escape_string($_POST['scholarship_details'] ?? '');
    $sports_achievements = $conn->real_escape_string($_POST['sports_achievements'] ?? '');
    $medical_information = $conn->real_escape_string($_POST['medical_information'] ?? '');

    $sql = "UPDATE students SET 
        full_name = '$full_name',
        date_of_birth = '$date_of_birth',
        gender = '$gender',
        nationality = '$nationality',
        religion = '$religion',
        community = '$community',
        community_other = '$community_other',
        aadhaar_number = '$aadhaar_number',
        mobile_number = '$mobile_number',
        email_id = '$email_id',
        city = '$city',
        state = '$state',
        pincode = '$pincode',
        permanent_address = '$permanent_address',
        class_12_school = '$class_12_school',
        class_12_board = '$class_12_board',
        class_12_percentage = $class_12_percentage,
        entrance_exam_type = '$entrance_exam_type',
        entrance_exam_score = '$entrance_exam_score',
        degree_type = '$degree_type',
        course_department = '$course_department',
        preferred_specialization = '$preferred_specialization',
        admission_type = '$admission_type',
        blood_group = '$blood_group',
        first_graduate = '$first_graduate',
        alternate_mobile = '$alternate_mobile',
        current_address = '$current_address',
        father_name = '$father_name',
        mother_name = '$mother_name',
        guardian_name = '$guardian_name',
        parent_occupation = '$parent_occupation',
        parent_mobile = '$parent_mobile',
        parent_email = '$parent_email',
        annual_family_income = $annual_family_income,
        class_10_school = '$class_10_school',
        class_10_board = '$class_10_board',
        class_10_register_number = '$class_10_register_number',
        class_10_percentage = $class_10_percentage,
        class_12_register_number = '$class_12_register_number',
        class_12_subject_1_marks = $class_12_subject_1_marks,
        class_12_subject_2_marks = $class_12_subject_2_marks,
        class_12_subject_3_marks = $class_12_subject_3_marks,
        class_12_subject_4_marks = $class_12_subject_4_marks,
        class_12_subject_5_marks = $class_12_subject_5_marks,
        class_12_subjects = '$class_12_subjects',
        programme_choice = '$programme_choice',
        hostel_requirement = '$hostel_requirement',
        transport_requirement = '$transport_requirement',
        scholarship_details = '$scholarship_details',
        sports_achievements = '$sports_achievements',
        medical_information = '$medical_information',
        application_status = '$application_status'
        WHERE id = $student_id";
    
    if ($conn->query($sql)) {
        $success = "✅ Student details updated successfully!";
        // Refresh the selected student data
        $selectedStudent = getStudentByAdmissionID($conn, $selectedStudent['admission_id']);
    } else {
        $error = "❌ Error updating student: " . $conn->error;
    }
}

// ============================================
// UPDATE STUDENT STATUS
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_status'])) {
    $student_id = (int)$_POST['student_id'];
    $new_status = $conn->real_escape_string($_POST['new_status']);
    
    $stmt = $conn->prepare("UPDATE students SET application_status = ? WHERE id = ?");
    $stmt->bind_param("si", $new_status, $student_id);
    
    if ($stmt->execute()) {
        $success = "✅ Student status updated to '$new_status' successfully!";
    } else {
        $error = "❌ Error updating status: " . $conn->error;
    }
    $stmt->close();
}

// ============================================
// CONFIRM ADMISSION (LEGACY - KEEP FOR COMPATIBILITY)
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm_admission'])) {
    $student_id = (int)$_POST['student_id'];
    
    $stmt = $conn->prepare("UPDATE students SET application_status = 'Confirmed', 
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
$pendingAdmissions = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Enquiry'")->fetch_assoc()['count'];
$approvedAdmissions = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Confirmed'")->fetch_assoc()['count'];

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
        
        .field-label, .form-label {
            color: #1a202c !important;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }
        
        .field-value, .form-control, .form-select {
            color: #2d3748 !important;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .field-value {
            border-left: 3px solid #667eea;
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
                    As a counselor, you have full control over student information and manage the admission process through different stages:
                    Enquiry → Counselor Meeting → Payment → Admission Confirmation.
                </p>
                <ul style="color: #666; line-height: 1.8; padding-left: 20px; margin-top: 15px;">
                    <li><strong>👥 View Students:</strong> Browse all registered students and their current status</li>
                    <li><strong>✏️ Edit Student Details:</strong> Update any student information (personal, contact, academic)</li>
                    <li><strong>📝 Update Status:</strong> Change student status through the admission workflow</li>
                    <li><strong>🏢 Change Department:</strong> Update student's department/course selection</li>
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
                                            $statusText = $student['application_status'];
                                            
                                            if ($student['application_status'] == 'Confirmed') $statusColor = 'success';
                                            elseif ($student['application_status'] == 'Payment Pending') $statusColor = 'warning';
                                            elseif ($student['application_status'] == 'Enquiry') $statusColor = 'primary';
                                            elseif ($student['application_status'] == 'Rejected') $statusColor = 'danger';
                                            ?>
                                            <span class="badge bg-<?php echo $statusColor; ?>">
                                                <?php echo htmlspecialchars($statusText); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?tab=view_student&view_student=<?php echo $student['id']; ?>" class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i> View & Edit
                                            </a>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal<?php echo $student['id']; ?>">
                                                <i class="fas fa-exchange-alt"></i> Update Status
                                            </button>
                                        </td>
                                    </tr>


                                    <!-- UPDATE STATUS MODAL -->
                                    <div class="modal fade" id="statusModal<?php echo $student['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title"><i class="fas fa-exchange-alt"></i> Update Student Status</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                    <div class="modal-body">
                                                        <p style="margin-bottom: 15px;">
                                                            <strong>Student:</strong> <?php echo htmlspecialchars($student['full_name']); ?><br>
                                                            <strong>Admission ID:</strong> <?php echo htmlspecialchars($student['admission_id']); ?><br>
                                                            <strong>Current Status:</strong> <span class="badge bg-<?php echo $statusColor; ?>"><?php echo htmlspecialchars($statusText); ?></span>
                                                        </p>
                                                        <div class="form-group">
                                                            <label class="form-label" style="font-weight: 600;">Select New Status</label>
                                                            <select name="new_status" class="form-select" required>
                                                                <option value="">-- Choose Status --</option>
                                                                <option value="Enquiry">Enquiry</option>
                                                                <option value="Payment Pending">Payment Pending</option>
                                                                <option value="Confirmed">Confirmed</option>
                                                                <option value="Rejected">Rejected</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <input type="hidden" name="student_id" value="<?php echo $student['id']; ?>">
                                                        <button type="submit" name="update_status" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Update Status
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

            <!-- EDIT STUDENT FORM -->
            <form method="POST" action="">
                <input type="hidden" name="student_id" value="<?php echo $selectedStudent['id']; ?>">

                <!-- SECTION 1: PERSONAL DETAILS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-user"></i> Personal Details</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Full Name *</label>
                                <input type="text" name="full_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['full_name']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input type="date" name="date_of_birth" class="form-control" value="<?php echo $selectedStudent['date_of_birth']; ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select name="gender" class="form-select">
                                    <option value="Male" <?php echo $selectedStudent['gender'] == 'Male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="Female" <?php echo $selectedStudent['gender'] == 'Female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="Other" <?php echo $selectedStudent['gender'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nationality</label>
                                <input type="text" name="nationality" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['nationality'] ?? 'Indian'); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Religion</label>
                                <input type="text" name="religion" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['religion'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Community/Category</label>
                                <select name="community" class="form-select">
                                    <option value="General" <?php echo $selectedStudent['community'] == 'General' ? 'selected' : ''; ?>>General</option>
                                    <option value="OC" <?php echo $selectedStudent['community'] == 'OC' ? 'selected' : ''; ?>>OC</option>
                                    <option value="BC" <?php echo $selectedStudent['community'] == 'BC' ? 'selected' : ''; ?>>BC</option>
                                    <option value="BCM" <?php echo $selectedStudent['community'] == 'BCM' ? 'selected' : ''; ?>>BCM</option>
                                    <option value="MBC/DNC" <?php echo $selectedStudent['community'] == 'MBC/DNC' ? 'selected' : ''; ?>>MBC/DNC</option>
                                    <option value="SC" <?php echo $selectedStudent['community'] == 'SC' ? 'selected' : ''; ?>>SC</option>
                                    <option value="SCA" <?php echo $selectedStudent['community'] == 'SCA' ? 'selected' : ''; ?>>SCA</option>
                                    <option value="ST" <?php echo $selectedStudent['community'] == 'ST' ? 'selected' : ''; ?>>ST</option>
                                    <option value="Other" <?php echo $selectedStudent['community'] == 'Other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6" id="community_other_container_view" style="display: <?php echo $selectedStudent['community'] == 'Other' ? 'block' : 'none'; ?>;">
                            <div class="mb-3">
                                <label class="form-label">Specify Other Community</label>
                                <input type="text" name="community_other" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['community_other'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Aadhaar Number</label>
                                <input type="text" name="aadhaar_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['aadhaar_number'] ?? ''); ?>" pattern="[0-9]{12}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Blood Group</label>
                                <input type="text" name="blood_group" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['blood_group'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">First Graduate</label>
                                <select name="first_graduate" class="form-select">
                                    <option value="Yes" <?php echo ($selectedStudent['first_graduate'] ?? '') == 'Yes' ? 'selected' : ''; ?>>Yes</option>
                                    <option value="No" <?php echo ($selectedStudent['first_graduate'] ?? '') == 'No' ? 'selected' : ''; ?>>No</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: CONTACT DETAILS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-phone"></i> Contact Details</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Mobile Number *</label>
                                <input type="tel" name="mobile_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['mobile_number']); ?>" pattern="[0-9]{10}" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Email ID *</label>
                                <input type="email" name="email_id" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['email_id']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['city'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">State</label>
                                <input type="text" name="state" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['state'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Pincode</label>
                                <input type="text" name="pincode" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['pincode'] ?? ''); ?>" pattern="[0-9]{6}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Permanent Address</label>
                                <textarea name="permanent_address" class="form-control" rows="3"><?php echo htmlspecialchars($selectedStudent['permanent_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Alternate Mobile</label>
                                <input type="text" name="alternate_mobile" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['alternate_mobile'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Current Address</label>
                                <textarea name="current_address" class="form-control" rows="3"><?php echo htmlspecialchars($selectedStudent['current_address'] ?? ''); ?></textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- PARENT/GUARDIAN DETAILS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-users"></i> Parent / Guardian Details</h3>
                    <div class="row">
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Father's Name</label><input type="text" name="father_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['father_name'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Mother's Name</label><input type="text" name="mother_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['mother_name'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Guardian Name</label><input type="text" name="guardian_name" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['guardian_name'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Occupation</label><input type="text" name="parent_occupation" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_occupation'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Mobile</label><input type="text" name="parent_mobile" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_mobile'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Parent Email</label><input type="email" name="parent_email" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['parent_email'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Annual Family Income</label><input type="number" name="annual_family_income" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['annual_family_income'] ?? '0'); ?>"></div></div>
                    </div>
                </div>

                <!-- 10th CLASS DETAILS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-book"></i> 10th Academic Details</h3>
                    <div class="row">
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">School Name</label><input type="text" name="class_10_school" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_school'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Board</label><input type="text" name="class_10_board" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_board'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Register Number</label><input type="text" name="class_10_register_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_register_number'] ?? ''); ?>"></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Percentage</label><input type="number" step="0.01" name="class_10_percentage" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_10_percentage'] ?? '0'); ?>"></div></div>
                    </div>
                </div>

                <!-- SECTION 3: ACADEMIC DETAILS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-book"></i> Academic Details</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">📚 12th Class</h5>
                            <div class="mb-3">
                                <label class="form-label">School Name</label>
                                <input type="text" name="class_12_school" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_school'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Board</label>
                                <select name="class_12_board" class="form-select">
                                    <option value="State Board" <?php echo ($selectedStudent['class_12_board'] ?? 'State Board') == 'State Board' ? 'selected' : ''; ?>>State Board</option>
                                    <option value="CBSE" <?php echo ($selectedStudent['class_12_board'] ?? '') == 'CBSE' ? 'selected' : ''; ?>>CBSE</option>
                                    <option value="ICSE" <?php echo ($selectedStudent['class_12_board'] ?? '') == 'ICSE' ? 'selected' : ''; ?>>ICSE</option>
                                    <option value="IB" <?php echo ($selectedStudent['class_12_board'] ?? '') == 'IB' ? 'selected' : ''; ?>>IB</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Percentage</label>
                                <input type="number" step="0.01" name="class_12_percentage" class="form-control" value="<?php echo $selectedStudent['class_12_percentage'] ?? ''; ?>">
                            </div>
                            <div class="mb-3"><label class="form-label">Register Number</label><input type="text" name="class_12_register_number" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_register_number'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subjects Studied</label><input type="text" name="class_12_subjects" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subjects'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subject 1 Marks</label><input type="number" step="0.01" name="class_12_subject_1_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_1_marks'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subject 2 Marks</label><input type="number" step="0.01" name="class_12_subject_2_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_2_marks'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subject 3 Marks</label><input type="number" step="0.01" name="class_12_subject_3_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_3_marks'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subject 4 Marks</label><input type="number" step="0.01" name="class_12_subject_4_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_4_marks'] ?? ''); ?>"></div>
                            <div class="mb-3"><label class="form-label">Subject 5 Marks</label><input type="number" step="0.01" name="class_12_subject_5_marks" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['class_12_subject_5_marks'] ?? ''); ?>"></div>
                        </div>
                        <div class="col-md-6">
                            <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">🎯 Entrance Exam</h5>
                            <div class="mb-3">
                                <label class="form-label">Exam Type</label>
                                <input type="text" name="entrance_exam_type" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['entrance_exam_type'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Score</label>
                                <input type="text" name="entrance_exam_score" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['entrance_exam_score'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 4: COURSE SELECTION & STATUS -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-graduation-cap"></i> Course Selection & Status</h3>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Degree Type</label>
                                <input type="text" name="degree_type" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['degree_type'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Department *</label>
                                <select name="course_department" class="form-select" required>
                                    <option value="">-- Select Department --</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo htmlspecialchars($dept); ?>" 
                                                <?php echo $dept == ($selectedStudent['course_department'] ?? '') ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars($dept); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Specialization</label>
                                <input type="text" name="preferred_specialization" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['preferred_specialization'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label class="form-label">Programme Choice</label>
                                <input type="text" name="programme_choice" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['programme_choice'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Admission Type</label>
                                <input type="text" name="admission_type" class="form-control" value="<?php echo htmlspecialchars($selectedStudent['admission_type'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Application Status *</label>
                                <select name="application_status" class="form-select" required>
                                    <option value="Enquiry" <?php echo $selectedStudent['application_status'] == 'Enquiry' ? 'selected' : ''; ?>>Enquiry</option>
                                    <option value="Payment Pending" <?php echo $selectedStudent['application_status'] == 'Payment Pending' ? 'selected' : ''; ?>>Payment Pending</option>
                                    <option value="Confirmed" <?php echo $selectedStudent['application_status'] == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                    <option value="Rejected" <?php echo $selectedStudent['application_status'] == 'Rejected' ? 'selected' : ''; ?>>Rejected</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION 5: ADDITIONAL INFO -->
                <div class="section-card">
                    <h3 class="section-title"><i class="fas fa-info-circle"></i> Additional Information</h3>
                    <div class="row">
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Hostel Requirement</label><select name="hostel_requirement" class="form-select"><option value="No" <?php echo ($selectedStudent['hostel_requirement']??'')=='No'?'selected':''; ?>>No</option><option value="Yes" <?php echo ($selectedStudent['hostel_requirement']??'')=='Yes'?'selected':''; ?>>Yes</option></select></div></div>
                        <div class="col-md-6"><div class="mb-3"><label class="form-label">Transport Requirement</label><select name="transport_requirement" class="form-select"><option value="No" <?php echo ($selectedStudent['transport_requirement']??'')=='No'?'selected':''; ?>>No</option><option value="Yes" <?php echo ($selectedStudent['transport_requirement']??'')=='Yes'?'selected':''; ?>>Yes</option></select></div></div>
                        <div class="col-md-12"><div class="mb-3"><label class="form-label">Scholarship Details</label><textarea name="scholarship_details" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['scholarship_details'] ?? ''); ?></textarea></div></div>
                        <div class="col-md-12"><div class="mb-3"><label class="form-label">Sports Achievements</label><textarea name="sports_achievements" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['sports_achievements'] ?? ''); ?></textarea></div></div>
                        <div class="col-md-12"><div class="mb-3"><label class="form-label">Medical Information</label><textarea name="medical_information" class="form-control" rows="2"><?php echo htmlspecialchars($selectedStudent['medical_information'] ?? ''); ?></textarea></div></div>
                    </div>
                </div>

                <!-- SAVE BUTTONS -->
                <div style="text-align: center; margin-top: 30px; padding: 20px; background: #f8f9fa; border-radius: 10px;">
                    <button type="submit" name="update_full_student" class="btn btn-success btn-lg" style="margin-right: 15px;">
                        <i class="fas fa-save"></i> Save All Changes
                    </button>
                    <a href="?tab=students" class="btn btn-secondary btn-lg">
                        <i class="fas fa-times"></i> Cancel
                    </a>
                </div>
            </form>

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

        // Handle community "Other" field toggle in edit modals and view form
        document.addEventListener('change', function(e) {
            if (e.target.name === 'community') {
                // Handle view form
                if (e.target.closest('form') && !e.target.closest('.modal')) {
                    const otherContainer = document.getElementById('community_other_container_view');
                    const otherInput = otherContainer ? otherContainer.querySelector('input[name="community_other"]') : null;
                    
                    if (otherContainer && otherInput) {
                        if (e.target.value === 'Other') {
                            otherContainer.style.display = 'block';
                            otherInput.required = true;
                        } else {
                            otherContainer.style.display = 'none';
                            otherInput.required = false;
                            otherInput.value = '';
                        }
                    }
                }
                // Handle modal forms
                else {
                    const form = e.target.closest('form');
                    const studentId = form.querySelector('input[name="student_id"]').value;
                    const otherContainer = document.getElementById('community_other_container' + studentId);
                    const otherInput = otherContainer ? otherContainer.querySelector('input[name="community_other"]') : null;
                    
                    if (otherContainer && otherInput) {
                        if (e.target.value === 'Other') {
                            otherContainer.style.display = 'block';
                            otherInput.required = true;
                        } else {
                            otherContainer.style.display = 'none';
                            otherInput.required = false;
                            otherInput.value = '';
                        }
                    }
                }
            }
        });
    });
</script>

</body>
</html>