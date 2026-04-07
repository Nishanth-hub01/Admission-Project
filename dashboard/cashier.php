<?php
// admission-system/dashboard/cashier.php
// Cashier Dashboard for Payment Management

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Cashier');
$page_title = "Cashier Dashboard - Payment Management";

$success = '';
$error = '';
$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$selectedStudent = null;

// ============================================
// GET ALL PAYMENTS WITH STUDENT DETAILS
// ============================================
$payments = [];
$sql = "SELECT 
    p.id, 
    p.student_id,
    s.admission_id,
    s.full_name,
    s.email_id,
    s.mobile_number,
    s.course_department,
    s.application_status,
    p.total_fee,
    p.paid_amount,
    p.payment_status,
    p.payment_date,
    p.created_at
FROM payments p
INNER JOIN students s ON p.student_id = s.id
WHERE s.application_status IN ('Payment Pending', 'Confirmed')
ORDER BY p.created_at DESC";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $payments[] = $row;
    }
}

// ============================================
// HANDLE PAYMENT UPDATE
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $payment_id = (int)$_POST['payment_id'];
    $additional_amount = (float)$_POST['paid_amount'];
    $payment_status = $conn->real_escape_string($_POST['payment_status']);
    
    // Get current payment record
    $checkPayment = $conn->query("SELECT total_fee, paid_amount, student_id FROM payments WHERE id = $payment_id");
    if ($checkPayment && $checkPayment->num_rows > 0) {
        $payment = $checkPayment->fetch_assoc();
        
        $new_total_paid = $payment['paid_amount'] + $additional_amount;
        
        if ($new_total_paid > $payment['total_fee']) {
            $error = "❌ Total paid amount (₹" . number_format($new_total_paid, 2) . ") cannot exceed total fee (₹" . number_format($payment['total_fee'], 2) . ")!";
        } else {
            $sql = "UPDATE payments SET paid_amount = $new_total_paid, payment_status = '$payment_status', payment_date = NOW() WHERE id = $payment_id";
            
            if ($conn->query($sql)) {
                $success = "✅ Payment of ₹" . number_format($additional_amount, 2) . " added successfully! Total paid: ₹" . number_format($new_total_paid, 2);
            } else {
                $error = "❌ Error updating payment: " . $conn->error;
            }
        }
    }
}

// ============================================
// HANDLE APPLICATION STATUS UPDATE
// ============================================
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_app_status'])) {
    $student_id = (int)$_POST['student_id'];
    $app_status = $conn->real_escape_string($_POST['application_status']);
    
    $updateStudentSql = "UPDATE students SET application_status = '$app_status'";
    if ($app_status == 'Confirmed') {
        $updateStudentSql .= ", admission_confirmed_date = NOW()";
    }
    $updateStudentSql .= " WHERE id = $student_id";
    
    if ($conn->query($updateStudentSql)) {
        $success = "✅ Status updated successfully!";
    } else {
        $error = "❌ Error updating status: " . $conn->error;
    }
}

