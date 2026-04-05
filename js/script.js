// admission-system/js/script.js

// ============================================
// Enhanced DOM Ready
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    initializeFormValidation();
    autoHideAlerts();
    initializeDataTables();
    initializeClass12CutoffCalculator();
    initializeProgrammeChoice();
    initializeProgrammeTooltips();
    initializeProgrammeSearch();
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
// Initialize Class 12 Cutoff Calculator
// ============================================
function initializeClass12CutoffCalculator() {
    const cutoffPercentageInput = document.querySelector('input[name="class_12_percentage"]');
    const cutoffMarkInput = document.querySelector('input[name="class_12_cutoff_mark"]');
    const subjectsSelect = document.querySelector('select[name="class_12_subjects"]');
    const mathsInput = document.querySelector('input[name="class_12_maths_marks"]');
    const physicsInput = document.querySelector('input[name="class_12_physics_marks"]');
    const chemistryInput = document.querySelector('input[name="class_12_chemistry_marks"]');

    const updateCutoff = () => {
        // Check if MPC is selected
        const selectedSubjects = subjectsSelect ? subjectsSelect.value : '';
        const isMPC = selectedSubjects === 'Mathematics, Physics, Chemistry';

        if (!isMPC || !mathsInput || !physicsInput || !chemistryInput) {
            // Clear cutoff values if not MPC or inputs don't exist
            if (cutoffMarkInput) cutoffMarkInput.value = '';
            if (cutoffPercentageInput) cutoffPercentageInput.value = '';
            return;
        }

        const maths = parseFloat(mathsInput.value) || 0;
        const physics = parseFloat(physicsInput.value) || 0;
        const chemistry = parseFloat(chemistryInput.value) || 0;

        // Calculate cutoff mark: Maths (100) + Physics/2 (50) + Chemistry/2 (50) = Total out of 200
        const cutoffMark = maths + (physics / 2) + (chemistry / 2);
        cutoffMarkInput.value = cutoffMark.toFixed(2);

        // Calculate cutoff percentage as (cutoff mark / 200) * 100
        const cutoffPercentage = (cutoffMark / 200) * 100;
        cutoffPercentageInput.value = cutoffPercentage.toFixed(2);
    };

    // Add event listeners
    if (mathsInput) mathsInput.addEventListener('input', updateCutoff);
    if (physicsInput) physicsInput.addEventListener('input', updateCutoff);
    if (chemistryInput) chemistryInput.addEventListener('input', updateCutoff);
    if (subjectsSelect) subjectsSelect.addEventListener('change', updateCutoff);

    // Initialize on page load
    updateCutoff();
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
// Enhanced Programme Choice Functionality
// ============================================
function initializeProgrammeChoice() {
    const programmeCheckboxes = document.querySelectorAll('.programme-checkbox');
    const selectedCountElement = document.getElementById('selected-count');

    if (!programmeCheckboxes.length) return;

    function updateSelectionSummary() {
        const selectedCheckboxes = document.querySelectorAll('.programme-checkbox:checked');
        const selectedCount = selectedCheckboxes.length;

        // Update counter
        if (selectedCountElement) {
            selectedCountElement.textContent = selectedCount;
        }

        // Visual feedback for selection limit
        const counterDisplay = document.querySelector('.counter-display');
        if (counterDisplay) {
            counterDisplay.classList.toggle('warning', selectedCount > 5);
            counterDisplay.classList.toggle('danger', selectedCount > 7);
        }
    }

    // Add event listeners to checkboxes
    programmeCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateSelectionSummary();

            // Add visual feedback to programme item
            const programmeItem = this.closest('.programme-item');
            if (programmeItem) {
                programmeItem.classList.toggle('selected', this.checked);
            }
        });
    });

    // Initialize on page load
    updateSelectionSummary();
}

// ============================================
// Programme Card Tooltip Enhancement
// ============================================
function initializeProgrammeTooltips() {
    // Initialize tooltips for the simple programme items
    const programmeItems = document.querySelectorAll('.programme-item');

    programmeItems.forEach(item => {
        const label = item.querySelector('label');
        if (label) {
            item.setAttribute('data-bs-toggle', 'tooltip');
            item.setAttribute('data-bs-placement', 'top');
            item.setAttribute('title', label.textContent);
        }
    });

    // Reinitialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

// ============================================


// ============================================
// Enhanced DOM Ready
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    initializeTooltips();
    initializeFormValidation();
    autoHideAlerts();
    initializeDataTables();
    initializeClass12CutoffCalculator();
    initializeProgrammeChoice();
    initializeProgrammeTooltips();
});
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