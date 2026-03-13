<?php
// admission-system/dashboard/management.php
// Management Dashboard with Advanced Reports

include('../includes/db.php');
include('../includes/auth.php');

requireRole('Management');
$page_title = "Management Dashboard";

// Get statistics
$totalApplications = $conn->query("SELECT COUNT(*) as count FROM students")->fetch_assoc()['count'];
$totalFeeCollected = $conn->query("SELECT SUM(paid_amount) as total FROM payments WHERE payment_status = 'Paid'")->fetch_assoc()['total'] ?? 0;
$pendingPayments = $conn->query("SELECT SUM(total_fee - paid_amount) as total FROM payments WHERE payment_status = 'Pending'")->fetch_assoc()['total'] ?? 0;
$totalApproved = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Approved'")->fetch_assoc()['count'];
$totalPending = $conn->query("SELECT COUNT(*) as count FROM students WHERE application_status = 'Submitted'")->fetch_assoc()['count'];

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

// Get department-wise distribution
$deptStats = [];
$deptResult = $conn->query("
    SELECT course_department, COUNT(*) as count, SUM(CASE WHEN application_status = 'Approved' THEN 1 ELSE 0 END) as approved
    FROM students
    WHERE course_department IS NOT NULL
    GROUP BY course_department
    ORDER BY count DESC
");

if ($deptResult && $deptResult->num_rows > 0) {
    while ($row = $deptResult->fetch_assoc()) {
        $deptStats[] = $row;
    }
}

// Get daily report data
$reportData = [];
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

if ($reportResult && $reportResult->num_rows > 0) {
    while ($row = $reportResult->fetch_assoc()) {
        $reportData[] = $row;
    }
}

// Get monthly statistics
$monthlyStats = [];
$monthlyResult = $conn->query("
    SELECT 
        DATE_FORMAT(s.created_at, '%b %Y') as month,
        COUNT(s.id) as applications,
        SUM(CASE WHEN p.payment_status = 'Paid' THEN p.paid_amount ELSE 0 END) as fees_collected
    FROM students s
    LEFT JOIN payments p ON s.id = p.student_id
    GROUP BY YEAR(s.created_at), MONTH(s.created_at)
    ORDER BY s.created_at DESC
    LIMIT 12
");

if ($monthlyResult && $monthlyResult->num_rows > 0) {
    while ($row = $monthlyResult->fetch_assoc()) {
        $monthlyStats[] = $row;
    }
}

// Calculate conversion rate safely
$conversionRate = ($totalApplications > 0) ? round(($totalApproved / $totalApplications) * 100, 1) : 0;
$totalFees = $totalFeeCollected + $pendingPayments;
$collectionRate = ($totalFees > 0) ? round(($totalFeeCollected / $totalFees) * 100, 1) : 0;

$currentTab = isset($_GET['tab']) ? $_GET['tab'] : 'dashboard';
$success = '';
$error = '';

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
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
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
            transition: all 0.3s;
        }
        
        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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
        
        .chart-container {
            position: relative;
            height: 350px;
            margin-bottom: 30px;
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .chart-title {
            font-weight: 700;
            color: #667eea;
            margin-bottom: 20px;
            font-size: 1.1rem;
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
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-chart-line"></i> <strong>Management Dashboard</strong>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <div class="navbar-nav ms-auto">
                <span class="nav-link"><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="../actions/logout.php" class="nav-link"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
</nav>

<div style="display: flex; min-height: calc(100vh - 60px);">

    <!-- SIDEBAR -->
    <div class="sidebar" style="width: 250px; background: #2c3e50; color: white; padding: 20px 0; position: fixed; height: calc(100vh - 60px); overflow-y: auto; top: 60px; left: 0; box-shadow: 2px 0 5px rgba(0,0,0,0.1);">
        <h5 style="padding: 15px 20px; color: #667eea; border-bottom: 1px solid #34495e;"><i class="fas fa-bars"></i> Menu</h5>
        <ul class="sidebar-menu" style="list-style: none; padding: 0; margin: 0;">
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="?tab=dashboard" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'dashboard' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="?tab=analytics" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'analytics' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>"><i class="fas fa-chart-bar"></i> Analytics</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="?tab=reports" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none; <?php echo $currentTab == 'reports' ? 'background: rgba(102, 126, 234, 0.2); color: white;' : ''; ?>"><i class="fas fa-file-alt"></i> Reports</a></li>
            <li style="border-bottom: 1px solid rgba(255,255,255,0.05);"><a href="../actions/logout.php" style="display: block; padding: 12px 20px; color: #ecf0f1; text-decoration: none;"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
    </div>

    <!-- MAIN CONTENT -->
    <div style="flex: 1; padding: 30px; margin-left: 250px; overflow-y: auto;">

        <h2 style="color: #667eea; margin-bottom: 25px;"><i class="fas fa-chart-line"></i> Management Dashboard</h2>

        <!-- DASHBOARD TAB -->
        <?php if ($currentTab == 'dashboard'): ?>

            <!-- KEY STATISTICS -->
            <div class="row mb-4">
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card">
                        <div class="stat-label"><i class="fas fa-file-alt"></i> Total Applications</div>
                        <div class="stat-number"><?php echo $totalApplications; ?></div>
                        <small style="color: #999;">Registered students</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: #2ecc71;">
                        <div class="stat-label" style="color: #2ecc71;"><i class="fas fa-check-circle"></i> Approved</div>
                        <div class="stat-number" style="color: #2ecc71;"><?php echo $totalApproved; ?></div>
                        <small style="color: #999;"><?php echo $conversionRate; ?>% conversion</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: #f39c12;">
                        <div class="stat-label" style="color: #f39c12;"><i class="fas fa-hourglass-half"></i> Pending</div>
                        <div class="stat-number" style="color: #f39c12;"><?php echo $totalPending; ?></div>
                        <small style="color: #999;">Under review</small>
                    </div>
                </div>
                <div class="col-lg-3 col-md-6">
                    <div class="stat-card" style="border-left-color: #3498db;">
                        <div class="stat-label" style="color: #3498db;"><i class="fas fa-money-bill"></i> Collected</div>
                        <div class="stat-number" style="color: #3498db;">₹<?php echo number_format($totalFeeCollected / 100000, 1); ?>L</div>
                        <small style="color: #999;"><?php echo $collectionRate; ?>% collection rate</small>
                    </div>
                </div>
            </div>

            <!-- TODAY'S SUMMARY -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="section-card">
                        <h4 style="color: #667eea; margin: 0; font-weight: 700;">
                            <i class="fas fa-calendar-day"></i> Today's Summary (<?php echo date('d M Y'); ?>)
                        </h4>
                        <div style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ecf0f1;">
                                <span style="color: #666;"><strong>New Applications:</strong></span>
                                <span style="color: #667eea; font-weight: 700; font-size: 1.3rem;"><?php echo $todayApplications; ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="color: #666;"><strong>Fees Collected:</strong></span>
                                <span style="color: #2ecc71; font-weight: 700; font-size: 1.3rem;">₹<?php echo number_format($todayPayments, 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- QUICK STATS -->
                <div class="col-md-6">
                    <div class="section-card">
                        <h4 style="color: #667eea; margin: 0; font-weight: 700;">
                            <i class="fas fa-info-circle"></i> Financial Summary
                        </h4>
                        <div style="margin-top: 15px;">
                            <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #ecf0f1;">
                                <span style="color: #666;"><strong>Total Fees:</strong></span>
                                <span style="color: #667eea; font-weight: 700; font-size: 1.3rem;">₹<?php echo number_format($totalFees, 0); ?></span>
                            </div>
                            <div style="display: flex; justify-content: space-between; padding: 10px 0;">
                                <span style="color: #666;"><strong>Pending Payment:</strong></span>
                                <span style="color: #f39c12; font-weight: 700; font-size: 1.3rem;">₹<?php echo number_format($pendingPayments, 0); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- DEPARTMENT-WISE DISTRIBUTION -->
            <div class="section-card">
                <h3 class="section-title"><i class="fas fa-graduation-cap"></i> Department-wise Distribution</h3>
                
                <?php if (!empty($deptStats)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Department</th>
                                    <th>Total Applications</th>
                                    <th>Approved</th>
                                    <th>Approval %</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($deptStats as $dept): ?>
                                    <tr>
                                        <td><strong><?php echo htmlspecialchars($dept['course_department']); ?></strong></td>
                                        <td><?php echo $dept['count']; ?></td>
                                        <td><span class="badge bg-success"><?php echo $dept['approved']; ?></span></td>
                                        <td><?php echo $dept['count'] > 0 ? round(($dept['approved'] / $dept['count']) * 100, 1) : 0; ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

        <!-- ANALYTICS TAB -->
        <?php elseif ($currentTab == 'analytics'): ?>

            <h3 style="color: #667eea; margin: 25px 0; font-weight: 700;"><i class="fas fa-chart-bar"></i> Analytics & Insights</h3>

            <div class="row">
                <!-- Monthly Applications Chart -->
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-title"><i class="fas fa-calendar-alt"></i> Monthly Applications</div>
                        <canvas id="monthlyApplicationsChart"></canvas>
                    </div>
                </div>

                <!-- Monthly Revenue Chart -->
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-title"><i class="fas fa-money-bill"></i> Monthly Revenue</div>
                        <canvas id="monthlyRevenueChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Application Status Chart -->
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-title"><i class="fas fa-tasks"></i> Application Status</div>
                        <canvas id="statusChart"></canvas>
                    </div>
                </div>

                <!-- Payment Status Chart -->
                <div class="col-lg-6">
                    <div class="chart-container">
                        <div class="chart-title"><i class="fas fa-credit-card"></i> Payment Status</div>
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>

        <!-- REPORTS TAB -->
        <?php elseif ($currentTab == 'reports'): ?>

            <h3 style="color: #667eea; margin: 25px 0; font-weight: 700;"><i class="fas fa-file-alt"></i> Detailed Reports</h3>

            <!-- DAILY REPORT TABLE -->
            <div class="section-card">
                <h4 style="color: #667eea; margin: 0 0 20px 0; font-weight: 700;">Last 30 Days Summary</h4>
                
                <?php if (!empty($reportData)): ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Applications</th>
                                    <th>Fees Collected</th>
                                    <th>Pending Amount</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reportData as $report): ?>
                                    <tr>
                                        <td><strong><?php echo date('d M Y', strtotime($report['date'])); ?></strong></td>
                                        <td><?php echo $report['applications']; ?></td>
                                        <td style="color: #2ecc71; font-weight: 600;">₹<?php echo number_format($report['fees_collected'] ?? 0, 0); ?></td>
                                        <td style="color: #f39c12; font-weight: 600;">₹<?php echo number_format($report['pending'] ?? 0, 0); ?></td>
                                        <td style="font-weight: 700; color: #667eea;">₹<?php echo number_format(($report['fees_collected'] ?? 0) + ($report['pending'] ?? 0), 0); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <p style="text-align: center; color: #999; padding: 20px;">No report data available</p>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CHARTS INITIALIZATION -->
<script>
    // Monthly Applications Chart
    <?php 
    $months = [];
    $applications = [];
    foreach ($monthlyStats as $stat) {
        $months[] = $stat['month'];
        $applications[] = $stat['applications'];
    }
    ?>

    if (document.getElementById('monthlyApplicationsChart')) {
        const monthlyAppCtx = document.getElementById('monthlyApplicationsChart').getContext('2d');
        new Chart(monthlyAppCtx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode(array_reverse($months)); ?>,
                datasets: [{
                    label: 'Applications',
                    data: <?php echo json_encode(array_reverse($applications)); ?>,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 6,
                    pointBackgroundColor: '#667eea'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { font: { size: 12, weight: 'bold' } } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Monthly Revenue Chart
    <?php 
    $revenues = [];
    foreach ($monthlyStats as $stat) {
        $revenues[] = (int)($stat['fees_collected'] ?? 0);
    }
    ?>

    if (document.getElementById('monthlyRevenueChart')) {
        const monthlyRevCtx = document.getElementById('monthlyRevenueChart').getContext('2d');
        new Chart(monthlyRevCtx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode(array_reverse($months)); ?>,
                datasets: [{
                    label: 'Revenue (₹)',
                    data: <?php echo json_encode(array_reverse($revenues)); ?>,
                    backgroundColor: '#f39c12',
                    borderColor: '#e67e22',
                    borderWidth: 2,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: true, labels: { font: { size: 12, weight: 'bold' } } }
                },
                scales: { y: { beginAtZero: true } }
            }
        });
    }

    // Status Chart
    if (document.getElementById('statusChart')) {
        const statusCtx = document.getElementById('statusChart').getContext('2d');
        new Chart(statusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending'],
                datasets: [{
                    data: [<?php echo $totalApproved; ?>, <?php echo $totalPending; ?>],
                    backgroundColor: ['#2ecc71', '#f39c12'],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 12, weight: 'bold' } }
                    }
                }
            }
        });
    }

    // Payment Chart
    if (document.getElementById('paymentChart')) {
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        new Chart(paymentCtx, {
            type: 'pie',
            data: {
                labels: ['Collected', 'Pending'],
                datasets: [{
                    data: [<?php echo $totalFeeCollected; ?>, <?php echo $pendingPayments; ?>],
                    backgroundColor: ['#3498db', '#e74c3c'],
                    borderColor: 'white',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { padding: 20, font: { size: 12, weight: 'bold' } }
                    }
                }
            }
        });
    }
</script>

</body>
</html>