<?php
// admission-system/dashboard/cashier.php

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Cashier');
$page_title = "Cashier Dashboard";

// Get payment statistics
$totalFee = $conn->query("SELECT SUM(total_fee) as total FROM payments")->fetch_assoc()['total'] ?? 0;
$paidAmount = $conn->query("SELECT SUM(paid_amount) as total FROM payments WHERE payment_status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$pendingAmount = $totalFee - $paidAmount;

// Get all payments with student details
$payments_result = $conn->query("
    SELECT p.*, s.name, s.email 
    FROM payments p
    JOIN students s ON p.student_id = s.id
    ORDER BY p.created_at DESC
");

// Handle payment update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
    $payment_id = (int)$_POST['payment_id'];
    $paid_amount = (float)$_POST['paid_amount'];
    $payment_status = $conn->real_escape_string($_POST['payment_status']);
    
    $sql = "UPDATE payments SET paid_amount = $paid_amount, payment_status = '$payment_status', payment_date = NOW() 
            WHERE id = $payment_id";
    
    if ($conn->query($sql)) {
        $success = "Payment updated successfully!";
    }
}

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
            <li><a href="cashier.php" class="active"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="#payments"><i class="fas fa-credit-card"></i> Payments</a></li>
            <li><a href="../actions/logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <h2 class="mb-4">Cashier Dashboard</h2>

        <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-money-bill"></i></div>
                    <h5>Total Fee</h5>
                    <div class="stat-number">₹<?php echo number_format($totalFee, 2); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-check-circle"></i></div>
                    <h5>Paid Amount</h5>
                    <div class="stat-number">₹<?php echo number_format($paidAmount, 2); ?></div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="stat-card">
                    <div class="stat-icon"><i class="fas fa-hourglass-end"></i></div>
                    <h5>Pending Amount</h5>
                    <div class="stat-number">₹<?php echo number_format($pendingAmount, 2); ?></div>
                </div>
            </div>
        </div>

        <!-- Payments List -->
        <div id="payments" class="form-card">
            <h4><i class="fas fa-credit-card"></i> Payment Details</h4>
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Total Fee</th>
                            <th>Paid Amount</th>
                            <th>Pending</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $payments_result = $conn->query("
                            SELECT p.*, s.name, s.email 
                            FROM payments p
                            JOIN students s ON p.student_id = s.id
                            ORDER BY p.created_at DESC
                        ");
                        while ($payment = $payments_result->fetch_assoc()): 
                            $pending = $payment['total_fee'] - $payment['paid_amount'];
                        ?>
                            <tr>
                                <td><?php echo htmlspecialchars($payment['name']); ?></td>
                                <td>₹<?php echo number_format($payment['total_fee'], 2); ?></td>
                                <td>₹<?php echo number_format($payment['paid_amount'], 2); ?></td>
                                <td>₹<?php echo number_format($pending, 2); ?></td>
                                <td>
                                    <?php if ($payment['payment_status'] == 'Paid'): ?>
                                        <span class="badge badge-success">Paid</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning">Pending</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" data-bs-target="#paymentModal<?php echo $payment['id']; ?>">
                                        <i class="fas fa-edit"></i> Update
                                    </button>
                                </td>
                            </tr>

                            <!-- Payment Modal -->
                            <div class="modal fade" id="paymentModal<?php echo $payment['id']; ?>" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Update Payment</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form method="POST">
                                            <div class="modal-body">
                                                <input type="hidden" name="payment_id" value="<?php echo $payment['id']; ?>">
                                                <div class="mb-3">
                                                    <label class="form-label">Student: <?php echo htmlspecialchars($payment['name']); ?></label>
                                                </div>
                                                <div class="mb-3">
                                                    <label class="form-label">Total Fee: ₹<?php echo number_format($payment['total_fee'], 2); ?></label>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="paid_amount" class="form-label">Paid Amount</label>
                                                    <input type="number" step="0.01" class="form-control" id="paid_amount" name="paid_amount" value="<?php echo $payment['paid_amount']; ?>" required>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="payment_status" class="form-label">Payment Status</label>
                                                    <select class="form-select" name="payment_status" required>
                                                        <option value="Pending" <?php echo $payment['payment_status'] == 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                                        <option value="Paid" <?php echo $payment['payment_status'] == 'Paid' ? 'selected' : ''; ?>>Paid</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" name="update_payment" class="btn btn-primary">Update Payment</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>