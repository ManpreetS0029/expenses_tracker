import "./bootstrap";
import Swal from "sweetalert2";

window.Swal = Swal;

function isDarkMode() {
    return document.documentElement.classList.contains("dark");
}

function getSwalConfig(customConfig = {}) {
    return {
        colorScheme: isDarkMode() ? "dark" : "light",
        ...customConfig,
    };
}

// Toast for success messages (e.g. from session flash)
document.addEventListener("DOMContentLoaded", function () {
    var toast = document.getElementById("toast-success");
    if (toast && toast.textContent.trim()) {
        Swal.fire(
            getSwalConfig({
                icon: "success",
                title: "Success!",
                text: toast.textContent.trim(),
                toast: true,
                position: "top-end",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            }),
        );
        toast.remove();
    }
});

// Global confirmation for delete actions
window.confirmSubmit = function (event, message) {
    event.preventDefault();
    const form = event.target;
    Swal.fire({
        title: "Are you sure?",
        text: message || "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#e11d48",
        cancelButtonColor: "#52525b",
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "Cancel",
        background: document.documentElement.classList.contains("dark")
            ? "#27272a"
            : "#fff",
        color: document.documentElement.classList.contains("dark")
            ? "#fafafa"
            : "#18181b",
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
};
