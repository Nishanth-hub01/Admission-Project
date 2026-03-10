<?php
// admission-system/includes/admission_id_helper.php
// Helper functions for Admission ID generation

/**
 * Generate Admission ID
 * Format: SI + DD + MM + Serial Number (3 digits)
 * Example: SI1003001
 * 
 * @param mysqli $conn - Database connection
 * @return string - Generated Admission ID
 */
function generateAdmissionID($conn) {
    // Get current date and month
    $date = date("d");
    $month = date("m");
    
    // Count students registered today
    $todayDate = date("Y-m-d");
    $countResult = $conn->query("SELECT COUNT(*) as count FROM students WHERE DATE(created_at) = '$todayDate'");
    $count = $countResult->fetch_assoc()['count'];
    
    // Calculate serial number (next number for today)
    $serial = $count + 1;
    
    // Format serial number with leading zeros (3 digits)
    $serialFormatted = str_pad($serial, 3, "0", STR_PAD_LEFT);
    
    // Generate Admission ID
    // SI = Institution short name (prefix)
    // DD = Date (2 digits)
    // MM = Month (2 digits)
    // Serial = 3 digit running number
    $admission_id = "SI" . $date . $month . $serialFormatted;
    
    return $admission_id;
}

/**
 * Check if Admission ID already exists
 * 
 * @param mysqli $conn - Database connection
 * @param string $admission_id - Admission ID to check
 * @return bool - True if exists, False if not
 */
function admissionIDExists($conn, $admission_id) {
    $admission_id = $conn->real_escape_string($admission_id);
    $result = $conn->query("SELECT id FROM students WHERE admission_id = '$admission_id'");
    return $result && $result->num_rows > 0;
}

/**
 * Get student by Admission ID
 * 
 * @param mysqli $conn - Database connection
 * @param string $admission_id - Admission ID to search
 * @return array|null - Student data or null if not found
 */
function getStudentByAdmissionID($conn, $admission_id) {
    $admission_id = $conn->real_escape_string($admission_id);
    $result = $conn->query("SELECT * FROM students WHERE admission_id = '$admission_id'");
    
    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    }
    return null;
}

/**
 * Format Admission ID with readable format
 * 
 * @param string $admission_id - Admission ID
 * @return string - Formatted ID
 */
function formatAdmissionID($admission_id) {
    // SI1003001 -> SI-10-03-001
    if (strlen($admission_id) == 9 && substr($admission_id, 0, 2) == 'SI') {
        $date = substr($admission_id, 2, 2);
        $month = substr($admission_id, 4, 2);
        $serial = substr($admission_id, 6, 3);
        return "SI-{$date}-{$month}-{$serial}";
    }
    return $admission_id;
}
?>