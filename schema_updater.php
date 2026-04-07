<?php
include('includes/db.php');

$response = [];

// 1. Alter Schema ENUM
$alterSql = "ALTER TABLE students MODIFY COLUMN application_status ENUM('Enquiry', 'Payment Pending', 'Confirmed', 'Rejected') DEFAULT 'Enquiry'";
if ($conn->query($alterSql)) {
    $response[] = "Schema ENUM altered successfully.";
} else {
    $response[] = "Schema ENUM alteration failed: " . $conn->error;
}

// 2. Update existing legacy records
$updateSql = "UPDATE students SET application_status = 'Confirmed' WHERE application_status = 'Admission Confirmed'";
if ($conn->query($updateSql)) {
    $response[] = "Legacy records updated successfully.";
} else {
    $response[] = "Legacy records update failed: " . $conn->error;
}

echo implode("\n", $response);
?>
