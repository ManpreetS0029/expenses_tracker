import './bootstrap';
import Swal from 'sweetalert2';

// Make Swal globally available
window.Swal = Swal;

// Helper function to detect dark mode
function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

// Helper function to get SweetAlert2 config with dark mode support
function getSwalConfig(customConfig = {}) {
    const darkMode = isDarkMode();
    
    const baseConfig = {
        colorScheme: darkMode ? 'dark' : 'light',
        ...customConfig
    };

    // Apply dark mode specific styling if needed
    if (darkMode) {
        // Custom dark mode colors matching the app's theme
        baseConfig.customClass = {
            popup: 'dark-mode-swal',
            title: 'dark-mode-swal-title',
            htmlContainer: 'dark-mode-swal-content',
            confirmButton: 'dark-mode-swal-confirm',
            cancelButton: 'dark-mode-swal-cancel',
        };
    }

    return baseConfig;
}

// Set SweetAlert2 default theme (will be overridden per call, but sets initial state)
Swal.mixin({
    colorScheme: isDarkMode() ? 'dark' : 'light',
});

// Livewire event listeners for SweetAlert2
document.addEventListener('DOMContentLoaded', function () {
    // Success Alert
    Livewire.on('alert-success', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        Swal.fire(getSwalConfig({
            icon: 'success',
            title: 'Success!',
            text: data.message || 'Operation completed successfully',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        }));
    });

    // Error Alert
    Livewire.on('alert-error', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        Swal.fire(getSwalConfig({
            icon: 'error',
            title: 'Error!',
            text: data.message || 'Something went wrong',
            confirmButtonColor: '#dc2626',
        }));
    });

    // Warning Alert
    Livewire.on('alert-warning', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        Swal.fire(getSwalConfig({
            icon: 'warning',
            title: 'Warning!',
            text: data.message || 'Please check your input',
            confirmButtonColor: '#f59e0b',
        }));
    });

    // Info Alert
    Livewire.on('alert-info', (event) => {
        const data = Array.isArray(event) ? event[0] : event;
        Swal.fire(getSwalConfig({
            icon: 'info',
            title: 'Info',
            text: data.message,
            confirmButtonColor: '#3b82f6',
        }));
    });
});

// Global function for confirmation dialogs
window.confirmDelete = function (message, callback) {
    Swal.fire(getSwalConfig({
        title: 'Are you sure?',
        text: message || "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc2626',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel',
    })).then((result) => {
        if (result.isConfirmed) {
            callback();
        }
    });
};
