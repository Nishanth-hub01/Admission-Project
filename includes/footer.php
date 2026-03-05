<?php
// admission-system/includes/footer.php
?>

<!-- Bootstrap 5 JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<!-- jQuery (Optional) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- DataTables JS (Optional) -->
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>

<!-- Custom JavaScript -->
<script src="<?php echo isset($js_path) ? $js_path : '../js/script.js'; ?>"></script>

<!-- Utility Functions -->
<script>
    // ============================================
    // Toast Notification Function
    // ============================================
    function showToast(message, type = 'info', duration = 5000) {
        const toastContainer = document.getElementById('toastContainer');
        const toastId = 'toast-' + Date.now();
        
        const toastHtml = `
            <div class="toast" id="${toastId}" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header bg-${type} text-white">
                    <i class="fas fa-${getIconByType(type)}"></i>
                    <strong class="me-auto" style="margin-left: 10px;">${capitalize(type)}</strong>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="toast"></button>
                </div>
                <div class="toast-body">
                    ${message}
                </div>
            </div>
        `;
        
        toastContainer.insertAdjacentHTML('beforeend', toastHtml);
        
        const toastElement = document.getElementById(toastId);
        const toast = new bootstrap.Toast(toastElement);
        toast.show();
        
        // Auto remove after duration
        setTimeout(() => {
            toastElement.remove();
        }, duration);
    }

    function getIconByType(type) {
        const icons = {
            'success': 'check-circle',
            'danger': 'exclamation-circle',
            'warning': 'exclamation-triangle',
            'info': 'info-circle'
        };
        return icons[type] || 'info-circle';
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // ============================================
    // Form Validation
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        // Bootstrap form validation
        const forms = document.querySelectorAll('form');
        Array.from(forms).forEach(form => {
            form.addEventListener('submit', function(event) {
                if (!form.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    });

    // ============================================
    // Auto-hide Alerts
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                const closeButton = alert.querySelector('.btn-close');
                if (closeButton) {
                    closeButton.click();
                }
            }, 5000);
        });
    });

    // ============================================
    // Confirm Delete Function
    // ============================================
    function confirmDelete(message = 'Are you sure you want to delete this record?') {
        return confirm(message);
    }

    // ============================================
    // Number Formatting
    // ============================================
    function formatCurrency(amount) {
        return new Intl.NumberFormat('en-IN', {
            style: 'currency',
            currency: 'INR'
        }).format(amount);
    }

    function formatNumber(number) {
        return new Intl.NumberFormat('en-IN').format(number);
    }

    // ============================================
    // Date Formatting
    // ============================================
    function formatDate(date) {
        return new Intl.DateTimeFormat('en-IN').format(new Date(date));
    }

    function formatDateTime(date) {
        return new Intl.DateTimeFormat('en-IN', {
            year: 'numeric',
            month: 'short',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        }).format(new Date(date));
    }

    // ============================================
    // Table Search/Filter
    // ============================================
    function filterTable(inputId, tableId) {
        const input = document.getElementById(inputId);
        const table = document.getElementById(tableId);
        const rows = table.getElementsByTagName('tr');

        input.addEventListener('keyup', function() {
            const filter = input.value.toUpperCase();
            
            for (let i = 1; i < rows.length; i++) {
                const text = rows[i].textContent || rows[i].innerText;
                if (text.toUpperCase().indexOf(filter) > -1) {
                    rows[i].style.display = '';
                } else {
                    rows[i].style.display = 'none';
                }
            }
        });
    }

    // ============================================
    // Enable Tooltips
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    });

    // ============================================
    // Enable Popovers
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    });

    // ============================================
    // Print Page
    // ============================================
    function printPage() {
        window.print();
    }

    function printElement(elementId) {
        const element = document.getElementById(elementId);
        const printWindow = window.open('', '', 'height=500,width=800');
        printWindow.document.write('<html><head><title>Print</title>');
        printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">');
        printWindow.document.write('<link rel="stylesheet" href="../css/style.css">');
        printWindow.document.write('</head><body>');
        printWindow.document.write(element.innerHTML);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();
    }

    // ============================================
    // Export Table to CSV
    // ============================================
    function exportTableToCSV(tableId, filename = 'export.csv') {
        const table = document.getElementById(tableId);
        let csv = [];
        
        // Get headers
        const headers = [];
        table.querySelectorAll('thead th').forEach(th => {
            headers.push('"' + th.textContent.trim() + '"');
        });
        csv.push(headers.join(','));
        
        // Get rows
        table.querySelectorAll('tbody tr').forEach(tr => {
            const row = [];
            tr.querySelectorAll('td').forEach(td => {
                row.push('"' + td.textContent.trim().replace(/"/g, '""') + '"');
            });
            csv.push(row.join(','));
        });
        
        // Create download link
        const csvContent = csv.join('\n');
        const blob = new Blob([csvContent], { type: 'text/csv' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
    }

    // ============================================
    // Copy to Clipboard
    // ============================================
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            showToast('Copied to clipboard!', 'success', 3000);
        }).catch(() => {
            showToast('Failed to copy', 'danger', 3000);
        });
    }

    // ============================================
    // Debounce Function
    // ============================================
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // ============================================
    // Check if online/offline
    // ============================================
    window.addEventListener('online', function() {
        showToast('Connection restored!', 'success', 3000);
    });

    window.addEventListener('offline', function() {
        showToast('Connection lost!', 'warning', 0);
    });

    // ============================================
    // Keyboard Shortcuts
    // ============================================
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + S to save (prevent default)
        if ((event.ctrlKey || event.metaKey) && event.key === 's') {
            event.preventDefault();
            showToast('Use the form submit button to save changes', 'info', 3000);
        }
        
        // Ctrl/Cmd + P to print
        if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
            event.preventDefault();
            printPage();
        }
    });

    // ============================================
    // Loading State for Buttons
    // ============================================
    document.addEventListener('submit', function(event) {
        const form = event.target;
        const submitBtn = form.querySelector('button[type="submit"]');
        
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span> Processing...';
            
            // Re-enable after 3 seconds (safety timeout)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = submitBtn.getAttribute('data-original-text') || 'Submit';
            }, 3000);
        }
    });

    // Store original button text
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('button[type="submit"]').forEach(btn => {
            btn.setAttribute('data-original-text', btn.innerHTML);
        });
    });

    // ============================================
    // User Activity Tracker (optional)
    // ============================================
    let lastActivity = Date.now();
    const inactivityTimeout = 30 * 60 * 1000; // 30 minutes

    function resetInactivityTimer() {
        lastActivity = Date.now();
    }

    document.addEventListener('mousemove', resetInactivityTimer);
    document.addEventListener('keypress', resetInactivityTimer);
    document.addEventListener('click', resetInactivityTimer);

    setInterval(() => {
        if (Date.now() - lastActivity > inactivityTimeout) {
            showToast('Your session will expire due to inactivity', 'warning', 5000);
            // Uncomment to auto-logout
            // window.location.href = '../actions/logout.php';
        }
    }, 60000);
</script>

<!-- Optional: Analytics Code -->
<!-- <script async src="https://www.googletagmanager.com/gtag/js?id=GA_ID"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'GA_ID');
</script> -->

</body>
</html>