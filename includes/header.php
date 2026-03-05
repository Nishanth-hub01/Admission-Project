<?php
// admission-system/includes/header.php

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Admission Management System - Educational Institution">
    <meta name="author" content="Your Institution Name">
    <meta name="theme-color" content="#667eea">
    
    <title><?php echo isset($page_title) ? htmlspecialchars($page_title) : 'Admission Management System'; ?></title>
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Segoe+UI:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?php echo isset($css_path) ? $css_path : '../css/style.css'; ?>">
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='75' font-size='75' fill='%23667eea'>📚</text></svg>">
    
    <!-- DataTables CSS (Optional) -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
    
    <!-- Custom Styles for Header -->
    <style>
        /* Preloader */
        .preloader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .preloader.active {
            display: flex;
        }

        .spinner-border {
            color: #667eea;
            width: 3rem;
            height: 3rem;
        }

        /* Smooth page transition */
        body {
            opacity: 1;
            transition: opacity 0.3s ease-in-out;
        }

        body.fade-out {
            opacity: 0.7;
        }

        /* Tooltip */
        .tooltip-inner {
            background-color: #667eea;
            font-weight: 500;
        }

        .bs-tooltip-top .tooltip-arrow::before,
        .bs-tooltip-auto[data-popper-placement^="top"] .tooltip-arrow::before {
            border-top-color: #667eea;
        }

        /* Loading animation */
        .loading {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 3px solid rgba(102, 126, 234, 0.3);
            border-radius: 50%;
            border-top-color: #667eea;
            animation: spin 1s ease-in-out infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* Toast notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 9999;
            min-width: 300px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            border-radius: 8px;
            animation: slideInUp 0.3s ease;
        }

        @keyframes slideInUp {
            from {
                transform: translateY(100%);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        /* Breadcrumb */
        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin-bottom: 20px;
        }

        .breadcrumb-item {
            font-size: 0.9rem;
        }

        .breadcrumb-item.active {
            color: #667eea;
            font-weight: 600;
        }

        .breadcrumb-item a {
            color: #667eea;
            text-decoration: none;
            transition: all 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #764ba2;
            text-decoration: underline;
        }
    </style>
</head>
<body>

<!-- Preloader -->
<div class="preloader" id="preloader">
    <div class="spinner-border" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Toast Container -->
<div id="toastContainer" style="position: fixed; top: 20px; right: 20px; z-index: 10000;"></div>

<script>
    // Hide preloader when page loads
    window.addEventListener('load', function() {
        document.getElementById('preloader').classList.remove('active');
    });

    // Show preloader on page unload
    window.addEventListener('beforeunload', function() {
        document.getElementById('preloader').classList.add('active');
    });
</script>