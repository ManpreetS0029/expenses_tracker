import './bootstrap';
import Swal from 'sweetalert2';

window.Swal = Swal;

function isDarkMode() {
    return document.documentElement.classList.contains('dark');
}

function getSwalConfig(customConfig = {}) {
    return {
        colorScheme: isDarkMode() ? 'dark' : 'light',
        ...customConfig,
    };
}

// Toast for success messages (e.g. from session flash)
document.addEventListener('DOMContentLoaded', function () {
    var toast = document.getElementById('toast-success');
    if (toast && toast.textContent.trim()) {
        Swal.fire(getSwalConfig({
            icon: 'success',
            title: 'Success!',
            text: toast.textContent.trim(),
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        }));
        toast.remove();
    }
});

// Global confirmation for delete actions
window.confirmDelete = function (message, callback) {
    if (typeof callback === 'function') {
        Swal.fire(getSwalConfig({
            title: 'Are you sure?',
            text: message || "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc2626',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel',
        })).then(function (result) {
            if (result.isConfirmed) {
                callback();
            }
        });
    } else {
        return confirm(message || 'Are you sure?');
    }
};
