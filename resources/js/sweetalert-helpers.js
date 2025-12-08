// SweetAlert2 Helper Functions

// Toast notification
window.toast = (icon, title, position = 'top-end', timer = 3000) => {
    return Swal.fire({
        icon: icon,
        title: title,
        toast: true,
        position: position,
        showConfirmButton: false,
        timer: timer,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });
};

// Success toast
window.toastSuccess = (message) => toast('success', message);

// Error toast
window.toastError = (message) => toast('error', message);

// Warning toast
window.toastWarning = (message) => toast('warning', message);

// Info toast
window.toastInfo = (message) => toast('info', message);

// Confirmation dialog
window.confirmDelete = (title = 'Are you sure?', text = 'You won\'t be able to revert this!') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    });
};

// Custom confirmation dialog
window.confirmAction = (title, text, confirmText = 'Yes', icon = 'question') => {
    return Swal.fire({
        title: title,
        text: text,
        icon: icon,
        showCancelButton: true,
        confirmButtonColor: '#2563eb',
        cancelButtonColor: '#6b7280',
        confirmButtonText: confirmText,
        cancelButtonText: 'Cancel'
    });
};

// Loading spinner
window.showLoading = (title = 'Processing...') => {
    Swal.fire({
        title: title,
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
};

// Close loading
window.closeLoading = () => {
    Swal.close();
};

// Success message
window.showSuccess = (title, text = '') => {
    return Swal.fire({
        icon: 'success',
        title: title,
        text: text,
        confirmButtonColor: '#2563eb'
    });
};

// Error message
window.showError = (title, text = '') => {
    return Swal.fire({
        icon: 'error',
        title: title,
        text: text,
        confirmButtonColor: '#2563eb'
    });
};
