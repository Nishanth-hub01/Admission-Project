<?php
// admission-system/dashboard/management.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Management');
$page_title = "Management Dashboard";

// Get statistics
$totalApplications = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$totalFeeCollected = $conn->query("SELECT SUM(paid_amount) as total FROM payments WHERE payment_status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$pendingPayments = $conn->query("SELECT SUM(total_fee - paid_amount) as total FROM payments WHERE payment_status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$totalApproved = $conn->query("SELECT COUNT(*) as count FROM students WHERE department IS NOT NULL")->fetch_assoc()['count'];

// Get today's applications
$today = date('Y-m-d');
$todayApplications = $conn->query("
    SELECT COUNT(*) as count FROM students 
    WHERE DATE(created_at) = '$today'
")->fetch_assoc()['count'];

// Get today's payments
$todayPayments = $conn->query("
    SELECT SUM(paid_amount) as total FROM payments 
    WHERE payment_status = 'Paid' AND DATE(payment_date) = '$today'
")->fetch_assoc()['total'] ?? 0;

// Get daily report data
$reportResult = $conn->query("
    SELECT 
        DATE(s.created_at) as date,
        COUNT(s.id) as applications,
        SUM(CASE WHEN p.payment_status = 'Paid' THEN p.paid_amount ELSE 0 END) as fees_collected,
        SUM(CASE WHEN p.payment_status = 'Pending' THEN p.total_fee - p.paid_amount ELSE 0 END) as pending
    FROM students s
    LEFT JOIN payments p ON s.id = p.student_id
    GROUP BY DATE(s.created_at)
    ORDER BY DATE(s.created_at) DESC
    LIMIT 30
");

include('../includes/header.php');
?>

<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> Admission System
        </a>
        <div class="navbar-nav ms-auto">
            <span class="nav-item nav-link">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</span>
            <a href="../actions/logout.php" class="nav-link">Logout</a>
        </div>
    </div>
</nav>

<div class="dashboard-container">
    <!-- Sidebar -->
    <div class="sidebar">
        <h5><i class="fas fa-bars"></i> Menu</h5>
        <ul class="sidebar-menu">
            <li><a href="management.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#reports"><i class="fas fa-file-alt"></i> Reports</a></li>
            <li><a href="#today-summary"><i class="fas fa-calendar-day"></i> Today Summary</a></li>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Management Dashboard</h2>

        <!-- Overall Statistics -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-file-alt"></i></div>
                    <h5>Total Applications</h5>
                    <div class="stat-number"><?php echo $totalApplications; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-double"></i></div>
                    <h5>Approved</h5>
                    <div class="stat-number"><?php echo $totalApproved; ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-money-bill-wave"></i></div>
                    <h5>Fees Collected</h5>
                    <div class="stat-number">₹<?php echo number_format($totalFeeCollected, 2); ?></div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <h5>Pending Payment</h5>
                    <div class="stat-number">₹<?php echo number_format($pendingPayments, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Today's Summary -->
        <div id="today-summary" class="form-card">
            <h4><i class="fas fa-calendar-day"></i> Today's Summary (<?php echo date('d-m-Y'); ?>)</h4>
            <div class="row">
                <div class="col-md-6">
                    <div class="alert alert-info">
                        <strong>Applications Today:</strong> <?php echo $todayApplications; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="alert alert-success">
                