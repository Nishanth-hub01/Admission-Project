<?php
// admission-system/actions/payment_update.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Cashier');

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $payment_id = (int)$_POST['payment_id'];
    $paid_amount = (float)$_POST['paid_amount'];
    $payment_status = $conn->real_escape_string($_POST['payment_status']);
    
    // Validate paid amount
    $payment = $conn->query("SELECT total_fee FROM payments WHERE id = $payment_id")->fetch_assoc();
    
    if ($paid_amount > $payment['total_fee']) {
        header("Location: ../dashboard/cashier.php?error=Paid amount cannot exceed total fee!");
        exit();
    }
    
    $sql = "UPDATE payments SET paid_amount = $paid_amount, payment_status = '$payment_status', payment_date = NOW() 
            WHERE id = $payment_id";
    
    if ($conn->query($sql)) {
        header("Location: ../dashboard/cashier.php?success=Payment updated successfully!");
    } else {
        header("Location: ../dashboard/cashier.php?error=Error updating payment!");
    }
    exit();
} else {
    header("Location: ../dashboard/cashier.php");
    exit();
}
?>