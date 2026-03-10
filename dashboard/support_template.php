<?php
// admission-system/dashboard/support_new_template.php
// Complete Support Staff Dashboard Template
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
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
        /* NAVBAR */
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            padding: 15px 20px;
        }
        
        .navbar-brand {
            font-size: 1.5rem;
            font-weight: 700;
            color: white !important;
        }
        
        .nav-link {
            color: rgba(255,255,255,0.9) !important;
            margin-left: 15px;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .nav-link:hover {
            color: white !important;
        }
        
        /* LAYOUT */
        .main-wrapper {
            display: flex;
            min-height: calc(100vh - 60px);
        }
        
        /* SIDEBAR */
        .sidebar {
            width: 250px;
            background: #2c3e50;
            color: white;
            padding: 0;
            position: fixed;
            height: calc(100vh - 60px);
            overflow-y: auto;
            top: 60px;
            left: 0;
            box-shadow: 2px 0 8px rgba(0,0,0,0.15);
            z-index: 999;
        }
        
        .sidebar-header {
            padding: 20px;
            background: #34495e;
            border-bottom: 2px solid #667eea;
            font-weight: 700;
            color: #667eea;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        
        .sidebar-menu li {
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 15px 20px;
            color: #ecf0f1;
            text-decoration: none;
            transition: all 0.3s;
            gap: 12px;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(102, 126, 234, 0.2);
            border-left: 4px solid #667eea;
            color: white;
            padding-left: 16px;
        }
        
        .sidebar-menu i {
            width: 20px;
            text-align: center;
        }
        
        /* MAIN CONTENT */
        .main-content {
            flex: 1;
            margin-left: 250px;
            padding: 30px;
            overflow-y: auto;
        }
        
        /* CARDS */
        .section-card {
            background: white;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 5px solid #667eea;
            transition: all 0.3s;
        }
        
        .section-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        
        .section-title {
            color: #667eea;
            font-weight: 700;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ecf0f1;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        /* STATS CARDS */
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
            transform: translateY(-5px);
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
        
        /* FORM CONTROLS */
        .form-control, .form-select {
            border: 2px solid #ecf0f1 !important;
            border-radius: 8px !important;
            padding: 12px 15px !important;
            font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: #667eea !important;
            box-shadow: 0 0 0 0.3rem rgba(102, 126, 234, 0.15) !important;
        }
        
        /* FIELD GROUPS */
        .field-group {
            margin-bottom: 20px;
        }
        
        .field-label {
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            display: block;
            font-size: 0.95rem;
        }
        
        .field-value {
            color: #555;
            padding: 12px 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border-left: 3px solid #667eea;
            font-weight: 500;
        }
        
        /* ADMISSION ID BOX */
        .admission-id-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            border-radius: 12px;
            text-align: center;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .admission-id-display {
            font-size: 2.5rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 15px 0;
            font-family: 'Courier New', monospace;
            background: rgba(255,255,255,0.1);
            padding: 15px;
            border-radius: 8px;
        }
        
        /* ALERTS */
        .alert {
            border-left: 4px solid;
            border-radius: 8px;
            animation: slideInDown 0.3s ease;
        }
        
        @keyframes slideInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* BUTTONS */
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
            border: none !important;
            padding: 10px 20px !important;
            border-radius: 8px !important;
            font-weight: 600 !important;
            transition: all 0.3s !important;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px) !important;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3) !important;
        }
        
        /* TABLES */
        .table {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        
        .table thead {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .table tbody tr {
            border-bottom: 1px solid #ecf0f1;
            transition: all 0.3s;
        }
        
        .table tbody tr:hover {
            background: #f8f9fa;
        }
        
        /* SEARCH BOX */
        .search-box {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            .sidebar {
                display: none;
            }
            
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .sidebar.show {
                display: block;
                width: 100%;
                position: relative;
                height: auto;
                top: 0;
            }
        }
        
        /* PRINT */
        @media print {
            .sidebar, .navbar, .btn, .search-box {
                display: none !important;
            }
            
            .main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            <i class="fas fa-graduation-cap"></i> Support Staff Portal
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

<div class="main-wrapper">
    <!-- SIDEBAR -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <i class="fas fa-bars"></i> Navigation
        </div>
        <ul class="sidebar-menu">
            <li>
                <a href="?tab=dashboard" class="<?php echo $currentTab == 'dashboard' ? 'active' : ''; ?>">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="?tab=search" class="<?php echo $currentTab == 'search' ? 'active' : ''; ?>">
                    <i class="fas fa-id-card"></i> Search by ID
                </a>
            </li>
            <li>
                <a href="?tab=register" class="<?php echo $currentTab == 'register' ? 'active' : ''; ?>">
                    <i class="fas fa-user-plus"></i> Register Student
                </a>
            </li>
            <li>
                <a href="?tab=all_students" class="<?php echo $currentTab == 'all_students' ? 'active' : ''; ?>">
                    <i class="fas fa-list"></i> All Students
                </a>
            </li>
            <li>
                <a href="../actions/logout.php">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </aside>

    <!-- MAIN CONTENT -->
    <main class="main-content">
        <h2 style="color: #667eea; margin-bottom: 25px; font-weight: 700;">
            <i class="fas fa-graduation-cap"></i> Support Staff Dashboard
        </h2>

        <!-- ALERTS -->
        <?php if ($success): ?>
            <div class="alert alert-success alert-dismissible fade show" style="border-left-color: #2ecc71;">
                <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger alert-dismissible fade show" style="border-left-color: #e74c3c;">
                <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- STATISTICS -->
        <div class="row mb-4">
            <div class="col-lg-4 col-md-6">
                <div class="stat-card">
                    <div class="stat-label"><i class="fas fa-graduation-cap"></i> Total Students</div>
                    <div class="stat-number"><?php echo $totalStudents; ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #2ecc71;">
                    <div class="stat-label" style="color: #2ecc71;"><i class="fas fa-calendar-day"></i> Today</div>
                    <div class="stat-number" style="color: #2ecc71;"><?php echo $todayStudents; ?></div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="stat-card" style="border-left-color: #3498db;">
                    <div class="stat-label" style="color: #3498db;"><i class="fas fa-calendar"></i> This Month</div>
                    <div class="stat-number" style="color: #3498db;"><?php echo $thisMonthStudents; ?></div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD TAB -->
        <?php if ($currentTab == 'dashboard'): ?>
            <?php include('../dashboard/support_dashboard.php'); ?>
        <?php endif; ?>

        <!-- SEARCH TAB -->
        <?php if ($currentTab == 'search'): ?>
            <?php include('../dashboard/support_search.php'); ?>
        <?php endif; ?>

        <!-- REGISTER TAB -->
        <?php if ($currentTab == 'register'): ?>
            <?php include('../dashboard/support_register.php'); ?>
        <?php endif; ?>

        <!-- ALL STUDENTS TAB -->
        <?php if ($currentTab == 'all_students'): ?>
            <?php include('../dashboard/support_all_students.php'); ?>
        <?php endif; ?>

        <!-- VIEW STUDENT TAB -->
        <?php if ($currentTab == 'view_student' && $selectedStudent): ?>
            <?php include('../dashboard/support_view_student.php'); ?>
        <?php endif; ?>

    </main>
</div>

<!-- BOOTSTRAP JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- CUSTOM SCRIPTS -->
<script>
    // Auto-hide alerts
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        });
    });

    // Copy Admission ID
    function copyAdmissionID(id) {
        navigator.clipboard.writeText(id).then(() => {
            showNotification('Admission ID copied: ' + id, 'success');
        }).catch(() => {
            showNotification('Failed to copy', 'error');
        });
    }

    // Show notification
    function showNotification(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.querySelector('.main-content').insertBefore(alertDiv, document.querySelector('.main-content').firstChild);
    }

</script>

</body>
</html>