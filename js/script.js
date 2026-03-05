// admission-system/js/script.js

// ============================================
// DOM Ready Event
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    initializeFormValidation();
    autoHideAlerts();
    initializeDataTables();
});

// ============================================
// Initialize Bootstrap Tooltips
// ============================================
function initializeTooltips() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// ============================================
// Form Validation
// ============================================
function initializeFormValidation() {
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
}

// ============================================
// Auto-hide Alerts
// ============================================
function autoHideAlerts() {
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
}

// ============================================
// Initialize DataTables
// ============================================
function initializeDataTables() {
    if (typeof $ !== 'undefined' && $.fn.dataTable) {
        $('.table').DataTable({
            responsive: true,
            pageLength: 10,
            language: {
                search: "Search:",
                lengthMenu: "Show _MENU_ entries",
                info: "Showing _START_ to _END_ of _TOTAL_ entries",
                paginate: {
                    first: "First",
                    last: "Last",
                    next: "Next",
                    previous: "Previous"
                }
            }
        });
    }
}

// ============================================
// Show Toast Notification
// ============================================
function showToast(message, type = 'info', duration = 5000) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.setAttribute('role', 'alert');
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    document.querySelector('main') ? document.querySelector('main').insertBefore(alertDiv, document.querySelector('main').firstChild) 
        : document.body.insertBefore(alertDiv, document.body.firstChild);
    
    setTimeout(() => {
        alertDiv.remove();
    }, duration);
}

// ============================================
// Confirm Delete
// ============================================
function confirmDelete(message = 'Are you sure you want to delete this record?') {
    return confirm(message);
}

// ============================================
// Format Currency
// ============================================
function formatCurrency(amount) {
    return new Intl.NumberFormat('en-IN', {
        style: 'currency',
        currency: 'INR'
    }).format(amount);
}

// ============================================
// Format Number
// ============================================
function formatNumber(number) {
    return new Intl.NumberFormat('en-IN').format(number);
}

// ============================================
// Format Date
// ============================================
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('en-IN', options);
}

// ============================================
// Export Table to CSV
// ============================================
function exportTableToCSV(tableId, filename = 'export.csv') {
    const table = document.getElementById(tableId);
    if (!table) return;
    
    let csv = [];
    const rows = table.querySelectorAll('tr');
    
    rows.forEach(row => {
        const cells = row.querySelectorAll('td, th');
        const rowData = Array.from(cells).map(cell => {
            return '"' + cell.textContent.trim().replace(/"/g, '""') + '"';
        });
        csv.push(rowData.join(','));
    });
    
    downloadCSV(csv.join('\n'), filename);
}

// ============================================
// Download CSV
// ============================================
function downloadCSV(csvContent, filename) {
    const link = document.createElement('a');
    const blob = new Blob([csvContent], { type: 'text/csv' });
    link.href = window.URL.createObjectURL(blob);
    link.download = filename;
    link.click();
}

// ============================================
// Print Page
// ============================================
function printPage() {
    window.print();
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
// Filter Table
// ============================================
function filterTable(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    
    if (!input || !table) return;
    
    const rows = table.querySelectorAll('tbody tr');
    
    input.addEventListener('keyup', function() {
        const filter = input.value.toUpperCase();
        rows.forEach(row => {
            const text = row.textContent.toUpperCase();
            row.style.display = text.includes(filter) ? '' : 'none';
        });
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
// Validate Email
// ============================================
function validateEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

// ============================================
// Validate Phone
// ============================================
function validatePhone(phone) {
    const regex = /^[0-9]{10}$/;
    return regex.test(phone);
}

// ============================================
// Show Loading State
// ============================================
function setButtonLoading(buttonId, isLoading = true) {
    const button = document.getElementById(buttonId);
    if (!button) return;
    
    if (isLoading) {
        button.disabled = true;
        button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
    } else {
        button.disabled = false;
        button.innerHTML = button.getAttribute('data-original-text') || 'Submit';
    }
}

// ============================================
// Enable Keyboard Shortcuts
// ============================================
document.addEventListener('keydown', function(event) {
    // Ctrl/Cmd + P to print
    if ((event.ctrlKey || event.metaKey) && event.key === 'p') {
        event.preventDefault();
        printPage();
    }
    
    // Ctrl/Cmd + S to save (prevent default)
    if ((event.ctrlKey || event.metaKey) && event.key === 's') {
        event.preventDefault();
        showToast('Use the form submit button to save changes', 'info', 3000);
    }
});

// ============================================
// Check Network Status
// ============================================
window.addEventListener('offline', function() {
    showToast('⚠️ Connection lost!', 'warning', 0);
});

window.addEventListener('online', function() {
    showToast('✅ Connection restored!', 'success', 3000);
});