// ============================================
// HANDLE PAYMENT RECEIPT GENERATION
// ============================================
if (isset($_GET['generate_receipt'])) {
    $payment_id = (int)$_GET['generate_receipt'];
    $paymentResult = $conn->query("SELECT p.*, s.full_name, s.admission_id, s.email_id, s.mobile_number 
                                   FROM payments p 
                                   INNER JOIN students s ON p.student_id = s.id 
                                   WHERE p.id = $payment_id");
    
    if ($paymentResult && $paymentResult->num_rows > 0) {
        $selectedStudent = $paymentResult->fetch_assoc();
        $currentTab = 'receipt';
    }
}

// ============================================
// GET STATISTICS
// ============================================
$totalPayments = $conn->query("SELECT SUM(p.paid_amount) as total FROM payments p INNER JOIN students s ON p.student_id = s.id WHERE s.application_status IN ('Payment Pending', 'Confirmed')")->fetch_assoc()['total'] ?? 0;
$totalPending = $conn->query("SELECT SUM(p.total_fee - p.paid_amount) as total FROM payments p INNER JOIN students s ON p.student_id = s.id WHERE s.application_status IN ('Payment Pending', 'Confirmed')")->fetch_assoc()['total'] ?? 0;
$totalStudents = $conn->query("SELECT COUNT(*) as count FROM payments p INNER JOIN students s ON p.student_id = s.id WHERE s.application_status IN ('Payment Pending', 'Confirmed')")->fetch_assoc()['count'] ?? 0;
$paidCount = $conn->query("SELECT COUNT(*) as count FROM payments p INNER JOIN students s ON p.student_id = s.id WHERE p.payment_status = 'Paid' AND s.application_status IN ('Payment Pending', 'Confirmed')")->fetch_assoc()['count'] ?? 0;
$pendingCount = $totalStudents - $paidCount;

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
        }
        
        .section-title {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            font-size: 1.2rem;
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
        
        .field-label {
            color: #1a202c;
            font-weight: 700;
            margin-bottom: 8px;
            display: block;
            font-size: 1rem;
        }
        
        .field-value {
            color: #2d3748;
            padding: 12px 15px;
            background: #edf2f7;
            border-radius: 6px;
            border-left: 4px solid #667eea;
            font-weight: 600;
        }
        
        .receipt-box {
            background: white;
            border: 2px solid #667eea;
            border-radius: 12px;
            padding: 40px;
            max-width: 600px;
            margin: 20px auto;
        }
        
        .receipt-header {
            text-align: center;
            border-bottom: 2px solid #667eea;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        
        .receipt-title {
            color: #667eea;
            font-size: 1.5rem;
            font-weight: 700;
        }
        
        .receipt-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px solid #ecf0f1;
        }
        
        .receipt-label {
            font-weight: 600;
            color: #333;
        }
        
        .receipt-value {
            color: #555;
            text-align: right;
        }
        
        .receipt-total {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-top: 2px solid #667eea;
            border-bottom: 2px solid #667eea;
            margin: 20px 0;
            font-weight: 700;
            font-size: 1.2rem;
            color: #667eea;
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-cash-register"></i> <strong>Cashier Dashboard</strong>
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
                <a href="?tab=payments" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'payments' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>">
                    <i class="fas fa-money-bill"></i> All Payments
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
            <i class="fas fa-cash-register"></i> Cashier Dashboard
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
                    <div class="stat-label"><i class="fas fa-money-bill"></i> Total Collected</div>
                    <div class="stat-number">₹<?php echo number_format($totalPayments, 0); ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #2ecc71;">
                    <div class="stat-label" style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Paid</div>
                    <div class="stat-number" style="color: #2ecc71;"><?php echo $paidCount; ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #f39c12;">
                    <div class="stat-label" style="color: #f39c12;"><i class="fas fa-hourglass-half"></i> Pending</div>
                    <div class="stat-number" style="color: #f39c12;"><?php echo $pendingCount; ?></div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD TAB -->
        <?php if ($currentTab == 'dashboard'): ?>
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-chart-bar"></i> Welcome to Cashier Dashboard</h3>
                <p style="color: #1a202c; line-height: 1.8; margin: 15px 0; font-weight: 500;">
                    As a cashier, you can manage student fee payments, update payment status, and generate payment receipts.
                </p>
                <ul style="color: #2d3748; line-height: 1.8; padding-left: 20px; margin-top: 15px; font-weight: 500;">
                    <li><strong style="color: #1a202c;">💰 Record Payments:</strong> Update student payments and mark them as paid</li>
                    <li><strong style="color: #1a202c;">📋 View All Payments:</strong> See complete payment details for all students</li>
                    <li><strong style="color: #1a202c;">🧾 Generate Receipts:</strong> Create payment receipts for students</li>
                    <li><strong style="color: #1a202c;">📊 Payment Statistics:</strong> Track payment collection and pending amounts</li>
                </ul>
            </div>

            <!-- QUICK SUMMARY -->
            <div class="row">
                <div class="col-md-6">
                    <div class="section-card">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">
                            <i class="fas fa-lightning-bolt"></i> Quick Actions
                        </h5>
                        <a href="?tab=payments" class="btn btn-primary w-100">
                            <i class="fas fa-list"></i> View All Payments
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="section-card">
                        <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">
                            <i class="fas fa-info-circle"></i> Payment Summary
                        </h5>
                        <p style="margin: 12px 0; color: #1a202c; font-size: 1.1rem;">
                            <strong>Total Collected:</strong> 
                            <span style="color: #16a34a; font-size: 1.4rem; font-weight: 800; text-shadow: 0 0 1px #86efac;">₹<?php echo number_format($totalPayments, 0); ?></span>
                        </p>
                        <p style="margin: 12px 0; color: #1a202c; font-size: 1.1rem;">
                            <strong>Amount Pending:</strong> 
                            <span style="color: #ea580c; font-size: 1.4rem; font-weight: 800; text-shadow: 0 0 1px #fdba74;">₹<?php echo number_format($totalPending, 0); ?></span>
                        </p>
                        <hr style="opacity: 0.1; margin: 15px 0;">
                        <p style="margin: 10px 0; color: #4a5568;">
                            <strong>Total Students:</strong> 
                            <span style="color: #667eea; font-weight: 700;"><?php echo $totalStudents; ?></span>
                        </p>
                    </div>
                </div>
            </div>

        <!-- PAYMENTS TAB -->
        <?php elseif ($currentTab == 'payments'): ?>
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-list"></i> All Student Payments</h3>
                
                <?php if (!empty($payments)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Admission ID</th>
                                    <th>Student Name</th>
                                    <th>Mobile</th>
                                    <th>Total Fee</th>
                                    <th>Paid Amount</th>
                                    <th>Pending</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td>
                                            <code style="background: #f0f0f0; padding: 4px 8px; border-radius: 4px;">
                                                <?php echo htmlspecialchars($payment['admission_id']); ?>
                                            </code>
                                        </td>
                                        <td><strong><?php echo htmlspecialchars($payment['full_name']); ?></strong></td>
                                        <td style="color: #2d3748;"><?php echo htmlspecialchars($payment['mobile_number']); ?></td>
                                        <td style="color: #1a202c; font-weight: 500;">₹<?php echo number_format($payment['total_fee'], 0); ?></td>
                                        <td style="color: #166534; font-weight: 700;">₹<?php echo number_format($payment['paid_amount'], 0); ?></td>
                                        <td style="color: #c53030; font-weight: 700;">₹<?php echo number_format($payment['total_fee'] - $payment['paid_amount'], 0); ?></td>
                                        <td>
                                            <?php 
                                            $statusColor = $payment['payment_status'] == 'Paid' ? 'success' : 'warning';
                                            ?>
                                            <span class="badge bg-<?php echo $statusColor; ?>">
                                                <?php echo htmlspecialchars($payment['payment_status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" 
                                                    data-bs-target="#paymentModal<?php echo $payment['id']; ?>">
                                                <i class="fas fa-edit"></i> Update
                                            </button>
                                            <button class="btn btn-sm btn-info text-white" data-bs-toggle="modal" 
                                                    data-bs-target="#statusModal<?php echo $payment['id']; ?>">
                                                <i class="fas fa-exchange-alt"></i> Status
                                            </button>
                                            <a href="?tab=receipt&generate_receipt=<?php echo $payment['id']; ?>" class="btn btn-sm btn-success">
                                                <i class="fas fa-receipt"></i> Receipt
                                            </a>
                                        </td>
                                    </tr>

                                    <!-- PAYMENT UPDATE MODAL -->
                                    <div class="modal fade" id="paymentModal<?php echo $payment['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white;">
                                                    <h5 class="modal-title"><i class="fas fa-edit"></i> Update Payment</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                     <div class="modal-body">
                                                        <div style="margin-bottom: 20px; background: #ebf4ff; padding: 15px; border-radius: 10px; border-left: 5px solid #667eea;">
                                                            <div style="color: #2c3e50; font-size: 1.1rem; margin-bottom: 10px;"><strong>Student:</strong> <?php echo htmlspecialchars($payment['full_name']); ?></div>
                                                            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
                                                                <div style="color: #1a202c;"><strong>Total Fee:</strong><br><span style="font-size: 1.2rem;">₹<?php echo number_format($payment['total_fee'], 0); ?></span></div>
                                                                <div style="color: #166534;"><strong>Already Paid:</strong><br><span style="font-size: 1.2rem; font-weight: 700;">₹<?php echo number_format($payment['paid_amount'], 0); ?></span></div>
                                                                <div style="color: #c53030; grid-column: span 2; border-top: 1px solid #cbd5e0; padding-top: 10px; margin-top: 5px;">
                                                                    <strong>Remaining Balance:</strong> <span style="font-size: 1.3rem; font-weight: 800;">₹<?php echo number_format($payment['total_fee'] - $payment['paid_amount'], 0); ?></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="form-group mb-4">
                                                            <label class="field-label" style="font-size: 1.1rem; color: #1a202c;">💰 Amount to Pay Now (Additional)</label>
                                                            <input type="number" name="paid_amount" class="form-control form-control-lg" 
                                                                   value="0" 
                                                                   min="0" max="<?php echo $payment['total_fee'] - $payment['paid_amount']; ?>" 
                                                                   style="border: 2px solid #667eea; font-weight: 700; font-size: 1.25rem; color: #1a202c;"
                                                                   step="0.01" required>
                                                            <small style="color: #4a5568; font-weight: 500;">Enter the exact amount collected from the student right now.</small>
                                                        </div>

                                                        <div class="form-group mb-3">
                                                            <label class="field-label">Payment Status</label>
                                                            <select name="payment_status" class="form-select" required>
                                                                <option value="Pending" <?php echo $payment['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                                <option value="Paid" <?php echo $payment['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                                            </select>
                                                        </div>

                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                                        <button type="submit" name="update_payment" class="btn btn-primary">
                                                            <i class="fas fa-save"></i> Update Payment
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- APPLICATION STATUS UPDATE MODAL -->
                                    <div class="modal fade" id="statusModal<?php echo $payment['id']; ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header" style="background: linear-gradient(135deg, #17a2b8 0%, #117a8b 100%); color: white;">
                                                    <h5 class="modal-title"><i class="fas fa-exchange-alt"></i> Update Status</h5>
                                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                                                </div>
                                                <form method="POST" action="">
                                                    <div class="modal-body">
                                                        <p style="margin-bottom: 15px;">
                                                            <strong>Student:</strong> <?php echo htmlspecialchars($payment['full_name']); ?>
                                                        </p>
                                                        <div class="form-group mb-3">
                                                            <label class="field-label">Student Application Status</label>
                                                            <select name="application_status" class="form-select" required>
                                                                <option value="Payment Pending" <?php echo ($payment['application_status'] ?? '') == 'Payment Pending' ? 'selected' : ''; ?>>Payment Pending</option>
                                                                <option value="Confirmed" <?php echo ($payment['application_status'] ?? '') == 'Confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <input type="hidden" name="student_id" value="<?php echo $payment['student_id']; ?>">
                                                        <button type="submit" name="update_app_status" class="btn btn-info" style="color: white;">
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
                        <p>No payments found</p>
                    </div>
                <?php endif; ?>
            </div>

        <!-- RECEIPT TAB -->
        <?php elseif ($currentTab == 'receipt' && $selectedStudent): ?>
            <div style="margin-bottom: 25px;">
                <a href="?tab=payments" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to Payments
                </a>
                <button onclick="window.print()" class="btn btn-primary">
                    <i class="fas fa-print"></i> Print Receipt
                </button>
            </div>

            <!-- PAYMENT RECEIPT -->
            <div class="receipt-box">
                <div class="receipt-header">
                    <div class="receipt-title">PAYMENT RECEIPT</div>
                    <p style="color: #666; margin: 10px 0;">Admission System</p>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Receipt Number:</span>
                    <span class="receipt-value"><?php echo 'RCP-' . str_pad($selectedStudent['id'], 6, '0', STR_PAD_LEFT); ?></span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Receipt Date:</span>
                    <span class="receipt-value"><?php echo date('d-m-Y'); ?></span>
                </div>

                <div class="receipt-row">
                    <span class="receipt-label">Receipt Time:</span>
                    <span class="receipt-value"><?php echo date('H:i:s'); ?></span>
                </div>

                <div style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">Student Information</h5>
                    
                    <div class="receipt-row">
                        <span class="receipt-label">Admission ID:</span>
                        <span class="receipt-value"><?php echo htmlspecialchars($selectedStudent['admission_id']); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Student Name:</span>
                        <span class="receipt-value"><?php echo htmlspecialchars($selectedStudent['full_name']); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Email:</span>
                        <span class="receipt-value" style="text-align: right; word-break: break-all;"><?php echo htmlspecialchars($selectedStudent['email_id']); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Mobile:</span>
                        <span class="receipt-value"><?php echo htmlspecialchars($selectedStudent['mobile_number']); ?></span>
                    </div>
                </div>

                <div style="margin: 30px 0; padding: 20px; background: #f8f9fa; border-radius: 8px;">
                    <h5 style="color: #667eea; font-weight: 700; margin-bottom: 15px;">Payment Details</h5>
                    
                    <div class="receipt-row">
                        <span class="receipt-label">Total Fee:</span>
                        <span class="receipt-value">₹<?php echo number_format($selectedStudent['total_fee'], 0); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Paid Amount:</span>
                        <span class="receipt-value">₹<?php echo number_format($selectedStudent['paid_amount'], 0); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Pending Amount:</span>
                        <span class="receipt-value">₹<?php echo number_format($selectedStudent['total_fee'] - $selectedStudent['paid_amount'], 0); ?></span>
                    </div>

                    <div class="receipt-total">
                        <span>Total Paid:</span>
                        <span>₹<?php echo number_format($selectedStudent['paid_amount'], 0); ?></span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Payment Status:</span>
                        <span class="receipt-value" style="font-weight: 700; color: <?php echo $selectedStudent['payment_status'] == 'Paid' ? '#2ecc71' : '#f39c12'; ?>;">
                            <?php echo htmlspecialchars($selectedStudent['payment_status']); ?>
                        </span>
                    </div>

                    <div class="receipt-row">
                        <span class="receipt-label">Payment Date:</span>
                        <span class="receipt-value"><?php echo !empty($selectedStudent['payment_date']) ? date('d-m-Y', strtotime($selectedStudent['payment_date'])) : 'N/A'; ?></span>
                    </div>
                </div>

                <div style="margin-top: 40px; text-align: center; padding-top: 20px; border-top: 2px solid #667eea;">
                    <p style="color: #999; font-size: 0.9rem;">Thank you for payment!</p>
                    <p style="color: #999; font-size: 0.8rem;">This is a computer-generated receipt. No signature required.</p>
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