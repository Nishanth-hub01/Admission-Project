<?php
// admission-system/dashboard/support_new.php
// Complete Support Staff Dashboard with Admission ID Management

include('../includes/db.php');
include('../includes/auth.php');
include('../includes/admission_id_helper.php');

requireRole('Support Staff');
$page_title = "Support Staff Dashboard";

$success = '';
$error = '';
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$searchResults = [];
$selectedStudent = null;
$allStudents = [];

// ============================================
// SEARCH BY ADMISSION ID
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['search_admission'])) {
    $admission_id = strtoupper($conn->real_escape_string(trim($_POST['admission_id'])));
    
    if (empty($admission_id)) {
        $error = "❌ Please enter an Admission ID!";
    } elseif (!preg_match('/^SI\d{8}\d{3}$/', $admission_id)) {
        $error = "❌ Invalid Admission ID format! (Expected: SI1003001)";
    } else {
        $student = getStudentByAdmissionID($conn, $admission_id);
        
        if ($student) {
            $selectedStudent = $student;
            $currentTab = 'view_student';
            $success = "✅ Student found successfully!";
        } else {
            $error = "❌ No student found with Admission ID: " . htmlspecialchars($admission_id);
        }
    }
}

// ============================================
// REGISTER NEW STUDENT
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register_student'])) {
    // Personal Details
    $full_name = $conn->real_escape_string(trim($_POST['full_name']));
    $date_of_birth = $conn->real_escape_string($_POST['date_of_birth']);
    $gender = $conn->real_escape_string($_POST['gender']);
    $nationality = $conn->real_escape_string($_POST['nationality'] ?? 'Indian');
    $religion = $conn->real_escape_string($_POST['religion'] ?? '');
    $community = $conn->real_escape_string($_POST['community']);
    $aadhaar_number = $conn->real_escape_string($_POST['aadhaar_number'] ?? '');
    $blood_group = $conn->real_escape_string($_POST['blood_group'] ?? '');
    $first_graduate = $conn->real_escape_string($_POST['first_graduate'] ?? '');
    
    // Contact Details
    $mobile_number = $conn->real_escape_string($_POST['mobile_number']);
    $alternate_mobile = $conn->real_escape_string($_POST['alternate_mobile'] ?? '');
    $email_id = $conn->real_escape_string($_POST['email_id']);
    $city = $conn->real_escape_string($_POST['city']);
    $state = $conn->real_escape_string($_POST['state']);
    $pincode = $conn->real_escape_string($_POST['pincode']);
    $permanent_address = $conn->real_escape_string($_POST['permanent_address']);
    $current_address = $conn->real_escape_string($_POST['current_address'] ?? '');
    
    // Parent Details
    $father_name = $conn->real_escape_string($_POST['father_name'] ?? '');
    $mother_name = $conn->real_escape_string($_POST['mother_name'] ?? '');
    $guardian_name = $conn->real_escape_string($_POST['guardian_name'] ?? '');
    $parent_occupation = $conn->real_escape_string($_POST['parent_occupation'] ?? '');
    $parent_mobile = $conn->real_escape_string($_POST['parent_mobile'] ?? '');
    $parent_email = $conn->real_escape_string($_POST['parent_email'] ?? '');
    $annual_family_income = !empty($_POST['annual_family_income']) ? (int)$_POST['annual_family_income'] : 0;
    
    // Academic Details
    $class_10_school = $conn->real_escape_string($_POST['class_10_school'] ?? '');
    $class_10_board = $conn->real_escape_string($_POST['class_10_board'] ?? 'State Board');
    $class_10_register_number = $conn->real_escape_string($_POST['class_10_register_number'] ?? '');
    $class_10_percentage = !empty($_POST['class_10_percentage']) ? (float)$_POST['class_10_percentage'] : 0;
    
    $class_12_school = $conn->real_escape_string($_POST['class_12_school'] ?? '');
    $class_12_board = $conn->real_escape_string($_POST['class_12_board'] ?? 'State Board');
    $class_12_register_number = $conn->real_escape_string($_POST['class_12_register_number'] ?? '');
    $class_12_percentage = !empty($_POST['class_12_percentage']) ? (float)$_POST['class_12_percentage'] : 0;
    $class_12_subject_1_marks = isset($_POST['class_12_subject_1_marks']) && $_POST['class_12_subject_1_marks'] !== '' ? (float)$_POST['class_12_subject_1_marks'] : null;
    $class_12_subject_2_marks = isset($_POST['class_12_subject_2_marks']) && $_POST['class_12_subject_2_marks'] !== '' ? (float)$_POST['class_12_subject_2_marks'] : null;
    $class_12_subject_3_marks = isset($_POST['class_12_subject_3_marks']) && $_POST['class_12_subject_3_marks'] !== '' ? (float)$_POST['class_12_subject_3_marks'] : null;
    $class_12_subject_4_marks = isset($_POST['class_12_subject_4_marks']) && $_POST['class_12_subject_4_marks'] !== '' ? (float)$_POST['class_12_subject_4_marks'] : null;
    $class_12_subject_5_marks = isset($_POST['class_12_subject_5_marks']) && $_POST['class_12_subject_5_marks'] !== '' ? (float)$_POST['class_12_subject_5_marks'] : null;
    $class_12_subjects = $conn->real_escape_string($_POST['class_12_subjects'] ?? '');

    $subjectMarks = array_filter([
        $class_12_subject_1_marks,
        $class_12_subject_2_marks,
        $class_12_subject_3_marks,
        $class_12_subject_4_marks,
        $class_12_subject_5_marks
    ], function($mark) {
        return $mark !== null;
    });

    if (count($subjectMarks) > 0) {
        $class_12_percentage = round(array_sum($subjectMarks) / count($subjectMarks), 2);
    }

    $class_12_subject_1_marks_sql = $class_12_subject_1_marks !== null ? $class_12_subject_1_marks : 'NULL';
    $class_12_subject_2_marks_sql = $class_12_subject_2_marks !== null ? $class_12_subject_2_marks : 'NULL';
    $class_12_subject_3_marks_sql = $class_12_subject_3_marks !== null ? $class_12_subject_3_marks : 'NULL';
    $class_12_subject_4_marks_sql = $class_12_subject_4_marks !== null ? $class_12_subject_4_marks : 'NULL';
    $class_12_subject_5_marks_sql = $class_12_subject_5_marks !== null ? $class_12_subject_5_marks : 'NULL';
    
    // Entrance Exam
    $entrance_exam_type = $conn->real_escape_string($_POST['entrance_exam_type'] ?? '');
    $entrance_exam_score = !empty($_POST['entrance_exam_score']) ? (float)$_POST['entrance_exam_score'] : 0;
    
    // Course Selection
    $degree_type = $conn->real_escape_string($_POST['degree_type'] ?? 'B.Tech');
    $course_department = $conn->real_escape_string($_POST['course_department'] ?? '');
    $preferred_specialization = $conn->real_escape_string($_POST['preferred_specialization'] ?? '');
    $admission_type = $conn->real_escape_string($_POST['admission_type'] ?? 'Merit');

    // Programme Choices
    $programme_choice = '';
    if (!empty($_POST['programme_choice']) && is_array($_POST['programme_choice'])) {
        $programme_values = [];
        foreach ($_POST['programme_choice'] as $choice) {
            $programme_values[] = $conn->real_escape_string(trim($choice));
        }
        $programme_choice = implode(', ', $programme_values);
    }
    if (empty($course_department) && !empty($programme_choice)) {
        $course_department = trim(explode(',', $programme_choice)[0]);
    }

    // File uploads
    $passport_photo = '';
    $class_10_marksheet = '';
    $class_12_marksheet = '';
    $first_graduate_certificate = '';

    $uploadDir = realpath(__DIR__ . '/../uploads');
    if (!$uploadDir) {
        mkdir(__DIR__ . '/../uploads', 0755, true);
        $uploadDir = realpath(__DIR__ . '/../uploads');
    }

    $uploadFields = [
        'passport_photo',
        'class_10_marksheet',
        'class_12_marksheet',
        'first_graduate_certificate'
    ];

    foreach ($uploadFields as $field) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $filename = basename($_FILES[$field]['name']);
            $extension = pathinfo($filename, PATHINFO_EXTENSION);
            $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
            if (in_array(strtolower($extension), $allowed) && $_FILES[$field]['size'] <= 5 * 1024 * 1024) {
                $safeName = time() . '_' . preg_replace('/[^A-Za-z0-9_.-]/', '_', $filename);
                $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $safeName;
                if (move_uploaded_file($_FILES[$field]['tmp_name'], $targetPath)) {
                    ${$field} = $conn->real_escape_string($safeName);
                }
            }
        }
    }

    // Bank Details
    $account_holder_name = $conn->real_escape_string($_POST['account_holder_name'] ?? '');
    $bank_name = $conn->real_escape_string($_POST['bank_name'] ?? '');
    $account_number = $conn->real_escape_string($_POST['account_number'] ?? '');
    $ifsc_code = $conn->real_escape_string($_POST['ifsc_code'] ?? '');
    $branch_name = $conn->real_escape_string($_POST['branch_name'] ?? '');
    
    // Additional Info
    $hostel_requirement = $conn->real_escape_string($_POST['hostel_requirement'] ?? 'No');
    $transport_requirement = $conn->real_escape_string($_POST['transport_requirement'] ?? 'No');
    $scholarship_details = $conn->real_escape_string($_POST['scholarship_details'] ?? '');
    $sports_achievements = $conn->real_escape_string($_POST['sports_achievements'] ?? '');
    $medical_information = $conn->real_escape_string($_POST['medical_information'] ?? '');
    
    // Validation
    if (empty($full_name) || empty($mobile_number) || empty($email_id) || empty($date_of_birth)) {
        $error = "❌ Full Name, Date of Birth, Mobile Number, and Email are required!";
    } else {
        // Check if email or mobile already exists
        $check = $conn->query("SELECT id FROM students WHERE email_id = '$email_id' OR mobile_number = '$mobile_number'");
        if ($check->num_rows > 0) {
            $error = "❌ Email or Mobile Number already exists!";
        } else {
            // Generate Admission ID
            $admission_id = generateAdmissionID($conn);
            
            // Insert student data
            $sql = "INSERT INTO students (
                admission_id, full_name, date_of_birth, gender, nationality, religion, community, aadhaar_number, blood_group, first_graduate,
                mobile_number, alternate_mobile, email_id, city, state, pincode, permanent_address, current_address,
                father_name, mother_name, guardian_name, parent_occupation, parent_mobile, parent_email, annual_family_income,
                class_10_school, class_10_board, class_10_register_number, class_10_percentage,
                class_12_school, class_12_board, class_12_register_number, class_12_percentage,
                class_12_subject_1_marks, class_12_subject_2_marks, class_12_subject_3_marks, class_12_subject_4_marks, class_12_subject_5_marks,
                class_12_subjects, class_12_marksheet,
                programme_choice,
                entrance_exam_type, entrance_exam_score,
                degree_type, course_department, preferred_specialization, admission_type,
                passport_photo, first_graduate_certificate, account_holder_name, bank_name, account_number, ifsc_code, branch_name,
                hostel_requirement, transport_requirement, scholarship_details, sports_achievements, medical_information,
                application_status
            ) VALUES (
                '$admission_id', '$full_name', '$date_of_birth', '$gender', '$nationality', '$religion', '$community', '$aadhaar_number', '$blood_group', '$first_graduate',
                '$mobile_number', '$alternate_mobile', '$email_id', '$city', '$state', '$pincode', '$permanent_address', '$current_address',
                '$father_name', '$mother_name', '$guardian_name', '$parent_occupation', '$parent_mobile', '$parent_email', $annual_family_income,
                '$class_10_school', '$class_10_board', '$class_10_register_number', $class_10_percentage,
                '$class_12_school', '$class_12_board', '$class_12_register_number', $class_12_percentage,
                $class_12_subject_1_marks_sql, $class_12_subject_2_marks_sql, $class_12_subject_3_marks_sql, $class_12_subject_4_marks_sql, $class_12_subject_5_marks_sql,
                '$class_12_subjects', '$class_12_marksheet',
                '$programme_choice',
                '$entrance_exam_type', $entrance_exam_score,
                '$degree_type', '$course_department', '$preferred_specialization', '$admission_type',
                '$passport_photo', '$first_graduate_certificate', '$account_holder_name', '$bank_name', '$account_number', '$ifsc_code', '$branch_name',
                '$hostel_requirement', '$transport_requirement', '$scholarship_details', '$sports_achievements', '$medical_information',
                'Submitted'
            )";
            
            if ($conn->query($sql)) {
                $student_id = $conn->insert_id;
                
                // Create payment record
                $total_fee = 100000;
                $conn->query("INSERT INTO payments (student_id, total_fee, paid_amount, payment_status) 
                             VALUES ($student_id, $total_fee, 0, 'Pending')");
                
                $success = "✅ Student registered successfully! Admission ID: <strong>$admission_id</strong>";
                $selectedStudent = $conn->query("SELECT * FROM students WHERE id = $student_id")->fetch_assoc();
                $currentTab = 'view_student';
            } else {
                $error = "❌ Error registering student: " . $conn->error;
            }
        }
    }
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
// GET ALL STUDENTS WITH PAGINATION
// ============================================
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

$countResult = $conn->query("SELECT COUNT(*) as total FROM students");
$totalRecords = $countResult->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $perPage);

$sql = "SELECT id, admission_id, full_name, mobile_number, email_id, course_department, 
        class_12_percentage, application_status, created_at 
        FROM students ORDER BY created_at DESC LIMIT $offset, $perPage";
$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $allStudents[] = $row;
    }
}

// ============================================
// GET STATISTICS
// ============================================
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$todayStudents = $conn->query("SELECT COUNT(*) as count FROM students WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['count'];
$thisMonthStudents = $conn->query("SELECT COUNT(*) as count FROM students WHERE YEAR(created_at) = YEAR(NOW()) AND MONTH(created_at) = MONTH(NOW())")->fetch_assoc()['count'];

// Include the HTML template
include('../dashboard/support_template.php');

?>
