<?php
// admission-system/actions/add_student.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Support Staff');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $conn->real_escape_string($_POST['name']);
    $dob = $conn->real_escape_string($_POST['dob']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $email = $conn->real_escape_string($_POST['email']);
    $address = $conn->real_escape_string($_POST['address']);
    $department = $conn->real_escape_string($_POST['department']);
    
    $documents = '';
    
    // Handle file upload
    if (isset($_FILES['documents']) && $_FILES['documents']['error'] == 0) {
        $allowed = array('pdf', 'jpg', 'jpeg');
        $filename = $_FILES['documents']['name'];
        $filetype = pathinfo($filename, PATHINFO_EXTENSION);
        
        if (in_array(strtolower($filetype), $allowed) && $_FILES['documents']['size'] <= 5242880) {
            $unique_name = time() . '_' . $filename;
            $upload_path = '../uploads/' . $unique_name;
            
            if (move_uploaded_file($_FILES['documents']['tmp_name'], $upload_path)) {
                $documents = $unique_name;
            }
        }
    }
    
    $sql = "INSERT INTO students (name, dob, phone, email, address, department, documents) 
            VALUES ('$name', '$dob', '$phone', '$email', '$address', '$department', '$documents')";
    
    if ($conn->query($sql)) {
        $student_id = $conn->insert_id;
        
        // Create payment record
        $total_fee = 100000; // Default fee
        $conn->query("INSERT INTO payments (student_id, total_fee, paid_amount, payment_status) 
                     VALUES ($student_id, $total_fee, 0, 'Pending')");
        
        header("Location: ../dashboard/support.php?success=Student added successfully!");
    } else {
        header("Location: ../dashboard/support.php?error=Error adding student!");
    }
} else {
    header("Location: ../dashboard/support.php");
}
?>