<?php
// admission-system/actions/update_department.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Counselor');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_department'])) {
    $student_id = (int)$_POST['student_id'];
    $department = $conn->real_escape_string($_POST['department']);
    
    $sql = "UPDATE students SET department = '$department' WHERE id = $student_id";
    
    if ($conn->query($sql)) {
        header("Location: ../dashboard/counselor.php?success=Department assigned successfully!");
    } else {
        header("Location: ../dashboard/counselor.php?error=Error updating department!");
    }
    exit();
} else {
    header("Location: ../dashboard/counselor.php");
    exit();
}
?>