<?php
// db_fix.php
include('includes/db.php');

$results = [];

// 1. Add missing column 'admission_confirmed_date'
$checkColumn = $conn->query("SHOW COLUMNS FROM students LIKE 'admission_confirmed_date'");
if ($checkColumn && $checkColumn->num_rows == 0) {
    if ($conn->query("ALTER TABLE students ADD COLUMN admission_confirmed_date TIMESTAMP NULL AFTER application_status")) {
        $results[] = "✅ Column 'admission_confirmed_date' added successfully.";
    } else {
        $results[] = "❌ Error adding column: " . $conn->error;
    }
} else {
    $results[] = "ℹ️ Column 'admission_confirmed_date' already exists.";
}

// 2. Ensure application_status ENUM is correct
$alterEnum = "ALTER TABLE students MODIFY COLUMN application_status ENUM('Enquiry', 'Payment Pending', 'Confirmed', 'Rejected') DEFAULT 'Enquiry'";
if ($conn->query($alterEnum)) {
    $results[] = "✅ 'application_status' ENUM updated successfully.";
} else {
    $results[] = "❌ Error updating ENUM: " . $conn->error;
}

// Summary
echo "<h3>Database Fix Results:</h3><ul>";
foreach ($results as $res) {
    echo "<li>$res</li>";
}
echo "</ul><p><a href='dashboard/cashier.php'>Return to Cashier Dashboard</a></p>";
?>
